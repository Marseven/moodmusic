import {
  CreatedAtFilter,
  UpdatedAtFilter,
} from '@common/datatable/filters/timestamp-filters';
import {message} from '@common/i18n/message';

export const GenreDatatablePageFilters = [
  new CreatedAtFilter({
    description: message('Date genre was created'),
  }),
  new UpdatedAtFilter({
    description: message('Date genre was last updated'),
  }),
];
