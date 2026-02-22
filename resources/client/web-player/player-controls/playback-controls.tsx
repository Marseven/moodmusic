import {MainSeekbar} from '@app/web-player/player-controls/seekbar/main-seekbar';
import {useIsMobileMediaQuery} from '@common/utils/hooks/is-mobile-media-query';
import clsx from 'clsx';
import {PlayButton} from '@common/player/ui/controls/play-button';
import {NextButton} from '@common/player/ui/controls/next-button';
import {PreviousButton} from '@common/player/ui/controls/previous-button';
import {ShuffleButton} from '@common/player/ui/controls/shuffle-button';
import {RepeatButton} from '@common/player/ui/controls/repeat-button';
import {BufferingIndicator} from '@app/web-player/player-controls/buffering-indicator';
import {usePlayerStore} from '@common/player/hooks/use-player-store';
import {Trans} from '@common/i18n/trans';

interface Props {
  className?: string;
}
export function PlaybackControls({className}: Props) {
  const isRadio = usePlayerStore(
    s => s.cuedMedia?.meta?.model_type === 'radioStation'
  );

  return (
    <div className={className}>
      <PlaybackButtons isRadio={isRadio} />
      {isRadio ? <LiveIndicator /> : <MainSeekbar />}
    </div>
  );
}

function LiveIndicator() {
  return (
    <div className="flex items-center justify-center gap-8 h-20">
      <span className="inline-flex items-center bg-primary text-white text-[10px] font-bold px-8 py-2 rounded-full animate-pulse">
        LIVE
      </span>
      <span className="text-xs text-muted">
        <Trans message="Radio en direct" />
      </span>
    </div>
  );
}

function PlaybackButtons({isRadio}: {isRadio?: boolean}) {
  const isMobile = useIsMobileMediaQuery();

  return (
    <div
      className={clsx(
        'flex items-center justify-center gap-6',
        isMobile && 'mb-20'
      )}
    >
      {!isRadio && <ShuffleButton size="sm" iconSize="sm" />}
      {!isRadio && <PreviousButton size="sm" iconSize="sm" />}
      <div className="relative">
        <BufferingIndicator />
        <PlayButton size="md" iconSize="lg" />
      </div>
      {!isRadio && <NextButton size="sm" iconSize="sm" />}
      {!isRadio && <RepeatButton size="sm" iconSize="sm" />}
    </div>
  );
}
