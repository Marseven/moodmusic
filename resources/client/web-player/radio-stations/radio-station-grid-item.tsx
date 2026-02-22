import {RadioStation} from './use-radio-stations';
import {ModernRadioIcon} from '@app/web-player/icons/modern-icons';
import {Trans} from '@common/i18n/trans';

interface RadioStationGridItemProps {
  station: RadioStation;
}
export function RadioStationGridItem({station}: RadioStationGridItemProps) {
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
          onClick={() => window.open(station.stream_url, '_blank')}
          className="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
        >
          <div className="w-48 h-48 rounded-full bg-primary flex items-center justify-center shadow-lg">
            <svg
              viewBox="0 0 24 24"
              fill="white"
              className="w-24 h-24 ml-2"
            >
              <path d="M8 5v14l11-7z" />
            </svg>
          </div>
        </button>
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
