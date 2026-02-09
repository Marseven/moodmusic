import React from 'react';
import {ColumnConfig} from '@common/datatable/column-config';
import {DataTablePage} from '@common/datatable/page/data-table-page';
import {DataTableEmptyStateMessage} from '@common/datatable/page/data-table-emty-state-message';
import {Trans} from '@common/i18n/trans';
import {FormattedDate} from '@common/i18n/formatted-date';
import {NameWithAvatar} from '@common/datatable/column-templates/name-with-avatar';
import {Chip} from '@common/ui/forms/input-field/chip-field/chip';
import {Purchase} from '@app/web-player/purchases/purchase';
import {formatPrice} from '@app/web-player/purchases/buy-button';
import {PurchasesDatatablePageFilters} from './purchases-datatable-page-filters';
import {User} from '@common/auth/user';

interface PurchaseWithRelations extends Purchase {
  user?: User;
}

const statusColor: Record<
  string,
  'positive' | 'primary' | 'danger' | 'chip'
> = {
  completed: 'positive',
  pending: 'primary',
  failed: 'danger',
  refunded: 'chip',
};

const columnConfig: ColumnConfig<PurchaseWithRelations>[] = [
  {
    key: 'user_id',
    allowsSorting: true,
    header: () => <Trans message="Client" />,
    width: 'flex-3 min-w-200',
    visibleInMode: 'all',
    body: purchase =>
      purchase.user ? (
        <NameWithAvatar
          image={purchase.user.avatar}
          label={purchase.user.display_name}
          description={purchase.user.email}
        />
      ) : null,
  },
  {
    key: 'purchasable_id',
    header: () => <Trans message="Article" />,
    width: 'flex-2',
    body: purchase => {
      const type = purchase.purchasable_type?.split('\\').pop();
      return (
        <div className="flex items-center gap-8">
          <span className="overflow-hidden overflow-ellipsis whitespace-nowrap">
            {purchase.purchasable
              ? (purchase.purchasable as any).name
              : `#${purchase.purchasable_id}`}
          </span>
          {type && (
            <Chip size="xs" className="flex-shrink-0 capitalize">
              {type}
            </Chip>
          )}
        </div>
      );
    },
  },
  {
    key: 'amount',
    allowsSorting: true,
    header: () => <Trans message="Amount" />,
    width: 'w-120',
    body: purchase => formatPrice(purchase.amount, purchase.currency),
  },
  {
    key: 'gateway_name',
    allowsSorting: true,
    header: () => <Trans message="Gateway" />,
    width: 'w-100',
    body: purchase => (
      <span className="capitalize">{purchase.gateway_name}</span>
    ),
  },
  {
    key: 'status',
    allowsSorting: true,
    header: () => <Trans message="Status" />,
    width: 'w-100',
    body: purchase => (
      <Chip size="xs" color={statusColor[purchase.status] || 'chip'}>
        {purchase.status}
      </Chip>
    ),
  },
  {
    key: 'reference',
    header: () => <Trans message="Reference" />,
    width: 'w-140',
    body: purchase => (
      <span className="font-mono text-xs truncate">{purchase.reference}</span>
    ),
  },
  {
    key: 'created_at',
    allowsSorting: true,
    header: () => <Trans message="Date" />,
    width: 'w-100',
    body: purchase => <FormattedDate date={purchase.created_at} />,
  },
];

export function PurchasesDatatablePage() {
  return (
    <DataTablePage
      endpoint="admin/purchases"
      title={<Trans message="Purchases" />}
      columns={columnConfig}
      filters={PurchasesDatatablePageFilters}
      queryParams={{
        with: 'user,purchasable',
      }}
      enableSelection={false}
      emptyStateMessage={
        <DataTableEmptyStateMessage
          title={<Trans message="No purchases have been made yet" />}
          filteringTitle={<Trans message="No matching purchases" />}
        />
      }
    />
  );
}
