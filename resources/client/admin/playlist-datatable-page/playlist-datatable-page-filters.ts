import {
  CreatedAtFilter,
  UpdatedAtFilter,
} from '@common/datatable/filters/timestamp-filters';
import {message} from '@common/i18n/message';
import {
  ALL_PRIMITIVE_OPERATORS,
  BackendFilter,
  FilterControlType,
  FilterOperator,
} from '@common/datatable/filters/backend-filter';

export const PlaylistDatatablePageFilters = [
  new BackendFilter({
    type: FilterControlType.Select,
    key: 'public',
    label: message('Visibility'),
    defaultValue: '01',
    description: message('Whether playlist is publicly viewable'),
    options: [
      {key: '01', label: message('Private'), value: false},
      {key: '02', label: message('Public'), value: true},
    ],
  }),
  new BackendFilter({
    type: FilterControlType.BooleanToggle,
    key: 'collaborative',
    defaultValue: true,
    label: message('Collaborative'),
    description: message('Whether playlist is marked as collaborative'),
  }),
  new BackendFilter({
    type: FilterControlType.Input,
    inputType: 'number',
    key: 'plays',
    label: message('Play count'),
    description: message('Number of times this playlist was played'),
    defaultValue: 100,
    defaultOperator: FilterOperator.gte,
    operators: ALL_PRIMITIVE_OPERATORS,
  }),
  new BackendFilter({
    type: FilterControlType.Input,
    inputType: 'number',
    key: 'views',
    label: message('View count'),
    description: message('Number of times this playlist page was viewed'),
    defaultValue: 100,
    defaultOperator: FilterOperator.gte,
    operators: ALL_PRIMITIVE_OPERATORS,
  }),
  new CreatedAtFilter({
    description: message('Date playlist was created'),
  }),
  new UpdatedAtFilter({
    description: message('Date playlist was last updated'),
  }),
];
