import {TrackImage} from '@app/web-player/tracks/track-image/track-image';
import {ArtistLinks} from '@app/web-player/artists/artist-links';
import React, {ReactNode, useContext, useMemo} from 'react';
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
import {ModernCollapseIcon, ModernExpandIcon, ModernQueueListIcon} from '@app/web-player/icons/modern-icons';
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
import {Sparkles, Heart, Smile, Cloud, Zap} from 'lucide-react';

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
  let content: ReactNode;

  if (track) {
    content = (
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
  } else {
    content = null;
  }

  return <>{content}</>;
}

// Mood Indicator - keyword-based detection from track metadata
function MoodIndicator({ track }: { track: Track }) {
  const moodInfo = useMemo(() => {
    const genreName = track.genres?.[0]?.name?.toLowerCase() || '';
    const trackName = track.name?.toLowerCase() || '';
    const artistName = track.artists?.[0]?.name?.toLowerCase() || '';
    const allText = `${genreName} ${trackName} ${artistName}`;

    const moodKeywords: Record<string, string[]> = {
      energetic: ['electronic', 'dance', 'edm', 'techno', 'house', 'energy', 'power', 'beat', 'bass', 'club', 'party', 'dubstep', 'drum', 'rock', 'metal', 'punk', 'hip-hop', 'rap', 'trap'],
      chill: ['chill', 'ambient', 'relax', 'calm', 'smooth', 'soft', 'mellow', 'lounge', 'acoustic', 'jazz', 'blues', 'folk', 'indie', 'lo-fi', 'trip-hop'],
      romantic: ['love', 'romance', 'romantic', 'heart', 'kiss', 'soul', 'baby', 'ballad', 'tender', 'sweet', 'r&b', 'rnb'],
      happy: ['pop', 'happy', 'joy', 'fun', 'bright', 'sunshine', 'summer', 'uplifting', 'cheerful', 'afro', 'reggae', 'soca', 'kompa'],
      focused: ['classical', 'study', 'piano', 'violin', 'orchestra', 'symphony', 'instrumental', 'cinematic', 'soundtrack', 'minimal'],
      melancholic: ['sad', 'melancholy', 'dark', 'emotional', 'tears', 'pain', 'broken', 'lonely', 'gothic', 'grunge'],
    };

    const scores: Record<string, number> = {};
    for (const [mood, keywords] of Object.entries(moodKeywords)) {
      scores[mood] = 0;
      for (const kw of keywords) {
        if (allText.includes(kw)) {
          scores[mood] += genreName.includes(kw) ? 3 : 1;
        }
      }
    }

    const best = Object.entries(scores).reduce((a, b) => a[1] >= b[1] ? a : b);

    const configs: Record<string, { icon: typeof Zap; label: string; cls: string }> = {
      energetic:   { icon: Zap,      label: 'Énergique',    cls: 'player-mood-energetic' },
      chill:       { icon: Cloud,    label: 'Détendu',      cls: 'player-mood-chill' },
      romantic:    { icon: Heart,    label: 'Romantique',   cls: 'player-mood-romantic' },
      happy:       { icon: Smile,    label: 'Joyeux',       cls: 'player-mood-happy' },
      focused:     { icon: Sparkles, label: 'Concentré',    cls: 'player-mood-focused' },
      melancholic: { icon: Cloud,    label: 'Mélancolique', cls: 'player-mood-melancholic' },
    };

    if (best[1] === 0) {
      return { icon: Zap, label: 'Énergique', cls: 'player-mood-energetic' };
    }
    return configs[best[0]];
  }, [track.id, track.genres, track.artists, track.name]);

  const MoodIcon = moodInfo.icon;

  return (
    <div className={`player-mood-indicator ${moodInfo.cls}`}>
      <MoodIcon className="w-14 h-14 mr-6" />
      <span className="text-xs font-medium whitespace-nowrap">{moodInfo.label}</span>
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
