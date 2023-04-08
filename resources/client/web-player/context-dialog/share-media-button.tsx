import {ContextMenuButton} from '@app/web-player/context-dialog/context-dialog-layout';
import {Trans} from '@common/i18n/trans';
import {useDialogContext} from '@common/ui/overlays/dialog/dialog-context';
import {Track} from '@app/web-player/tracks/track';
import {Artist} from '@app/web-player/artists/artist';
import {Album} from '@app/web-player/albums/album';
import {openGlobalDialog} from '@app/web-player/state/global-dialog-store';
import React from 'react';
import {ShareMediaDialog} from '@app/web-player/sharing/share-media-dialog';

interface Props {
  item: Track | Album | Artist;
}
export function ShareMediaButton({item}: Props) {
  const {close: closeMenu} = useDialogContext();
  return (
    <ContextMenuButton
      onClick={() => {
        closeMenu();
        openGlobalDialog(ShareMediaDialog, {
          item,
        });
      }}
    >
      <Trans message="Share" />
    </ContextMenuButton>
  );
}
