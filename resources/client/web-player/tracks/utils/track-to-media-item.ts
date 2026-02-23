import {Track} from '@app/web-player/tracks/track';
import {MediaItem} from '@common/player/media-item';
import {getTrackImageSrc} from '@app/web-player/tracks/track-image/track-image';
import {Album} from '@app/web-player/albums/album';
import {guessPlayerProvider} from '@common/player/utils/guess-player-provider';
import {usePurchaseGatingStore} from '@app/web-player/purchases/purchase-gating-store';

export const PAID_TRACK_START_OFFSET = 30;

export function trackToMediaItem(
  track: Track,
  queueGroupId?: string | number
): MediaItem<Track> {
  const provider: MediaItem['provider'] = track.src
    ? guessPlayerProvider(track.src)
    : 'youtube';

  const price = parseFloat(String(track.price ?? 0));
  const isPaidUnpurchased =
    price > 0 && !usePurchaseGatingStore.getState().isTrackPurchased(track.id);
  const initialTime = isPaidUnpurchased ? PAID_TRACK_START_OFFSET : undefined;

  if (!track.src || provider === 'youtube') {
    return {
      id: track.id,
      provider: 'youtube',
      meta: track,
      src: track.src ? track.src : 'resolve',
      groupId: queueGroupId,
      initialTime,
    };
  }

  return {
    id: track.id,
    src: track.src,
    provider,
    meta: track,
    poster: getTrackImageSrc(track),
    groupId: queueGroupId,
    initialTime,
  };
}

export function tracksToMediaItems(
  tracks: Track[],
  queueGroupId?: string,
  album?: Album
) {
  return tracks.map(track => {
    if (album && !track.album) {
      track = {
        ...track,
        album: {...album, tracks: undefined},
      };
    }
    return trackToMediaItem(track);
  });
}
