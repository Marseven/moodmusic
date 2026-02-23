import {Album} from '@app/web-player/albums/album';
import {AlbumImage} from '@app/web-player/albums/album-image/album-image';
import {AlbumLink, getAlbumLink} from '@app/web-player/albums/album-link';
import {ArtistLinks} from '@app/web-player/artists/artist-links';
import {Trans} from '@common/i18n/trans';
import {loadMediaItemTracks} from '@app/web-player/requests/load-media-item-tracks';
import {queueGroupId} from '@app/web-player/queue-group-id';
import {toast} from '@common/ui/toast/toast';
import {message} from '@common/i18n/message';
import {Track} from '@app/web-player/tracks/track';
import {
  ContextDialogLayout,
  ContextMenuButton,
} from '@app/web-player/context-dialog/context-dialog-layout';
import {PlaylistPanelButton} from '@app/web-player/context-dialog/playlist-panel';
import {useAlbumPermissions} from '@app/web-player/albums/use-album-permissions';
import React, {useCallback} from 'react';
import {AddToQueueButton} from '@app/web-player/context-dialog/add-to-queue-menu-button';
import {ToggleInLibraryMenuButton} from '@app/web-player/context-dialog/toggle-in-library-menu-button';
import {CopyLinkMenuButton} from '@app/web-player/context-dialog/copy-link-menu-button';
import {useDeleteAlbum} from '@app/web-player/albums/requests/use-delete-album';
import {useDialogContext} from '@common/ui/overlays/dialog/dialog-context';
import {openDialog} from '@common/ui/overlays/store/dialog-store';
import {ConfirmationDialog} from '@common/ui/overlays/dialog/confirmation-dialog';
import {ToggleRepostMenuButton} from '@app/web-player/context-dialog/toggle-repost-menu-button';
import {getArtistLink} from '@app/web-player/artists/artist-link';
import {useIsMobileMediaQuery} from '@common/utils/hooks/is-mobile-media-query';
import {ShareMediaButton} from '@app/web-player/context-dialog/share-media-button';

interface AlbumContextMenuProps {
  album: Album;
}
export function AlbumContextDialog({album}: AlbumContextMenuProps) {
  const {canEdit} = useAlbumPermissions(album);
  const isMobile = useIsMobileMediaQuery();

  const loadTracks = useCallback(() => {
    return loadAlbumTracks(album);
  }, [album]);

  return (
    <ContextDialogLayout
      image={<AlbumImage album={album} />}
      title={<AlbumLink album={album} />}
      description={<ArtistLinks artists={album.artists} />}
      loadTracks={loadTracks}
    >
      <AddToQueueButton item={album} loadTracks={loadTracks} />
      <PlaylistPanelButton />
      <ToggleInLibraryMenuButton items={[album]} />
      {isMobile && album.artists?.[0] && (
        <ContextMenuButton type="link" to={getArtistLink(album.artists[0])}>
          <Trans message="Voir l'artiste" />
        </ContextMenuButton>
      )}
      {!isMobile && (
        <CopyLinkMenuButton link={getAlbumLink(album, {absolute: true})}>
          <Trans message="Copier le lien de l'album" />
        </CopyLinkMenuButton>
      )}
      <ShareMediaButton item={album} />
      <ToggleRepostMenuButton item={album} />
      {canEdit && (
        <ContextMenuButton
          type="link"
          to={`/backstage/albums/${album.id}/insights`}
        >
          <Trans message="Statistiques" />
        </ContextMenuButton>
      )}
      {canEdit && (
        <ContextMenuButton
          type="link"
          to={`/backstage/albums/${album.id}/edit`}
        >
          <Trans message="Modifier" />
        </ContextMenuButton>
      )}
      <DeleteButton album={album} />
    </ContextDialogLayout>
  );
}

function DeleteButton({album}: AlbumContextMenuProps) {
  const {close: closeMenu} = useDialogContext();
  const deleteAlbum = useDeleteAlbum();
  const {canDelete} = useAlbumPermissions(album);

  if (!canDelete) {
    return null;
  }

  return (
    <ContextMenuButton
      disabled={deleteAlbum.isLoading}
      onClick={() => {
        closeMenu();
        openDialog(ConfirmationDialog, {
          isDanger: true,
          title: <Trans message="Supprimer l'album" />,
          body: <Trans message="Es-tu sûr de vouloir supprimer cet album ?" />,
          confirm: <Trans message="Supprimer" />,
          onConfirm: () => {
            deleteAlbum.mutate({albumId: album.id});
          },
        });
      }}
    >
      <Trans message="Supprimer" />
    </ContextMenuButton>
  );
}

async function loadAlbumTracks(album: Album): Promise<Track[]> {
  // load album tracks if not loaded already
  if (typeof album.tracks === 'undefined') {
    const tracks = await loadMediaItemTracks(queueGroupId(album));
    if (!tracks.length) {
      toast(message('Cet album ne contient aucun titre.'));
    }
    return tracks;
  }
  return album.tracks;
}
