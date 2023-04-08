import {
  ALL_PRIMITIVE_OPERATORS,
  BackendFilter,
  FilterControlType,
  FilterOperator,
} from '@common/datatable/filters/backend-filter';
import {message} from '@common/i18n/message';
import {
  CreatedAtFilter,
  UpdatedAtFilter,
} from '@common/datatable/filters/timestamp-filters';

export const ArtistDatatableFilters: BackendFilter[] = [
  new BackendFilter({
    type: FilterControlType.Select,
    key: 'verified',
    label: message('Status'),
    description: message('Whether artist is verified'),
    defaultValue: '01',
    defaultOperator: FilterOperator.eq,
    options: [
      {
        key: '01',
        label: message('Verified'),
        value: true,
      },
      {
        key: '02',
        label: message('Not verified'),
        value: false,
      },
    ],
  }),
  new BackendFilter({
    type: FilterControlType.Input,
    inputType: 'number',
    key: 'plays',
    label: message('Plays count'),
    description: message('Number of times artist tracks have been played'),
    defaultValue: 100,
    defaultOperator: FilterOperator.gte,
    operators: ALL_PRIMITIVE_OPERATORS,
  }),
  new BackendFilter({
    type: FilterControlType.Input,
    inputType: 'number',
    key: 'views',
    label: message('Views count'),
    description: message('Number of times artist page have been viewed'),
    defaultValue: 100,
    defaultOperator: FilterOperator.gte,
    operators: ALL_PRIMITIVE_OPERATORS,
  }),
  new CreatedAtFilter({
    description: message('Date artist was created'),
  }),
  new UpdatedAtFilter({
    description: message('Date artist was last updated'),
  }),
];
