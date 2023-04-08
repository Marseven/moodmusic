import {TrackImage} from '@app/web-player/tracks/track-image/track-image';
import {ArtistLinks} from '@app/web-player/artists/artist-links';
import {ReactNode, useContext} from 'react';
import {useCuedTrack} from '@app/web-player/player-controls/use-cued-track';
import {usePlayerStore} from '@common/player/hooks/use-player-store';
import {PlaybackControls} from '@app/web-player/player-controls/playback-controls';
import {Slider} from '@common/ui/forms/slider/slider';
import {usePlayerActions} from '@common/player/hooks/use-player-actions';
import {IconButton} from '@common/ui/buttons/icon-button';
import {useTrans} from '@common/i18n/use-trans';
import {message} from '@common/i18n/message';
import {VolumeUpIcon} from '@common/icons/material/VolumeUp';
import {VolumeOffIcon} from '@common/icons/material/VolumeOff';
import {VolumeDownIcon} from '@common/icons/material/VolumeDown';
import {QueueMusicIcon} from '@common/icons/material/QueueMusic';
import {LikeIconButton} from '@app/web-player/library/like-icon-button';
import {DashboardLayoutContext} from '@common/ui/layout/dashboard-layout-context';
import {
  playerOverlayState,
  usePlayerOverlayStore,
} from '@app/web-player/state/player-overlay-store';
import {KeyboardArrowDownIcon} from '@common/icons/material/KeyboardArrowDown';
import {KeyboardArrowUpIcon} from '@common/icons/material/KeyboardArrowUp';
import {LyricsButton} from '@app/web-player/player-controls/lyrics-button';
import {DownloadTrackButton} from '@app/web-player/player-controls/download-track-button';
import {useSettings} from '@common/core/settings/use-settings';

export function DesktopPlayerControls() {
  const mediaIsCued = usePlayerStore(s => s.cuedMedia != null);
  if (!mediaIsCued) return null;

  return (
    <div className="h-96 px-16 flex items-center justify-between border-t bg dashboard-grid-footer z-30">
      <QueuedTrack />
      <PlaybackControls className="w-2/5 max-w-[722px]" />
      <SecondaryControls />
    </div>
  );
}

function QueuedTrack() {
  const track = useCuedTrack();
  let content: ReactNode;

  if (track) {
    content = (
      <div className="flex items-center gap-14">
        <TrackImage className="rounded w-56 h-56 object-cover" track={track} />
        <div>
          <div className="text-sm">{track.name}</div>
          <div className="text-xs text-muted">
            <ArtistLinks artists={track.artists} />
          </div>
        </div>
        <LikeIconButton likeable={track} />
      </div>
    );
  } else {
    content = null;
  }

  return <div className="min-w-180 w-[30%]">{content}</div>;
}

function SecondaryControls() {
  const {rightSidenavStatus, setRightSidenavStatus} = useContext(
    DashboardLayoutContext
  );
  return (
    <div className="flex items-center justify-end min-w-180 w-[30%]">
      <LyricsButton />
      <DownloadTrackButton />
      <IconButton
        className="flex-shrink-0"
        onClick={() => {
          setRightSidenavStatus(
            rightSidenavStatus === 'closed' ? 'open' : 'closed'
          );
        }}
      >
        <QueueMusicIcon />
      </IconButton>
      <VolumeControls />
      <OverlayButton />
    </div>
  );
}

function OverlayButton() {
  const isActive = usePlayerOverlayStore(s => s.isMaximized);
  const isUninitialized = usePlayerStore(s => s.status === 'uninitialized');
  const {player} = useSettings();

  if (player?.hide_video_button) {
    return null;
  }

  return (
    <IconButton
      className="flex-shrink-0 ml-26"
      color="chip"
      variant="flat"
      radius="rounded"
      size="xs"
      iconSize="sm"
      disabled={isUninitialized}
      onClick={() => {
        playerOverlayState.toggle();
      }}
    >
      {isActive ? <KeyboardArrowDownIcon /> : <KeyboardArrowUpIcon />}
    </IconButton>
  );
}

function VolumeControls() {
  const volume = usePlayerStore(s => s.volume);
  const player = usePlayerActions();
  const isUninitialized = usePlayerStore(s => s.status === 'uninitialized');

  return (
    <div className="flex w-min items-center gap-4">
      <ToggleMuteButton />
      <Slider
        isDisabled={isUninitialized}
        showThumbOnHoverOnly
        thumbSize="w-14 h-14"
        trackColor="neutral"
        minValue={0}
        maxValue={100}
        className="flex-auto"
        width="w-96"
        value={volume}
        onChange={value => {
          player.setVolume(value);
        }}
      />
    </div>
  );
}

function ToggleMuteButton() {
  const {trans} = useTrans();
  const isMuted = usePlayerStore(s => s.muted);
  const volume = usePlayerStore(s => s.volume);
  const player = usePlayerActions();
  const isUninitialized = usePlayerStore(s => s.status === 'uninitialized');

  if (isMuted) {
    return (
      <IconButton
        disabled={isUninitialized}
        size="sm"
        iconSize="md"
        aria-label={trans(message('Unmute'))}
        onClick={() => player.setMuted(false)}
      >
        <VolumeOffIcon />
      </IconButton>
    );
  }
  return (
    <IconButton
      disabled={isUninitialized}
      size="sm"
      iconSize="md"
      aria-label={trans(message('Mute'))}
      onClick={() => player.setMuted(true)}
    >
      {volume < 40 ? <VolumeDownIcon /> : <VolumeUpIcon />}
    </IconButton>
  );
}
