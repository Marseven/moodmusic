import {Track} from '@app/web-player/tracks/track';
import {Table, TableProps} from '@common/ui/tables/table';
import {ColumnConfig} from '@common/datatable/column-config';
import {Trans} from '@common/i18n/trans';
import React, {ReactElement, useMemo} from 'react';
import {AlbumLink} from '@app/web-player/albums/album-link';
import {ScheduleIcon} from '@common/icons/material/Schedule';
import {FormattedDuration} from '@common/i18n/formatted-duration';
import {ArtistLinks} from '@app/web-player/artists/artist-links';
import {TogglePlaybackColumn} from '@app/web-player/tracks/track-table/toggle-playback-column';
import {TrackNameColumn} from '@app/web-player/tracks/track-table/track-name-column';
import {TrackTableMeta} from '@app/web-player/tracks/track-table/use-track-table-meta';
import {Skeleton} from '@common/ui/skeleton/skeleton';
import {NameWithAvatarPlaceholder} from '@common/datatable/column-templates/name-with-avatar';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {RowElementProps} from '@common/ui/tables/table-row';
import {TableTrackContextDialog} from '@app/web-player/tracks/context-dialog/table-track-context-dialog';
import {TrendingUpIcon} from '@common/icons/material/TrendingUp';
import {FormattedRelativeTime} from '@common/i18n/formatted-relative-time';
import {trackToMediaItem} from '@app/web-player/tracks/utils/track-to-media-item';
import {usePlayerActions} from '@common/player/hooks/use-player-actions';
import {useIsMobileMediaQuery} from '@common/utils/hooks/is-mobile-media-query';
import {TrackOptionsColumn} from '@app/web-player/tracks/track-table/track-options-column';
import {TableDataItem} from '@common/ui/tables/types/table-data-item';
import {TrackContextDialogProps} from '@app/web-player/tracks/context-dialog/track-context-dialog';

const columnConfig: ColumnConfig<Track>[] = [
  {
    key: 'index',
    header: () => <span>#</span>,
    width: 'w-1',
    padding: 'pl-16 pr-22',
    align: 'center',
    body: (track, row) => {
      if (row.isPlaceholder) {
        return <Skeleton size="w-20 h-20" variant="rect" />;
      }
      return (
        <TogglePlaybackColumn
          track={track}
          rowIndex={row.index}
          isHovered={row.isHovered}
        />
      );
    },
  },
  {
    key: 'name',
    allowsSorting: true,
    padding: 'pr-16',
    width: 'max-sm:w-5/6 max-sm:max-w-1',
    header: () => <Trans message="Title" />,
    body: (track, row) => {
      if (row.isPlaceholder) {
        return <NameWithAvatarPlaceholder showDescription={false} />;
      }
      return <TrackNameColumn track={track} />;
    },
  },
  {
    key: 'artist',
    header: () => <Trans message="Artist" />,
    body: (track, row) => {
      if (row.isPlaceholder) {
        return <Skeleton className="leading-3 max-w-4/5" />;
      }
      return <ArtistLinks artists={track.artists} />;
    },
  },
  {
    key: 'album_name',
    allowsSorting: true,
    header: () => <Trans message="Album" />,
    body: (track, row) => {
      if (row.isPlaceholder) {
        return <Skeleton className="leading-3 max-w-4/5" />;
      }
      return track.album ? <AlbumLink album={track.album} /> : null;
    },
  },
  {
    key: 'added_at',
    sortingKey: 'likes.created_at',
    allowsSorting: true,
    width: 'w-1',
    header: () => <Trans message="Date added" />,
    body: (track, row) => {
      if (row.isPlaceholder) {
        return <Skeleton className="leading-3 max-w-4/5" />;
      }
      return <FormattedRelativeTime date={track.added_at} />;
    },
  },
  {
    key: 'options',
    width: 'w-1',
    padding: 'px-4',
    align: 'end',
    header: () => <Trans message="Options" />,
    hideHeader: true,
    body: (track, row) => {
      if (row.isPlaceholder) {
        return (
          <div className="flex justify-end">
            <Skeleton size="w-20 h-20" variant="rect" />
          </div>
        );
      }
      return <TrackOptionsColumn track={track} isHovered={row.isHovered} />;
    },
  },
  {
    key: 'duration',
    allowsSorting: true,
    width: 'w-1',
    className: 'text-muted',
    header: () => <ScheduleIcon />,
    body: (track, row) => {
      if (row.isPlaceholder) {
        return <Skeleton className="leading-3" />;
      }
      return track.duration ? <FormattedDuration ms={track.duration} /> : null;
    },
  },
  {
    key: 'popularity',
    allowsSorting: true,
    width: 'w-90',
    className: 'text-muted',
    header: () => <TrendingUpIcon />,
    body: (track, row) => {
      if (row.isPlaceholder) {
        return <Skeleton className="leading-3" />;
      }
      return (
        <div className="h-6 w-full relative bg-chip">
          <div
            style={{width: `${track.popularity || 50}%`}}
            className="h-full w-0 absolute top-0 left-0 bg-black/30"
          />
        </div>
      );
    },
  },
];

