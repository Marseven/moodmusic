import {Track} from '@app/web-player/tracks/track';
import {MediaItem} from '@common/player/media-item';
import {getTrackImageSrc} from '@app/web-player/tracks/track-image/track-image';
import {Album} from '@app/web-player/albums/album';

export function trackToMediaItem(
  track: Track,
  queueGroupId?: string
): MediaItem<Track> {
  if (track.url) {
    return {
      id: track.id,
      src: track.url,
      provider: 'htmlVideo',
      meta: track,
      image: getTrackImageSrc(track),
      duration: track.duration ? Math.round(track.duration / 1000) : undefined,
      groupId: queueGroupId,
    };
  }

  return {
    id: track.id,
    provider: 'youtube',
    meta: track,
    src: track.youtube_id ? track.youtube_id : 'resolve',
    duration: track.duration ? Math.round(track.duration / 1000) : undefined,
    groupId: queueGroupId,
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
