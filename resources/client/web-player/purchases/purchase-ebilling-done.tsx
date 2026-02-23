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
          <CircleCheckBig className="text-positive" size={48} />
          <h2 className="text-2xl font-semibold mt-20">
            <Trans message="Félicitation ! Tu viens de donner la force. Le 241 te remercie." />
          </h2>
          <p className="text-muted mt-8">
            <Trans message="Ta force a bien été transmise." />
          </p>
          <Button
            variant="flat"
            color="primary"
            className="mt-24"
            elementType={Link}
            to="/library/purchases"
          >
            <Trans message="Voir mes forces données" />
          </Button>
        </>
      )}
      {status === 'pending' && (
        <>
          <div className="text-warning text-6xl">&#9203;</div>
          <h2 className="text-2xl font-semibold mt-20">
            <Trans message="Paiement en cours de traitement" />
          </h2>
          <p className="text-muted mt-8">
            <Trans message="Ton paiement est encore en cours de vérification. Reviens dans un instant." />
          </p>
          <Button
            variant="flat"
            color="primary"
            className="mt-24"
            elementType={Link}
            to="/library/purchases"
          >
            <Trans message="Voir mes forces données" />
          </Button>
        </>
      )}
      {status === 'failed' && (
        <>
          <AlertCircle className="text-danger" size={48} />
          <h2 className="text-2xl font-semibold mt-20">
            <Trans message="Échec du paiement" />
          </h2>
          <p className="text-muted mt-8">
            <Trans message="Le paiement n'a pas été finalisé. Réessaie." />
          </p>
          <Button
            variant="flat"
            color="primary"
            className="mt-24"
            elementType={Link}
            to="/"
          >
            <Trans message="Retour à l'accueil" />
          </Button>
        </>
      )}
    </div>
  );
}
