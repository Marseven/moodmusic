import {useCuedTrack} from '@app/web-player/player-controls/use-cued-track';
import {TrackImage} from '@app/web-player/tracks/track-image/track-image';
import {usePlayerStore} from '@common/player/hooks/use-player-store';
import {usePlayerActions} from '@common/player/hooks/use-player-actions';
import {useTrans} from '@common/i18n/use-trans';
import {IconButton} from '@common/ui/buttons/icon-button';
import {message} from '@common/i18n/message';
import {SkipPreviousFilledIcon} from '@app/web-player/tracks/track-table/skip-previous-filled';
import {SkipNextFilledIcon} from '@app/web-player/tracks/track-table/skip-next-filled';
import {PauseIcon} from '@common/icons/material/Pause';
import {PlayArrowFilledIcon} from '@app/web-player/tracks/play-arrow-filled';
import {useBaseSeekbar} from '@app/web-player/player-controls/seekbar/use-base-seekbar';
import React, {useEffect} from 'react';
import {ProgressBar} from '@common/ui/progress/progress-bar';
import {CustomMenuItem} from '@common/menus/custom-menu';
import clsx from 'clsx';
import {useCustomMenu} from '@common/menus/use-custom-menu';
import {Trans} from '@common/i18n/trans';
import {NavbarAuthMenu} from '@common/ui/navigation/navbar/navbar-auth-menu';
import {PersonIcon} from '@common/icons/material/Person';
import {Badge} from '@common/ui/badge/badge';
import {useAuth} from '@common/auth/use-auth';
import {Menu, MenuTrigger} from '@common/ui/navigation/menu/menu-trigger';
import {Item} from '@common/ui/forms/listbox/item';
import {useNavigate} from '@common/utils/hooks/use-navigate';
import {useSettings} from '@common/core/settings/use-settings';
import {playerOverlayState} from '@app/web-player/state/player-overlay-store';

export function MobilePlayerControls() {
  return (
    <div className="fixed bottom-0 left-0 right-0 w-[calc(100%-20px)] mx-auto bg-background/95">
      <PlayerControls />
      <MobileNavbar />
    </div>
  );
}

function PlayerControls() {
  const mediaIsCued = usePlayerStore(s => s.cuedMedia != null);
  if (!mediaIsCued) return null;

  return (
    <div
      className="bg-chip rounded p-6 flex items-center gap-24 justify-between shadow relative"
      onClick={() => {
        playerOverlayState.toggle();
      }}
    >
      <QueuedTrack />
      <PlaybackButtons />
      <PlayerProgressBar />
    </div>
  );
}

function QueuedTrack() {
  const track = useCuedTrack();

  if (!track) {
    return null;
  }

  return (
    <div className="flex items-center gap-10 min-w-0 flex-auto">
      <TrackImage className="rounded w-36 h-36 object-cover" track={track} />
      <div className="flex-auto whitespace-nowrap overflow-hidden">
        <div className="text-sm font-medium overflow-hidden overflow-ellipsis">
          {track.name}
        </div>
        <div className="text-xs text-muted overflow-hidden overflow-ellipsis">
          {track.artists?.map(a => a.name).join(', ')}
        </div>
      </div>
    </div>
  );
}

function PlaybackButtons() {
  const isUninitialized = usePlayerStore(s => s.status === 'uninitialized');
  const player = usePlayerActions();
  const {trans} = useTrans();

  return (
    <div className="flex items-center justify-center">
      <IconButton
        disabled={isUninitialized}
        size="md"
        aria-label={trans(message('Previous'))}
        onClick={e => {
          e.stopPropagation();
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
        onClick={e => {
          e.stopPropagation();
          player.playNext();
        }}
      >
        <SkipNextFilledIcon />
      </IconButton>
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
    <IconButton
      aria-label={label}
      size="md"
      iconSize="lg"
      disabled={status === 'uninitialized'}
      onClick={e => {
        e.stopPropagation();
        if (status === 'playing') {
          player.pause();
        } else {
          player.play();
        }
      }}
    >
      {status === 'playing' ? <PauseIcon /> : <PlayArrowFilledIcon />}
    </IconButton>
  );
}

function PlayerProgressBar() {
  const duration = usePlayerStore(s => s.mediaDuration);
  const {time, player, listeners, stopTimer, setTime} = useBaseSeekbar();

  useEffect(() => {
    const unsubscribe = player.subscribe({
      ...listeners,
      // stop timer and set time to zero when cueing a new track
      onCued: () => {
        stopTimer();
        setTime(0);
      },
    });
    return () => {
      unsubscribe();
    };
  }, [player, listeners, stopTimer, setTime]);

  return (
    <ProgressBar
      size="xs"
      className="absolute left-0 right-0 bottom-0"
      progressColor="bg-white"
      trackColor="bg-white/10"
      trackHeight="h-2"
      radius="rounded-none"
      minValue={0}
      maxValue={duration}
      value={time}
    />
  );
}

function MobileNavbar() {
  const menu = useCustomMenu('mobile-bottom');
  if (!menu) return null;

  return (
    <div className="flex items-center justify-center gap-30 my-12">
      {menu.items.map(item => (
        <CustomMenuItem
          unstyled
          iconClassName="block mx-auto mb-6"
          iconSize="md"
          className={({isActive}) => clsx('text-xs', isActive && 'font-bold')}
          key={item.id}
          item={item}
        />
      ))}
      <AccountButton />
    </div>
  );
}

function AccountButton() {
  const {user} = useAuth();
  const hasUnreadNotif = !!user?.unread_notifications_count;
  const navigate = useNavigate();
  const {registration} = useSettings();

  const button = (
    <button className="text-xs">
      <Badge
        badgeClassName="mb-6"
        badgeLabel={user?.unread_notifications_count}
        badgeIsVisible={hasUnreadNotif}
      >
        <PersonIcon size="md" />
      </Badge>
      <div className="text-xs">
        <Trans message="Account" />
      </div>
    </button>
  );

  if (!user) {
    return (
      <MenuTrigger>
        {button}
        <Menu>
          <Item value="login" onSelected={() => navigate('/login')}>
            <Trans message="Login" />
          </Item>
          {!registration.disable && (
            <Item value="register" onSelected={() => navigate('/register')}>
              <Trans message="Register" />
            </Item>
          )}
        </Menu>
      </MenuTrigger>
    );
  }

  return <NavbarAuthMenu>{button}</NavbarAuthMenu>;
}
