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
import {Pencil, Sparkles} from 'lucide-react';
import {
  OriginalContentCategory,
  CrupdateOriginalContentCategoryDialog,
} from './crupdate-original-content-category-dialog';
import musicSvg from './../tracks-datatable-page/music.svg';

const columnConfig: ColumnConfig<OriginalContentCategory>[] = [
  {
    key: 'display_name',
    allowsSorting: true,
    visibleInMode: 'all',
    width: 'flex-3 min-w-200',
    header: () => <Trans message="Nom" />,
    body: category => category.display_name,
  },
  {
    key: 'name',
    allowsSorting: true,
    width: 'w-120',
    header: () => <Trans message="Slug" />,
    body: category => (
      <span className="text-xs text-muted">{category.name}</span>
    ),
  },
  {
    key: 'icon',
    width: 'w-100',
    header: () => <Trans message="Icône" />,
    body: category => category.icon || '—',
  },
  {
    key: 'position',
    allowsSorting: true,
    width: 'w-80',
    header: () => <Trans message="Position" />,
    body: category => category.position,
  },
  {
    key: 'is_active',
    allowsSorting: true,
    width: 'w-80',
    header: () => <Trans message="Statut" />,
    body: category => (
      <Chip size="xs" color={category.is_active ? 'positive' : 'chip'}>
        {category.is_active ? 'Active' : 'Inactive'}
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
    body: category => (
      <DialogTrigger type="modal">
        <IconButton size={null} className="text-muted h-20 w-20">
          <Pencil size={20} />
        </IconButton>
        <CrupdateOriginalContentCategoryDialog category={category} />
      </DialogTrigger>
    ),
  },
];

export function OriginalContentCategoriesDatatablePage() {
  return (
    <DataTablePage
      endpoint="admin/original-content-categories"
      title={
        <span className="flex items-center gap-8">
          <Sparkles size={22} />
          <Trans message="Création Originale — Catégories" />
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
            <Trans message="Aucune catégorie de création originale" />
          }
          filteringTitle={
            <Trans message="Aucune catégorie correspondante" />
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
          <Trans message="Ajouter une catégorie" />
        </DataTableAddItemButton>
        <CrupdateOriginalContentCategoryDialog />
      </DialogTrigger>
    </Fragment>
  );
}
