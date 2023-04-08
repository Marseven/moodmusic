import {useTrans} from '@common/i18n/use-trans';
import {message} from '@common/i18n/message';
import {Playlist} from '@app/web-player/playlists/playlist';
import defaultImage from './../albums/album-image/default-album-image.png';
import {getTrackImageSrc} from '@app/web-player/tracks/track-image/track-image';
import clsx from 'clsx';

interface PlaylistImageProps {
  playlist: Playlist;
  className?: string;
  size?: string;
}
export function PlaylistImage({playlist, className, size}: PlaylistImageProps) {
  const {trans} = useTrans();
  return (
    <img
      className={clsx(className, size, 'object-cover bg-fg-base/4')}
      draggable={false}
      loading="lazy"
      src={getPlaylistImageSrc(playlist)}
      alt={trans(message('Image for :name', {values: {name: playlist.name}}))}
    />
  );
}

export function getPlaylistImageSrc(playlist: Playlist) {
  if (playlist.image) {
    return playlist.image;
  }
  const firstTrackImage = playlist.tracks?.[0]
    ? getTrackImageSrc(playlist.tracks[0])
    : null;
  if (firstTrackImage) {
    return firstTrackImage;
  }
  return defaultImage;
}
