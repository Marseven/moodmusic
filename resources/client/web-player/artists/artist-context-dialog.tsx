import {Trans} from '@common/i18n/trans';
import {Track} from '@app/web-player/tracks/track';
import {
  ContextDialogLayout,
  ContextMenuButton,
} from '@app/web-player/context-dialog/context-dialog-layout';
import React, {useCallback} from 'react';
import {ToggleInLibraryMenuButton} from '@app/web-player/context-dialog/toggle-in-library-menu-button';
import {CopyLinkMenuButton} from '@app/web-player/context-dialog/copy-link-menu-button';
import {useDialogContext} from '@common/ui/overlays/dialog/dialog-context';
import {openDialog} from '@common/ui/overlays/store/dialog-store';
import {ConfirmationDialog} from '@common/ui/overlays/dialog/confirmation-dialog';
import {Artist} from '@app/web-player/artists/artist';
import {useArtistPermissions} from '@app/web-player/artists/use-artist-permissions';
import {SmallArtistImage} from '@app/web-player/artists/artist-image/small-artist-image';
import {ArtistLink, getArtistLink} from '@app/web-player/artists/artist-link';
import {useDeleteArtist} from '@app/web-player/artists/requests/use-delete-artist';
import {getRadioLink} from '@app/web-player/radio/get-radio-link';
import {useShouldShowRadioButton} from '@app/web-player/tracks/context-dialog/use-should-show-radio-button';
import {ShareMediaButton} from '@app/web-player/context-dialog/share-media-button';

interface ArtistContextDialogProps {
  artist: Artist;
}
export function ArtistContextDialog({artist}: ArtistContextDialogProps) {
  const showRadioButton = useShouldShowRadioButton();
  const {canEdit} = useArtistPermissions(artist);
  const loadTracks = useCallback(() => {
    return loadArtistTracks(artist);
  }, [artist]);

  return (
    <ContextDialogLayout
      image={<SmallArtistImage artist={artist} />}
      title={<ArtistLink artist={artist} />}
      loadTracks={loadTracks}
    >
      <ToggleInLibraryMenuButton items={[artist]} />
      {showRadioButton && (
        <ContextMenuButton type="link" to={getRadioLink(artist)}>
          <Trans message="Aller à la radio de l'artiste" />
        </ContextMenuButton>
      )}
      <CopyLinkMenuButton link={getArtistLink(artist, {absolute: true})}>
        <Trans message="Copier le lien de l'artiste" />
      </CopyLinkMenuButton>
      <ShareMediaButton item={artist} />
      {canEdit && (
        <ContextMenuButton
          type="link"
          to={`/backstage/artists/${artist.id}/insights`}
        >
          <Trans message="Statistiques" />
        </ContextMenuButton>
      )}
      {canEdit && (
        <ContextMenuButton
          type="link"
          to={`/backstage/artists/${artist.id}/edit`}
        >
          <Trans message="Modifier" />
        </ContextMenuButton>
      )}
      <DeleteButton artist={artist} />
    </ContextDialogLayout>
  );
}

function DeleteButton({artist}: ArtistContextDialogProps) {
  const {close: closeMenu} = useDialogContext();
  const deleteArtist = useDeleteArtist(artist.id);
  const {canDelete} = useArtistPermissions(artist);

  if (!canDelete) {
    return null;
  }

  return (
    <ContextMenuButton
      disabled={deleteArtist.isLoading}
      onClick={() => {
        closeMenu();
        openDialog(ConfirmationDialog, {
          isDanger: true,
          title: <Trans message="Supprimer l'artiste" />,
          body: (
            <Trans message="Es-tu sûr de vouloir supprimer cet artiste ?" />
          ),
          confirm: <Trans message="Supprimer" />,
          onConfirm: () => {
            deleteArtist.mutate();
          },
        });
      }}
    >
      <Trans message="Supprimer" />
    </ContextMenuButton>
  );
}

// tracks are never used/loaded in artist context dialog
async function loadArtistTracks(artist: Artist): Promise<Track[]> {
  return Promise.resolve([]);
}
