import {create} from 'zustand';

interface BeforeInstallPromptEvent extends Event {
  prompt(): Promise<void>;
  userChoice: Promise<{outcome: 'accepted' | 'dismissed'}>;
}

const DISMISS_KEY = 'pwa-install-dismissed';
const DISMISS_DAYS = 7;

function isDismissed(): boolean {
  const raw = localStorage.getItem(DISMISS_KEY);
  if (!raw) return false;
  const expiry = parseInt(raw, 10);
  if (Date.now() > expiry) {
    localStorage.removeItem(DISMISS_KEY);
    return false;
  }
  return true;
}

function saveDismiss(): void {
  const expiry = Date.now() + DISMISS_DAYS * 24 * 60 * 60 * 1000;
  localStorage.setItem(DISMISS_KEY, String(expiry));
}

interface PwaInstallState {
  deferredPrompt: BeforeInstallPromptEvent | null;
  isInstallable: boolean;
  isInstalled: boolean;
  isDismissed: boolean;
  promptInstall: () => Promise<void>;
  dismiss: () => void;
}

export const usePwaInstall = create<PwaInstallState>()((set, get) => ({
  deferredPrompt: null,
  isInstallable: false,
  isInstalled: window.matchMedia('(display-mode: standalone)').matches,
  isDismissed: isDismissed(),

  promptInstall: async () => {
    const {deferredPrompt} = get();
    if (!deferredPrompt) return;

    await deferredPrompt.prompt();
    const {outcome} = await deferredPrompt.userChoice;

    if (outcome === 'accepted') {
      set({isInstalled: true, isInstallable: false, deferredPrompt: null});
    } else {
      get().dismiss();
    }
  },

  dismiss: () => {
    saveDismiss();
    set({isDismissed: true, deferredPrompt: null, isInstallable: false});
  },
}));

// Listen for the browser's install prompt
window.addEventListener('beforeinstallprompt', (e: Event) => {
  e.preventDefault();
  usePwaInstall.setState({
    deferredPrompt: e as BeforeInstallPromptEvent,
    isInstallable: true,
  });
});

// Detect when app is installed
window.addEventListener('appinstalled', () => {
  usePwaInstall.setState({
    isInstalled: true,
    isInstallable: false,
    deferredPrompt: null,
  });
});
