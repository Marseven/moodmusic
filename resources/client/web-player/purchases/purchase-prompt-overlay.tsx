import React from 'react';
import {AnimatePresence, m} from 'framer-motion';
import {Trans} from '@common/i18n/trans';
import {Button} from '@common/ui/buttons/button';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {usePurchaseGatingStore} from './purchase-gating-store';
import {PurchaseDialog} from './purchase-dialog';
import {formatPrice} from './buy-button';

export function PurchasePromptOverlay() {
  const isVisible = usePurchaseGatingStore(s => s.isPromptVisible);
  const track = usePurchaseGatingStore(s => s.gatedTrack);
  const skipTrack = usePurchaseGatingStore(s => s.skipTrack);

  return (
    <AnimatePresence>
      {isVisible && track && (
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
            className="mx-16 w-full max-w-400 mood-glass-modal p-24"
          >
            {/* Track info */}
            <div className="mb-20 text-center">
              {track.image && (
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
            </div>

            {/* Message */}
            <p className="mb-20 text-center text-sm text-muted">
              <Trans message="This preview has ended. Purchase this track to listen in full." />
            </p>

            {/* Price */}
            {track.price != null && track.price > 0 && (
              <div className="mb-20 text-center text-xl font-bold text-primary">
                {formatPrice(track.price, track.currency ?? 'XAF')}
              </div>
            )}

            {/* Actions */}
            <div className="flex gap-12">
              <Button
                className="flex-1"
                variant="outline"
                onClick={skipTrack}
              >
                <Trans message="Skip" />
              </Button>
              <DialogTrigger
                type="modal"
                onClose={() => {
                  // After purchase dialog closes, hide the prompt
                  usePurchaseGatingStore.getState().hidePrompt();
                }}
              >
                <Button
                  className="flex-1"
                  variant="flat"
                  color="primary"
                >
                  <Trans message="Buy" />
                </Button>
                <PurchaseDialog item={track} />
              </DialogTrigger>
            </div>
          </m.div>
        </m.div>
      )}
    </AnimatePresence>
  );
}
