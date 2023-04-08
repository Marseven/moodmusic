import {ContextMenuButton} from '@app/web-player/context-dialog/context-dialog-layout';
import {Trans} from '@common/i18n/trans';
import {useDialogContext} from '@common/ui/overlays/dialog/dialog-context';
import {useAddItemsToLibrary} from '@app/web-player/library/requests/use-add-items-to-library';
import {useRemoveItemsFromLibrary} from '@app/web-player/library/requests/use-remove-items-from-library';
import {useLibraryStore} from '@app/web-player/library/state/likes-store';
import {Likeable} from '@app/web-player/library/likeable';
import {useAuthClickCapture} from '@app/web-player/use-auth-click-capture';

interface ToggleInLibraryMenuButtonProps {
  items: Likeable[];
}
export function ToggleInLibraryMenuButton({
  items,
}: ToggleInLibraryMenuButtonProps) {
  const authHandler = useAuthClickCapture();
  const {close: closeMenu} = useDialogContext();
  const addToLibrary = useAddItemsToLibrary();
  const removeFromLibrary = useRemoveItemsFromLibrary();
  const allInLibrary = useLibraryStore(s => s.has(items));

  if (allInLibrary) {
    return (
      <ContextMenuButton
        onClickCapture={authHandler}
        onClick={() => {
          closeMenu();
          removeFromLibrary.mutate({likeables: items});
        }}
      >
        <Trans message="Remove from your music" />
      </ContextMenuButton>
    );
  }

  return (
    <ContextMenuButton
      onClickCapture={authHandler}
      onClick={() => {
        closeMenu();
        addToLibrary.mutate({likeables: items});
      }}
    >
      <Trans message="Add to your music" />
    </ContextMenuButton>
  );
}
