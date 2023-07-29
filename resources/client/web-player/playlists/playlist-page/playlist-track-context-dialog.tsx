import {Playlist} from '@app/web-player/playlists/playlist';
import {Trans} from '@common/i18n/trans';
import {ContextMenuButton} from '@app/web-player/context-dialog/context-dialog-layout';
import {useRemoveTracksFromPlaylist} from '@app/web-player/playlists/requests/use-remove-tracks-from-playlist';
import {useDialogContext} from '@common/ui/overlays/dialog/dialog-context';
import {TableTrackContextDialog} from '@app/web-player/tracks/context-dialog/table-track-context-dialog';
import {useAuth} from '@common/auth/use-auth';

interface PlaylistTrackContextDialogProps {
  playlist: Playlist;
}
export function PlaylistTrackContextDialog({
  playlist,
  ...props
}: PlaylistTrackContextDialogProps) {
  const {user} = useAuth();
  const {close: closeMenu} = useDialogContext();
  const removeTracks = useRemoveTracksFromPlaylist();

  const canRemove = playlist.owner_id === user?.id || playlist.collaborative;

  return (
    <TableTrackContextDialog {...props}>
      {tracks => {
        return canRemove ? (
          <ContextMenuButton
            onClick={() => {
              if (!removeTracks.isLoading) {
                removeTracks.mutate({playlistId: playlist.id, tracks});
                closeMenu();
              }
            }}
          >
            <Trans message="Remove from this playlist" />
          </ContextMenuButton>
        ) : null;
      }}
    </TableTrackContextDialog>
  );
}
