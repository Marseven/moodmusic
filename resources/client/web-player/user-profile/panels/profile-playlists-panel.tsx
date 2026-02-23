import {FullPageLoader} from '@common/ui/progress/full-page-loader';
import {IllustratedMessage} from '@common/ui/images/illustrated-message';
import {Trans} from '@common/i18n/trans';
import {InfiniteScrollSentinel} from '@common/ui/infinite-scroll/infinite-scroll-sentinel';
import React from 'react';
import {ProfileContentProps} from '@app/web-player/user-profile/user-profile-page';
import {ContentGrid} from '@app/web-player/playable-item/content-grid';
import {PlaylistGridItem} from '@app/web-player/playlists/playlist-grid-item';
import {ModernQueueListIcon} from '@app/web-player/icons/modern-icons';
import {useUserPlaylists} from '@app/web-player/library/requests/use-user-playlists';

export function ProfilePlaylistsPanel({user}: ProfileContentProps) {
  const query = useUserPlaylists(user.id);

  if (query.isInitialLoading) {
    return <FullPageLoader className="min-h-100" />;
  }

  if (!query.items.length) {
    return (
      <IllustratedMessage
        imageHeight="h-auto"
        imageMargin="mb-14"
        image={<ModernQueueListIcon size="lg" className="text-muted" />}
        title={<Trans message="Pas encore de playlists" />}
        description={
          <Trans
            message="Suis :user pour voir les playlists qu'il créera à l'avenir."
            values={{user: user.display_name}}
          />
        }
      />
    );
  }

  return (
    <div>
      <ContentGrid>
        {query.items.map(playlist => (
          <PlaylistGridItem key={playlist.id} playlist={playlist} />
        ))}
      </ContentGrid>
      <InfiniteScrollSentinel query={query} />
    </div>
  );
}
