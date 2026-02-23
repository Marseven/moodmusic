import {useUserLikedTracks} from '@app/web-player/library/requests/use-user-liked-tracks';
import {FullPageLoader} from '@common/ui/progress/full-page-loader';
import {IllustratedMessage} from '@common/ui/images/illustrated-message';
import {ModernMusicNoteIcon} from '@app/web-player/icons/modern-icons';
import {Trans} from '@common/i18n/trans';
import React from 'react';
import {ProfileContentProps} from '@app/web-player/user-profile/user-profile-page';
import {TrackList} from '@app/web-player/tracks/track-list/track-list';

export function ProfileTracksPanel({user}: ProfileContentProps) {
  const query = useUserLikedTracks(user.id);

  if (query.isInitialLoading) {
    return <FullPageLoader className="min-h-100" />;
  }

  if (!query.items.length) {
    return (
      <IllustratedMessage
        imageHeight="h-auto"
        imageMargin="mb-14"
        image={<ModernMusicNoteIcon size="lg" className="text-muted" />}
        title={<Trans message="Pas encore de titres" />}
        description={
          <Trans
            message="Suis :user pour voir les titres qu'il aimera à l'avenir."
            values={{user: user.display_name}}
          />
        }
      />
    );
  }

  return <TrackList query={query} />;
}
