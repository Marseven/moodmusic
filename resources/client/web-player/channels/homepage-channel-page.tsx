import {useSettings} from '@common/core/settings/use-settings';
import {ChannelPage} from '@app/web-player/channels/channel-page';
import {useAuth} from '@common/auth/use-auth';
import React from 'react';
import {useRadioStations} from '@app/web-player/radio-stations/use-radio-stations';
import {RadioStationGridItem} from '@app/web-player/radio-stations/radio-station-grid-item';
import {ModernRadioIcon} from '@app/web-player/icons/modern-icons';
import {Trans} from '@common/i18n/trans';
import {Link} from 'react-router-dom';

export function HomepageChannelPage() {
  const {homepage} = useSettings();
  const {user} = useAuth();
  let slugOrId = 'discover';
  if (homepage.type.startsWith('channel') && homepage.value) {
    slugOrId = homepage.value;
  }

  const firstName = user?.display_name?.split(' ')[0];

  return (
    <div>
      {user && (
        <h2 className="text-2xl font-semibold mb-16 px-8">
          On dit quoi, {firstName} ?
        </h2>
      )}
      <ChannelPage slugOrId={slugOrId} />
      <HomeRadioSection />
    </div>
  );
}

function HomeRadioSection() {
  const {data, isLoading} = useRadioStations();
  const stations = data?.stations;

  if (isLoading || !stations?.length) return null;

  return (
    <div className="mt-48">
      <div className="flex items-center justify-between mb-16">
        <div className="flex items-center gap-10">
          <ModernRadioIcon size="md" className="text-primary" />
          <h2 className="text-xl font-semibold">
            <Trans message="Stations Radio" />
          </h2>
          <span className="inline-flex items-center bg-primary text-white text-[9px] font-bold px-6 py-2 rounded-full animate-pulse">
            LIVE
          </span>
        </div>
        <Link
          to="/radio-stations"
          className="text-sm text-muted hover:text-primary transition-colors"
        >
          <Trans message="Voir tout" />
        </Link>
      </div>
      <div className="content-grid grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-16">
        {stations.slice(0, 6).map(station => (
          <RadioStationGridItem key={station.id} station={station} />
        ))}
      </div>
    </div>
  );
}
