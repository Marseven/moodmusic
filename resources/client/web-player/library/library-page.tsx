import {Trans} from '@common/i18n/trans';
import {StaticPageTitle} from '@common/seo/static-page-title';
import React, {Fragment, ReactElement, ReactNode} from 'react';
import {Link, Navigate} from 'react-router-dom';
import {SvgIconProps} from '@common/icons/svg-icon';
import {getPlaylistLink} from '@app/web-player/playlists/playlist-link';
import {IconButton} from '@common/ui/buttons/icon-button';
import {
  ModernMusicNoteIcon,
  ModernDiscIcon,
  ModernMicrophoneIcon,
  ModernQueueListIcon,
  ModernClockIcon,
  ModernPlusIcon,
  ModernDownloadIcon,
} from '@app/web-player/icons/modern-icons';
import {CreatePlaylistDialog} from '@app/web-player/playlists/crupdate-dialog/create-playlist-dialog';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {useNavigate} from '@common/utils/hooks/use-navigate';
import {useAuthClickCapture} from '@app/web-player/use-auth-click-capture';
import {useUserPlaylists} from '@app/web-player/library/requests/use-user-playlists';
import {PlaylistImage} from '@app/web-player/playlists/playlist-image';
import {InfiniteScrollSentinel} from '@common/ui/infinite-scroll/infinite-scroll-sentinel';
import {AdHost} from '@common/admin/ads/ad-host';
import {useIsTabletMediaQuery} from '@common/utils/hooks/is-tablet-media-query';

export function LibraryPage() {
  const navigate = useNavigate();
  const authHandler = useAuthClickCapture();
  const query = useUserPlaylists('me');
  const isSmallScreen = useIsTabletMediaQuery();

  if (!isSmallScreen) {
    return <Navigate to="/library/songs" replace />;
  }

  return (
    <Fragment>
      <StaticPageTitle>
        <Trans message="Your tracks" />
      </StaticPageTitle>
      <AdHost slot="general_top" className="mb-34" />
      <div className="flex items-center justify-between gap-24 mb-20">
        <h1 className="text-2xl font-semibold whitespace-nowrap">
          <Trans message="Your library" />
        </h1>
        <DialogTrigger
          type="modal"
          onClose={newPlaylist => {
            if (newPlaylist) {
              navigate(getPlaylistLink(newPlaylist));
            }
          }}
        >
          <IconButton className="flex-shrink-0" onClickCapture={authHandler}>
            <ModernPlusIcon />
          </IconButton>
          <CreatePlaylistDialog />
        </DialogTrigger>
      </div>
      <div>
        <MenuItem
          icon={<ModernMusicNoteIcon className="text-main" />}
          to="/library/songs"
        >
          <Trans message="Songs" />
        </MenuItem>
        <MenuItem icon={<ModernQueueListIcon />} to="/library/playlists">
          <Trans message="Playlists" />
        </MenuItem>
        <MenuItem icon={<ModernDiscIcon />} to="/library/albums">
          <Trans message="Albums" />
        </MenuItem>
        <MenuItem icon={<ModernMicrophoneIcon />} to="/library/artists">
          <Trans message="Artists" />
        </MenuItem>
        <MenuItem icon={<ModernClockIcon />} to="/library/history">
          <Trans message="Play history" />
        </MenuItem>
        <MenuItem icon={<ModernDownloadIcon />} to="/library/purchases">
          <Trans message="Purchases" />
        </MenuItem>
        {query.items.map(playlist => (
          <MenuItem
            key={playlist.id}
            wrapIcon={false}
            icon={
              <PlaylistImage
                size="w-42 h-42"
                className="rounded"
                playlist={playlist}
              />
            }
            to={getPlaylistLink(playlist)}
          >
            {playlist.name}
          </MenuItem>
        ))}
        <InfiniteScrollSentinel query={query} />
      </div>
    </Fragment>
  );
}

interface MenuItemProps {
  icon: ReactElement<SvgIconProps>;
  children: ReactNode;
  to: string;
  wrapIcon?: boolean;
}
function MenuItem({icon, children, to, wrapIcon = true}: MenuItemProps) {
  return (
    <Link className="flex items-center gap-14 mb-18 text-sm" to={to}>
      {wrapIcon ? (
        <div className="rounded bg-chip p-8 w-42 h-42">{icon}</div>
      ) : (
        icon
      )}
      {children}
    </Link>
  );
}
