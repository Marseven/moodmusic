import React, {useEffect, useState} from 'react';
import {AnimatePresence, m} from 'framer-motion';
import {Trans} from '@common/i18n/trans';
import {Button} from '@common/ui/buttons/button';
import {useAdStore} from './ad-store';

export function AdOverlay() {
  const isPlayingAd = useAdStore(s => s.isPlayingAd);
  const currentAd = useAdStore(s => s.currentAd);
  const adAudio = useAdStore(s => s.adAudio);
  const onAdClick = useAdStore(s => s.onAdClick);
  const [remaining, setRemaining] = useState(0);

  useEffect(() => {
    if (!adAudio || !currentAd) return;

    setRemaining(currentAd.duration);

    const interval = setInterval(() => {
      if (adAudio.duration && !isNaN(adAudio.currentTime)) {
        const left = Math.max(
          0,
          Math.ceil(adAudio.duration - adAudio.currentTime),
        );
        setRemaining(left);
      }
    }, 500);

    return () => clearInterval(interval);
  }, [adAudio, currentAd]);

  return (
    <AnimatePresence>
      {isPlayingAd && currentAd && (
        <m.div
          initial={{opacity: 0}}
          animate={{opacity: 1}}
          exit={{opacity: 0}}
          transition={{duration: 0.3}}
          className="fixed inset-x-0 bottom-0 z-50 flex items-end justify-center"
          style={{bottom: 'var(--player-controls-height, 96px)'}}
        >
          <div className="mx-auto mb-8 w-full max-w-780 overflow-hidden mood-glass-panel">
            {/* Banner image (full width) */}
            {currentAd.image_url && (
              <button
                type="button"
                onClick={onAdClick}
                className="block w-full"
              >
                <img
                  src={currentAd.image_url}
                  alt={currentAd.name}
                  className="w-full object-contain"
                />
              </button>
            )}

            {/* Info bar */}
            <div className="flex items-center gap-16 px-16 py-8">
              <div className="flex min-w-0 flex-1 flex-col gap-4">
                <div className="flex items-center gap-8">
                  <span className="rounded bg-warning/20 px-8 py-2 text-xs font-bold uppercase text-warning">
                    <Trans message="Ad" />
                  </span>
                  <span className="truncate text-sm font-medium">
                    {currentAd.name}
                  </span>
                </div>

                {/* Progress bar */}
                <div className="h-4 w-full overflow-hidden rounded-full bg-chip">
                  <div
                    className="h-full rounded-full bg-warning transition-all duration-500"
                    style={{
                      width:
                        currentAd.duration > 0
                          ? `${((currentAd.duration - remaining) / currentAd.duration) * 100}%`
                          : '0%',
                    }}
                  />
                </div>
              </div>

              {/* Countdown + CTA */}
              <div className="flex flex-shrink-0 items-center gap-12">
                <span className="text-sm text-muted">{remaining}s</span>
                {currentAd.click_url && (
                  <Button
                    size="xs"
                    variant="outline"
                    color="primary"
                    onClick={onAdClick}
                  >
                    <Trans message="Learn more" />
                  </Button>
                )}
              </div>
            </div>
          </div>
        </m.div>
      )}
    </AnimatePresence>
  );
}
