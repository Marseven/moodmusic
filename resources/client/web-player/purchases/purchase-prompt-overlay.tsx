import React, {useMemo} from 'react';
import {AnimatePresence, m} from 'framer-motion';
import {Trans} from '@common/i18n/trans';
import {Button} from '@common/ui/buttons/button';
import {IconButton} from '@common/ui/buttons/icon-button';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {usePurchaseGatingStore} from './purchase-gating-store';
import {PurchaseDialog} from './purchase-dialog';
import {formatPrice} from './buy-button';
import {ModernXIcon} from '@app/web-player/icons/modern-icons';

export function PurchasePromptOverlay() {
  const isVisible = usePurchaseGatingStore(s => s.isPromptVisible);
  const track = usePurchaseGatingStore(s => s.gatedTrack);
  const hidePrompt = usePurchaseGatingStore(s => s.hidePrompt);

  // Determine if gating comes from album price (track has no own price but album does)
  const purchaseItem = useMemo(() => {
    if (!track) return null;
    const trackPrice = parseFloat(String(track.price ?? 0));
    if (trackPrice > 0) return track;
    const albumPrice = parseFloat(String(track.album?.price ?? 0));
    if (albumPrice > 0 && track.album) return track.album;
    return track;
  }, [track]);

  const displayPrice = useMemo(() => {
    if (!purchaseItem) return 0;
    return parseFloat(String(purchaseItem.price ?? 0));
  }, [purchaseItem]);

  const displayCurrency = purchaseItem?.currency ?? 'XAF';

  return (
    <AnimatePresence>
      {isVisible && track && purchaseItem && (
        <m.div
          initial={{opacity: 0}}
          animate={{opacity: 1}}
          exit={{opacity: 0}}
          transition={{duration: 0.3}}
          className="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
        >
          <m.div
            initial={{scale: 0.95, opacity: 0}}
            animate={{scale: 1, opacity: 1}}
            exit={{scale: 0.95, opacity: 0}}
            className="relative mx-16 w-full max-w-400 mood-glass-modal p-24"
          >
            {/* Close button */}
            <IconButton
              className="absolute right-8 top-8 text-muted"
              size="sm"
              onClick={hidePrompt}
            >
              <ModernXIcon />
            </IconButton>

            {/* Track info */}
            <div className="mb-20 text-center">
              {(track.image || track.album?.image) && (
                <img
                  src={track.image || track.album?.image}
                  alt={track.name}
                  className="mx-auto mb-16 h-80 w-80 rounded-lg object-cover"
                />
              )}
              <h3 className="text-lg font-bold">{track.name}</h3>
              {track.artists?.[0] && (
                <p className="text-sm text-muted">{track.artists[0].name}</p>
              )}
              {purchaseItem.model_type === 'album' && (
                <p className="mt-4 text-xs text-muted">
                  Album : {purchaseItem.name}
                </p>
              )}
            </div>

            {/* Message */}
            <p className="mb-20 text-center text-sm text-muted">
              {purchaseItem.model_type === 'album' ? (
                <Trans message="This preview has ended. Purchase this album to listen in full." />
              ) : (
                <Trans message="This preview has ended. Purchase this track to listen in full." />
              )}
            </p>

            {/* Price */}
            {displayPrice > 0 && (
              <div className="mb-20 text-center text-xl font-bold text-primary">
                {formatPrice(displayPrice, displayCurrency)}
              </div>
            )}

            {/* Action */}
            <DialogTrigger
              type="modal"
              onClose={() => {
                usePurchaseGatingStore.getState().hidePrompt();
              }}
            >
              <Button
                className="w-full"
                variant="flat"
                color="primary"
              >
                <Trans message="Donner la force" />
              </Button>
              <PurchaseDialog item={purchaseItem} />
            </DialogTrigger>
          </m.div>
        </m.div>
      )}
    </AnimatePresence>
  );
}
