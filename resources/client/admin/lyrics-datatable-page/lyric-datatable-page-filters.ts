import {
  CreatedAtFilter,
  UpdatedAtFilter,
} from '@common/datatable/filters/timestamp-filters';
import {message} from '@common/i18n/message';

export const LyricDatatablePageFilters = [
  new CreatedAtFilter({
    description: message('Date lyric was created'),
  }),
  new UpdatedAtFilter({
    description: message('Date lyric was last updated'),
  }),
];
