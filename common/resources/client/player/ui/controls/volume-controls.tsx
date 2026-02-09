import React from 'react';
import {usePlayerActions} from '@common/player/hooks/use-player-actions';
import {usePlayerStore} from '@common/player/hooks/use-player-store';
import {IconButton} from '@common/ui/buttons/icon-button';
import {ButtonProps} from '@common/ui/buttons/button';
import {MediaMuteIcon} from '@common/icons/media/media-mute';
import {MediaVolumeLowIcon} from '@common/icons/media/media-volume-low';
import {MediaVolumeHighIcon} from '@common/icons/media/media-volume-high';
import {Tooltip} from '@common/ui/tooltip/tooltip';
import {Trans} from '@common/i18n/trans';

interface Props {
  trackColor?: string;
  fillColor?: string;
  buttonColor?: ButtonProps['color'];
}
export function VolumeControls({trackColor, fillColor, buttonColor}: Props) {
  const volume = usePlayerStore(s => s.volume);
  const player = usePlayerActions();
  const playerReady = usePlayerStore(s => s.providerReady);

  const volumePercentage = volume;

  return (
    <div className="flex w-min items-center gap-4 volume-controls">
      <ToggleMuteButton color={buttonColor} />
      <VolumeSlider volume={volume} onVolumeChange={(vol) => player.setVolume(vol)} playerReady={playerReady} />
    </div>
  );
}

interface VolumeSliderProps {
  volume: number;
  onVolumeChange: (volume: number) => void;
  playerReady: boolean;
}

function VolumeSlider({volume, onVolumeChange, playerReady}: VolumeSliderProps) {
  const [isDragging, setIsDragging] = React.useState(false);
  const [isHovering, setIsHovering] = React.useState(false);
  const trackRef = React.useRef<HTMLDivElement>(null);

  const volumePercentage = volume;

  const calculateValueFromPosition = React.useCallback((clientX: number) => {
    if (!trackRef.current) return 0;
    const rect = trackRef.current.getBoundingClientRect();
    const percentage = Math.max(0, Math.min(100, ((clientX - rect.left) / rect.width) * 100));
    return percentage;
  }, []);

  const onPointerDown = React.useCallback((e: React.PointerEvent) => {
    if (!playerReady) return;
    setIsDragging(true);
    
    const value = calculateValueFromPosition(e.clientX);
    onVolumeChange(value);
    
    (e.target as Element).setPointerCapture(e.pointerId);
  }, [playerReady, onVolumeChange, calculateValueFromPosition]);

  const onPointerMove = React.useCallback((e: React.PointerEvent) => {
    if (isDragging) {
      const value = calculateValueFromPosition(e.clientX);
      onVolumeChange(value);
    }
  }, [isDragging, onVolumeChange, calculateValueFromPosition]);

  const onPointerUp = React.useCallback((e: React.PointerEvent) => {
    setIsDragging(false);
    (e.target as Element).releasePointerCapture(e.pointerId);
  }, []);

  const thumbVisible = isDragging || isHovering;

  return (
    <div className="touch-none flex-auto w-96" role="group">
      <div 
        ref={trackRef}
        className="h-30 relative" 
        role="presentation"
        onPointerDown={onPointerDown}
        onPointerMove={onPointerMove}
        onPointerUp={onPointerUp}
        onMouseEnter={() => setIsHovering(true)}
        onMouseLeave={() => setIsHovering(false)}
      >
        <div className="absolute inset-0 m-auto h-4 rounded bg-divider"></div>
        <div 
          className="absolute inset-0 my-auto h-4 rounded bg-primary" 
          style={{width: `${volumePercentage}%`}}
        ></div>
        <div 
          role="presentation" 
          className={`outline-none rounded-full top-1/2 -translate-y-1/2 -translate-x-1/2 absolute inset-0 w-14 h-14 shadow-md bg-primary ${thumbVisible ? 'visible' : 'invisible'}`}
          style={{left: `${volumePercentage}%`}}
        >
          <input 
            tabIndex={playerReady ? 0 : -1}
            min={0}
            max={100}
            step={1}
            aria-orientation="horizontal"
            aria-valuetext={`${Math.floor(volume)}`}
            type="range"
            className="sr-only"
            value={volume}
            disabled={!playerReady}
            onChange={(e) => {
              onVolumeChange(parseFloat(e.target.value));
            }}
          />
        </div>
      </div>
    </div>
  );
}

interface ToggleMuteButtonProps {
  color?: ButtonProps['color'];
}
function ToggleMuteButton({color}: ToggleMuteButtonProps) {
  const isMuted = usePlayerStore(s => s.muted);
  const volume = usePlayerStore(s => s.volume);
  const player = usePlayerActions();
  const playerReady = usePlayerStore(s => s.providerReady);

  if (isMuted) {
    return (
      <Tooltip label={<Trans message="Unmute" />}>
        <IconButton
          disabled={!playerReady}
          color={color}
          size="sm"
          iconSize="md"
          onClick={() => player.setMuted(false)}
        >
          <MediaMuteIcon />
        </IconButton>
      </Tooltip>
    );
  }
  return (
    <Tooltip label={<Trans message="Mute" />}>
      <IconButton
        disabled={!playerReady}
        color={color}
        size="sm"
        iconSize="md"
        onClick={() => player.setMuted(true)}
      >
        {volume < 40 ? <MediaVolumeLowIcon /> : <MediaVolumeHighIcon />}
      </IconButton>
    </Tooltip>
  );
}
