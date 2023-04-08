import {NameWithAvatar} from '@common/datatable/column-templates/name-with-avatar';
import React from 'react';
import {Track} from '@app/web-player/tracks/track';
import clsx from 'clsx';
import {useTrackTableMeta} from '@app/web-player/tracks/track-table/use-track-table-meta';
import {getTrackImageSrc} from '@app/web-player/tracks/track-image/track-image';
import {useIsTrackCued} from '@app/web-player/tracks/hooks/use-is-track-cued';
import {useIsMobileMediaQuery} from '@common/utils/hooks/is-mobile-media-query';

interface TrackNameColumnProps {
  track: Track;
}
export function TrackNameColumn({track}: TrackNameColumnProps) {
  const isMobile = useIsMobileMediaQuery();
  const {hideTrackImage, queueGroupId} = useTrackTableMeta();
  const isCued = useIsTrackCued(track.id, queueGroupId);

  return (
    <NameWithAvatar
      image={!hideTrackImage ? getTrackImageSrc(track) : undefined}
      label={track.name}
      avatarSize={isMobile ? 'lg' : 'md'}
      description={
        isMobile ? track.artists?.map(a => a.name).join(', ') : undefined
      }
      labelClassName={clsx(
        isCued && 'text-primary',
        isMobile && 'text-[15px] leading-6'
      )}
    />
  );
}
