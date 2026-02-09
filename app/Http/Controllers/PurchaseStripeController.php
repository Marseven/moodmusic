<?php

namespace App\Http\Controllers;

use App\Purchase;
use Common\Core\BaseController;
use Common\Settings\Settings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Webhook;

class PurchaseStripeController extends BaseController
{
    public function createCheckout(Request $request): JsonResponse
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
            Stripe::setApiKey(config('services.stripe.secret'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($purchase->currency),
                        'product_data' => [
                            'name' => $itemName,
                        ],
                        'unit_amount' => (int) ($purchase->amount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'metadata' => [
                    'purchase_id' => $purchase->id,
                    'reference' => $purchase->reference,
                ],
                'success_url' => url('/purchases/stripe/done') . '?reference=' . $purchase->reference,
                'cancel_url' => url('/purchases/stripe/cancel') . '?reference=' . $purchase->reference,
            ]);

            $purchase->update(['gateway_id' => $session->id]);

            return response()->json([
                'checkout_url' => $session->url,
                'session_id' => $session->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('Purchase Stripe error: ' . $e->getMessage(), [
                'purchase_id' => $purchase->id,
            ]);
            return response()->json([
                'message' => 'Erreur Stripe. Veuillez réessayer.',
            ], 500);
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            if ($webhookSecret) {
                $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
            } else {
                $event = json_decode($payload);
            }
        } catch (\Exception $e) {
            Log::error('Purchase Stripe webhook signature error: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        $type = is_object($event) ? ($event->type ?? null) : ($event['type'] ?? null);
        $sessionData = is_object($event) ? ($event->data->object ?? null) : ($event['data']['object'] ?? null);

        if ($type === 'checkout.session.completed' && $sessionData) {
            $metadata = is_object($sessionData) ? ($sessionData->metadata ?? null) : ($sessionData['metadata'] ?? null);
            $reference = is_object($metadata) ? ($metadata->reference ?? null) : ($metadata['reference'] ?? null);
            $purchaseId = is_object($metadata) ? ($metadata->purchase_id ?? null) : ($metadata['purchase_id'] ?? null);

            $purchase = null;
            if ($reference) {
                $purchase = Purchase::where('reference', $reference)->first();
            }
            if (!$purchase && $purchaseId) {
                $purchase = Purchase::find($purchaseId);
            }

            if ($purchase && $purchase->status === 'pending') {
                $purchase->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
                Log::info('Purchase Stripe: payment confirmed', ['purchase_id' => $purchase->id]);
            }
        }

        return response('OK', 200);
    }
}
