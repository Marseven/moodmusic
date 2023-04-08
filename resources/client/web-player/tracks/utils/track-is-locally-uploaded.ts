import {Track} from '@app/web-player/tracks/track';

export function trackIsLocallyUploaded(track: Track): boolean {
  return (
    track?.url != null &&
    (track.url.startsWith('storage') ||
      track.url.includes('storage/track_media'))
  );
}
