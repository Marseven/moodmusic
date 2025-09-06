import {CheckoutLayout} from '../checkout-layout';
import {useParams, useSearchParams} from 'react-router-dom';
import {useEffect, useState} from 'react';
import {message} from '@common/i18n/message';
import {CheckoutProductSummary} from '../checkout-product-summary';
import {
  BillingRedirectMessage,
  BillingRedirectMessageConfig,
} from '../../billing-redirect-message';
import {apiClient} from '@common/http/query-client';
import {useBootstrapData} from '@common/core/bootstrap-data/bootstrap-data-context';
import {FullPageLoader} from '../../../ui/progress/full-page-loader';
import type {Subscription} from '@common/billing/subscription';

export function CheckoutEbillingDone() {
  const {invalidateBootstrapData} = useBootstrapData();
  const [searchParams] = useSearchParams();
  const {productId, priceId} = useParams();
  const [subscription, setSubscription] = useState<Subscription | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  const billId = searchParams.get('invoice');
  const status = searchParams.get('status');

  useEffect(() => {
    if (billId) {
      verifyPaymentStatus(billId);
    } else {
      setIsLoading(false);
    }
  }, [billId]);

  const verifyPaymentStatus = async (billId: string) => {
    try {
      const response = await apiClient.get(`billing/ebilling/verify-payment/${billId}`);
      setSubscription(response.data.subscription);
      
      if (response.data.status === 'PAID') {
        await storeSubscriptionDetailsLocally(response.data.subscription.id);
        invalidateBootstrapData();
        // Set success status
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('status', 'success');
        window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
      } else {
        // Payment not completed yet, show pending/retry state
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('status', 'pending');
        window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
      }
    } catch (error: any) {
      console.error('Payment verification failed:', error);
      // Set error status to show appropriate message
      const urlParams = new URLSearchParams(window.location.search);
      urlParams.set('status', 'error');
      window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
    } finally {
      setIsLoading(false);
    }
  };

  if (isLoading) {
    return <FullPageLoader />;
  }

  const config = getRedirectMessageConfig(
    status,
    productId,
    priceId
  );

  return (
    <CheckoutLayout>
      <BillingRedirectMessage config={config} />
      <CheckoutProductSummary showBillingLine={false} />
    </CheckoutLayout>
  );

}

function getRedirectMessageConfig(
  status?: 'success' | 'error' | 'pending' | string | null,
  productId?: string,
  priceId?: string
): BillingRedirectMessageConfig {
  switch (status) {
    case 'success':
      return {
        message: message('Subscription successful!'),
        status: 'success',
        buttonLabel: message('Return to site'),
        link: '/billing',
      };
    case 'pending':
      return {
        message: message('Payment is being processed. Please wait or try refreshing the page.'),
        status: 'warning',
        buttonLabel: message('Refresh page'),
        link: window.location.href,
      };
    default:
      return {
        message: message('Payment failed or was cancelled. Please try again.'),
        status: 'error',
        buttonLabel: message('Try again'),
        link: errorLink(productId, priceId),
      };
  }
}

function errorLink(productId?: string, priceId?: string): string {
  return productId && priceId ? `/buy/${productId}/${priceId}` : '/';
}

function storeSubscriptionDetailsLocally(subscriptionId: string) {
  return apiClient.post('billing/ebilling/store-subscription-details-locally', {
    ebilling_subscription_id: subscriptionId,
  });
}
