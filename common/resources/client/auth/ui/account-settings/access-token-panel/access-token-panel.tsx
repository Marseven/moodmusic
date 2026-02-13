import {Link} from 'react-router-dom';
import clsx from 'clsx';
import {AccountSettingsPanel} from '../account-settings-panel';
import {User} from '../../../user';
import {IllustratedMessage} from '../../../../ui/images/illustrated-message';
import {SvgImage} from '../../../../ui/images/svg-image/svg-image';
import {Button} from '../../../../ui/buttons/button';
import {FormattedDate} from '../../../../i18n/formatted-date';
import {AccessToken} from '../../../access-token';
import {DialogTrigger} from '../../../../ui/overlays/dialog/dialog-trigger';
import {ConfirmationDialog} from '../../../../ui/overlays/dialog/confirmation-dialog';
import {useDeleteAccessToken} from './delete-access-token';
import {CreateNewTokenDialog} from './create-new-token-dialog';
import {LinkStyle} from '../../../../ui/buttons/external-link';
import {useAuth} from '../../../use-auth';
import {Trans} from '../../../../i18n/trans';
import secureFilesSvg from './secure-files.svg';
import {useSettings} from '../../../../core/settings/use-settings';
import {queryClient} from '@common/http/query-client';
import {AccountSettingsId} from '@common/auth/ui/account-settings/account-settings-sidenav';

interface Props {
  user: User;
}
export function AccessTokenPanel({user}: Props) {
  const tokens = user.tokens || [];
  const {hasPermission} = useAuth();
  const {api} = useSettings();
  if (!api?.integrated || !hasPermission('api.access')) return null;
  return (
    <AccountSettingsPanel
      id={AccountSettingsId.Developers}
      title={<Trans message="Jetons d'accès API" />}
      titleSuffix={
        <Link className={LinkStyle} to="/api-docs" target="_blank">
          <Trans message="Documentation" />
        </Link>
      }
      actions={<CreateNewTokenButton />}
    >
      {!tokens.length ? (
        <IllustratedMessage
          className="py-40"
          image={<SvgImage src={secureFilesSvg} />}
          title={<Trans message="Vous n'avez pas encore de jetons d'accès personnels" />}
        />
      ) : (
        tokens.map((token, index) => (
          <TokenLine
            token={token}
            key={token.id}
            isLast={index === tokens.length - 1}
          />
        ))
      )}
    </AccountSettingsPanel>
  );
}

interface TokenLineProps {
  token: AccessToken;
  isLast: boolean;
}
function TokenLine({token, isLast}: TokenLineProps) {
  return (
    <div
      className={clsx(
        'flex items-center gap-24 p-12 rounded-xl bg-white/5 border border-white/8 transition-colors hover:bg-white/8',
        !isLast && 'mb-10'
      )}
    >
      <div className="text-sm">
        <div className="font-semibold">
          <Trans message="Nom" />
        </div>
        <div>{token.name}</div>
        <div className="font-semibold mt-10">
          <Trans message="Dernière utilisation" />
        </div>
        <div>
          {token.last_used_at ? (
            <FormattedDate date={token.last_used_at} />
          ) : (
            <Trans message="Jamais" />
          )}
        </div>
      </div>
      <DeleteTokenButton token={token} />
    </div>
  );
}

function CreateNewTokenButton() {
  return (
    <DialogTrigger type="modal">
      <Button variant="flat" color="primary">
        <Trans message="Créer un jeton" />
      </Button>
      <CreateNewTokenDialog />
    </DialogTrigger>
  );
}

interface DeleteTokenButtonProps {
  token: AccessToken;
}
function DeleteTokenButton({token}: DeleteTokenButtonProps) {
  const deleteToken = useDeleteAccessToken();
  return (
    <DialogTrigger
      type="modal"
      onClose={isConfirmed => {
        if (isConfirmed) {
          deleteToken.mutate(
            {id: token.id},
            {
              onSuccess: () => queryClient.invalidateQueries(['users']),
            }
          );
        }
      }}
    >
      <Button
        size="xs"
        variant="outline"
        color="danger"
        className="flex-shrink-0 ml-auto"
      >
        <Trans message="Supprimer" />
      </Button>
      <ConfirmationDialog
        isDanger
        title={<Trans message="Supprimer le jeton?" />}
        body={
          <Trans message="Ce jeton sera supprimé immédiatement et définitivement. Une fois supprimé, il ne pourra plus être utilisé pour faire des requêtes API." />
        }
        confirm={<Trans message="Supprimer" />}
      />
    </DialogTrigger>
  );
}
