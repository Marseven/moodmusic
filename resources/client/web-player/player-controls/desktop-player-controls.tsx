import {TrackImage} from '@app/web-player/tracks/track-image/track-image';
import {ArtistLinks} from '@app/web-player/artists/artist-links';
import React, {ReactNode, useContext} from 'react';
import {useCuedTrack} from '@app/web-player/player-controls/use-cued-track';
import {usePlayerStore} from '@common/player/hooks/use-player-store';
import {PlaybackControls} from '@app/web-player/player-controls/playback-controls';
import {IconButton} from '@common/ui/buttons/icon-button';
import {LikeIconButton} from '@app/web-player/library/like-icon-button';
import {DashboardLayoutContext} from '@common/ui/layout/dashboard-layout-context';
import {
  playerOverlayState,
  usePlayerOverlayStore,
} from '@app/web-player/state/player-overlay-store';
import {ModernCollapseIcon, ModernExpandIcon, ModernQueueListIcon, ModernRadioIcon} from '@app/web-player/icons/modern-icons';
import {LyricsButton} from '@app/web-player/player-controls/lyrics-button';
import {DownloadTrackButton} from '@app/web-player/player-controls/download-track-button';
import {useSettings} from '@common/core/settings/use-settings';
import {getTrackLink, TrackLink} from '@app/web-player/tracks/track-link';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {TrackContextDialog} from '@app/web-player/tracks/context-dialog/track-context-dialog';
import {Link} from 'react-router-dom';
import {ArtistContextDialog} from '@app/web-player/artists/artist-context-dialog';
import {VolumeControls} from '@common/player/ui/controls/volume-controls';
import {Tooltip} from '@common/ui/tooltip/tooltip';
import {Trans} from '@common/i18n/trans';
import {Track} from '@app/web-player/tracks/track';
import {MOOD_CONFIG} from '@app/web-player/player-controls/mood-config';

export function DesktopPlayerControls() {
  const mediaIsCued = usePlayerStore(s => s.cuedMedia != null);
  if (!mediaIsCued) return null;

  return (
    <div className="h-auto flex items-center justify-between border-t bg dashboard-grid-footer z-30 music-player-container mood-system-enhanced">
      <QueuedTrack />
      <PlaybackControls className="w-2/5 max-w-[722px]" />
      <SecondaryControls />
    </div>
  );
}

function QueuedTrack() {
  const track = useCuedTrack();
  const cuedMedia = usePlayerStore(s => s.cuedMedia);
  const isTrack = track && track.model_type !== 'radioStation';

  if (!track) return null;

  if (!isTrack) {
    return (
      <div className="player-track-section">
        {cuedMedia?.poster ? (
          <img
            src={cuedMedia.poster}
            className="player-track-image w-56 h-56 object-cover rounded-lg flex-shrink-0"
            alt={track.name}
          />
        ) : (
          <div className="player-track-image w-56 h-56 rounded-lg flex-shrink-0 bg-fg-base/4 flex items-center justify-center text-muted">
            <ModernRadioIcon size="md" />
          </div>
        )}
        <div className="player-track-info">
          <span className="player-track-title whitespace-nowrap min-w-0 max-w-full text-sm font-medium">
            {track.name}
          </span>
          <div className="player-track-artist flex items-center gap-6">
            <span className="inline-flex items-center bg-primary text-white text-[9px] font-bold px-6 py-1 rounded-full animate-pulse">
              LIVE
            </span>
            <span className="text-xs text-muted">
              <Trans message="Radio en direct" />
            </span>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="player-track-section">
      <MoodIndicator track={track} />
      <DialogTrigger type="popover" triggerOnContextMenu placement="top">
        <Link to={getTrackLink(track)} className="flex-shrink-0">
          <TrackImage
            className="player-track-image w-56 h-56 object-cover"
            track={track}
          />
        </Link>
        <TrackContextDialog tracks={[track]} />
      </DialogTrigger>
      <div className="player-track-info">
        <DialogTrigger type="popover" triggerOnContextMenu placement="top">
          <TrackLink
            track={track}
            className="player-track-title whitespace-nowrap min-w-0 max-w-full"
          />
          <TrackContextDialog tracks={[track]} />
        </DialogTrigger>
        {track.artists?.length ? (
          <DialogTrigger type="popover" triggerOnContextMenu placement="top">
            <div className="player-track-artist">
              <ArtistLinks
                artists={track.artists}
                className="whitespace-nowrap"
              />
            </div>
            <ArtistContextDialog artist={track.artists[0]} />
          </DialogTrigger>
        ) : null}
      </div>
      <LikeIconButton likeable={track} />
    </div>
  );
}

// Mood Indicator - reads pre-computed mood from backend
function MoodIndicator({track}: {track: Track}) {
  if (!track.mood) return null;

  const config = MOOD_CONFIG[track.mood];
  if (!config) return null;

  const MoodIcon = config.icon;

  return (
    <div className={`player-mood-indicator ${config.cls}`}>
      <MoodIcon className="w-14 h-14 mr-6" />
      <span className="text-xs font-medium whitespace-nowrap">
        {config.label}
      </span>
    </div>
  );
}

function SecondaryControls() {
  const {rightSidenavStatus, setRightSidenavStatus} = useContext(
    DashboardLayoutContext
  );
  return (
    <div className="player-secondary-controls">
      <LyricsButton className="player-button-glass mood-transition-smooth" />
      <DownloadTrackButton className="player-button-glass mood-transition-smooth" />
      <Tooltip label={<Trans message="Queue" />}>
        <IconButton
          size="xs"
          className="flex-shrink-0 queue-button-glass mood-transition-smooth"
          onClick={() => {
            setRightSidenavStatus(
              rightSidenavStatus === 'closed' ? 'open' : 'closed'
            );
          }}
        >
          <ModernQueueListIcon />
        </IconButton>
      </Tooltip>
      <div className="volume-controls-glass">
        <VolumeControls trackColor="neutral" />
      </div>
      <OverlayButton />
    </div>
  );
}

function OverlayButton() {
  const isActive = usePlayerOverlayStore(s => s.isMaximized);
  const playerReady = usePlayerStore(s => s.providerReady);
  const {player} = useSettings();

  if (player?.hide_video_button) {
    return null;
  }

  return (
    <Tooltip label={<Trans message="Expand" />}>
      <IconButton
        className="flex-shrink-0 ml-26 player-button-glass mood-transition-smooth"
        color="chip"
        variant="flat"
        radius="rounded"
        size="xs"
        iconSize="sm"
        disabled={!playerReady}
        onClick={() => {
          playerOverlayState.toggle();
        }}
      >
        {isActive ? <ModernCollapseIcon /> : <ModernExpandIcon />}
      </IconButton>
    </Tooltip>
  );
}
