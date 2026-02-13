import {Navbar} from '@common/ui/navigation/navbar/navbar';
import {useUser} from '../use-user';
import {ProgressCircle} from '@common/ui/progress/progress-circle';
import {SocialLoginPanel} from './social-login-panel';
import {BasicInfoPanel} from './basic-info-panel/basic-info-panel';
import {ChangePasswordPanel} from './change-password-panel/change-password-panel';
import {LocalizationPanel} from './localization-panel';
import {AccessTokenPanel} from './access-token-panel/access-token-panel';
import {DangerZonePanel} from './danger-zone-panel/danger-zone-panel';
import {Trans} from '@common/i18n/trans';
import {StaticPageTitle} from '@common/seo/static-page-title';
import {AccountSettingsPanel} from '@common/auth/ui/account-settings/account-settings-panel';
import {TwoFactorStepper} from '@common/auth/ui/two-factor/stepper/two-factor-auth-stepper';
import {
  AccountSettingsId,
  AccountSettingsSidenav,
} from '@common/auth/ui/account-settings/account-settings-sidenav';
import {SessionsPanel} from '@common/auth/ui/account-settings/sessions-panel/sessions-panel';

export function AccountSettingsPage() {
  const {data, isLoading} = useUser('me', {
    with: ['roles', 'social_profiles', 'tokens'],
  });
  return (
    <div className="bg-alt flex flex-col h-full">
      <StaticPageTitle>
        <Trans message="Paramètres du compte" />
      </StaticPageTitle>
      <Navbar className="flex-shrink-0" menuPosition="account-settings-page" />
      <div className="flex-auto overflow-auto scroll-smooth">
        <div className="container mx-auto my-24 md:my-40 px-24">
          <div className="mb-32">
            <h1 className="text-3xl md:text-4xl font-bold tracking-tight">
              <Trans message="Paramètres du compte" />
            </h1>
            <p className="mt-8 text-muted text-base">
              <Trans message="Consultez et mettez à jour les détails de votre compte, votre profil et plus encore." />
            </p>
          </div>
          {isLoading || !data ? (
            <div className="mood-glass-panel p-12 text-center">
              <ProgressCircle
                className="my-80"
                aria-label="Loading user.."
                isIndeterminate
              />
            </div>
          ) : (
            <div className="flex items-start gap-30">
              <div className="mood-glass-nav rounded-2xl">
                <AccountSettingsSidenav />
              </div>
              <main className="flex-auto min-w-0">
                <BasicInfoPanel user={data.user} />
                <SocialLoginPanel user={data.user} />
                <ChangePasswordPanel />
                <AccountSettingsPanel
                  id={AccountSettingsId.TwoFactor}
                  title={<Trans message="Authentification à deux facteurs" />}
                >
                  <div className="max-w-580">
                    <TwoFactorStepper user={data.user} />
                  </div>
                </AccountSettingsPanel>
                <SessionsPanel user={data.user} />
                <LocalizationPanel user={data.user} />
                <AccessTokenPanel user={data.user} />
                <DangerZonePanel />
              </main>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
