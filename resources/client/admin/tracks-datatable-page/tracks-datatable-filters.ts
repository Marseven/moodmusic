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

export const TracksDatatableFilters: BackendFilter[] = [
  new BackendFilter({
    type: FilterControlType.Select,
    key: 'image',
    label: message('Artwork'),
    description: message('Whether track has any artwork uploaded'),
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
    type: FilterControlType.Select,
    key: 'album_id',
    label: message('Album'),
    description: message('Whether track is part of an album'),
    defaultValue: '01',
    defaultOperator: FilterOperator.eq,
    options: [
      {
        key: '01',
        label: message('Part of an album'),
        value: {operator: FilterOperator.ne, value: null},
      },
      {
        key: '02',
        label: message('Single'),
        value: {operator: FilterOperator.eq, value: null},
      },
    ],
  }),
  new BackendFilter({
    type: FilterControlType.Input,
    inputType: 'number',
    key: 'plays',
    label: message('Plays count'),
    description: message('Number of times this track was played'),
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
  new BackendFilter({
    type: FilterControlType.SelectModel,
    defaultOperator: FilterOperator.has,
    model: ARTIST_MODEL,
    key: 'artists',
    label: message('Artist'),
    description: message('Artist this track belongs to'),
  }),
];
