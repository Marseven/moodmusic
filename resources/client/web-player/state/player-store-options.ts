import {getBootstrapData} from '@common/core/bootstrap-data/use-backend-bootstrap-data';
import {findYoutubeVideosForTrack} from '@app/web-player/tracks/requests/find-youtube-videos-for-track';
import {MediaItem, YoutubeMediaItem} from '@common/player/media-item';
import {apiClient} from '@common/http/query-client';
import {Track} from '@app/web-player/tracks/track';
import {playerOverlayState} from '@app/web-player/state/player-overlay-store';
import {loadMediaItemTracks} from '@app/web-player/requests/load-media-item-tracks';
import {tracksToMediaItems} from '@app/web-player/tracks/utils/track-to-media-item';
import {PlayerStoreOptions} from '@common/player/state/player-store-options';
import {
  YouTubePlayerState,
  YoutubeProviderError,
  YoutubeProviderInternalApi,
} from '@common/player/providers/youtube/youtube-types';
import {toast} from '@common/ui/toast/toast';
import {message} from '@common/i18n/message';
import {useAdStore} from '@app/web-player/ads/ad-store';
import {usePurchaseGatingStore} from '@app/web-player/purchases/purchase-gating-store';

// used to track play history for logging plays on backend (prevents logging play twice, unless track is fully played)
const trackPlays = new Set<number>();

// this is needed in order to stop YouTube embed from trying to
// cue a video that will error out while valid video is already playing
const failedVideoId = ' ';

// list of video Ids for which YouTube embed errored out
const failedVideoIds = new Set<string>();
let tracksSkippedDueToError = 0;

async function resolveSrc(
  media: YoutubeMediaItem<Track>
): Promise<YoutubeMediaItem> {
  const results = await findYoutubeVideosForTrack(media.meta!);
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
    if (!track) return;
    const image = track.image || track.album?.image;
    navigator.mediaSession.metadata = new MediaMetadata({
      title: track.name,
      artist: track.artists?.[0]?.name,
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
  persistQueueInLocalStorage: true,
  defaultVolume: getBootstrapData().settings.player?.default_volume,
  setMediaSessionMetadata,
  youtube: {
    srcResolver: resolveSrc,
    onStateChange: state => {
      if (state === YouTubePlayerState.Playing) {
        tracksSkippedDueToError = 0;
      }
    },
  },
  onBeforePlay: () => {
    const player = getBootstrapData().settings.player;
    // on mobile, YouTube embed playback needs to be started via user gesture
    // on YouTube embed itself, starting it with custom play button will not work
    if (
      player?.mobile?.auto_open_overlay &&
      // check if mobile
      window.matchMedia('(max-width: 768px)').matches
    ) {
      playerOverlayState.open();
      // wait for overlay animation to complete
      return new Promise<void>(resolve => setTimeout(() => resolve(), 151));
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
    cued: ({state: {cuedMedia}}) => {
      if (!cuedMedia) return;
      const site_name = getBootstrapData().settings.branding.site_name;
      let title = `${cuedMedia.meta.name}`;
      const artistName = cuedMedia.meta.artists?.[0].name;

      if (artistName) {
        title = `${title} - ${artistName} - ${site_name}`;
      } else {
        title = `${title} - ${site_name}`;
      }

      document.title = title;
    },
    play: ({state: {cuedMedia, pause}}) => {
      // prevent playback if user does not have permission to play music
      const hasPermission = userHasPlayPermission();
      if (!hasPermission) {
        toast.danger(
          message('Your current plan does not allow music playback.')
        );
        pause();
        return;
      }

      // If an ad is playing, pause the main player immediately
      const adStore = useAdStore.getState();
      if (adStore.isPlayingAd) {
        pause();
        return;
      }

      // log track play (skip for non-track media like radio stations)
      if (cuedMedia && cuedMedia.meta?.model_type !== 'radioStation' && !trackPlays.has(cuedMedia.meta.id)) {
        trackPlays.add(cuedMedia.meta.id);
        apiClient.post(`tracks/plays/${cuedMedia.meta.id}/log`, {
          queueId: cuedMedia.groupId,
        });
      }
    },
    playbackEnd: ({state: {cuedMedia, pause, play}}) => {
      // clear track play
      if (cuedMedia) {
        trackPlays.delete(cuedMedia.meta.id);
      }

      // Reset purchase gating for the track that just ended
      usePurchaseGatingStore.getState().reset();

      // Ad system: increment counter and check if ad should play
      const adStore = useAdStore.getState();
      adStore.incrementTrackCount();
      if (adStore.shouldShowAd()) {
        // The internal playbackEnd listener will call playNext().
        // We need to pause the next track once it starts, play the ad,
        // then resume. We do this by setting isPlayingAd before playNext runs.
        // The play listener above will pause the player when isPlayingAd is true.
        adStore.playAd(() => {
          // Resume: play the currently cued (next) track
          play();
        });
      }
    },
    progress: ({currentTime, state: {cuedMedia, pause, playNext}}) => {
      if (!cuedMedia) return;
      if (cuedMedia.meta?.model_type === 'radioStation') return;
      const track = cuedMedia.meta as Track;
      const gatingStore = usePurchaseGatingStore.getState();

      // Skip if already prompted for this track
      if (gatingStore.promptedTrackId === track.id) return;

      // Check if this is a paid track that hasn't been purchased
      // price comes as string from Laravel decimal:2 cast, so use parseFloat
      const price = parseFloat(String(track.price ?? 0));
      if (price > 0) {
        const settings = getBootstrapData().settings;
        const previewDuration = parseInt(settings?.ads?.preview_duration ?? '30') || 30;

        if (currentTime >= previewDuration) {
          if (!gatingStore.isTrackPurchased(track.id)) {
            pause();
            gatingStore.showPrompt(track, playNext);
          }
        }
      }
    },
    error: async ({
      sourceEvent,
      state: {cuedMedia, providerApi, providerName, emit},
    }) => {
      const e = sourceEvent as YoutubeProviderError;
      if (providerName === 'youtube' && providerApi) {
        //const provider = state.provider as YoutubeProvider;
        logYoutubeError(e);

        if (e.videoId) {
          failedVideoIds.add(`${e.videoId}`);
        }

        const media = cuedMedia
          ? await resolveSrc(cuedMedia as YoutubeMediaItem)
          : null;

        // try to play alternative videos we fetched
        if (media?.src && media?.src !== failedVideoId) {
          await (
            providerApi.internalProviderApi as YoutubeProviderInternalApi
          ).loadVideoById(media.src);
          providerApi.play();

          // there are no more alternative videos to try, we can error out
        } else {
          tracksSkippedDueToError++;

          // try to play up to two next queued tracks if we can't play
          // a video for this one. If we can't play 3 tracks in a row
          // we can assume there's an issue with YouTube API and bail
          if (tracksSkippedDueToError <= 2) {
            emit('playbackEnd');
          }
        }
      } else {
        tracksSkippedDueToError = 0;
      }
    },
  },
  onDestroy: () => {
    tracksSkippedDueToError = 0;
    useAdStore.getState().reset();
    usePurchaseGatingStore.getState().reset();
  },
};

function logYoutubeError(e: YoutubeProviderError) {
  const code = e?.code;
  if (!e || !e.videoId) return;
  apiClient.post('youtube/log-client-error', {
    code,
    videoUrl: e.videoId,
  });
}

function userHasPlayPermission(): boolean {
  const user = getBootstrapData().user;
  const guest_role = getBootstrapData().guest_role;
  const permissions = user?.permissions || guest_role?.permissions;
  return permissions?.find(p => p.name === 'music.play') != null;
}
