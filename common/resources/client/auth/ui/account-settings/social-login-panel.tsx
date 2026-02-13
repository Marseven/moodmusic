import clsx from 'clsx';
import {cloneElement, ReactElement} from 'react';
import {SocialService, useSocialLogin} from '../../requests/use-social-login';
import {toast} from '@common/ui/toast/toast';
import {Button} from '@common/ui/buttons/button';
import {EnvatoIcon} from '@common/icons/social/envato';
import {GoogleIcon} from '@common/icons/social/google';
import {FacebookIcon} from '@common/icons/social/facebook';
import {TwitterIcon} from '@common/icons/social/twitter';
import {User} from '../../user';
import {AccountSettingsPanel} from './account-settings-panel';
import {Trans} from '@common/i18n/trans';
import {message} from '@common/i18n/message';
import {useSettings} from '@common/core/settings/use-settings';
import {queryClient} from '@common/http/query-client';
import {AccountSettingsId} from '@common/auth/ui/account-settings/account-settings-sidenav';

interface Props {
  user: User;
}
export function SocialLoginPanel({user}: Props) {
  return (
    <AccountSettingsPanel
      id={AccountSettingsId.SocialLogin}
      title={<Trans message="Gérer la connexion sociale" />}
    >
      <SocialLoginPanelRow
        icon={<EnvatoIcon viewBox="0 0 50 50" className="bg-envato" />}
        service="envato"
        user={user}
      />
      <SocialLoginPanelRow
        icon={<GoogleIcon viewBox="0 0 48 48" />}
        service="google"
        user={user}
      />
      <SocialLoginPanelRow
        icon={<FacebookIcon className="text-facebook" />}
        service="facebook"
        user={user}
      />
      <SocialLoginPanelRow
        icon={<TwitterIcon className="text-twitter" />}
        service="twitter"
        user={user}
      />
      <div className="text-muted text-xs pt-16 pb-6 opacity-70">
        <Trans message="Si vous désactivez les connexions sociales, vous pourrez toujours vous connecter en utilisant votre email et votre mot de passe." />
      </div>
    </AccountSettingsPanel>
  );
}

interface SocialLoginPanelRowProps {
  service: SocialService;
  user: User;
  className?: string;
  icon: ReactElement;
}

function SocialLoginPanelRow({
  service,
  user,
  className,
  icon,
}: SocialLoginPanelRowProps) {
  const {social} = useSettings();
  const {connectSocial, disconnectSocial} = useSocialLogin();
  const username = user?.social_profiles?.find(
    s => s.service_name === service
  )?.username;

  if (!social?.[service]?.enable) {
    return null;
  }

  return (
    <div
      className={clsx(
        'flex items-center gap-14 px-14 py-16 mb-8 rounded-xl bg-white/5 border border-white/8 transition-colors hover:bg-white/8',
        className
      )}
    >
      {cloneElement(icon, {
        size: 'xl',
        className: clsx(icon.props.className, 'border border-white/15 p-8 rounded-lg'),
      })}
      <div className="mr-auto whitespace-nowrap overflow-hidden text-ellipsis">
        <div className="first-letter:capitalize text-sm font-bold overflow-hidden text-ellipsis">
          <Trans message="Compte :service" values={{service}} />
        </div>
        <div className="text-xs mt-2">
          {username || <Trans message="Désactivé" />}
        </div>
      </div>
      <Button
        disabled={disconnectSocial.isLoading}
        size="xs"
        variant="outline"
        color={username ? 'danger' : 'primary'}
        onClick={async () => {
          if (username) {
            disconnectSocial.mutate(
              {service},
              {
                onSuccess: () => {
                  queryClient.invalidateQueries(['users']);
                  toast(
                    message('Compte :service désactivé', {values: {service}})
                  );
                },
              }
            );
          } else {
            const e = await connectSocial(service);
            if (e?.status === 'SUCCESS') {
              queryClient.invalidateQueries(['users']);
              toast(message('Compte :service activé', {values: {service}}));
            }
          }
        }}
      >
        {username ? <Trans message="Désactiver" /> : <Trans message="Activer" />}
      </Button>
    </div>
  );
}
