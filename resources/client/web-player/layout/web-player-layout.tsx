import {Link, Outlet} from 'react-router-dom';
import {PlayerContext} from '@common/player/player-context';
import {playerStoreOptions} from '@app/web-player/state/player-store-options';
import React, {Fragment, useContext, useMemo, useRef} from 'react';
import {useSettings} from '@common/core/settings/use-settings';
import {
  closeGlobalDialog,
  useGlobalDialogStore,
} from '@app/web-player/state/global-dialog-store';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {Button} from '@common/ui/buttons/button';
import {Trans} from '@common/i18n/trans';
import {useAuth} from '@common/auth/use-auth';
import {MenuItem} from '@common/ui/navigation/menu/menu-trigger';
import {MicIcon} from '@common/icons/material/Mic';
import {usePrimaryArtistForCurrentUser} from '@app/web-player/backstage/use-primary-artist-for-current-user';
import {useNavigate} from '@common/utils/hooks/use-navigate';
import {DashboardLayout} from '@common/ui/layout/dashboard-layout';
import {DashboardSidenav} from '@common/ui/layout/dashboard-sidenav';
import {Sidenav} from '@app/web-player/layout/sidenav';
import {DashboardContent} from '@common/ui/layout/dashboard-content';
import {QueueSidenav} from '@app/web-player/layout/queue-sidenav';
import clsx from 'clsx';
import {useMediaQuery} from '@common/utils/hooks/use-media-query';
import {usePlayerStore} from '@common/player/hooks/use-player-store';
import {Navbar} from '@common/ui/navigation/navbar/navbar';
import {SearchAutocomplete} from '@app/web-player/search/search-autocomplete';
import {useIsMobileMediaQuery} from '@common/utils/hooks/is-mobile-media-query';
import {MobilePlayerControls} from '@app/web-player/player-controls/mobile-player-controls';
import {DesktopPlayerControls} from '@app/web-player/player-controls/desktop-player-controls';
import {PlayerOverlay} from '@app/web-player/overlay/player-overlay';
import {DashboardLayoutContext} from '@common/ui/layout/dashboard-layout-context';
import {getArtistLink} from '@app/web-player/artists/artist-link';

export function WebPlayerLayout() {
  const playerRef = useRef<HTMLDivElement>(null);
  const {player} = useSettings();
  const isMobile = useIsMobileMediaQuery();

  const options = useMemo(() => {
    return {
      ...playerStoreOptions,
      ref: playerRef,
    };
  }, []);

  return (
    <PlayerContext id="web-player" options={options}>
      <DashboardLayout
        name="web-player"
        initialRightSidenavStatus={player?.hide_queue ? 'closed' : 'open'}
      >
        {!isMobile && <PlayerNavbar />}
        {!isMobile && (
          <DashboardSidenav position="left" display="block">
            <Sidenav />
          </DashboardSidenav>
        )}
        <DashboardContent>
          <Main />
        </DashboardContent>
        {!isMobile && <RightSidenav />}
        <PlayerControlsBar />
      </DashboardLayout>
      <PlayerOverlay playerRef={playerRef} />
    </PlayerContext>
  );
}

function PlayerControlsBar() {
  const {isMobileMode} = useContext(DashboardLayoutContext);
  if (isMobileMode) {
    return <MobilePlayerControls />;
  }
  return <DesktopPlayerControls />;
}

function PlayerNavbar() {
  const navigate = useNavigate();
  const primaryArtist = usePrimaryArtistForCurrentUser();
  const {player} = useSettings();
  const menuItems = useMemo(() => {
    if (primaryArtist) {
      return [
        <MenuItem
          value="author"
          key="author"
          startIcon={<MicIcon />}
          onSelected={() => {
            navigate(getArtistLink(primaryArtist));
          }}
        >
          <Trans message="Artist profile" />
        </MenuItem>,
      ];
    }
    if (player?.show_become_artist_btn) {
      return [
        <MenuItem
          value="author"
          key="author"
          startIcon={<MicIcon />}
          onSelected={() => {
            navigate('/backstage/requests');
          }}
        >
          <Trans message="Become an author" />
        </MenuItem>,
      ];
    }

    return [];
  }, [primaryArtist, navigate, player?.show_become_artist_btn]);
  return (
    <Navbar
      hideLogo
      color="bg"
      darkModeColor="bg"
      size="sm"
      authMenuItems={menuItems}
      className="dashboard-grid-header"
    >
      <SearchAutocomplete />
      <ActionButtons />
    </Navbar>
  );
}

interface MainProps {
  className?: string;
}
function Main({className}: MainProps) {
  const isMobile = useIsMobileMediaQuery();
  return (
    <main
      className={clsx(
        'overflow-x-hidden relative',
        className,
        // mobile player controls are fixed to bottom of screen,
        // make sure we can scroll to the bottom of the page
        isMobile && 'pb-124'
      )}
    >
      <div className="web-player-container @container min-h-full mx-auto p-16 md:p-30">
        <Outlet />
        <GlobalDialogContainer />
      </div>
    </main>
  );
}

function RightSidenav() {
  const isOverlay = useMediaQuery('(max-width: 1280px)');
  const hideQueue = usePlayerStore(s => !s.shuffledQueue.length);
  return (
    <DashboardSidenav
      position="right"
      size="w-256"
      mode={isOverlay ? 'overlay' : undefined}
      overlayPosition="absolute"
      display="block"
      forceClosed={hideQueue}
    >
      <QueueSidenav />
    </DashboardSidenav>
  );
}

function ActionButtons() {
  const {player, billing} = useSettings();
  const {isLoggedIn, hasPermission, isSubscribed} = useAuth();

  const showUploadButton =
    player?.show_upload_btn && isLoggedIn && hasPermission('music.create');
  const showTryProButton =
    billing?.enable && hasPermission('plans.view') && !isSubscribed;

  return (
    <Fragment>
      {showTryProButton && (
        <Button
          variant="outline"
          size="xs"
          color="primary"
          elementType={Link}
          to="/pricing"
        >
          <Trans message="Try Pro" />
        </Button>
      )}
      {showUploadButton && (
        <Button
          variant={showTryProButton ? 'text' : 'outline'}
          size="xs"
          color={showTryProButton ? undefined : 'primary'}
          elementType={Link}
          to="/backstage/upload"
        >
          <Trans message="Upload" />
        </Button>
      )}
    </Fragment>
  );
}

function GlobalDialogContainer() {
  const {dialog: DialogElement, data} = useGlobalDialogStore();
  return (
    <DialogTrigger
      type="modal"
      isOpen={DialogElement != null}
      onClose={value => {
        closeGlobalDialog(value);
      }}
    >
      {DialogElement ? <DialogElement {...data} /> : null}
    </DialogTrigger>
  );
}
