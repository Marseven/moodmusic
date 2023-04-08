import {Track} from '@app/web-player/tracks/track';
import defaultImage from './track-default-image.png';
import {useTrans} from '@common/i18n/use-trans';
import {message} from '@common/i18n/message';
import clsx from 'clsx';

interface TrackImageProps {
  track: Track;
  className?: string;
  size?: string;
}
export function TrackImage({track, className, size}: TrackImageProps) {
  const {trans} = useTrans();
  return (
    <img
      className={clsx(className, size, 'object-cover bg-fg-base/4')}
      draggable={false}
      loading="lazy"
      src={getTrackImageSrc(track)}
      alt={trans(message('Image for :name', {values: {name: track.name}}))}
    />
  );
}

export function getTrackImageSrc(track: Track, includeDefault = true) {
  if (track.image) {
    return track.image;
  } else if (track.album?.image) {
    return track.album.image;
  } else if (includeDefault) {
    return defaultImage;
  }
}
