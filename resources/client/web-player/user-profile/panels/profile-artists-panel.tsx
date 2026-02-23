import {FullPageLoader} from '@common/ui/progress/full-page-loader';
import {IllustratedMessage} from '@common/ui/images/illustrated-message';
import {Trans} from '@common/i18n/trans';
import {InfiniteScrollSentinel} from '@common/ui/infinite-scroll/infinite-scroll-sentinel';
import React from 'react';
import {ProfileContentProps} from '@app/web-player/user-profile/user-profile-page';
import {ContentGrid} from '@app/web-player/playable-item/content-grid';
import {useUserLikedArtists} from '@app/web-player/library/requests/use-user-liked-artists';
import {ModernMicrophoneIcon} from '@app/web-player/icons/modern-icons';
import {ArtistGridItem} from '@app/web-player/artists/artist-grid-item';

export function ProfileArtistsPanel({user}: ProfileContentProps) {
  const query = useUserLikedArtists(user.id);

  if (query.isInitialLoading) {
    return <FullPageLoader className="min-h-100" />;
  }

  if (!query.items.length) {
    return (
      <IllustratedMessage
        imageHeight="h-auto"
        imageMargin="mb-14"
        image={<ModernMicrophoneIcon size="lg" className="text-muted" />}
        title={<Trans message="Pas encore d'artistes" />}
        description={
          <Trans
            message="Suis :user pour voir les artistes qu'il aimera à l'avenir."
            values={{user: user.display_name}}
          />
        }
      />
    );
  }

  return (
    <div>
      <ContentGrid>
        {query.items.map(artist => (
          <ArtistGridItem key={artist.id} artist={artist} />
        ))}
      </ContentGrid>
      <InfiniteScrollSentinel query={query} />
    </div>
  );
}
