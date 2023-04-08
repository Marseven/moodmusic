import {Artist} from '@app/web-player/artists/artist';
import {Trans} from '@common/i18n/trans';
import {SmallArtistImage} from '@app/web-player/artists/artist-image/small-artist-image';
import {Link} from 'react-router-dom';
import {getArtistLink} from '@app/web-player/artists/artist-link';
import {TopTracksTable} from '@app/web-player/artists/artist-page/discography-panel/top-tracks-table';
import {PaginationResponse} from '@common/http/backend-response/pagination-response';
import {Album} from '@app/web-player/albums/album';
import {ArtistAlbumsList} from '@app/web-player/artists/artist-page/discography-panel/artist-albums-list';
import {IconButton} from '@common/ui/buttons/icon-button';
import {ViewAgendaIcon} from '@common/icons/material/ViewAgenda';
import {GridViewIcon} from '@common/icons/material/GridView';
import {useLocalStorage} from '@common/utils/hooks/local-storage';
import {ArtistAlbumsGrid} from '@app/web-player/artists/artist-page/discography-panel/artist-albums-grid';
import {Tooltip} from '@common/ui/tooltip/tooltip';
import {
  albumGridViewPerPage,
  albumListViewPerPage,
  AlbumViewMode,
} from '@app/web-player/artists/requests/use-artist-albums';
import {useIsMobileMediaQuery} from '@common/utils/hooks/is-mobile-media-query';
import {useSettings} from '@common/core/settings/use-settings';
import {AdHost} from '@common/admin/ads/ad-host';
import React from 'react';

interface DiscographyTabProps {
  artist: Artist;
  initialAlbums?: PaginationResponse<Album>;
}
export function DiscographyTab({artist, initialAlbums}: DiscographyTabProps) {
  const {player} = useSettings();
  const [viewMode, setViewMode] = useLocalStorage<AlbumViewMode>(
    'artistPage.albumLayout',
    player?.default_artist_view || 'list'
  );
  return (
    <div>
      <Header artist={artist} />
      <AdHost slot="artist_bottom" className="mt-34" />
      <div className="mt-44">
        <div className="flex items-center border-b pb-4 mb-30 text-muted">
          <h2 className="text-base mr-auto">
            <Trans message="Albums" />
          </h2>
          <Tooltip label={<Trans message="List view" />}>
            <IconButton
              className="ml-24 flex-shrink-0"
              color={viewMode === 'list' ? 'primary' : undefined}
              onClick={() => setViewMode('list')}
            >
              <ViewAgendaIcon />
            </IconButton>
          </Tooltip>
          <Tooltip label={<Trans message="Grid view" />}>
            <IconButton
              className="flex-shrink-0"
              color={viewMode === 'grid' ? 'primary' : undefined}
              onClick={() => setViewMode('grid')}
            >
              <GridViewIcon />
            </IconButton>
          </Tooltip>
        </div>
        {viewMode === 'list' ? (
          <ArtistAlbumsList
            artist={artist}
            initialAlbums={
              initialAlbums?.per_page === albumListViewPerPage
                ? initialAlbums
                : null
            }
          />
        ) : (
          <ArtistAlbumsGrid
            artist={artist}
            initialAlbums={
              initialAlbums?.per_page === albumGridViewPerPage
                ? initialAlbums
                : null
            }
          />
        )}
      </div>
    </div>
  );
}

interface HeaderProps {
  artist: Artist;
}
function Header({artist}: HeaderProps) {
  const isMobile = useIsMobileMediaQuery();
  if (!artist.top_tracks?.length) return null;
  const similarArtists = artist.similar?.slice(0, 4) || [];

  return (
    <div className="flex items-start gap-30">
      <TopTracksTable tracks={artist.top_tracks} />
      {!isMobile && (
        <div className="w-1/3 max-w-320">
          <h2 className="text-muted text-base my-16">
            <Trans message="Similar artists" />
          </h2>
          <div>
            {similarArtists.map(similar => (
              <Link
                key={similar.id}
                to={getArtistLink(similar)}
                className="flex items-center gap-14 block p-4 mb-4 rounded hover:bg-hover cursor-pointer"
              >
                <SmallArtistImage
                  artist={similar}
                  className="w-44 h-44 object-cover rounded-full"
                />
                <div className="text-sm">{similar.name}</div>
              </Link>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}
