import React, {Fragment} from 'react';
import {Track} from '@app/web-player/tracks/track';
import clsx from 'clsx';
import {useIsMobileMediaQuery} from '@common/utils/hooks/is-mobile-media-query';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {IconButton} from '@common/ui/buttons/icon-button';
import {MoreHorizIcon} from '@common/icons/material/MoreHoriz';
import {TrackContextDialog} from '@app/web-player/tracks/context-dialog/track-context-dialog';
import {LikeIconButton} from '@app/web-player/library/like-icon-button';
import {MoreVertIcon} from '@common/icons/material/MoreVert';

interface Props {
  track: Track;
  isHovered: boolean;
}
export function TrackOptionsColumn({track, isHovered}: Props) {
  const isMobile = useIsMobileMediaQuery();

  return (
    <Fragment>
      <DialogTrigger type="popover">
        <IconButton
          size={isMobile ? 'sm' : 'md'}
          className={clsx(
            isMobile ? 'ml-8 -mr-8 text-muted' : 'mr-8',
            !isMobile && !isHovered && 'invisible'
          )}
        >
          {isMobile ? <MoreVertIcon /> : <MoreHorizIcon />}
        </IconButton>
        <TrackContextDialog tracks={[track]} />
      </DialogTrigger>
      {!isMobile && <LikeIconButton size="xs" likeable={track} />}
    </Fragment>
  );
}
