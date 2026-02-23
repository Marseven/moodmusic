import {Track} from '@app/web-player/tracks/track';
import {MediaItem} from '@common/player/media-item';
import {getTrackImageSrc} from '@app/web-player/tracks/track-image/track-image';
import {Album} from '@app/web-player/albums/album';
import {guessPlayerProvider} from '@common/player/utils/guess-player-provider';
import {usePurchaseGatingStore} from '@app/web-player/purchases/purchase-gating-store';

export const PAID_TRACK_START_OFFSET = 30;

/** Get the effective price of a track (own price or album price) */
export function getTrackEffectivePrice(track: Track): number {
  const trackPrice = parseFloat(String(track.price ?? 0));
  if (trackPrice > 0) return trackPrice;
  const albumPrice = parseFloat(String(track.album?.price ?? 0));
  return albumPrice;
}

export function trackToMediaItem(
  track: Track,
  queueGroupId?: string | number
): MediaItem<Track> {
  const provider: MediaItem['provider'] = track.src
    ? guessPlayerProvider(track.src)
    : 'youtube';

  const price = getTrackEffectivePrice(track);
  const isPaidUnpurchased =
    price > 0 && !usePurchaseGatingStore.getState().isTrackOrAlbumPurchased(track);
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
