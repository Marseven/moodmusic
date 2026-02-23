import {ArtistLinks} from '@app/web-player/artists/artist-links';
import {Trans} from '@common/i18n/trans';
import {Track} from '@app/web-player/tracks/track';
import {
  ContextDialogLayout,
  ContextMenuButton,
  ContextMenuLayoutProps,
} from '@app/web-player/context-dialog/context-dialog-layout';
import {PlaylistPanelButton} from '@app/web-player/context-dialog/playlist-panel';
import {CopyLinkMenuButton} from '@app/web-player/context-dialog/copy-link-menu-button';
import {getTrackLink, TrackLink} from '@app/web-player/tracks/track-link';
import {TrackImage} from '@app/web-player/tracks/track-image/track-image';
import {useTrackPermissions} from '@app/web-player/tracks/hooks/use-track-permissions';
import {AddToQueueButton} from '@app/web-player/context-dialog/add-to-queue-menu-button';
import React, {Fragment, ReactNode, useCallback} from 'react';
import {ToggleInLibraryMenuButton} from '@app/web-player/context-dialog/toggle-in-library-menu-button';
import {ToggleRepostMenuButton} from '@app/web-player/context-dialog/toggle-repost-menu-button';
import {getRadioLink} from '@app/web-player/radio/get-radio-link';
import {useShouldShowRadioButton} from '@app/web-player/tracks/context-dialog/use-should-show-radio-button';
import {useDialogContext} from '@common/ui/overlays/dialog/dialog-context';
import {openDialog} from '@common/ui/overlays/store/dialog-store';
import {ConfirmationDialog} from '@common/ui/overlays/dialog/confirmation-dialog';
import {useDeleteTracks} from '@app/web-player/tracks/requests/use-delete-tracks';
import {useIsMobileMediaQuery} from '@common/utils/hooks/is-mobile-media-query';
import {getArtistLink} from '@app/web-player/artists/artist-link';
import {getAlbumLink} from '@app/web-player/albums/album-link';
import {ShareMediaButton} from '@app/web-player/context-dialog/share-media-button';
import {useSettings} from '@common/core/settings/use-settings';
import {LyricsDialog} from '@app/web-player/tracks/lyrics/lyrics-dialog';
import {PurchaseDialog} from '@app/web-player/purchases/purchase-dialog';
import {useUserPurchases, isPurchased} from '@app/web-player/purchases/use-user-purchases';
import {useAuth} from '@common/auth/use-auth';

export interface TrackContextDialogProps {
  tracks: Track[];
  children?: (tracks: Track[]) => ReactNode;
  showAddToQueueButton?: boolean;
}
export function TrackContextDialog({
  children,
  tracks,
  showAddToQueueButton = true,
}: TrackContextDialogProps) {
  const isMobile = useIsMobileMediaQuery();
  const firstTrack = tracks[0];
  const {canEdit, canDelete} = useTrackPermissions(tracks);
  const shouldShowRadio = useShouldShowRadioButton();
  const {player} = useSettings();
  const {close} = useDialogContext();
  const {user} = useAuth();
  const {data: purchasesData} = useUserPurchases();

  const loadTracks = useCallback(() => {
    return Promise.resolve(tracks);
  }, [tracks]);

  const headerProps: Partial<ContextMenuLayoutProps> =
    tracks.length === 1
      ? {
          image: <TrackImage track={firstTrack} />,
          title: <TrackLink track={firstTrack} />,
          description: <ArtistLinks artists={firstTrack.artists} />,
        }
      : {};

  return (
    <ContextDialogLayout {...headerProps} loadTracks={loadTracks}>
      {showAddToQueueButton && (
        <AddToQueueButton item={tracks} loadTracks={loadTracks} />
      )}
      <ToggleInLibraryMenuButton items={tracks} />
      {children?.(tracks)}
      <PlaylistPanelButton />
      {tracks.length === 1 ? (
        <Fragment>
          {shouldShowRadio && (
            <ContextMenuButton type="link" to={getRadioLink(firstTrack)}>
              <Trans message="Aller à la radio du titre" />
            </ContextMenuButton>
          )}
          {isMobile && (
            <Fragment>
              {firstTrack.artists?.[0] && (
                <ContextMenuButton
                  type="link"
                  to={getArtistLink(firstTrack.artists[0])}
                >
                  <Trans message="Voir l'artiste" />
                </ContextMenuButton>
              )}
              {firstTrack.album && (
                <ContextMenuButton
                  type="link"
                  to={getAlbumLink(firstTrack.album)}
                >
                  <Trans message="Voir l'album" />
                </ContextMenuButton>
              )}
              <ContextMenuButton type="link" to={getTrackLink(firstTrack)}>
                <Trans message="Voir le titre" />
              </ContextMenuButton>
            </Fragment>
          )}
          {!player?.hide_lyrics && tracks.length === 1 && (
            <ContextMenuButton
              onClick={() => {
                close();
                openDialog(LyricsDialog, {track: firstTrack});
              }}
            >
              <Trans message="Voir les paroles" />
            </ContextMenuButton>
          )}
          {!isMobile && (
            <CopyLinkMenuButton
              link={getTrackLink(firstTrack, {absolute: true})}
            >
              <Trans message="Copier le lien du titre" />
            </CopyLinkMenuButton>
          )}
          {tracks.length === 1 && <ShareMediaButton item={firstTrack} />}
          {tracks.length === 1 ? (
            <ToggleRepostMenuButton item={tracks[0]} />
          ) : null}
          {tracks.length === 1 &&
            user &&
            firstTrack.price != null &&
            firstTrack.price > 0 &&
            !isPurchased(
              purchasesData?.purchases,
              'track',
              firstTrack.id,
            ) && (
              <ContextMenuButton
                onClick={() => {
                  close();
                  openDialog(PurchaseDialog, {item: firstTrack});
                }}
              >
                <Trans message="Donner la force" />
              </ContextMenuButton>
            )}
          {tracks.length === 1 && canEdit && (
            <ContextMenuButton
              type="link"
              to={`/backstage/tracks/${firstTrack.id}/insights`}
            >
              <Trans message="Statistiques" />
            </ContextMenuButton>
          )}
          {tracks.length === 1 && canEdit && (
            <ContextMenuButton
              type="link"
              to={`/backstage/tracks/${firstTrack.id}/edit`}
            >
              <Trans message="Modifier" />
            </ContextMenuButton>
          )}
        </Fragment>
      ) : null}
      {canDelete && !isMobile && <DeleteButton tracks={tracks} />}
    </ContextDialogLayout>
  );
}

function DeleteButton({tracks}: TrackContextDialogProps) {
  const {close: closeMenu} = useDialogContext();
  const deleteTracks = useDeleteTracks();
  const {canDelete} = useTrackPermissions(tracks);

  if (!canDelete) {
    return null;
  }

  return (
    <ContextMenuButton
      disabled={deleteTracks.isLoading}
      onClick={() => {
        closeMenu();
        openDialog(ConfirmationDialog, {
          isDanger: true,
          title: <Trans message="Supprimer les titres" />,
          body: (
            <Trans message="Es-tu sûr de vouloir supprimer les titres sélectionnés ?" />
          ),
          confirm: <Trans message="Supprimer" />,
          onConfirm: () => {
            deleteTracks.mutate({trackIds: tracks.map(t => t.id)});
          },
        });
      }}
    >
      <Trans message="Supprimer" />
    </ContextMenuButton>
  );
}
