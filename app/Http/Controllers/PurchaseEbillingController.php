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
            'phone' => 'nullable|string|size:9',
            'operator' => 'nullable|string|in:airtelmoney,moovmoney4',
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

        $msisdn = $data['phone'] ?? $user->phone ?? '074000000';

        // Save phone to user profile if provided
        if (!empty($data['phone']) && $user->phone !== $data['phone']) {
            $user->update(['phone' => $data['phone']]);
        }

        $globalPayload = [
            'payer_email' => $user->email,
            'payer_msisdn' => $msisdn,
            'amount' => number_format($purchase->amount, 2, '.', ''),
            'short_description' => "Achat: {$itemName}",
            'external_reference' => $purchase->reference,
            'payer_name' => $user->first_name . ' ' . $user->last_name,
            'expiry_period' => 60,
        ];

        try {
            $credentials = $this->getEbillingCredentials();
            if (!$credentials['username'] || !$credentials['sharedkey']) {
                Log::channel('ebilling')->error('[purchase:createOrder] Credentials missing', [
                    'has_username' => !empty($credentials['username']),
                    'has_sharedkey' => !empty($credentials['sharedkey']),
                    'purchase_id' => $purchase->id,
                ]);
                return response()->json([
                    'message' => 'Configuration eBilling incomplète.',
                ], 500);
            }

            Log::channel('ebilling')->info('[purchase:createOrder] Starting', [
                'user_id' => $user->id,
                'purchase_id' => $purchase->id,
                'item' => $itemName,
                'amount' => $purchase->amount,
                'reference' => $purchase->reference,
            ]);

            $response = $this->ebilling()->post('/api/v1/merchant/e_bills', $globalPayload);

            if (!$response->successful()) {
                Log::channel('ebilling')->error('[purchase:createOrder] API call failed', [
                    'purchase_id' => $purchase->id,
                    'http_status' => $response->status(),
                    'response_body' => $response->body(),
                    'payload' => $globalPayload,
                ]);
                return response()->json([
                    'message' => "Erreur eBilling ({$response->status()}).",
                ], $response->status());
            }

            $billId = $response->json('e_bill.bill_id');
            $purchase->update(['gateway_id' => $billId]);

            $callbackUrl = url('/purchases/ebilling/done') . '?reference=' . $purchase->reference;
            $portalUrl = $this->getPortalBaseUrl();

            Log::channel('ebilling')->info('[purchase:createOrder] Success - bill created', [
                'purchase_id' => $purchase->id,
                'bill_id' => $billId,
                'checkout_url' => "{$portalUrl}?invoice={$billId}&redirect_url={$callbackUrl}",
            ]);

            return response()->json([
                'bill_id' => $billId,
                'checkout_url' => "{$portalUrl}?invoice={$billId}&redirect_url={$callbackUrl}",
            ]);
        } catch (\Throwable $e) {
            Log::channel('ebilling')->error('[purchase:createOrder] Exception', [
                'purchase_id' => $purchase->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
            ]);
            return response()->json([
                'message' => 'Erreur serveur. Veuillez réessayer.',
            ], 500);
        }
    }

    public function sendUssdPush(Request $request): JsonResponse
    {
        $data = $request->validate([
            'bill_id' => 'required|string',
            'phone' => 'required|string|size:9',
            'operator' => 'required|string|in:airtelmoney,moovmoney4',
        ]);

        try {
            $credentials = $this->getEbillingCredentials();
            if (!$credentials['username'] || !$credentials['sharedkey']) {
                return response()->json(['message' => 'Configuration eBilling incomplète.'], 500);
            }

            Log::channel('ebilling')->info('[purchase:sendUssdPush] Starting', [
                'bill_id' => $data['bill_id'],
                'operator' => $data['operator'],
                'phone' => $data['phone'],
            ]);

            $response = $this->ebilling()->post(
                "/api/v1/merchant/e_bills/{$data['bill_id']}/ussd_push",
                [
                    'payer_msisdn' => $data['phone'],
                    'payment_system_name' => $data['operator'],
                ]
            );

            if ($response->successful()) {
                $responseData = $response->json();
                $message = $responseData['message'] ?? '';

                Log::channel('ebilling')->info('[purchase:sendUssdPush] Response', [
                    'bill_id' => $data['bill_id'],
                    'response' => $responseData,
                ]);

                if (strtolower($message) === 'accepted') {
                    return response()->json(['message' => 'Push USSD envoyé avec succès.']);
                }

                return response()->json([
                    'message' => 'Le push USSD n\'a pas été accepté.',
                    'details' => $responseData,
                ], 422);
            }

            Log::channel('ebilling')->error('[purchase:sendUssdPush] API call failed', [
                'bill_id' => $data['bill_id'],
                'http_status' => $response->status(),
                'response_body' => $response->body(),
            ]);

            return response()->json([
                'message' => "Erreur eBilling ({$response->status()}).",
            ], $response->status());
        } catch (\Throwable $e) {
            Log::channel('ebilling')->error('[purchase:sendUssdPush] Exception', [
                'bill_id' => $data['bill_id'],
                'error' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Erreur serveur.'], 500);
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();
        Log::channel('ebilling')->info('[purchase:webhook] Received', ['payload' => $payload]);

        $reference = $payload['external_reference'] ?? $payload['reference'] ?? null;
        $billId = $payload['bill_id'] ?? $payload['billingid'] ?? $payload['transaction_id'] ?? $payload['transactionid'] ?? null;

        if (!$reference && !$billId) {
            Log::channel('ebilling')->warning('[purchase:webhook] Missing reference and bill_id', ['payload' => $payload]);
            return response('Missing reference or bill_id', 400);
        }

        // Only handle purchase references (pur_ prefix)
        if ($reference && !str_starts_with($reference, 'pur_')) {
            Log::channel('ebilling')->info('[purchase:webhook] Not a purchase reference, skipping', [
                'reference' => $reference,
            ]);
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
            Log::channel('ebilling')->error('[purchase:webhook] Purchase not found', [
                'reference' => $reference,
                'bill_id' => $billId,
            ]);
            return response('Purchase not found', 404);
        }

        Log::channel('ebilling')->info('[purchase:webhook] Purchase found', [
            'purchase_id' => $purchase->id,
            'user_id' => $purchase->user_id,
            'current_status' => $purchase->status,
            'reference' => $reference,
            'bill_id' => $billId,
        ]);

        // Verify with eBilling API
        $verifyBillId = $billId ?? $purchase->gateway_id;
        $status = $this->verifyPaymentStatus($verifyBillId);

        if ($status === null) {
            Log::channel('ebilling')->error('[purchase:webhook] API verification failed', [
                'purchase_id' => $purchase->id,
                'bill_id' => $verifyBillId,
            ]);
            return response('Payment verification failed', 500);
        }

        Log::channel('ebilling')->info('[purchase:webhook] API verification result', [
            'purchase_id' => $purchase->id,
            'verified_status' => $status,
        ]);

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
            Log::channel('ebilling')->info('[purchase:verifyByReference] Already resolved', [
                'purchase_id' => $purchase->id,
                'status' => $purchase->status,
            ]);
            return response()->json([
                'status' => $purchase->status,
                'purchase' => $purchase,
            ]);
        }

        // Verify with eBilling API if we have a gateway_id
        if ($purchase->gateway_id) {
            Log::channel('ebilling')->info('[purchase:verifyByReference] Checking with API', [
                'purchase_id' => $purchase->id,
                'gateway_id' => $purchase->gateway_id,
            ]);
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

            Log::channel('ebilling')->error('[purchase:verify] API request failed', [
                'bill_id' => $billId,
                'http_status' => $response->status(),
                'response_body' => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::channel('ebilling')->error('[purchase:verify] Exception', [
                'bill_id' => $billId,
                'error' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
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
                Log::channel('ebilling')->info('[purchase] Payment confirmed', [
                    'purchase_id' => $purchase->id,
                    'user_id' => $purchase->user_id,
                    'amount' => $purchase->amount,
                    'bill_id' => $billId,
                ]);
                break;

            case 'failed':
            case 'cancelled':
                $purchase->update(['status' => 'failed']);
                Log::channel('ebilling')->warning('[purchase] Payment failed/cancelled', [
                    'purchase_id' => $purchase->id,
                    'user_id' => $purchase->user_id,
                    'bill_id' => $billId,
                    'status' => $normalized,
                ]);
                break;

            case 'pending':
            case 'ready':
                Log::channel('ebilling')->info('[purchase] Payment still pending', [
                    'purchase_id' => $purchase->id,
                    'bill_id' => $billId,
                    'status' => $normalized,
                ]);
                break;

            default:
                Log::channel('ebilling')->warning('[purchase] Unrecognized status', [
                    'purchase_id' => $purchase->id,
                    'status' => $status,
                    'bill_id' => $billId,
                ]);
        }
    }
}
