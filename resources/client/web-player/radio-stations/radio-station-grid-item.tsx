import {RadioStation} from './use-radio-stations';
import {ModernRadioIcon} from '@app/web-player/icons/modern-icons';
import {usePlayerActions} from '@common/player/hooks/use-player-actions';
import {usePlayerStore} from '@common/player/hooks/use-player-store';
import {MediaItem} from '@common/player/media-item';
import {Pause, Play} from 'lucide-react';
import {toast} from '@common/ui/toast/toast';
import {message} from '@common/i18n/message';

function radioStationToMediaItem(station: RadioStation): MediaItem {
  return {
    id: `radio-station-${station.id}`,
    src: station.stream_url,
    provider: 'htmlAudio',
    poster: station.image || undefined,
    groupId: 'radio-stations',
    meta: {
      id: station.id,
      name: station.name,
      model_type: 'radioStation',
      image: station.image,
    },
  } as MediaItem;
}

interface RadioStationGridItemProps {
  station: RadioStation;
}
export function RadioStationGridItem({station}: RadioStationGridItemProps) {
  const player = usePlayerActions();
  const mediaId = `radio-station-${station.id}`;
  const isPlaying = usePlayerStore(
    s => s.isPlaying && s.cuedMedia?.id === mediaId
  );
  const isCued = usePlayerStore(s => s.cuedMedia?.id === mediaId);

  const handlePlay = async () => {
    try {
      if (isPlaying) {
        player.pause();
      } else if (isCued) {
        await player.play();
      } else {
        const mediaItem = radioStationToMediaItem(station);
        await player.overrideQueueAndPlay([mediaItem], 0);
      }
    } catch {
      toast.danger(
        message('Impossible de lire cette station. Vérifiez votre connexion.')
      );
    }
  };

  return (
    <div className="mood-glass-panel mood-glass-interactive playable-grid-card rounded-xl overflow-hidden group">
      <div className="relative w-full aspect-square bg-fg-base/4">
        {station.image ? (
          <img
            src={station.image}
            alt={station.name}
            className="w-full h-full object-cover"
          />
        ) : (
          <div className="w-full h-full flex items-center justify-center text-muted">
            <ModernRadioIcon size="xl" />
          </div>
        )}
        <button
          onClick={handlePlay}
          className="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
        >
          <div className="w-48 h-48 rounded-full bg-primary flex items-center justify-center shadow-lg">
            {isPlaying ? (
              <Pause className="w-24 h-24" fill="white" stroke="white" />
            ) : (
              <Play className="w-24 h-24 ml-2" fill="white" stroke="white" />
            )}
          </div>
        </button>
        {isPlaying && (
          <div className="absolute top-8 right-8 bg-primary text-white text-[10px] font-bold px-6 py-2 rounded-full animate-pulse">
            LIVE
          </div>
        )}
      </div>
      <div className="p-12">
        <div className="font-medium text-sm truncate">{station.name}</div>
        <div className="flex items-center gap-8 mt-4">
          {station.frequency && (
            <span className="text-xs font-semibold bg-primary/10 text-primary px-6 py-2 rounded-full">
              {station.frequency}
            </span>
          )}
          {station.genre && (
            <span className="text-xs text-muted truncate">
              {station.genre}
            </span>
          )}
        </div>
        {station.description && (
          <p className="text-xs text-muted mt-6 line-clamp-2">
            {station.description}
          </p>
        )}
      </div>
    </div>
  );
}
