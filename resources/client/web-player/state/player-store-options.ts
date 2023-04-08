import {PlayerStoreOptions} from '@common/player/player-store-options';
import {getBootstrapData} from '@common/core/bootstrap-data/use-backend-bootstrap-data';
import {findYoutubeVideosForTrack} from '@app/web-player/tracks/requests/find-youtube-videos-for-track';
import {YoutubeProvider} from '@common/player/providers/youtube-provider';
import {MediaItem, YoutubeMediaItem} from '@common/player/media-item';
import {apiClient} from '@common/http/query-client';
import {Track} from '@app/web-player/tracks/track';
import {playerOverlayState} from '@app/web-player/state/player-overlay-store';
import {loadMediaItemTracks} from '@app/web-player/requests/load-media-item-tracks';
import {tracksToMediaItems} from '@app/web-player/tracks/utils/track-to-media-item';
import {toast} from '@common/ui/toast/toast';
import {message} from '@common/i18n/message';
import OnErrorEvent = YT.OnErrorEvent;

// used to track play history for logging plays on backend (prevents logging play twice, unless track is fully played)
const trackPlays = new Set<number>();

// on mobile, YouTube embed playback needs to be started via user gesture the first
// time on YouTube embed itself, starting it with custom play button will not work
let playbackStartedViaGesture = false;

// this is needed in order to stop YouTube embed from playing trying to
// cue a video that will error out while valid video is already playing
const failedVideoId = ' ';

// list of video Ids for which YouTube embed errored out
const failedVideoIds = new Set<string>();
let tracksSkippedDueToError = 0;

async function resolveSrc(media: YoutubeMediaItem) {
  const results = await findYoutubeVideosForTrack(media.meta);
  // Find first video ID that did not error out yet
  const match = results?.find(r => !failedVideoIds.has(`${r.id}`))?.id;
  return {
    ...media,
    src: match || failedVideoId,
  };
}

function setMediaSessionMetadata(media: MediaItem<Track>) {
  if ('mediaSession' in navigator) {
    const track = media.meta;
    const image = track.image || track.album?.image;
    navigator.mediaSession.metadata = new MediaMetadata({
      title: track.name,
      artist: track.artists?.[0].name,
      album: track.album?.name,
      artwork: image
        ? [
            {
              src: image,
              sizes: '300x300',
              type: 'image/jpg',
            },
          ]
        : undefined,
    });
  }
}

export const playerStoreOptions: Partial<PlayerStoreOptions> = {
  defaultVolume: getBootstrapData().settings.player?.default_volume,
  setMediaSessionMetadata,
  youtube: {
    srcResolver: resolveSrc,
    suggestedQuality: getBootstrapData().settings.youtube?.suggested_quality,
    onStateChange: e => {
      if (e.data === YT.PlayerState.PLAYING) {
        tracksSkippedDueToError = 0;
      }
    },
  },
  onBeforePlay: () => {
    const player = getBootstrapData().settings.player;

    // prevent playback if user does not have permission to play music
    const hasPermission = userHasPlayPermission();
    if (!hasPermission) {
      toast.danger(message('Your current plan does not allow music playback.'));
      return {preventPlayback: true};
    }

    if (
      !playbackStartedViaGesture &&
      player?.mobile?.auto_open_overlay &&
      // check if mobile
      window.matchMedia('(max-width: 768px)').matches
    ) {
      playerOverlayState.toggle();
      playbackStartedViaGesture = true;
    }
  },
  loadMoreMediaItems: async media => {
    if (media?.groupId) {
      const tracks = await loadMediaItemTracks(
        media.groupId as string,
        media.meta
      );
      return tracksToMediaItems(tracks);
    }
  },
  listeners: {
    // change document title to currently cued track name
    onCued: (media?: MediaItem<Track>) => {
      if (!media) return;
      const site_name = getBootstrapData().settings.branding.site_name;
      let title = `${media.meta.name}`;
      const artistName = media.meta.artists?.[0].name;

      if (artistName) {
        title = `${title} - ${artistName} - ${site_name}`;
      } else {
        title = `${title} - ${site_name}`;
      }

      document.title = title;
    },
    onPlay: (media?: MediaItem<Track>) => {
      if (media && !trackPlays.has(media.meta.id)) {
        trackPlays.add(media.meta.id);
        apiClient.post(`track/plays/${media.meta.id}/log`, {
          queueId: media.groupId,
        });
      }
    },
    onPlaybackEnd: media => {
      // clear track play
      if (media) {
        trackPlays.delete(media.meta.id);
      }
    },
    onError: async (e, state) => {
      if (state?.provider?.name === 'youtube') {
        const provider = state.provider as YoutubeProvider;
        logYoutubeError(e);

        const videoId = provider.getYoutubeId();
        if (videoId) {
          failedVideoIds.add(`${videoId}`);
        }

        const media = provider.cuedMedia
          ? await resolveSrc(provider.cuedMedia)
          : null;

        // try to play alternative videos we fetched
        if (media?.src && media?.src !== failedVideoId) {
          await provider.cueYoutubeVideo(media as Required<YoutubeMediaItem>);
          provider.youtube?.playVideo();

          // there are no more alternative videos to try, we can error out
        } else {
          tracksSkippedDueToError++;

          // try to play up to two next queued tracks if we can't play
          // a video for this one. If we can't play 3 tracks in a row
          // we can assume there's an issue with YouTube API and bail
          if (tracksSkippedDueToError <= 2) {
            provider.listeners.onPlaybackEnd?.();
          }
        }
      } else {
        tracksSkippedDueToError = 0;
      }
    },
  },
  onDestroy: () => {
    tracksSkippedDueToError = 0;
  },
};

function logYoutubeError(e?: OnErrorEvent) {
  const videoUrl = e?.target?.getVideoUrl();
  if (!e || !videoUrl || videoUrl.endsWith('%20')) return; // %20 = failedVideoId
  apiClient.post('youtube/log-client-error', {
    code: e.data,
    videoUrl: e.target.getVideoUrl(),
  });
}

function userHasPlayPermission(): boolean {
  const user = getBootstrapData().user;
  const guest_role = getBootstrapData().guest_role;
  const permissions = user?.permissions || guest_role?.permissions;
  return permissions?.find(p => p.name === 'music.play') != null;
}
