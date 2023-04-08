import {usePlayerStore} from '@common/player/hooks/use-player-store';
import {IconButton} from '@common/ui/buttons/icon-button';
import {PlayArrowFilledIcon} from '@app/web-player/tracks/play-arrow-filled';
import {usePlayerActions} from '@common/player/hooks/use-player-actions';
import {PauseIcon} from '@common/icons/material/Pause';
import {SkipPreviousFilledIcon} from '@app/web-player/tracks/track-table/skip-previous-filled';
import {SkipNextFilledIcon} from '@app/web-player/tracks/track-table/skip-next-filled';
import {RepeatOneIcon} from '@common/icons/material/RepeatOne';
import {RepeatIcon} from '@common/icons/material/Repeat';
import {ShuffleIcon} from '@common/icons/material/Shuffle';
import {useTrans} from '@common/i18n/use-trans';
import {message} from '@common/i18n/message';
import {MainSeekbar} from '@app/web-player/player-controls/seekbar/main-seekbar';
import {useIsMobileMediaQuery} from '@common/utils/hooks/is-mobile-media-query';
import clsx from 'clsx';

interface Props {
  className?: string;
}
export function PlaybackControls({className}: Props) {
  return (
    <div className={className}>
      <PlaybackButtons />
      <MainSeekbar />
    </div>
  );
}

function PlaybackButtons() {
  const isMobile = useIsMobileMediaQuery();
  const isUninitialized = usePlayerStore(s => s.status === 'uninitialized');
  const player = usePlayerActions();
  const {trans} = useTrans();

  // need to add a gap on mobile between buttons and seekbar, otherwise seekbar will be impossible to tap
  return (
    <div
      className={clsx(
        'flex items-center justify-center gap-6',
        isMobile && 'mb-20'
      )}
    >
      <ShuffleButton />
      <IconButton
        disabled={isUninitialized}
        size="md"
        aria-label={trans(message('Previous'))}
        onClick={() => {
          player.playPrevious();
        }}
      >
        <SkipPreviousFilledIcon />
      </IconButton>
      <PlayButton />
      <IconButton
        disabled={isUninitialized}
        aria-label={trans(message('Next'))}
        size="md"
        onClick={() => {
          player.playNext();
        }}
      >
        <SkipNextFilledIcon />
      </IconButton>
      <RepeatButton />
    </div>
  );
}

function PlayButton() {
  const status = usePlayerStore(s => s.status);
  const player = usePlayerActions();

  const {trans} = useTrans();
  const label =
    status === 'playing' ? trans(message('Pause')) : trans(message('Play'));

  return (
    <div className="relative w-42 h-42 isolate">
      <IconButton
        aria-label={label}
        size="md"
        iconSize="xl"
        disabled={status === 'uninitialized'}
        className="relative z-20"
        onClick={() => {
          if (status === 'playing') {
            player.pause();
          } else {
            player.play();
          }
        }}
      >
        {status === 'playing' ? <PauseIcon /> : <PlayArrowFilledIcon />}
      </IconButton>
    </div>
  );
}

function ShuffleButton() {
  const isShuffling = usePlayerStore(s => s.shuffling);
  const player = usePlayerActions();

  const {trans} = useTrans();
  const label = isShuffling
    ? trans(message('Disable shuffle'))
    : trans(message('Enable shuffle'));

  return (
    <IconButton
      aria-label={label}
      iconSize="sm"
      color={isShuffling ? 'primary' : undefined}
      onClick={() => {
        player.toggleShuffling();
      }}
    >
      <ShuffleIcon />
    </IconButton>
  );
}

function RepeatButton() {
  const repeating = usePlayerStore(s => s.repeat);
  const player = usePlayerActions();

  const {trans} = useTrans();
  let label: string;
  if (repeating === 'all') {
    label = trans(message('Enable repeat one'));
  } else if (repeating === 'one') {
    label = trans(message('Disable repeat'));
  } else {
    label = trans(message('Enable repeat'));
  }

  return (
    <IconButton
      aria-label={label}
      iconSize="sm"
      color={repeating ? 'primary' : undefined}
      onClick={() => {
        player.toggleRepeatMode();
      }}
    >
      {repeating === 'one' ? <RepeatOneIcon /> : <RepeatIcon />}
    </IconButton>
  );
}
