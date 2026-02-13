import {useBillingUser} from './use-billing-user';
import {CancelledPlanPanel} from './panels/cancelled-plan-panel';
import {ActivePlanPanel} from './panels/active-plan-panel';
import {PaymentMethodPanel} from './panels/payment-method-panel';
import {InvoiceHistoryPanel} from './panels/invoice-history-panel';
import {Trans} from '@common/i18n/trans';
import {Chip} from '@common/ui/forms/input-field/chip-field/chip';
import {FormattedPrice} from '@common/i18n/formatted-price';
import {Button} from '@common/ui/buttons/button';
import {Link} from 'react-router-dom';
import {AlertTriangle as ExclamationTriangleIcon} from 'lucide-react';
import {useEbilling} from '../checkout/ebilling/use-ebilling';

export function BillingPage() {
  const {subscription, isLoading} = useBillingUser();
  
  
  // Si en cours de chargement
  if (isLoading) {
    return <div className="p-24 text-center"><Trans message="Chargement..." /></div>;
  }
  
  // Si pas d'abonnement, afficher un message avec lien vers pricing
  if (!subscription?.price || !subscription?.product) {
    return (
      <div className="p-24 text-center">
        <div className="mood-glass-panel p-32 max-w-md mx-auto">
          <h2 className="text-2xl font-semibold mb-16">
            <Trans message="Aucun abonnement actif" />
          </h2>
          <p className="mb-24 text-muted">
            <Trans message="Vous n'avez actuellement aucun abonnement actif. Découvrez nos offres et choisissez le plan qui vous convient." />
          </p>
          <Button
            variant="flat"
            color="primary"
            className="mood-cta-button"
            elementType={Link}
            to="/pricing"
          >
            <Trans message="Voir les offres d'abonnement" />
          </Button>
        </div>
      </div>
    );
  }

  // Si l'abonnement existe mais n'est pas correctement payé (gateway_id manquant ou paid_at null)
  if (!subscription.gateway_id || !subscription.paid_at) {
    return <PendingPaymentPanel subscription={subscription} />;
  }

  const planPanel = subscription.ends_at ? (
    <CancelledPlanPanel />
  ) : (
    <ActivePlanPanel />
  );

  return (
    <div className="p-20">
      {planPanel}
      <PaymentMethodPanel />
      <InvoiceHistoryPanel />
    </div>
  );
}

function PendingPaymentPanel({subscription}: {subscription: any}) {
  const {initiatePayment} = useEbilling();

  return (
    <div className="mood-glass-panel p-28 m-20">
      <h2 className="text-2xl font-bold mb-20 flex items-center gap-12">
        <ExclamationTriangleIcon className="w-28 h-28 text-yellow-500" />
        <Trans message="Paiement en attente" />
      </h2>
      <div className="flex flex-col md:flex-row gap-24 md:justify-between mt-24">
        <div className="min-w-0">
          <Chip
            className="w-min mb-12"
            size="xs"
            radius="rounded"
            color="warning"
          >
            <Trans message="En attente" />
          </Chip>
          <div className="text-xl font-bold mb-8">
            {subscription?.product?.name || 'Abonnement'}
          </div>
          <FormattedPrice className="text-xl mb-12" price={subscription?.price} />
          <div className="text-base flex items-center gap-8 text-muted">
            <ExclamationTriangleIcon className="w-16 h-16 flex-shrink-0" />
            <Trans message="Le paiement n'a pas été finalisé. Votre abonnement est en attente de confirmation." />
          </div>
        </div>
        <div className="w-full md:w-[240px] flex-shrink-0 flex flex-col gap-12">
          <Button
            variant="flat"
            color="primary"
            className="w-full mood-cta-button"
            onClick={async () => {
              const productId = subscription?.product?.id?.toString();
              const priceId = subscription?.price?.id?.toString();

              if (productId && priceId) {
                await initiatePayment(productId, priceId);
              } else {
                window.location.href = '#/pricing';
              }
            }}
          >
            <Trans message="Reprendre le paiement" />
          </Button>
          <Button
            variant="outline"
            color="danger"
            className="w-full"
            onClick={() => {
              if (confirm('Êtes-vous sûr de vouloir annuler cette tentative de paiement ?\n\nVous serez redirigé vers la page des offres pour pouvoir reprendre votre abonnement.')) {
                const baseUrl = window.location.origin + '/Mood/moodmusic';
                fetch(`${baseUrl}/api/v1/billing/subscription/cancel-incomplete`, {
                  method: 'DELETE',
                  headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                  }
                }).then(response => {
                  if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                  }
                  return response.json();
                }).then(() => {
                  window.location.reload();
                }).catch(error => {
                  console.error('Erreur lors de la suppression:', error);
                  window.location.href = '#/pricing';
                });
              }
            }}
          >
            <Trans message="Annuler cette tentative" />
          </Button>
        </div>
      </div>
    </div>
  );
}
