import React, {useEffect, useState} from 'react';
import {Dialog} from '@common/ui/overlays/dialog/dialog';
import {DialogHeader} from '@common/ui/overlays/dialog/dialog-header';
import {DialogBody} from '@common/ui/overlays/dialog/dialog-body';
import {DialogFooter} from '@common/ui/overlays/dialog/dialog-footer';
import {Trans} from '@common/i18n/trans';
import {Button} from '@common/ui/buttons/button';
import {useDialogContext} from '@common/ui/overlays/dialog/dialog-context';
import {Track} from '@app/web-player/tracks/track';
import {Album} from '@app/web-player/albums/album';
import {
  useInitiatePurchase,
  useCreateEbillingOrder,
  useCreateStripeCheckout,
  useCreatePaypalOrder,
} from './use-initiate-purchase';
import {toast} from '@common/ui/toast/toast';
import {message} from '@common/i18n/message';
import {apiClient, queryClient} from '@common/http/query-client';
import {useSettings} from '@common/core/settings/use-settings';

type Gateway = 'ebilling' | 'stripe' | 'paypal';

interface Props {
  item: Track | Album;
}

export function PurchaseDialog({item}: Props) {
  const {close} = useDialogContext();
  const {billing} = useSettings();
  const [isProcessing, setIsProcessing] = useState(false);

  const defaultGateway: Gateway = billing?.ebilling?.enable ? 'ebilling' : billing?.stripe?.enable ? 'stripe' : 'paypal';
  const [selectedGateway, setSelectedGateway] = useState<Gateway>(defaultGateway);

  const initiatePurchase = useInitiatePurchase();
  const createEbillingOrder = useCreateEbillingOrder();
  const createStripeCheckout = useCreateStripeCheckout();
  const createPaypalOrder = useCreatePaypalOrder();

  const [forcesCount, setForcesCount] = useState<number>(0);
  const [forcesArtistName, setForcesArtistName] = useState<string | null>(null);

  useEffect(() => {
    apiClient.get('purchases/forces-count', {
      params: {purchasable_type: item.model_type, purchasable_id: item.id},
    }).then(res => {
      setForcesCount(res.data.forces_count || 0);
      setForcesArtistName(res.data.artist_name || null);
    }).catch(() => {});
  }, [item.id, item.model_type]);

  const price = item.price ?? 0;
  const currency = item.currency ?? 'XAF';
  const artistName = item.artists?.[0]?.name;

  const handlePurchase = async () => {
    setIsProcessing(true);
    try {
      const result = await initiatePurchase.mutateAsync({
        purchasable_type: item.model_type as 'track' | 'album',
        purchasable_id: item.id,
        gateway: selectedGateway,
      });

      const purchaseId = result.purchase.id;

      let gatewayResult;
      switch (selectedGateway) {
        case 'ebilling':
          gatewayResult = await createEbillingOrder.mutateAsync({
            purchase_id: purchaseId,
          });
          break;
        case 'stripe':
          gatewayResult = await createStripeCheckout.mutateAsync({
            purchase_id: purchaseId,
          });
          break;
        case 'paypal':
          gatewayResult = await createPaypalOrder.mutateAsync({
            purchase_id: purchaseId,
          });
          break;
      }

      if (gatewayResult?.checkout_url) {
        window.location.href = gatewayResult.checkout_url;
      } else {
        toast.danger(message('Could not redirect to payment'));
        setIsProcessing(false);
      }
    } catch {
      setIsProcessing(false);
    }
  };

  const formatPrice = (amount: number, curr: string) => {
    if (curr === 'XOF' || curr === 'XAF') {
      return `${amount.toLocaleString('fr-FR')} FCFA`;
    }
    return `${amount.toLocaleString('fr-FR')} ${curr}`;
  };

  const allGateways: {id: Gateway; label: string; description: string}[] = [
    {
      id: 'ebilling',
      label: 'eBilling',
      description: 'Mobile Money / Carte bancaire',
    },
    {
      id: 'stripe',
      label: 'Stripe',
      description: 'Carte bancaire internationale',
    },
    {
      id: 'paypal',
      label: 'PayPal',
      description: 'Compte PayPal',
    },
  ];

  const gateways = allGateways.filter(gw => {
    if (gw.id === 'ebilling') return billing?.ebilling?.enable;
    if (gw.id === 'stripe') return billing?.stripe?.enable;
    if (gw.id === 'paypal') return billing?.paypal?.enable;
    return false;
  });

  return (
    <Dialog size="sm">
      <DialogHeader>
        <Trans message="Purchase" />
      </DialogHeader>
      <DialogBody>
        <div className="mb-20 rounded-lg border p-16">
          <div className="text-lg font-semibold">{item.name}</div>
          {artistName && (
            <div className="text-sm text-muted">{artistName}</div>
          )}
          <div className="mt-8 text-2xl font-bold text-primary">
            {formatPrice(price, currency)}
          </div>
          {forcesCount > 0 && (
            <div className="mt-8 text-xs text-muted">
              Déjà {forcesCount} force{forcesCount > 1 ? 's' : ''} donnée{forcesCount > 1 ? 's' : ''} à {forcesArtistName || artistName || 'cet artiste'}
            </div>
          )}
        </div>

        <div className="mb-8 text-sm font-medium">
          <Trans message="Payment method" />
        </div>
        <div className="flex flex-col gap-8">
          {gateways.map(gw => (
            <button
              key={gw.id}
              type="button"
              onClick={() => setSelectedGateway(gw.id)}
              className={`flex items-center gap-12 rounded-lg border p-12 text-left transition-colors ${
                selectedGateway === gw.id
                  ? 'border-primary bg-primary/5'
                  : 'border-divider hover:bg-hover'
              }`}
            >
              <div
                className={`h-16 w-16 rounded-full border-2 flex-shrink-0 ${
                  selectedGateway === gw.id
                    ? 'border-primary bg-primary'
                    : 'border-text-muted'
                }`}
              />
              <div>
                <div className="text-sm font-medium">{gw.label}</div>
                <div className="text-xs text-muted">{gw.description}</div>
              </div>
            </button>
          ))}
        </div>
      </DialogBody>
      <DialogFooter>
        <Button onClick={() => close()} disabled={isProcessing}>
          <Trans message="Cancel" />
        </Button>
        <Button
          variant="flat"
          color="primary"
          onClick={handlePurchase}
          disabled={isProcessing}
        >
          {isProcessing ? (
            <Trans message="Processing..." />
          ) : (
            <Trans
              message="Donner la force - :price"
              values={{price: formatPrice(price, currency)}}
            />
          )}
        </Button>
      </DialogFooter>
    </Dialog>
  );
}
