import {ColumnConfig} from '@common/datatable/column-config';
import {DataTableEmptyStateMessage} from '@common/datatable/page/data-table-emty-state-message';
import {DataTablePage} from '@common/datatable/page/data-table-page';
import {DeleteSelectedItemsAction} from '@common/datatable/page/delete-selected-items-action';
import React, {Fragment} from 'react';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {FormattedDate} from '@common/i18n/formatted-date';
import {IconButton} from '@common/ui/buttons/icon-button';
import {DataTableAddItemButton} from '@common/datatable/data-table-add-item-button';
import {Trans} from '@common/i18n/trans';
import {NameWithAvatar} from '@common/datatable/column-templates/name-with-avatar';
import {EditIcon} from '@common/icons/material/Edit';
import {Playlist} from '@app/web-player/playlists/playlist';
import {PlaylistLink} from '@app/web-player/playlists/playlist-link';
import {UserProfileLink} from '@app/web-player/users/user-profile-link';
import playlistImage from './../channels-datatable-page/playlist.svg';
import {CheckIcon} from '@common/icons/material/Check';
import {CloseIcon} from '@common/icons/material/Close';
import {FormattedNumber} from '@common/i18n/formatted-number';
import {UpdatePlaylistDialog} from '@app/web-player/playlists/crupdate-dialog/update-playlist-dialog';
import {queryClient} from '@common/http/query-client';
import {DatatableDataQueryKey} from '@common/datatable/requests/paginated-resources';
import {CreatePlaylistDialog} from '@app/web-player/playlists/crupdate-dialog/create-playlist-dialog';
import {PlaylistDatatablePageFilters} from '@app/admin/playlist-datatable-page/playlist-datatable-page-filters';
import {useSettings} from '@common/core/settings/use-settings';
import {ImportPlaylistDialog} from '@app/admin/playlist-datatable-page/import-playlist-dialog';
import {Tooltip} from '@common/ui/tooltip/tooltip';
import {PublishIcon} from '@common/icons/material/Publish';

const columnConfig: ColumnConfig<Playlist>[] = [
  {
    key: 'name',
    allowsSorting: true,
    header: () => <Trans message="Playlist" />,
    body: playlist => (
      <NameWithAvatar
        image={playlist.image}
        label={<PlaylistLink playlist={playlist} />}
      />
    ),
    width: 'col-w-2',
  },
  {
    key: 'owner',
    header: () => <Trans message="Owner" />,
    body: playlist =>
      playlist.owner ? (
        <NameWithAvatar
          image={playlist.owner?.avatar}
          label={<UserProfileLink user={playlist.owner} />}
          description={playlist.owner.email}
        />
      ) : null,
    width: 'col-w-2',
  },
  {
    key: 'public',
    allowsSorting: true,
    header: () => <Trans message="Public" />,
    body: entry =>
      entry.public ? (
        <CheckIcon className="icon-md text-positive" />
      ) : (
        <CloseIcon className="icon-md text-danger" />
      ),
    width: 'w-1',
  },
  {
    key: 'collaborative',
    allowsSorting: true,
    header: () => <Trans message="Collaborative" />,
    body: entry =>
      entry.collaborative ? (
        <CheckIcon className="icon-md text-positive" />
      ) : (
        <CloseIcon className="icon-md text-danger" />
      ),
    width: 'w-1',
  },
  {
    key: 'views',
    allowsSorting: true,
    header: () => <Trans message="Views" />,
    body: playlist => <FormattedNumber value={playlist.views} />,
    width: 'w-1',
  },
  {
    key: 'updated_at',
    allowsSorting: true,
    header: () => <Trans message="Last updated" />,
    body: playlist => <FormattedDate date={playlist.updated_at} />,
  },
  {
    key: 'actions',
    header: () => <Trans message="Actions" />,
    hideHeader: true,
    align: 'end',
    body: playlist => {
      return (
        <DialogTrigger
          type="modal"
          onClose={updatedPlaylist => {
            if (updatedPlaylist) {
              invalidateQuery();
            }
          }}
        >
          <IconButton size="md" className="text-muted">
            <EditIcon />
          </IconButton>
          <UpdatePlaylistDialog playlist={playlist} />
        </DialogTrigger>
      );
    },
  },
];

export function PlaylistDatatablePage() {
  return (
    <DataTablePage
      endpoint="playlists"
      title={<Trans message="Playlists" />}
      columns={columnConfig}
      filters={PlaylistDatatablePageFilters}
      queryParams={{
        with: 'owner',
      }}
      actions={<Actions />}
      selectedActions={<DeleteSelectedItemsAction />}
      emptyStateMessage={
        <DataTableEmptyStateMessage
          image={playlistImage}
          title={<Trans message="No playlists have been created yet" />}
          filteringTitle={<Trans message="No matching playlists" />}
        />
      }
    />
  );
}

function Actions() {
  const {spotify_is_setup} = useSettings();
  return (
    <Fragment>
      {spotify_is_setup && (
        <DialogTrigger
          type="modal"
          onClose={newPlaylist => {
            if (newPlaylist) {
              invalidateQuery();
            }
          }}
        >
          <Tooltip label={<Trans message="Import by spotify ID" />}>
            <IconButton
              variant="outline"
              color="primary"
              radius="rounded"
              className="flex-shrink-0"
              size="sm"
            >
              <PublishIcon />
            </IconButton>
          </Tooltip>
          <ImportPlaylistDialog />
        </DialogTrigger>
      )}
      <DialogTrigger
        type="modal"
        onClose={newPlaylist => {
          if (newPlaylist) {
            invalidateQuery();
          }
        }}
      >
        <DataTableAddItemButton>
          <Trans message="Add new playlist" />
        </DataTableAddItemButton>
        <CreatePlaylistDialog />
      </DialogTrigger>
    </Fragment>
  );
}

function invalidateQuery() {
  queryClient.invalidateQueries(DatatableDataQueryKey('playlists'));
}
