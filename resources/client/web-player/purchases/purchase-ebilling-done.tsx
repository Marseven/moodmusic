import {useEffect, useState} from 'react';
import {useSearchParams, Link} from 'react-router-dom';
import {apiClient} from '@common/http/query-client';
import {Trans} from '@common/i18n/trans';
import {Button} from '@common/ui/buttons/button';
import {FullPageLoader} from '@common/ui/progress/full-page-loader';
import {CircleCheckBig, AlertCircle} from 'lucide-react';

export function PurchaseEbillingDone() {
  const [searchParams] = useSearchParams();
  const reference = searchParams.get('reference');
  const [status, setStatus] = useState<'loading' | 'completed' | 'pending' | 'failed'>('loading');

  useEffect(() => {
    if (!reference) {
      setStatus('failed');
      return;
    }

    let attempts = 0;
    const maxAttempts = 3;

    const verify = async () => {
      try {
        const response = await apiClient.get('purchases/ebilling/verify', {
          params: {reference},
        });
        const purchaseStatus = response.data.status;

        if (purchaseStatus === 'completed') {
          setStatus('completed');
        } else if (purchaseStatus === 'failed') {
          setStatus('failed');
        } else if (attempts < maxAttempts) {
          attempts++;
          setTimeout(verify, 3000);
        } else {
          setStatus('pending');
        }
      } catch {
        setStatus('failed');
      }
    };

    verify();
  }, [reference]);

  if (status === 'loading') {
    return <FullPageLoader />;
  }

  return (
    <div className="flex flex-col items-center justify-center min-h-320 p-24 text-center">
      {status === 'completed' && (
        <>
          <CircleCheckBig className="text-positive" size="xl" />
          <h2 className="text-2xl font-semibold mt-20">
            <Trans message="Purchase successful!" />
          </h2>
          <p className="text-muted mt-8">
            <Trans message="Your purchase has been confirmed." />
          </p>
          <Button
            variant="flat"
            color="primary"
            className="mt-24"
            elementType={Link}
            to="/library/purchases"
          >
            <Trans message="View my purchases" />
          </Button>
        </>
      )}
      {status === 'pending' && (
        <>
          <div className="text-warning text-6xl">&#9203;</div>
          <h2 className="text-2xl font-semibold mt-20">
            <Trans message="Payment is being processed" />
          </h2>
          <p className="text-muted mt-8">
            <Trans message="Your payment is still being verified. Please check back shortly." />
          </p>
          <Button
            variant="flat"
            color="primary"
            className="mt-24"
            elementType={Link}
            to="/library/purchases"
          >
            <Trans message="View my purchases" />
          </Button>
        </>
      )}
      {status === 'failed' && (
        <>
          <AlertCircle className="text-danger" size="xl" />
          <h2 className="text-2xl font-semibold mt-20">
            <Trans message="Payment failed" />
          </h2>
          <p className="text-muted mt-8">
            <Trans message="The payment was not completed. Please try again." />
          </p>
          <Button
            variant="flat"
            color="primary"
            className="mt-24"
            elementType={Link}
            to="/"
          >
            <Trans message="Return to site" />
          </Button>
        </>
      )}
    </div>
  );
}
