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
import {ARTIST_MODEL} from '@app/web-player/artists/artist';

export const AlbumsDatatableFilters: BackendFilter[] = [
  new BackendFilter({
    type: FilterControlType.Select,
    key: 'image',
    label: message('Artwork'),
    description: message('Whether album has any artwork uploaded'),
    defaultValue: '02',
    defaultOperator: FilterOperator.eq,
    options: [
      {
        key: '01',
        label: message('Has artwork'),
        value: false,
      },
      {
        key: '02',
        label: message('Does not have artwork'),
        value: true,
      },
    ],
  }),
  new BackendFilter({
    type: FilterControlType.Input,
    inputType: 'number',
    key: 'plays',
    label: message('Plays count'),
    description: message('Number of times this album was played'),
    defaultValue: 100,
    defaultOperator: FilterOperator.gte,
    operators: ALL_PRIMITIVE_OPERATORS,
  }),
  new CreatedAtFilter({
    description: message('Date album was created'),
  }),
  new UpdatedAtFilter({
    description: message('Date album was last updated'),
  }),
  new BackendFilter({
    type: FilterControlType.SelectModel,
    defaultOperator: FilterOperator.has,
    model: ARTIST_MODEL,
    key: 'artists',
    label: message('Artist'),
    description: message('Artist this album belongs to'),
  }),
];
