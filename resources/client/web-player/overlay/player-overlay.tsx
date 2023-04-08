import React, {
  Fragment,
  memo,
  MutableRefObject,
  useContext,
  useEffect,
  useRef,
} from 'react';
import {PlayerStoreContext} from '@common/player/player-context';
import {usePlayerStore} from '@common/player/hooks/use-player-store';
import clsx from 'clsx';
import {
  playerOverlayState,
  usePlayerOverlayStore,
} from '@app/web-player/state/player-overlay-store';
import {IconButton} from '@common/ui/buttons/icon-button';
import {KeyboardArrowDownIcon} from '@common/icons/material/KeyboardArrowDown';
import {QueueMusicIcon} from '@common/icons/material/QueueMusic';
import {PlaybackControls} from '@app/web-player/player-controls/playback-controls';
import {useCuedTrack} from '@app/web-player/player-controls/use-cued-track';
import {ArtistLinks} from '@app/web-player/artists/artist-links';
import {LikeIconButton} from '@app/web-player/library/like-icon-button';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {TrackContextDialog} from '@app/web-player/tracks/context-dialog/track-context-dialog';
import {MoreVertIcon} from '@common/icons/material/MoreVert';
import fscreen from 'fscreen';
import {FullscreenIcon} from '@common/icons/material/Fullscreen';
import {useIsMobileMediaQuery} from '@common/utils/hooks/is-mobile-media-query';
import {TrackTable} from '@app/web-player/tracks/track-table/track-table';
import {LyricsButton} from '@app/web-player/player-controls/lyrics-button';
import {useMediaQuery} from '@common/utils/hooks/use-media-query';
import {DownloadTrackButton} from '@app/web-player/player-controls/download-track-button';
import {useSettings} from '@common/core/settings/use-settings';
import {TrackLink} from '@app/web-player/tracks/track-link';

interface Props {
  playerRef: MutableRefObject<HTMLDivElement | null>;
}
export function PlayerOverlay({playerRef}: Props) {
  const {player} = useSettings();
  const isMobile = useMediaQuery('(max-width: 1024px)');
  const isMaximized = usePlayerOverlayStore(s => s.isMaximized);
  const isQueueOpen = usePlayerOverlayStore(s => s.isQueueOpen);
  const mediaIsCued = usePlayerStore(s => s.cuedMedia != null);
  const overlayRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (!isMaximized) return;
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === 'Escape') {
        playerOverlayState.toggle();
      }
    };
    document.addEventListener('keydown', handleKeyDown);
    return () => document.removeEventListener('keydown', handleKeyDown);
  }, [isMaximized]);

  return (
    <div
      ref={overlayRef}
      className={clsx(
        'fixed bg right-0 transition-all outline-none',
        (!mediaIsCued || (isMobile && !isMaximized)) && 'hidden',
        isMaximized
          ? 'bottom-0 w-full h-full flex flex-col pb-50 player-overlay-bg'
          : 'bottom-96 right-0 w-256 h-[213px]',
        // hide video in sidebar on mobile or if hidden in settings
        (player?.hide_video || isMobile) && !isMaximized && 'hidden'
      )}
    >
      {isMaximized && (
        <div className="flex items-center flex-shrink-0 p-10 mb-10">
          <IconButton
            className="mr-auto"
            onClick={() => playerOverlayState.toggle()}
          >
            <KeyboardArrowDownIcon />
          </IconButton>
          {isMobile && <LyricsButton />}
          {isMobile && <DownloadTrackButton />}
          <IconButton
            onClick={() => playerOverlayState.toggleQueue()}
            color={isQueueOpen ? 'primary' : undefined}
          >
            <QueueMusicIcon />
          </IconButton>
          <FullscreenButton overlayRef={overlayRef} />
        </div>
      )}
      <div
        className={clsx(
          'min-h-0 max-w-full flex-auto',
          isMaximized && isMobile && 'aspect-square',
          isMaximized && !isMobile && 'aspect-video',
          isMaximized ? 'mx-auto px-14 mt-auto' : 'w-full h-full'
        )}
      >
        <PlayerContainer playerRef={playerRef} />
      </div>
      {isMaximized && (
        <Fragment>
          <QueuedTrack />
          <PlaybackControls className="container mx-auto px-14 flex-shrink-0 mb-auto" />
        </Fragment>
      )}
      {isMaximized && isQueueOpen && <PlayerQueue />}
    </div>
  );
}

interface FullscreenButtonProps {
  overlayRef: MutableRefObject<HTMLDivElement | null>;
}
function FullscreenButton({overlayRef}: FullscreenButtonProps) {
  const isUninitialized = usePlayerStore(s => s.status === 'uninitialized');
  const isMobile = useIsMobileMediaQuery();
  if (!fscreen.fullscreenEnabled || isMobile) {
    return null;
  }

  return (
    <IconButton
      className="flex-shrink-0 ml-12"
      size="sm"
      disabled={isUninitialized}
      onClick={() => {
        if (!overlayRef.current) return;
        if (fscreen.fullscreenElement) {
          fscreen.exitFullscreen();
        } else {
          fscreen.requestFullscreen(overlayRef.current);
        }
      }}
    >
      <FullscreenIcon />
    </IconButton>
  );
}

function QueuedTrack() {
  const track = useCuedTrack();

  if (!track) {
    return null;
  }

  return (
    <div className="container mx-auto px-14 my-40 flex-shrink-0 flex items-center justify-center gap-34">
      <LikeIconButton likeable={track} />
      <div className="text-center">
        <div className="text-base">
          <TrackLink track={track} />
        </div>
        <div className="text-sm text-muted">
          <ArtistLinks artists={track.artists} />
        </div>
      </div>
      <DialogTrigger type="popover">
        <IconButton>
          <MoreVertIcon />
        </IconButton>
        <TrackContextDialog tracks={[track]} />
      </DialogTrigger>
    </div>
  );
}

interface PlayerContainerProps {
  playerRef: MutableRefObject<HTMLDivElement | null>;
}
const PlayerContainer = memo(({playerRef}: PlayerContainerProps) => {
  const {getState} = useContext(PlayerStoreContext);
  return (
    <div
      className="w-full h-full flex-auto bg-black player-container"
      ref={el => {
        if (el) {
          playerRef.current = el;
          getState().init();
        } else {
          getState().destroy();
        }
      }}
    />
  );
});

function PlayerQueue() {
  const queue = usePlayerStore(s => s.shuffledQueue);
  return (
    <div className="bg-inherit fixed top-70 left-0 right-0 bottom-0 px-14 md:px-50 overflow-y-auto">
      <TrackTable tracks={queue.map(item => item.meta)} />
    </div>
  );
}
