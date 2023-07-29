import {Outlet} from 'react-router-dom';
import {PlayerContext} from '@common/player/player-context';
import {playerStoreOptions} from '@app/web-player/state/player-store-options';
import React, {useContext} from 'react';
import {useSettings} from '@common/core/settings/use-settings';
import {DashboardLayout} from '@common/ui/layout/dashboard-layout';
import {DashboardSidenav} from '@common/ui/layout/dashboard-sidenav';
import {Sidenav} from '@app/web-player/layout/sidenav';
import {DashboardContent} from '@common/ui/layout/dashboard-content';
import {QueueSidenav} from '@app/web-player/layout/queue/queue-sidenav';
import clsx from 'clsx';
import {useMediaQuery} from '@common/utils/hooks/use-media-query';
import {usePlayerStore} from '@common/player/hooks/use-player-store';
import {useIsMobileMediaQuery} from '@common/utils/hooks/is-mobile-media-query';
import {MobilePlayerControls} from '@app/web-player/player-controls/mobile-player-controls';
import {DesktopPlayerControls} from '@app/web-player/player-controls/desktop-player-controls';
import {PlayerOverlay} from '@app/web-player/overlay/player-overlay';
import {DashboardLayoutContext} from '@common/ui/layout/dashboard-layout-context';
import {PlayerNavbar} from '@app/web-player/layout/player-navbar';

export function WebPlayerLayout() {
  const {player} = useSettings();
  const isMobile = useIsMobileMediaQuery();

  return (
    <PlayerContext id="web-player" options={playerStoreOptions}>
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
      <PlayerOverlay />
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

interface MainProps {
  className?: string;
}
function Main({className}: MainProps) {
  const isMobile = useIsMobileMediaQuery();
  return (
    <main
      className={clsx(
        'overflow-x-hidden relative stable-scrollbar',
        className,
        // mobile player controls are fixed to bottom of screen,
        // make sure we can scroll to the bottom of the page
        isMobile && 'pb-124'
      )}
    >
      <div className="web-player-container @container min-h-full mx-auto p-16 md:p-30">
        <Outlet />
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
