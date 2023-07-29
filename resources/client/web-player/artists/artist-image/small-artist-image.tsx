import defaultImage from './artist-default-image-small.jpg';
import {useTrans} from '@common/i18n/use-trans';
import {message} from '@common/i18n/message';
import {Artist} from '@app/web-player/artists/artist';
import {Trans} from '@common/i18n/trans';
import {CheckIcon} from '@common/icons/material/Check';
import clsx from 'clsx';

interface SmallArtistImageProps {
  artist: Artist;
  className?: string;
  wrapperClassName?: string;
  size?: string;
  showVerifiedBadge?: boolean;
}
export function SmallArtistImage({
  artist,
  className,
  wrapperClassName,
  size,
  showVerifiedBadge = false,
}: SmallArtistImageProps) {
  const {trans} = useTrans();
  return (
    <div
      className={clsx('relative flex-shrink-0 isolate', size, wrapperClassName)}
    >
      <img
        className={clsx(size, className, 'object-cover bg-fg-base/4')}
        draggable={false}
        loading="lazy"
        src={getSmallArtistImage(artist)}
        alt={trans(message('Image for :name', {values: {name: artist.name}}))}
      />
      {showVerifiedBadge && artist.verified && (
        <div
          className="absolute bottom-24 text-sm left-0 right-0 w-max max-w-full mx-auto flex items-center gap-6 bg-black/60 text-white rounded-full py-4 px-8"
          color="positive"
        >
          <div className="bg-primary rounded-full p-1">
            <CheckIcon className="text-white" size="sm" />
          </div>
          <Trans message="Verified artist" />
        </div>
      )}
    </div>
  );
}

export function getSmallArtistImage(artist: Artist): string {
  return artist.image_small || artist.albums?.[0]?.image || defaultImage;
}
