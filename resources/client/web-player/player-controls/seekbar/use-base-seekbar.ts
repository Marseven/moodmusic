import {useCallback, useEffect, useMemo, useRef, useState} from 'react';
import {usePlayerActions} from '@common/player/hooks/use-player-actions';
import {ProviderListeners} from '@common/player/player-state';

export function useBaseSeekbar() {
  const [time, setTime] = useState(0);
  const player = usePlayerActions();

  const intervalRef = useRef<ReturnType<typeof setInterval> | undefined>();

  const stopTimer = useCallback(() => {
    clearInterval(intervalRef.current);
    intervalRef.current = undefined;
  }, []);

  const startTimer = useCallback(() => {
    stopTimer();

    setTime(Math.round(player.getCurrentTime()));

    intervalRef.current = setInterval(() => {
      setTime(prevTime => {
        const newTime = prevTime + 1;
        if (newTime > player.getState().mediaDuration) {
          stopTimer();
          return prevTime;
        }
        return newTime;
      });
    }, 1000);
  }, [player, stopTimer]);

  // sync seekbar with playback progress and status
  const listeners: ProviderListeners = useMemo(() => {
    return {
      onPlay: () => {
        startTimer();
      },
      onPause: () => {
        stopTimer();
      },
      onSeek: time => setTime(time),
      onPlaybackEnd: () => stopTimer(),
    };
  }, [startTimer, stopTimer]);

  useEffect(() => {
    return stopTimer;
  }, [stopTimer]);

  return {time, setTime, listeners, startTimer, stopTimer, player};
}
