import {Playlist} from '@app/web-player/playlists/playlist';
import {Trans} from '@common/i18n/trans';
import {ContextMenuButton} from '@app/web-player/context-dialog/context-dialog-layout';
import {useRemoveTracksFromPlaylist} from '@app/web-player/playlists/requests/use-remove-tracks-from-playlist';
import {useDialogContext} from '@common/ui/overlays/dialog/dialog-context';
import {TableTrackContextDialog} from '@app/web-player/tracks/context-dialog/table-track-context-dialog';

interface PlaylistTrackContextDialogProps {
  playlist: Playlist;
}
export function PlaylistTrackContextDialog({
  playlist,
  ...props
}: PlaylistTrackContextDialogProps) {
  const {close: closeMenu} = useDialogContext();
  const removeTracks = useRemoveTracksFromPlaylist();

  return (
    <TableTrackContextDialog {...props}>
      {tracks => (
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
      )}
    </TableTrackContextDialog>
  );
}
