import {Track} from '@app/web-player/tracks/track';
import {usePlayerStore} from '@common/player/hooks/use-player-store';
import {useEffect} from 'react';
import {
  tracksToMediaItems,
  trackToMediaItem,
} from '@app/web-player/tracks/utils/track-to-media-item';
import {useBaseSeekbar} from '@app/web-player/player-controls/seekbar/use-base-seekbar';

export function useTrackSeekbar(track: Track, queue?: Track[]) {
  const duration = usePlayerStore(s => {
    // either use exact duration from provider if this track is cued, or use duration from track props
    return s.cuedMedia?.id === track.id && s.mediaDuration
      ? s.mediaDuration
      : (track.duration || 0) / 1000;
  });

  const {player, startTimer, stopTimer, listeners, time, setTime} =
    useBaseSeekbar();

  // when this track is cued, listen to playback events and sync seekbar,
  // when a different track is cued, unsubscribe and reset seekbar
  useEffect(() => {
    let unsubscribeFromPlayback: (() => void) | undefined;

    // subscribe on initial load, if this track is already cued
    if (player.getState().cuedMedia?.id === track.id) {
      unsubscribeFromPlayback = player.subscribe(listeners);
      // if we render seekbar when player is already playing, need to start the timer here
      if (player.getState().status === 'playing') {
        startTimer();
      }
    }

    const unsubscribeFromCuedTrack = player.subscribe({
      onCued: media => {
        if (media?.id === track.id) {
          unsubscribeFromPlayback = player.subscribe(listeners);
        } else {
          unsubscribeFromPlayback?.();
          stopTimer();
          setTime(0);
        }
      },
    });

    return () => {
      unsubscribeFromCuedTrack?.();
      unsubscribeFromPlayback?.();
    };
  }, [player, track, listeners, setTime, stopTimer, startTimer]);

  return {
    duration,
    minValue: 0,
    maxValue: duration,
    value: time,
    onPointerDown: () => {
      stopTimer();
      player.pause();
      player.cue(trackToMediaItem(track));
    },
    onChange: (value: number) => {
      setTime(value);
      player.seek(value);
    },
    onChangeEnd: () => {
      if (queue?.length) {
        const pointer = queue?.findIndex(t => t.id === track.id);
        player.overrideQueueAndPlay(tracksToMediaItems(queue), pointer);
      } else {
        player.play(track ? trackToMediaItem(track) : undefined);
      }
    },
  };
}
