import React, {Fragment} from 'react';
import {ColumnConfig} from '@common/datatable/column-config';
import {DataTablePage} from '@common/datatable/page/data-table-page';
import {DataTableEmptyStateMessage} from '@common/datatable/page/data-table-emty-state-message';
import {DeleteSelectedItemsAction} from '@common/datatable/page/delete-selected-items-action';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {DataTableAddItemButton} from '@common/datatable/data-table-add-item-button';
import {Trans} from '@common/i18n/trans';
import {IconButton} from '@common/ui/buttons/icon-button';
import {Chip} from '@common/ui/forms/input-field/chip-field/chip';
import {Pencil, Radio, Antenna} from 'lucide-react';
import {
  RadioStation,
  CrupdateRadioStationDialog,
} from './crupdate-radio-station-dialog';
import musicSvg from './../tracks-datatable-page/music.svg';

const columnConfig: ColumnConfig<RadioStation>[] = [
  {
    key: 'name',
    allowsSorting: true,
    visibleInMode: 'all',
    width: 'flex-3 min-w-200',
    header: () => <Trans message="Nom" />,
    body: station => (
      <div className="flex items-center gap-10">
        {station.image ? (
          <img
            src={station.image}
            alt={station.name}
            className="h-36 w-36 rounded object-cover"
          />
        ) : (
          <div className="flex h-36 w-36 items-center justify-center rounded bg-chip">
            <Radio size={18} className="text-muted" />
          </div>
        )}
        <span>{station.name}</span>
      </div>
    ),
  },
  {
    key: 'stream_url',
    header: () => <Trans message="URL du flux" />,
    width: 'flex-2 min-w-200',
    body: station => (
      <span className="text-xs text-muted truncate">{station.stream_url}</span>
    ),
  },
  {
    key: 'frequency',
    allowsSorting: true,
    header: () => <Trans message="Fréquence" />,
    width: 'w-100',
    body: station => station.frequency || '—',
  },
  {
    key: 'genre',
    allowsSorting: true,
    header: () => <Trans message="Genre" />,
    width: 'w-100',
    body: station => station.genre || '—',
  },
  {
    key: 'sort_order',
    allowsSorting: true,
    header: () => <Trans message="Ordre" />,
    width: 'w-80',
    body: station => station.sort_order,
  },
  {
    key: 'is_active',
    allowsSorting: true,
    header: () => <Trans message="Statut" />,
    width: 'w-80',
    body: station => (
      <Chip size="xs" color={station.is_active ? 'positive' : 'chip'}>
        {station.is_active ? 'Active' : 'Inactive'}
      </Chip>
    ),
  },
  {
    key: 'actions',
    header: () => <Trans message="Actions" />,
    hideHeader: true,
    align: 'end',
    visibleInMode: 'all',
    width: 'w-42 flex-shrink-0',
    body: station => (
      <DialogTrigger type="modal">
        <IconButton size="md" className="text-muted">
          <Pencil size={20} />
        </IconButton>
        <CrupdateRadioStationDialog station={station} />
      </DialogTrigger>
    ),
  },
];

export function RadioStationsDatatablePage() {
  return (
    <DataTablePage
      endpoint="admin/radio-stations"
      title={
        <span className="flex items-center gap-8">
          <Antenna size={22} />
          <Trans message="Stations Radio" />
        </span>
      }
      columns={columnConfig}
      actions={<Actions />}
      selectedActions={<DeleteSelectedItemsAction />}
      enableSelection
      emptyStateMessage={
        <DataTableEmptyStateMessage
          image={musicSvg}
          title={
            <Trans message="Aucune station radio n'a été créée" />
          }
          filteringTitle={
            <Trans message="Aucune station correspondante" />
          }
        />
      }
    />
  );
}

function Actions() {
  return (
    <Fragment>
      <DialogTrigger type="modal">
        <DataTableAddItemButton>
          <Trans message="Ajouter une station" />
        </DataTableAddItemButton>
        <CrupdateRadioStationDialog />
      </DialogTrigger>
    </Fragment>
  );
}
