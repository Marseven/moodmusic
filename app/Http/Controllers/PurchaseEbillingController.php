<?php

namespace App\Http\Controllers;

use App\Album;
use App\Purchase;
use App\Track;
use Common\Billing\Gateways\Ebilling\InteractsWithEbillingRestApi;
use Common\Core\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PurchaseEbillingController extends BaseController
{
    use InteractsWithEbillingRestApi;

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

        $user = Auth::user();
        $purchasable = $purchase->purchasable;
        $itemName = $purchasable->name ?? 'Article';

        $globalPayload = [
            'payer_email' => $user->email,
            'payer_msisdn' => $user->phone ?? '074000000',
            'amount' => number_format($purchase->amount, 2, '.', ''),
            'short_description' => "Achat: {$itemName}",
            'external_reference' => $purchase->reference,
            'payer_name' => $user->first_name . ' ' . $user->last_name,
            'expiry_period' => 60,
        ];

        try {
            $credentials = $this->getEbillingCredentials();
            if (!$credentials['username'] || !$credentials['sharedkey']) {
                return response()->json([
                    'message' => 'Configuration eBilling incomplète.',
                ], 500);
            }

            $response = $this->ebilling()->post('/api/v1/merchant/e_bills', $globalPayload);

            if (!$response->successful()) {
                Log::error('Purchase eBilling API error', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'purchase_id' => $purchase->id,
                ]);
                return response()->json([
                    'message' => "Erreur eBilling ({$response->status()}).",
                ], $response->status());
            }

            $billId = $response->json('e_bill.bill_id');
            $purchase->update(['gateway_id' => $billId]);

            $callbackUrl = url('/purchases/ebilling/done') . '?reference=' . $purchase->reference;
            $portalUrl = $this->getPortalBaseUrl();

            return response()->json([
                'bill_id' => $billId,
                'checkout_url' => "{$portalUrl}?invoice={$billId}&redirect_url={$callbackUrl}",
            ]);
        } catch (\Throwable $e) {
            Log::error('Purchase eBilling exception: ' . $e->getMessage(), [
                'purchase_id' => $purchase->id,
            ]);
            return response()->json([
                'message' => 'Erreur serveur. Veuillez réessayer.',
            ], 500);
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();
        Log::info('Purchase eBilling webhook received', ['payload' => $payload]);

        $reference = $payload['external_reference'] ?? $payload['reference'] ?? null;
        $billId = $payload['bill_id'] ?? $payload['billingid'] ?? $payload['transaction_id'] ?? $payload['transactionid'] ?? null;

        if (!$reference && !$billId) {
            return response('Missing reference or bill_id', 400);
        }

        // Only handle purchase references (pur_ prefix)
        if ($reference && !str_starts_with($reference, 'pur_')) {
            // Not a purchase webhook, skip
            return response('Not a purchase reference', 200);
        }

        $purchase = null;
        if ($reference) {
            $purchase = Purchase::where('reference', $reference)->first();
        }
        if (!$purchase && $billId) {
            $purchase = Purchase::where('gateway_id', $billId)
                ->where('reference', 'like', 'pur_%')
                ->first();
        }

        if (!$purchase) {
            Log::error('Purchase eBilling webhook: purchase not found', [
                'reference' => $reference,
                'bill_id' => $billId,
            ]);
            return response('Purchase not found', 404);
        }

        // Verify with eBilling API
        $verifyBillId = $billId ?? $purchase->gateway_id;
        $status = $this->verifyPaymentStatus($verifyBillId);

        if ($status === null) {
            return response('Payment verification failed', 500);
        }

        $this->processStatus($status, $purchase, $billId);

        return response('Webhook processed', 200);
    }

    public function verifyByReference(Request $request): JsonResponse
    {
        $reference = $request->query('reference');

        if (!$reference || !str_starts_with($reference, 'pur_')) {
            return response()->json(['message' => 'Invalid reference.'], 400);
        }

        $purchase = Purchase::where('reference', $reference)
            ->where('user_id', Auth::id())
            ->first();

        if (!$purchase) {
            return response()->json(['message' => 'Purchase not found.'], 404);
        }

        // If already completed/failed, return current status
        if ($purchase->status !== 'pending') {
            return response()->json([
                'status' => $purchase->status,
                'purchase' => $purchase,
            ]);
        }

        // Verify with eBilling API if we have a gateway_id
        if ($purchase->gateway_id) {
            $ebillingStatus = $this->verifyPaymentStatus($purchase->gateway_id);
            if ($ebillingStatus) {
                $this->processStatus($ebillingStatus, $purchase, $purchase->gateway_id);
                $purchase->refresh();
            }
        }

        return response()->json([
            'status' => $purchase->status,
            'purchase' => $purchase,
        ]);
    }

    protected function verifyPaymentStatus(?string $billId): ?string
    {
        if (!$billId) {
            return null;
        }

        try {
            $response = $this->ebilling()->get("/api/v1/merchant/e_bills/{$billId}");
            if ($response->successful()) {
                return $response->json('state');
            }
        } catch (\Exception $e) {
            Log::error('Purchase eBilling verification error', [
                'bill_id' => $billId,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    protected function processStatus(string $status, Purchase $purchase, ?string $billId): void
    {
        $normalized = strtolower($status);

        switch ($normalized) {
            case 'paid':
            case 'processed':
                $updateData = [
                    'status' => 'completed',
                    'paid_at' => now(),
                ];
                if ($billId) {
                    $updateData['gateway_id'] = $billId;
                }
                $purchase->update($updateData);
                Log::info('Purchase eBilling: payment confirmed', ['purchase_id' => $purchase->id]);
                break;

            case 'failed':
            case 'cancelled':
                $purchase->update(['status' => 'failed']);
                Log::info('Purchase eBilling: payment failed', ['purchase_id' => $purchase->id]);
                break;

            case 'pending':
            case 'ready':
                Log::info('Purchase eBilling: payment pending', ['purchase_id' => $purchase->id]);
                break;
        }
    }
}
