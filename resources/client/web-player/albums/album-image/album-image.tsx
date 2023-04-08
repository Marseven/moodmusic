import defaultImage from './default-album-image.png';
import {useTrans} from '@common/i18n/use-trans';
import {message} from '@common/i18n/message';
import {Album} from '@app/web-player/albums/album';
import clsx from 'clsx';

interface AlbumImageProps {
  album: Album;
  className?: string;
  size?: string;
}
export function AlbumImage({album, className, size}: AlbumImageProps) {
  const {trans} = useTrans();
  return (
    <img
      className={clsx(className, size, 'object-cover bg-fg-base/4')}
      draggable={false}
      loading="lazy"
      src={getAlbumImage(album)}
      alt={trans(message('Image for :name', {values: {name: album.name}}))}
    />
  );
}

export function getAlbumImage(album: Album): string {
  return album?.image || defaultImage;
}
