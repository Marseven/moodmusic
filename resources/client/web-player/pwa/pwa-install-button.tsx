import {usePwaInstall} from './use-pwa-install';
import {Trans} from '@common/i18n/trans';
import {ModernDownloadIcon} from '@app/web-player/icons/modern-icons';

export function PwaInstallButton() {
  const {isInstallable, isInstalled, isDismissed, promptInstall} =
    usePwaInstall();

  if (!isInstallable || isInstalled || isDismissed) {
    return null;
  }

  return (
    <button
      onClick={() => promptInstall()}
      className="flex items-center gap-8 text-sm h-44 px-12 mx-12 rounded-lg mood-transition-smooth hover:bg-hover hover:mood-glass-button w-[calc(100%-24px)] text-left"
    >
      <ModernDownloadIcon className="text-muted" size="sm" />
      <Trans message="Installer l'app" />
    </button>
  );
}
