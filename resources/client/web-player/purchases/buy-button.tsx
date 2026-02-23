import React from 'react';
import {Button} from '@common/ui/buttons/button';
import {Trans} from '@common/i18n/trans';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {Track} from '@app/web-player/tracks/track';
import {Album} from '@app/web-player/albums/album';
import {PurchaseDialog} from './purchase-dialog';
import {useUserPurchases, isPurchased} from './use-user-purchases';
import {useAuth} from '@common/auth/use-auth';
import {ButtonSize} from '@common/ui/buttons/button-size';
import {useSettings} from '@common/core/settings/use-settings';
import {downloadFileFromUrl} from '@common/uploads/utils/download-file-from-url';
import {trackIsLocallyUploaded} from '@app/web-player/tracks/utils/track-is-locally-uploaded';

export function formatPrice(amount: number, curr: string): string {
  if (curr === 'XOF' || curr === 'XAF') {
    return `${amount.toLocaleString('fr-FR')} FCFA`;
  }
  return `${amount.toLocaleString('fr-FR')} ${curr}`;
}

interface Props {
  item: Track | Album;
  size?: ButtonSize;
  radius?: string;
  className?: string;
  compact?: boolean;
}

export function BuyButton({item, size = 'xs', radius = 'rounded', className, compact}: Props) {
  const {user, hasPermission} = useAuth();
  const {data} = useUserPurchases();
  const {base_url, player} = useSettings();

  const price = item.price;
  const currency = item.currency ?? 'XAF';

  if (!price || price <= 0) {
    return null;
  }

  const alreadyPurchased = isPurchased(
    data?.purchases,
    item.model_type,
    item.id,
  );

  if (alreadyPurchased) {
    const isTrack = item.model_type === 'track';
    const canDownload =
      isTrack &&
      !!player?.enable_download &&
      hasPermission('music.download') &&
      trackIsLocallyUploaded(item as Track);

    if (canDownload) {
      return (
        <Button
          size={size}
          variant="flat"
          color="positive"
          radius={radius}
          className={className}
          onClick={() => {
            downloadFileFromUrl(`${base_url}/api/v1/tracks/${item.id}/download`);
          }}
        >
          <Trans message="Download" />
        </Button>
      );
    }

    return (
      <span className="inline-flex items-center bg-positive/10 text-positive text-xs font-medium px-8 py-2 rounded-full whitespace-nowrap">
        <Trans message="Force donnée" />
      </span>
    );
  }

  if (!user) {
    return null;
  }

  return (
    <DialogTrigger type="modal">
      <Button
        size={size}
        variant="flat"
        color="primary"
        radius={radius}
        className={className}
      >
        {compact ? (
          formatPrice(price, currency)
        ) : (
          <Trans
            message="Donner la force - :price"
            values={{price: formatPrice(price, currency)}}
          />
        )}
      </Button>
      <PurchaseDialog item={item} />
    </DialogTrigger>
  );
}
