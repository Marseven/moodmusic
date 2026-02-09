import {useMutation} from '@tanstack/react-query';
import {apiClient} from '@common/http/query-client';
import {Purchase} from './purchase';
import {toast} from '@common/ui/toast/toast';
import {message} from '@common/i18n/message';

interface InitiatePurchasePayload {
  purchasable_type: 'track' | 'album';
  purchasable_id: number;
  gateway: 'ebilling' | 'stripe' | 'paypal';
}

interface InitiatePurchaseResponse {
  purchase: Purchase;
}

export function useInitiatePurchase() {
  return useMutation(
    (payload: InitiatePurchasePayload) => initiatePurchase(payload),
    {
      onError: () => {
        toast.danger(message('Could not initiate purchase'));
      },
    },
  );
}

function initiatePurchase(
  payload: InitiatePurchasePayload,
): Promise<InitiatePurchaseResponse> {
  return apiClient.post('purchases/initiate', payload).then(r => r.data);
}

interface GatewayOrderPayload {
  purchase_id: number;
}

interface GatewayOrderResponse {
  checkout_url?: string;
  bill_id?: string;
  session_id?: string;
  order_id?: string;
}

export function useCreateEbillingOrder() {
  return useMutation(
    (payload: GatewayOrderPayload) =>
      apiClient
        .post('purchases/ebilling/create-order', payload)
        .then(r => r.data as GatewayOrderResponse),
    {
      onError: () => {
        toast.danger(message('eBilling payment error'));
      },
    },
  );
}

export function useCreateStripeCheckout() {
  return useMutation(
    (payload: GatewayOrderPayload) =>
      apiClient
        .post('purchases/stripe/create-checkout', payload)
        .then(r => r.data as GatewayOrderResponse),
    {
      onError: () => {
        toast.danger(message('Stripe payment error'));
      },
    },
  );
}

export function useCreatePaypalOrder() {
  return useMutation(
    (payload: GatewayOrderPayload) =>
      apiClient
        .post('purchases/paypal/create-order', payload)
        .then(r => r.data as GatewayOrderResponse),
    {
      onError: () => {
        toast.danger(message('PayPal payment error'));
      },
    },
  );
}
