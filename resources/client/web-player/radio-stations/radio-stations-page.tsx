import {Fragment} from 'react';
import {Trans} from '@common/i18n/trans';
import {StaticPageTitle} from '@common/seo/static-page-title';
import {AdHost} from '@common/admin/ads/ad-host';
import {useRadioStations} from './use-radio-stations';
import {RadioStationGridItem} from './radio-station-grid-item';
import {ContentGrid} from '@app/web-player/playable-item/content-grid';
import {PageErrorMessage} from '@common/errors/page-error-message';
import {MoodEmptyState} from '@app/web-player/mood-empty-state';
import {ModernRadioIcon} from '@app/web-player/icons/modern-icons';

export function RadioStationsPage() {
  const {data, isLoading, isError} = useRadioStations();

  if (isError) {
    return <PageErrorMessage />;
  }

  const stations = data?.stations || [];

  return (
    <Fragment>
      <StaticPageTitle>
        <Trans message="Stations Radio" />
      </StaticPageTitle>
      <AdHost slot="general_top" className="mb-34" />
      <div className="flex items-center gap-12 mb-34">
        <ModernRadioIcon size="lg" className="text-primary" />
        <h1 className="text-2xl font-semibold">
          <Trans message="Stations Radio" />
        </h1>
      </div>
      {isLoading ? (
        <ContentGrid>
          {[1, 2, 3, 4, 5, 6].map(i => (
            <div
              key={i}
              className="mood-glass-panel rounded-xl animate-pulse"
            >
              <div className="w-full aspect-square bg-fg-base/4 rounded-t-xl" />
              <div className="p-12 space-y-8">
                <div className="h-14 bg-fg-base/4 rounded w-3/4" />
                <div className="h-10 bg-fg-base/4 rounded w-1/2" />
              </div>
            </div>
          ))}
        </ContentGrid>
      ) : stations.length > 0 ? (
        <ContentGrid>
          {stations.map(station => (
            <RadioStationGridItem key={station.id} station={station} />
          ))}
        </ContentGrid>
      ) : (
        <MoodEmptyState
          icon={ModernRadioIcon}
          title={<Trans message="Aucune station radio" />}
          description={
            <Trans message="Les stations radio seront bientôt disponibles." />
          }
        />
      )}
      <AdHost slot="general_bottom" className="mt-34" />
    </Fragment>
  );
}
