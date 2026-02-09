import {useCallback, useEffect, useRef, useState} from 'react';
import {usePlayerActions} from '@common/player/hooks/use-player-actions';
import {usePlayerStore} from '@common/player/hooks/use-player-store';

export function useBaseSeekbar() {
  const {pause, seek, setIsSeeking, play, getCurrentTime, subscribe} = usePlayerActions();
  const duration = usePlayerStore(s => s.mediaDuration);
  const playerReady = usePlayerStore(s => s.providerReady);
  const pauseWhileSeeking = usePlayerStore(s => s.pauseWhileSeeking);
  const isPlaying = usePlayerStore(s => s.isPlaying);
  const providerKey = usePlayerStore(s =>
    s.providerName && s.cuedMedia?.id
      ? `${s.providerName}+${s.cuedMedia.id}`
      : null
  );
  
  const [currentTime, setCurrentTime] = useState(() => getCurrentTime());
  const wasPlayingBeforeDragging = useRef(false);

  // Listen to player events using subscribe
  useEffect(() => {
    return subscribe({
      progress: ({currentTime}) => setCurrentTime(currentTime),
    });
  }, [subscribe]);

  // Update current time when media or provider changes
  useEffect(() => {
    if (providerKey) {
      setCurrentTime(getCurrentTime());
    }
  }, [providerKey, getCurrentTime]);

  const handlePointerDown = useCallback(() => {
    setIsSeeking(true);
    if (pauseWhileSeeking) {
      wasPlayingBeforeDragging.current = isPlaying;
      pause();
    }
  }, [setIsSeeking, pauseWhileSeeking, isPlaying, pause]);

  const handleChange = useCallback((value: number) => {
    setCurrentTime(value);
    seek(value);
  }, [seek]);

  const handleChangeEnd = useCallback(() => {
    setIsSeeking(false);
    if (pauseWhileSeeking && wasPlayingBeforeDragging.current) {
      play();
      wasPlayingBeforeDragging.current = false;
    }
  }, [setIsSeeking, pauseWhileSeeking, play]);

  return {
    currentTime,
    duration,
    playerReady,
    handlePointerDown,
    handleChange,
    handleChangeEnd,
  };
}