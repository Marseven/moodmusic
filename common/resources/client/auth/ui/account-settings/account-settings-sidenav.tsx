import {List, ListItem} from '@common/ui/list/list';
import {PersonIcon} from '@common/icons/material/Person';
import {Trans} from '@common/i18n/trans';
import {LoginIcon} from '@common/icons/material/Login';
import {LockIcon} from '@common/icons/material/Lock';
import {PhonelinkLockIcon} from '@common/icons/material/PhonelinkLock';
import {LanguageIcon} from '@common/icons/material/Language';
import {ApiIcon} from '@common/icons/material/Api';
import {DangerousIcon} from '@common/icons/material/Dangerous';
import {ReactNode} from 'react';
import {DevicesIcon} from '@common/icons/material/Devices';
import {useAuth} from '@common/auth/use-auth';
import {useSettings} from '@common/core/settings/use-settings';

export enum AccountSettingsId {
  AccountDetails = 'account-details',
  SocialLogin = 'social-login',
  Password = 'password',
  TwoFactor = 'two-factor',
  LocationAndLanguage = 'location-and-language',
  Developers = 'developers',
  DeleteAccount = 'delete-account',
  Sessions = 'sessions',
}

export function AccountSettingsSidenav() {
  const p = AccountSettingsId;

  const {hasPermission} = useAuth();
  const {api, social} = useSettings();

  const socialEnabled =
    social?.envato || social?.google || social?.facebook || social?.twitter;

  return (
    <aside className="flex-shrink-0 sticky top-10 hidden lg:block">
      <List padding="p-0">
        <Item icon={<PersonIcon />} panel={p.AccountDetails}>
          <Trans message="Détails du compte" />
        </Item>
        {socialEnabled && (
          <Item icon={<LoginIcon />} panel={p.SocialLogin}>
            <Trans message="Connexion sociale" />
          </Item>
        )}
        <Item icon={<LockIcon />} panel={p.Password}>
          <Trans message="Mot de passe" />
        </Item>
        <Item icon={<PhonelinkLockIcon />} panel={p.TwoFactor}>
          <Trans message="Authentification à deux facteurs" />
        </Item>
        <Item icon={<DevicesIcon />} panel={p.Sessions}>
          <Trans message="Sessions actives" />
        </Item>
        <Item icon={<LanguageIcon />} panel={p.LocationAndLanguage}>
          <Trans message="Localisation et langue" />
        </Item>
        {api?.integrated && hasPermission('api.access') ? (
          <Item icon={<ApiIcon />} panel={p.Developers}>
            <Trans message="Développeurs" />
          </Item>
        ) : null}
        <Item icon={<DangerousIcon />} panel={p.DeleteAccount}>
          <Trans message="Supprimer le compte" />
        </Item>
      </List>
    </aside>
  );
}

interface ItemProps {
  children: ReactNode;
  icon: ReactNode;
  isLast?: boolean;
  panel: AccountSettingsId;
}
function Item({children, icon, isLast, panel}: ItemProps) {
  return (
    <ListItem
      startIcon={icon}
      className={isLast ? undefined : 'mb-10'}
      onSelected={() => {
        const panelEl = document.querySelector(`#${panel}`);
        if (panelEl) {
          panelEl.scrollIntoView({
            behavior: 'smooth',
            block: 'start',
          });
        }
      }}
    >
      {children}
    </ListItem>
  );
}
