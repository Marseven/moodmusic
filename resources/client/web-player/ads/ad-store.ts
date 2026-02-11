import {create} from 'zustand';
import {apiClient} from '@common/http/query-client';
import {getBootstrapData} from '@common/core/bootstrap-data/use-backend-bootstrap-data';

export interface AdSpotData {
  id: number;
  name: string;
  audio_url: string;
  image_url?: string;
  click_url?: string;
  duration: number;
}

interface AdState {
  tracksSinceLastAd: number;
  isPlayingAd: boolean;
  currentAd: AdSpotData | null;
  adAudio: HTMLAudioElement | null;
  // Callback to resume player after ad finishes
  resumePlayback: (() => void) | null;
}

interface AdActions {
  incrementTrackCount: () => void;
  shouldShowAd: () => boolean;
  playAd: (resumePlayback: () => void) => Promise<void>;
  stopAd: () => void;
  onAdClick: () => void;
  reset: () => void;
}

function isSubscribed(): boolean {
  const user = getBootstrapData().user;
  return user?.subscriptions?.find((sub: any) => sub.valid) != null;
}

function getAdsSettings() {
  const settings = getBootstrapData().settings;
  return {
    enabled: settings?.ads?.enabled ?? false,
    frequency: parseInt(settings?.ads?.frequency ?? '3') || 3,
  };
}

export const useAdStore = create<AdState & AdActions>()((set, get) => ({
  tracksSinceLastAd: 0,
  isPlayingAd: false,
  currentAd: null,
  adAudio: null,
  resumePlayback: null,

  incrementTrackCount: () => {
    set(state => ({tracksSinceLastAd: state.tracksSinceLastAd + 1}));
  },

  shouldShowAd: () => {
    const {enabled, frequency} = getAdsSettings();
    if (!enabled || isSubscribed()) return false;
    return get().tracksSinceLastAd >= frequency;
  },

  playAd: async (resumePlayback: () => void) => {
    // Set isPlayingAd SYNCHRONOUSLY before any async operation.
    // This ensures the play listener will pause the main player
    // when the internal playbackEnd handler calls playNext().
    set({
      isPlayingAd: true,
      tracksSinceLastAd: 0,
      resumePlayback,
    });

    try {
      const response = await apiClient.get('ads/next');
      const ad: AdSpotData | null = response.data.ad;

      if (!ad) {
        // No ad available, resume playback
        set({isPlayingAd: false, resumePlayback: null});
        resumePlayback();
        return;
      }

      const audio = new Audio(ad.audio_url);
      audio.volume = 0.8;

      audio.addEventListener('ended', () => {
        get().stopAd();
      });

      audio.addEventListener('error', () => {
        // On audio error, just stop the ad and resume
        get().stopAd();
      });

      set({
        currentAd: ad,
        adAudio: audio,
      });

      await audio.play();
    } catch {
      // If fetching ad fails, resume playback
      set({isPlayingAd: false, tracksSinceLastAd: 0, resumePlayback: null});
      resumePlayback();
    }
  },

  stopAd: () => {
    const {adAudio, resumePlayback} = get();
    if (adAudio) {
      adAudio.pause();
      adAudio.src = '';
    }
    set({
      isPlayingAd: false,
      currentAd: null,
      adAudio: null,
      resumePlayback: null,
    });
    // Resume the main player
    resumePlayback?.();
  },

  onAdClick: () => {
    const {currentAd} = get();
    if (currentAd?.click_url) {
      apiClient.post(`ads/${currentAd.id}/click`);
      window.open(currentAd.click_url, '_blank');
    }
  },

  reset: () => {
    const {adAudio} = get();
    if (adAudio) {
      adAudio.pause();
      adAudio.src = '';
    }
    set({
      tracksSinceLastAd: 0,
      isPlayingAd: false,
      currentAd: null,
      adAudio: null,
      resumePlayback: null,
    });
  },
}));
