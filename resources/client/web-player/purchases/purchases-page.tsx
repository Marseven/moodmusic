import React from 'react';
import {useQuery} from '@tanstack/react-query';
import {apiClient} from '@common/http/query-client';
import {Trans} from '@common/i18n/trans';
import {IllustratedMessage} from '@common/ui/images/illustrated-message';
import {Purchase} from './purchase';
import {Track} from '@app/web-player/tracks/track';
import {Album} from '@app/web-player/albums/album';
import {TrackImage} from '@app/web-player/tracks/track-image/track-image';
import {AlbumImage} from '@app/web-player/albums/album-image/album-image';
import {getTrackLink} from '@app/web-player/tracks/track-link';
import {getAlbumLink} from '@app/web-player/albums/album-link';
import {Link} from 'react-router-dom';
import {FormattedDate} from '@common/i18n/formatted-date';
import {Button} from '@common/ui/buttons/button';
import {useSettings} from '@common/core/settings/use-settings';

interface PurchasesResponse {
  pagination: {
    data: Purchase[];
    current_page: number;
    last_page: number;
  };
}

function usePurchases() {
  return useQuery(['purchases'], () =>
    apiClient.get('purchases').then(r => r.data as PurchasesResponse),
  );
}

export function PurchasesPage() {
  const {data, isLoading} = usePurchases();
  const {base_url} = useSettings();

  if (isLoading) {
    return (
      <div className="flex items-center justify-center p-40">
        <Trans message="Chargement..." />
      </div>
    );
  }

  const purchases = data?.pagination?.data ?? [];

  if (!purchases.length) {
    return (
      <IllustratedMessage
        className="mt-40"
        title={<Trans message="Aucune force donnée" />}
        description={
          <Trans message="Tes titres et albums achetés apparaîtront ici" />
        }
      />
    );
  }

  const formatPrice = (amount: number, currency: string) => {
    if (currency === 'XOF' || currency === 'XAF') {
      return `${Number(amount).toLocaleString('fr-FR')} FCFA`;
    }
    return `${Number(amount).toLocaleString('fr-FR')} ${currency}`;
  };

  return (
    <div>
      <h1 className="text-2xl font-bold mb-24">
        <Trans message="Mes forces données" />
      </h1>
      <div className="flex flex-col gap-8">
        {purchases.map(purchase => {
          const item = purchase.purchasable;
          if (!item) return null;

          const isTrack = purchase.purchasable_type.includes('Track');
          const link = isTrack
            ? getTrackLink(item as Track)
            : getAlbumLink(item as Album);
          const downloadUrl = isTrack
            ? `${base_url}/api/v1/tracks/${item.id}/download`
            : null;

          return (
            <div
              key={purchase.id}
              className="flex items-center gap-16 rounded-lg border p-12"
            >
              <div className="flex-shrink-0">
                {isTrack ? (
                  <TrackImage
                    track={item as Track}
                    className="rounded"
                    size="w-56 h-56"
                  />
                ) : (
                  <AlbumImage
                    album={item as Album}
                    className="rounded"
                    size="w-56 h-56"
                  />
                )}
              </div>
              <div className="flex-auto min-w-0">
                <Link
                  to={link}
                  className="block font-medium truncate hover:underline"
                >
                  {item.name}
                </Link>
                <div className="text-sm text-muted flex items-center gap-8">
                  <span>{formatPrice(purchase.amount, purchase.currency)}</span>
                  {purchase.paid_at && (
                    <>
                      <span>&middot;</span>
                      <FormattedDate date={purchase.paid_at} />
                    </>
                  )}
                </div>
              </div>
              {downloadUrl && (
                <Button
                  size="xs"
                  variant="outline"
                  elementType="a"
                  href={downloadUrl}
                >
                  <Trans message="Télécharger" />
                </Button>
              )}
            </div>
          );
        })}
      </div>
    </div>
  );
}
