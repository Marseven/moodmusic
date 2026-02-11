import {useSettings} from '@common/core/settings/use-settings';
import {Link, NavLink} from 'react-router-dom';
import {useTrans} from '@common/i18n/use-trans';
import {useIsDarkMode} from '@common/ui/themes/use-is-dark-mode';
import {CustomMenu} from '@common/menus/custom-menu';
import {Trans} from '@common/i18n/trans';
import {IconButton} from '@common/ui/buttons/icon-button';
import {ModernPlusIcon, ModernDownloadIcon} from '@app/web-player/icons/modern-icons';
import {ReactNode} from 'react';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {CreatePlaylistDialog} from '@app/web-player/playlists/crupdate-dialog/create-playlist-dialog';
import {useAuthUserPlaylists} from '@app/web-player/playlists/requests/use-auth-user-playlists';
import {getPlaylistLink} from '@app/web-player/playlists/playlist-link';
import clsx from 'clsx';
import {useNavigate} from '@common/utils/hooks/use-navigate';
import {useAuthClickCapture} from '@app/web-player/use-auth-click-capture';
import {PwaInstallButton} from '@app/web-player/pwa/pwa-install-button';

const menuItemClassName = (isActive: boolean): string => {
  return clsx(
    'h-44 px-12 mx-12 rounded-lg mood-transition-smooth',
    isActive 
      ? 'mood-glass-button text-primary bg-primary/10' 
      : 'hover:bg-hover hover:mood-glass-button'
  );
};

interface Props {
  className?: string;
}
export function Sidenav({className}: Props) {
  return (
    <div className={clsx('mood-glass-nav py-12 overflow-y-auto', className)}>
      <Logo />
      <CustomMenu
        className="mt-24 items-stretch"
        menu="sidebar-primary"
        orientation="vertical"
        gap="gap-none"
        iconClassName="text-muted"
        itemClassName={({isActive}) => menuItemClassName(isActive)}
      />
      <div className="mt-48">
        <SectionTitle>
          <Trans message="Your Music" />
        </SectionTitle>
        <CustomMenu
          className="mt-12 text-sm items-stretch"
          menu="sidebar-secondary"
          orientation="vertical"
          gap="gap-none"
          iconClassName="text-muted"
          itemClassName={({isActive}) => menuItemClassName(isActive)}
        />
        <NavLink
          to="/library/purchases"
          className={({isActive}) =>
            clsx(menuItemClassName(isActive), 'flex items-center gap-8 text-sm mt-4')
          }
        >
          <ModernDownloadIcon className="text-muted" size="sm" />
          <Trans message="Mes achats" />
        </NavLink>
        <PlaylistSection />
      </div>
      <PwaInstallButton />
    </div>
  );
}

interface SectionTitleProps {
  children?: ReactNode;
}
function SectionTitle({children}: SectionTitleProps) {
  return (
    <div className="uppercase text-xs font-semibold text-muted mb-8 mx-24">
      {children}
    </div>
  );
}

function Logo() {
  const {branding} = useSettings();
  const {trans} = useTrans();
  const isDarkMode = useIsDarkMode();
  const logoUrl = isDarkMode ? branding.logo_light : branding.logo_dark;

  return (
    <Link
      to="/"
      className="block flex-shrink-0 mx-18"
      aria-label={trans({message: 'Go to homepage'})}
    >
      <img
        className="block w-auto h-56 max-w-[188px] object-contain"
        src={logoUrl}
        alt={trans({message: 'Site logo'})}
      />
    </Link>
  );
}

function PlaylistSection() {
  const {data} = useAuthUserPlaylists();
  const navigate = useNavigate();
  const authHandler = useAuthClickCapture();

  return (
    <div className="mt-40">
      <div className="flex items-center justify-between mr-24">
        <SectionTitle>
          <Trans message="Playlists" />
        </SectionTitle>
        <DialogTrigger
          type="modal"
          onClose={newPlaylist => {
            if (newPlaylist) {
              navigate(getPlaylistLink(newPlaylist));
            }
          }}
        >
          <IconButton
            className="flex-shrink-0 text-muted"
            onClickCapture={authHandler}
          >
            <ModernPlusIcon />
          </IconButton>
          <CreatePlaylistDialog />
        </DialogTrigger>
      </div>
      {data?.playlists?.map(playlist => (
        <NavLink
          to={getPlaylistLink(playlist)}
          key={playlist.id}
          className={({isActive}) =>
            clsx(menuItemClassName(isActive), 'flex items-center text-sm')
          }
        >
          <div className="overflow-hidden overflow-ellipsis">
            {playlist.name}
          </div>
        </NavLink>
      ))}
    </div>
  );
}