export interface TrackTableProps {
  tracks: Track[] | TableDataItem[]; // might be passing in placeholder items for skeletons
  hideArtist?: boolean;
  hideAlbum?: boolean;
  hideTrackImage?: boolean;
  hidePopularity?: boolean;
  hideAddedAtColumn?: boolean;
  hideHeaderRow?: boolean;
  queueGroupId?: string;
  contextDialog?: ReactElement<TrackContextDialogProps>;
  renderRowAs?: TableProps<Track>['renderRowAs'];
  sortDescriptor?: TableProps<Track>['sortDescriptor'];
  onSortChange?: TableProps<Track>['onSortChange'];
  enableSorting?: TableProps<Track>['enableSorting'];
  tableBody?: TableProps<Track>['tableBody'];
  className?: string;
}
export function TrackTable({
  tracks,
  hideArtist = false,
  hideAlbum = false,
  hideHeaderRow = false,
  hideTrackImage = false,
  hidePopularity = true,
  hideAddedAtColumn = true,
  queueGroupId,
  contextDialog,
  renderRowAs,
  ...tableProps
}: TrackTableProps) {
  const player = usePlayerActions();
  const isMobile = useIsMobileMediaQuery();
  hideHeaderRow = hideHeaderRow || !!isMobile;

  const filteredColumns = useMemo(() => {
    // on mobile show only name and options
    if (isMobile) {
      return columnConfig.filter(col => {
        return col.key === 'name' || col.key === 'options';
      });
    }
    // on desktop show all props, except for explicitly hidden ones
    return columnConfig.filter(col => {
      if (col.key === 'artist' && hideArtist) {
        return false;
      }
      if (col.key === 'album_name' && hideAlbum) {
        return false;
      }
      if (col.key === 'popularity' && hidePopularity) {
        return false;
      }
      if (col.key === 'added_at' && hideAddedAtColumn) {
        return false;
      }
      return true;
    });
  }, [isMobile, hideArtist, hideAlbum, hidePopularity, hideAddedAtColumn]);

  const meta: TrackTableMeta = useMemo(() => {
    return {queueGroupId: queueGroupId, hideTrackImage};
  }, [queueGroupId, hideTrackImage]);

  return (
    <Table
      closeOnInteractOutside
      hideHeaderRow={hideHeaderRow}
      selectionStyle="highlight"
      enableSelection={!isMobile}
      selectRowOnContextMenu
      renderRowAs={renderRowAs || TrackTableRowWithContextMenu}
      columns={filteredColumns}
      data={tracks as Track[]}
      meta={meta}
      hideBorder={!!isMobile}
      onAction={(track, index) => {
        const newQueue = tracks.map(d =>
          trackToMediaItem(d as Track, queueGroupId)
        );
        player.overrideQueueAndPlay(newQueue, index);
      }}
      {...tableProps}
    />
  );
}

function TrackTableRowWithContextMenu({
  item,
  children,
  ...domProps
}: RowElementProps<Track>) {
  const row = <tr {...domProps}>{children}</tr>;
  if (item.isPlaceholder) {
    return row;
  }
  return (
    <DialogTrigger type="popover" triggerOnContextMenu placement="bottom-start">
      {row}
      <TableTrackContextDialog />
    </DialogTrigger>
  );
}
