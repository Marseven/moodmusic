import {
  albumLayoutKey,
  useArtist,
  UseArtistParams,
} from '@app/web-player/artists/requests/use-artist';
import {ArtistPageHeader} from '@app/web-player/artists/artist-page/artist-page-header';
import {PageMetaTags} from '@common/http/page-meta-tags';
import {PageStatus} from '@common/http/page-status';
import {getFromLocalStorage} from '@common/utils/hooks/local-storage';
import React from 'react';
import {ArtistPageTabs} from '@app/web-player/artists/artist-page/artist-page-tabs';
import {useSettings} from '@common/core/settings/use-settings';
import {artistPageTabs} from '@app/web-player/artists/artist-page-tabs';
import {AdHost} from '@common/admin/ads/ad-host';

export function ArtistPage() {
  const isListView = getFromLocalStorage(albumLayoutKey, 'list') === 'list';
  const {artistPage} = useSettings();
  const tabIds = artistPage.tabs.filter(tab => tab.active).map(tab => tab.id);

  let load = ['similar', 'genres', 'profile'];
  if (tabIds.includes(artistPageTabs.tracks)) {
    load.push('tracks');
  }
  if (tabIds.includes(artistPageTabs.discography)) {
    load = [...load, 'albums', 'topTracks'];
  }
  const params: UseArtistParams = {
    with: load,
    withCount: 'likes',
    autoUpdate: true,
    loadAlbumTracks: isListView,
    albumsPerPage: isListView ? 5 : 25,
  };
  const query = useArtist(params);

  if (query.data) {
    return (
      <div>
        <PageMetaTags query={query} />
        <AdHost slot="general_top" className="mb-34" />
        <ArtistPageHeader artist={query.data.artist} />
        <AdHost slot="artist_top" className="mt-14" />
        <ArtistPageTabs data={query.data} />
        <AdHost slot="general_bottom" className="mt-34" />
      </div>
    );
  }

  return <PageStatus query={query} loaderClassName="absolute inset-0 m-auto" />;
}
