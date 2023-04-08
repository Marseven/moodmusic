import {useMemo} from 'react';
import {useAuth} from '@common/auth/use-auth';
import {Artist} from '@app/web-player/artists/artist';

export function useArtistPermissions(artist: Artist) {
  const {user, hasPermission} = useAuth();
  return useMemo(() => {
    const permissions = {
      canEdit: false,
      canDelete: false,
    };
    if (user?.id) {
      const managesAlbum = !!user.artists?.find(a => artist.id);

      permissions.canEdit =
        hasPermission('artists.update') ||
        hasPermission('music.update') ||
        managesAlbum;

      permissions.canDelete =
        hasPermission('artists.delete') ||
        hasPermission('music.delete') ||
        managesAlbum;
    }
    return permissions;
  }, [user, artist, hasPermission]);
}
