import {AccountSettingsPanel} from '../account-settings-panel';
import {Button} from '@common/ui/buttons/button';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {ConfirmationDialog} from '@common/ui/overlays/dialog/confirmation-dialog';
import {useDeleteAccount} from './delete-account';
import {Trans} from '@common/i18n/trans';
import {AccountSettingsId} from '@common/auth/ui/account-settings/account-settings-sidenav';

export function DangerZonePanel() {
  const deleteAccount = useDeleteAccount();

  return (
    <AccountSettingsPanel
      id={AccountSettingsId.DeleteAccount}
      title={<Trans message="Zone dangereuse" />}
      variant="danger"
    >
      <DialogTrigger
        type="modal"
        onClose={isConfirmed => {
          if (isConfirmed) {
            deleteAccount.mutate();
          }
        }}
      >
        <Button variant="flat" color="danger">
          <Trans message="Supprimer le compte" />
        </Button>
        <ConfirmationDialog
          isDanger
          title={<Trans message="Supprimer le compte?" />}
          body={
            <Trans message="Votre compte sera supprimé immédiatement et définitivement. Une fois supprimés, les comptes ne peuvent pas être restaurés." />
          }
          confirm={<Trans message="Supprimer" />}
        />
      </DialogTrigger>
    </AccountSettingsPanel>
  );
}
