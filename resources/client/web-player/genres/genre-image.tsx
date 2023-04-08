import defaultImage from './../artists/artist-image/artist-default-image-small.jpg';
import {useTrans} from '@common/i18n/use-trans';
import {message} from '@common/i18n/message';
import clsx from 'clsx';
import {Genre} from '@app/web-player/genres/genre';
import {getBootstrapData} from '@common/core/bootstrap-data/use-backend-bootstrap-data';

interface GenreImageProps {
  genre: Genre;
  className?: string;
  size?: string;
}
export function GenreImage({genre, className, size}: GenreImageProps) {
  const {trans} = useTrans();
  return (
    <img
      className={clsx(className, size, 'object-cover bg-fg-base/4')}
      draggable={false}
      loading="lazy"
      src={getGenreImage(genre)}
      alt={trans(message('Image for :name', {values: {name: genre.name}}))}
    />
  );
}

export function getGenreImage(genre: Genre): string {
  const data = getBootstrapData();
  return genre?.image
    ? `${data.settings.base_url}/${genre?.image}`
    : defaultImage;
}
