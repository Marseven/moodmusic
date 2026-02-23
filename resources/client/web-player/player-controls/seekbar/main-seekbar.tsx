import {useCallback, useMemo, useRef, useState} from 'react';
import {FormattedCurrentTime} from '@common/player/ui/controls/formatted-current-time';
import {FormattedPlayerDuration} from '@common/player/ui/controls/formatted-player-duration';
import {useBaseSeekbar} from './use-base-seekbar';

const BAR_COUNT = 64;
const BAR_WIDTH = 3;
const BAR_GAP = 1;
const BAR_HEIGHT_MIN = 2;
const BAR_HEIGHT_MAX = 16;
const CANVAS_HEIGHT = BAR_HEIGHT_MAX + 2;

function generateWaveform(seed: number): number[] {
  const bars: number[] = [];
  for (let i = 0; i < BAR_COUNT; i++) {
    const x = i / BAR_COUNT;
    const wave1 = Math.sin(x * Math.PI * 2 * 3 + seed) * 0.3;
    const wave2 = Math.sin(x * Math.PI * 2 * 7 + seed * 1.5) * 0.2;
    const wave3 = Math.sin(x * Math.PI * 2 * 13 + seed * 0.7) * 0.15;
    const base = 0.35;
    const val = Math.max(0.1, Math.min(1, base + wave1 + wave2 + wave3));
    bars.push(val);
  }
  return bars;
}

export function MainSeekbar() {
  const {
    currentTime,
    duration,
    playerReady,
    handlePointerDown,
    handleChange,
    handleChangeEnd,
  } = useBaseSeekbar();

  const [isDragging, setIsDragging] = useState(false);
  const [isHovering, setIsHovering] = useState(false);
  const trackRef = useRef<HTMLDivElement>(null);

  const progressPercentage = duration > 0 ? (currentTime / duration) * 100 : 0;

  const waveform = useMemo(
    () => generateWaveform(duration > 0 && isFinite(duration) ? duration : 42),
    [duration > 0 && isFinite(duration)]
  );

  const calculateValueFromPosition = useCallback(
    (clientX: number) => {
      if (!trackRef.current || !duration) return 0;
      const rect = trackRef.current.getBoundingClientRect();
      const percentage = Math.max(
        0,
        Math.min(100, ((clientX - rect.left) / rect.width) * 100)
      );
      return (percentage / 100) * duration;
    },
    [duration]
  );

  const onPointerDown = useCallback(
    (e: React.PointerEvent) => {
      if (!playerReady) return;
      setIsDragging(true);
      handlePointerDown();
      const value = calculateValueFromPosition(e.clientX);
      handleChange(value);
      (e.target as Element).setPointerCapture(e.pointerId);
    },
    [playerReady, handlePointerDown, handleChange, calculateValueFromPosition]
  );

  const onPointerMove = useCallback(
    (e: React.PointerEvent) => {
      if (isDragging) {
        const value = calculateValueFromPosition(e.clientX);
        handleChange(value);
      }
    },
    [isDragging, handleChange, calculateValueFromPosition]
  );

  const onPointerUp = useCallback(
    (e: React.PointerEvent) => {
      setIsDragging(false);
      handleChangeEnd();
      (e.target as Element).releasePointerCapture(e.pointerId);
    },
    [handleChangeEnd]
  );

  const thumbActive = isDragging || isHovering;
  const totalWidth = BAR_COUNT * (BAR_WIDTH + BAR_GAP);

  return (
    <div className="flex items-center gap-12">
      <div className="text-xs text-muted flex-shrink-0 min-w-40 text-right tabular-nums">
        <FormattedCurrentTime />
      </div>
      <div
        ref={trackRef}
        className="wave-seekbar group relative flex-auto cursor-pointer"
        style={{height: `${CANVAS_HEIGHT + 8}px`}}
        role="presentation"
        onPointerDown={onPointerDown}
        onPointerMove={onPointerMove}
        onPointerUp={onPointerUp}
        onMouseEnter={() => setIsHovering(true)}
        onMouseLeave={() => setIsHovering(false)}
      >
        {/* Fallback thin progress track */}
        <div
          className="absolute left-0 top-1/2 -translate-y-1/2 w-full h-2 rounded-full"
          style={{background: 'rgba(255,255,255,0.1)'}}
        >
          <div
            className="h-full rounded-full"
            style={{
              width: `${progressPercentage}%`,
              background: 'rgb(var(--be-primary))',
            }}
          />
        </div>
        {/* SVG Waveform bars */}
        <svg
          className="absolute top-0 left-0 w-full"
          viewBox={`0 0 ${totalWidth} ${CANVAS_HEIGHT}`}
          preserveAspectRatio="none"
          style={{height: `${CANVAS_HEIGHT + 8}px`}}
        >
          {waveform.map((val, i) => {
            const barHeight =
              BAR_HEIGHT_MIN + val * (BAR_HEIGHT_MAX - BAR_HEIGHT_MIN);
            const x = i * (BAR_WIDTH + BAR_GAP);
            const y = (CANVAS_HEIGHT - barHeight) / 2;
            const barProgress =
              (i / BAR_COUNT) * 100;
            const isPlayed = barProgress < progressPercentage;
            return (
              <rect
                key={i}
                x={x}
                y={y}
                width={BAR_WIDTH}
                height={barHeight}
                rx={1.5}
                style={{fill: isPlayed ? 'rgb(var(--be-primary))' : 'rgba(255,255,255,0.25)'}}
              />
            );
          })}
        </svg>
        {/* Thumb */}
        <div
          className={`wave-seekbar-thumb ${thumbActive ? 'active' : ''}`}
          style={{left: `${progressPercentage}%`}}
        />
        <input
          tabIndex={playerReady ? 0 : -1}
          min={0}
          max={duration || 0}
          step={1}
          aria-orientation="horizontal"
          aria-valuetext={`${Math.floor(currentTime)}`}
          type="range"
          className="sr-only"
          value={currentTime}
          disabled={!playerReady}
          onChange={e => handleChange(parseFloat(e.target.value))}
        />
      </div>
      <div className="text-xs text-muted flex-shrink-0 min-w-40 tabular-nums">
        <FormattedPlayerDuration />
      </div>
    </div>
  );
}
