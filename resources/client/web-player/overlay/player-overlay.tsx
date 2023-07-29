import React, {
  Fragment,
  MutableRefObject,
  useContext,
  useEffect,
  useMemo,
  useRef,
} from 'react';
import {usePlayerStore} from '@common/player/hooks/use-player-store';
import clsx from 'clsx';
import {
  playerOverlayState,
  usePlayerOverlayStore,
} from '@app/web-player/state/player-overlay-store';
import {IconButton} from '@common/ui/buttons/icon-button';
import {KeyboardArrowDownIcon} from '@common/icons/material/KeyboardArrowDown';
import {PlaybackControls} from '@app/web-player/player-controls/playback-controls';
import {useCuedTrack} from '@app/web-player/player-controls/use-cued-track';
import {ArtistLinks} from '@app/web-player/artists/artist-links';
import {LikeIconButton} from '@app/web-player/library/like-icon-button';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {TrackContextDialog} from '@app/web-player/tracks/context-dialog/track-context-dialog';
import {MoreVertIcon} from '@common/icons/material/MoreVert';
import fscreen from 'fscreen';
import {useIsMobileMediaQuery} from '@common/utils/hooks/is-mobile-media-query';
import {TrackTable} from '@app/web-player/tracks/track-table/track-table';
import {LyricsButton} from '@app/web-player/player-controls/lyrics-button';
import {useMediaQuery} from '@common/utils/hooks/use-media-query';
import {DownloadTrackButton} from '@app/web-player/player-controls/download-track-button';
import {TrackLink} from '@app/web-player/tracks/track-link';
import {useLocation} from 'react-router-dom';
import {usePrevious} from '@common/utils/hooks/use-previous';
import {PlayerOutlet} from '@common/player/ui/player-outlet';
import {PlayerPoster} from '@common/player/ui/controls/player-poster';
import {MediaFullscreenIcon} from '@common/icons/media/media-fullscreen';
import {MediaQueueListIcon} from '@common/icons/media/media-queue-list';
import {useMiniPlayerIsHidden} from '@app/web-player/overlay/use-mini-player-is-hidden';
import {usePlayerClickHandler} from '@common/player/hooks/use-player-click-handler';
import {QueueTrackContextDialog} from '@app/web-player/layout/queue/queue-track-context-dialog';
import {RowElementProps} from '@common/ui/tables/table-row';
import {Track} from '@app/web-player/tracks/track';
import {TableContext} from '@common/ui/tables/table-context';
import {MediaItem} from '@common/player/media-item';

export function PlayerOverlay() {
  const isMobile = useMediaQuery('(max-width: 1024px)');
  const isMaximized = usePlayerOverlayStore(s => s.isMaximized);
  const isQueueOpen = usePlayerOverlayStore(s => s.isQueueOpen);
  const isFullscreen = usePlayerStore(s => s.isFullscreen);
  const miniPlayerIsHidden = useMiniPlayerIsHidden();
  const overlayRef = useRef<HTMLDivElement>(null);
  const {pathname} = useLocation();
  const playerClickHandler = usePlayerClickHandler();
  const haveVideo = usePlayerStore(
    s => s.providerApi != null && s.providerName !== 'htmlAudio'
  );
  const previousPathname = usePrevious(pathname);

  // close overlay when route changes
  useEffect(() => {
    if (isMaximized && previousPathname && pathname !== previousPathname) {
      playerOverlayState.toggle();
    }
  }, [pathname, previousPathname, isMaximized]);

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
        miniPlayerIsHidden && !isMaximized && 'hidden',
        isMaximized
          ? 'bottom-0 w-full h-full flex flex-col pb-50 player-overlay-bg'
          : 'bottom-96 right-0 w-256 h-[213px]'
      )}
    >
      {isMaximized && (
        <div className="flex items-center flex-shrink-0 p-10 mb-10">
          <IconButton
            iconSize="lg"
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
            <MediaQueueListIcon />
          </IconButton>
          <FullscreenButton overlayRef={overlayRef} />
        </div>
      )}
      <div
        onClick={() => {
          // native video will be put into fullscreen, it will already handle click and double click events
          if (!isFullscreen) {
            playerClickHandler();
          }
        }}
        className={clsx(
          'min-h-0 max-w-full flex-auto relative',
          isMaximized ? 'mx-auto px-14 mt-auto' : 'w-full h-full',
          isMaximized && haveVideo ? 'aspect-video' : 'aspect-square max-h-400'
        )}
      >
        <PlayerPoster className="absolute inset-0" />
        <div
          className={haveVideo ? 'w-full h-full flex-auto bg-black' : undefined}
        >
          <PlayerOutlet className="w-full h-full" />
        </div>
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
  const playerReady = usePlayerStore(s => s.providerReady);
  const isMobile = useIsMobileMediaQuery();
  if (!fscreen.fullscreenEnabled || isMobile) {
    return null;
  }

  return (
    <IconButton
      className="flex-shrink-0 ml-12"
      disabled={!playerReady}
      onClick={() => {
        if (!overlayRef.current) return;
        if (fscreen.fullscreenElement) {
          fscreen.exitFullscreen();
        } else {
          fscreen.requestFullscreen(overlayRef.current);
        }
      }}
    >
      <MediaFullscreenIcon />
    </IconButton>
  );
}

function QueuedTrack() {
  const track = useCuedTrack();
  const isMobile = useIsMobileMediaQuery();

  if (!track) {
    return null;
  }

  return (
    <div
      className={clsx(
        'container mx-auto px-14 flex-shrink-0 flex items-center justify-center gap-34',
        isMobile ? 'my-40' : 'my-60'
      )}
    >
      <LikeIconButton likeable={track} />
      <div className="text-center min-w-0">
        <div className="text-base whitespace-nowrap overflow-hidden overflow-ellipsis">
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

function PlayerQueue() {
  const queue = usePlayerStore(s => s.shuffledQueue);
  const tracks = queue.map(item => item.meta);
  return (
    <div className="bg-inherit fixed top-70 left-0 right-0 bottom-0 px-14 md:px-50 overflow-y-auto">
      <TrackTable
        tracks={tracks}
        queueGroupId={queue[0]?.groupId}
        renderRowAs={PlayerQueueRow}
      />
    </div>
  );
}

function PlayerQueueRow({item, children, ...domProps}: RowElementProps<Track>) {
  const queue = usePlayerStore(s => s.shuffledQueue);
  const {selectedRows} = useContext(TableContext);
  const queueItems = useMemo(() => {
    return selectedRows
      .map(trackId => queue.find(item => item.meta.id === trackId))
      .filter(t => !!t) as MediaItem[];
  }, [queue, selectedRows]);

  const row = <div {...domProps}>{children}</div>;
  if (item.isPlaceholder) {
    return row;
  }

  return (
    <DialogTrigger type="popover" triggerOnContextMenu placement="bottom-start">
      {row}
      <QueueTrackContextDialog queueItems={queueItems} />
    </DialogTrigger>
  );
}
