import {create} from 'zustand';
import {Track} from '@app/web-player/tracks/track';
import {apiClient} from '@common/http/query-client';

interface PurchaseGatingState {
  isPromptVisible: boolean;
  gatedTrack: Track | null;
  promptedTrackId: number | null;
  skipToNext: (() => void) | null;
  // Cache of purchased track IDs
  purchasedTrackIds: Set<number>;
  purchasesCacheLoaded: boolean;
}

interface PurchaseGatingActions {
  showPrompt: (track: Track, skipToNext: () => void) => void;
  hidePrompt: () => void;
  skipTrack: () => void;
  reset: () => void;
  loadPurchases: () => Promise<void>;
  isTrackPurchased: (trackId: number) => boolean;
  markAsPurchased: (trackId: number) => void;
}

export const usePurchaseGatingStore = create<
  PurchaseGatingState & PurchaseGatingActions
>()((set, get) => ({
  isPromptVisible: false,
  gatedTrack: null,
  promptedTrackId: null,
  skipToNext: null,
  purchasedTrackIds: new Set(),
  purchasesCacheLoaded: false,

  showPrompt: (track: Track, skipToNext: () => void) => {
    set({
      isPromptVisible: true,
      gatedTrack: track,
      promptedTrackId: track.id,
      skipToNext,
    });
  },

  hidePrompt: () => {
    set({
      isPromptVisible: false,
      gatedTrack: null,
      skipToNext: null,
    });
  },

  skipTrack: () => {
    const {skipToNext} = get();
    set({
      isPromptVisible: false,
      gatedTrack: null,
      skipToNext: null,
    });
    skipToNext?.();
  },

  reset: () => {
    set({
      isPromptVisible: false,
      gatedTrack: null,
      promptedTrackId: null,
      skipToNext: null,
    });
  },

  loadPurchases: async () => {
    if (get().purchasesCacheLoaded) return;
    try {
      const response = await apiClient.get('purchased-items');
      const items: Array<{purchasable_type: string; purchasable_id: number}> =
        response.data.purchases || [];
      const trackIds = new Set<number>();
      for (const item of items) {
        if (item.purchasable_type === 'App\\Track') {
          trackIds.add(item.purchasable_id);
        }
      }
      set({purchasedTrackIds: trackIds, purchasesCacheLoaded: true});
    } catch {
      // silently fail
    }
  },

  isTrackPurchased: (trackId: number) => {
    return get().purchasedTrackIds.has(trackId);
  },

  markAsPurchased: (trackId: number) => {
    const ids = new Set(get().purchasedTrackIds);
    ids.add(trackId);
    set({purchasedTrackIds: ids});
  },
}));
