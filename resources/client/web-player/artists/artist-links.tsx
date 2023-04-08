import {Artist} from '@app/web-player/artists/artist';
import {ArtistLink} from '@app/web-player/artists/artist-link';
import {Fragment, HTMLAttributeAnchorTarget} from 'react';
import {Trans} from '@common/i18n/trans';

interface ArtistLinksProps {
  artists?: Artist[];
  className?: string;
  target?: HTMLAttributeAnchorTarget;
}
export function ArtistLinks({artists, className, target}: ArtistLinksProps) {
  if (!artists?.length) {
    return (
      <div className={className}>
        <Trans message="Various artists" />
      </div>
    );
  }
  return (
    <div className={className}>
      {artists.map((artist, i) => (
        <Fragment key={artist.id}>
          {i > 0 && ', '}
          <ArtistLink artist={artist} target={target} />
        </Fragment>
      ))}
    </div>
  );
}
