import {FullPageLoader} from '@common/ui/progress/full-page-loader';
import {IllustratedMessage} from '@common/ui/images/illustrated-message';
import {Trans} from '@common/i18n/trans';
import React from 'react';
import {ProfileContentProps} from '@app/web-player/user-profile/user-profile-page';
import {useUserLikedAlbums} from '@app/web-player/library/requests/use-user-liked-albums';
import {ModernDiscIcon} from '@app/web-player/icons/modern-icons';
import {AlbumList} from '@app/web-player/albums/album-list/album-list';

export function ProfileAlbumsPanel({user}: ProfileContentProps) {
  const query = useUserLikedAlbums(user.id, {
    queryParams: {
      with: 'tracks',
    },
  });

  if (query.isInitialLoading) {
    return <FullPageLoader className="min-h-100" />;
  }

  if (!query.items.length) {
    return (
      <IllustratedMessage
        imageHeight="h-auto"
        imageMargin="mb-14"
        image={<ModernDiscIcon size="lg" className="text-muted" />}
        title={<Trans message="Pas encore d'albums" />}
        description={
          <Trans
            message="Suis :user pour voir les albums qu'il aimera à l'avenir."
            values={{user: user.display_name}}
          />
        }
      />
    );
  }

  return <AlbumList query={query} />;
}
