import React, {Fragment} from 'react';
import {ColumnConfig} from '@common/datatable/column-config';
import {DataTablePage} from '@common/datatable/page/data-table-page';
import {DataTableEmptyStateMessage} from '@common/datatable/page/data-table-emty-state-message';
import {DeleteSelectedItemsAction} from '@common/datatable/page/delete-selected-items-action';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {DataTableAddItemButton} from '@common/datatable/data-table-add-item-button';
import {Trans} from '@common/i18n/trans';
import {FormattedDate} from '@common/i18n/formatted-date';
import {IconButton} from '@common/ui/buttons/icon-button';
import {Pencil} from 'lucide-react';
import {Chip} from '@common/ui/forms/input-field/chip-field/chip';
import {
  AdSpot,
  CrupdateAdSpotDialog,
} from './crupdate-ad-spot-dialog';
import advertisingSvg from './../tracks-datatable-page/music.svg';

const columnConfig: ColumnConfig<AdSpot>[] = [
  {
    key: 'name',
    allowsSorting: true,
    visibleInMode: 'all',
    width: 'flex-3 min-w-200',
    header: () => <Trans message="Name" />,
    body: adSpot => adSpot.name,
  },
  {
    key: 'duration',
    allowsSorting: true,
    header: () => <Trans message="Duration" />,
    width: 'w-80',
    body: adSpot => `${adSpot.duration}s`,
  },
  {
    key: 'priority',
    allowsSorting: true,
    header: () => <Trans message="Priority" />,
    width: 'w-80',
    body: adSpot => adSpot.priority,
  },
  {
    key: 'impressions',
    allowsSorting: true,
    header: () => <Trans message="Impressions" />,
    width: 'w-100',
    body: adSpot => adSpot.impressions.toLocaleString(),
  },
  {
    key: 'clicks',
    allowsSorting: true,
    header: () => <Trans message="Clicks" />,
    width: 'w-80',
    body: adSpot => adSpot.clicks.toLocaleString(),
  },
  {
    key: 'active',
    allowsSorting: true,
    header: () => <Trans message="Status" />,
    width: 'w-80',
    body: adSpot => (
      <Chip size="xs" color={adSpot.active ? 'positive' : 'chip'}>
        {adSpot.active ? 'Active' : 'Inactive'}
      </Chip>
    ),
  },
  {
    key: 'start_date',
    allowsSorting: true,
    header: () => <Trans message="Start" />,
    width: 'w-100',
    body: adSpot =>
      adSpot.start_date ? <FormattedDate date={adSpot.start_date} /> : '—',
  },
  {
    key: 'end_date',
    allowsSorting: true,
    header: () => <Trans message="End" />,
    width: 'w-100',
    body: adSpot =>
      adSpot.end_date ? <FormattedDate date={adSpot.end_date} /> : '—',
  },
  {
    key: 'actions',
    header: () => <Trans message="Actions" />,
    hideHeader: true,
    align: 'end',
    visibleInMode: 'all',
    width: 'w-42 flex-shrink-0',
    body: adSpot => (
      <DialogTrigger type="modal">
        <IconButton size="md" className="text-muted">
          <Pencil />
        </IconButton>
        <CrupdateAdSpotDialog adSpot={adSpot} />
      </DialogTrigger>
    ),
  },
];

export function AdSpotsDatatablePage() {
  return (
    <DataTablePage
      endpoint="ad-spots"
      title={<Trans message="Ad Spots" />}
      columns={columnConfig}
      actions={<Actions />}
      selectedActions={<DeleteSelectedItemsAction />}
      enableSelection
      emptyStateMessage={
        <DataTableEmptyStateMessage
          image={advertisingSvg}
          title={<Trans message="No ad spots have been created yet" />}
          filteringTitle={<Trans message="No matching ad spots" />}
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
          <Trans message="Add new ad spot" />
        </DataTableAddItemButton>
        <CrupdateAdSpotDialog />
      </DialogTrigger>
    </Fragment>
  );
}
