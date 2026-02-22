import {SortDescriptor} from '@common/ui/tables/types/sort-descriptor';
import {Menu, MenuTrigger} from '@common/ui/navigation/menu/menu-trigger';
import {Button} from '@common/ui/buttons/button';
import {ModernChevronDownIcon} from '@app/web-player/icons/modern-icons';
import {Trans} from '@common/i18n/trans';
import {Item} from '@common/ui/forms/listbox/item';
import React from 'react';
import {MessageDescriptor} from '@common/i18n/message-descriptor';
import {useIsMobileMediaQuery} from '@common/utils/hooks/is-mobile-media-query';
import {IconButton} from '@common/ui/buttons/icon-button';
import {ModernArrowUpDownIcon} from '@app/web-player/icons/modern-icons';

interface Props {
  items: Record<string, MessageDescriptor>;
  sortDescriptor: SortDescriptor;
  setSortDescriptor: (sort: SortDescriptor) => void;
}
export function LibraryPageSortDropdown({
  items,
  sortDescriptor,
  setSortDescriptor,
}: Props) {
  const isMobile = useIsMobileMediaQuery();
  const selectedValue = `${sortDescriptor.orderBy}:${sortDescriptor.orderDir}`;
  return (
    <MenuTrigger
      selectionMode="single"
      selectedValue={selectedValue}
      onSelectionChange={newValue => {
        const [orderBy, orderDir] = (newValue as string).split(':');
        setSortDescriptor({
          orderBy,
          orderDir: orderDir as SortDescriptor['orderDir'],
        });
      }}
    >
      {isMobile ? (
        <IconButton>
          <ModernArrowUpDownIcon />
        </IconButton>
      ) : (
        <Button
          variant="outline"
          className="flex-shrink-0"
          endIcon={<ModernChevronDownIcon />}
        >
          <Trans {...items[selectedValue]} />
        </Button>
      )}
      <Menu>
        {Object.entries(items).map(([value, label]) => (
          <Item key={value} value={value}>
            <Trans {...label} />
          </Item>
        ))}
      </Menu>
    </MenuTrigger>
  );
}
