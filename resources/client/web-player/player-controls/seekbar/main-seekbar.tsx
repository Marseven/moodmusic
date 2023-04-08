import {Slider} from '@common/ui/forms/slider/slider';
import {FormattedDuration} from '@common/i18n/formatted-duration';
import {useEffect} from 'react';
import {usePlayerStore} from '@common/player/hooks/use-player-store';
import {useBaseSeekbar} from '@app/web-player/player-controls/seekbar/use-base-seekbar';

export function MainSeekbar() {
  const duration = usePlayerStore(s => s.mediaDuration);
  const isUninitialized = usePlayerStore(s => s.status === 'uninitialized');
  const {time, player, listeners, startTimer, stopTimer, setTime} =
    useBaseSeekbar();

  useEffect(() => {
    const unsubscribe = player.subscribe({
      ...listeners,
      // stop timer and set time to zero when cueing a new track
      onCued: () => {
        stopTimer();
        setTime(0);
      },
    });

    // if we render seekbar when player is already playing (player overlay for example), need to start the timer here
    if (player.getState().status === 'playing') {
      startTimer();
    }

    return () => {
      unsubscribe();
    };
  }, [player, listeners, stopTimer, startTimer, setTime]);

  return (
    <div className="flex items-center gap-12">
      <div className="text-xs text-muted flex-shrink-0 min-w-40 text-right">
        {time ? <FormattedDuration seconds={time} /> : '0:00'}
      </div>
      <Slider
        isDisabled={isUninitialized}
        trackColor="neutral"
        thumbSize="w-14 h-14"
        showThumbOnHoverOnly={true}
        minValue={0}
        maxValue={duration}
        className="flex-auto"
        width="w-auto"
        value={time}
        onPointerDown={() => {
          stopTimer();
          player.pause();
        }}
        onChange={value => {
          setTime(value);
          player.seek(value);
        }}
        onChangeEnd={() => {
          player.play();
        }}
      />
      <div className="text-xs text-muted flex-shrink-0 min-w-40">
        <FormattedDuration seconds={duration} />
      </div>
    </div>
  );
}
