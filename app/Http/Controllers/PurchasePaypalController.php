<?php

namespace App\Http\Controllers;

use App\Purchase;
use Common\Core\BaseController;
use Common\Settings\Settings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PurchasePaypalController extends BaseController
{
    public function createOrder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'purchase_id' => 'required|integer|exists:purchases,id',
        ]);

        $purchase = Purchase::findOrFail($data['purchase_id']);

        if ($purchase->user_id !== Auth::id()) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        if ($purchase->status !== 'pending') {
            return response()->json(['message' => 'Cet achat a déjà été traité.'], 400);
        }

        $purchasable = $purchase->purchasable;
        $itemName = $purchasable->name ?? 'Article';

        try {
            $accessToken = $this->getPaypalAccessToken();

            $response = Http::withToken($accessToken)
                ->post($this->getPaypalBaseUrl() . '/v2/checkout/orders', [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'reference_id' => $purchase->reference,
                        'amount' => [
                            'currency_code' => $purchase->currency,
                            'value' => number_format($purchase->amount, 2, '.', ''),
                        ],
                        'description' => "Achat: {$itemName}",
                    ]],
                    'application_context' => [
                        'return_url' => url('/purchases/paypal/done') . '?reference=' . $purchase->reference,
                        'cancel_url' => url('/purchases/paypal/cancel') . '?reference=' . $purchase->reference,
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('Purchase PayPal create order error', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                return response()->json(['message' => 'Erreur PayPal.'], 500);
            }

            $orderId = $response->json('id');
            $approveLink = collect($response->json('links'))
                ->firstWhere('rel', 'approve')['href'] ?? null;

            $purchase->update(['gateway_id' => $orderId]);

            return response()->json([
                'order_id' => $orderId,
                'checkout_url' => $approveLink,
            ]);
        } catch (\Throwable $e) {
            Log::error('Purchase PayPal exception: ' . $e->getMessage(), [
                'purchase_id' => $purchase->id,
            ]);
            return response()->json(['message' => 'Erreur serveur.'], 500);
        }
    }

    public function captureOrder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'order_id' => 'required|string',
        ]);

        $purchase = Purchase::where('gateway_id', $data['order_id'])->first();

        if (!$purchase) {
            return response()->json(['message' => 'Achat non trouvé.'], 404);
        }

        try {
            $accessToken = $this->getPaypalAccessToken();

            $response = Http::withToken($accessToken)
                ->post($this->getPaypalBaseUrl() . "/v2/checkout/orders/{$data['order_id']}/capture");

            if ($response->successful() && $response->json('status') === 'COMPLETED') {
                $purchase->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);

                return response()->json([
                    'status' => 'completed',
                    'purchase' => $purchase,
                ]);
            }

            return response()->json([
                'status' => 'failed',
                'message' => 'Capture PayPal échouée.',
            ], 400);
        } catch (\Throwable $e) {
            Log::error('Purchase PayPal capture exception: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur.'], 500);
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();
        Log::info('Purchase PayPal webhook received', ['event_type' => $payload['event_type'] ?? null]);

        $eventType = $payload['event_type'] ?? null;

        if ($eventType === 'PAYMENT.CAPTURE.COMPLETED') {
            $resource = $payload['resource'] ?? [];
            $referenceId = null;

            // Extract reference from purchase_units
            $purchaseUnits = $resource['purchase_units'] ?? [];
            foreach ($purchaseUnits as $unit) {
                if (isset($unit['reference_id']) && str_starts_with($unit['reference_id'], 'pur_')) {
                    $referenceId = $unit['reference_id'];
                    break;
                }
            }

            if ($referenceId) {
                $purchase = Purchase::where('reference', $referenceId)->first();
                if ($purchase && $purchase->status === 'pending') {
                    $purchase->update([
                        'status' => 'completed',
                        'paid_at' => now(),
                    ]);
                    Log::info('Purchase PayPal: payment confirmed via webhook', ['purchase_id' => $purchase->id]);
                }
            }
        }

        return response('OK', 200);
    }

    protected function getPaypalAccessToken(): string
    {
        $settings = app(Settings::class);
        $clientId = $settings->get('billing.paypal.client_id') ?? config('services.paypal.client_id');
        $secret = $settings->get('billing.paypal.secret') ?? config('services.paypal.secret');

        $response = Http::asForm()
            ->withBasicAuth($clientId, $secret)
            ->post($this->getPaypalBaseUrl() . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        return $response->json('access_token');
    }

    protected function getPaypalBaseUrl(): string
    {
        $settings = app(Settings::class);
        $testMode = $settings->get('billing.paypal_test_mode', config('services.paypal.sandbox', true));
        return $testMode
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
    }
}
