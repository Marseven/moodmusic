import {
  BackendFilter,
  FilterControlType,
  FilterOperator,
} from '@common/datatable/filters/backend-filter';
import {message} from '@common/i18n/message';
import {
  CreatedAtFilter,
  UpdatedAtFilter,
} from '@common/datatable/filters/timestamp-filters';
import {USER_MODEL} from '@common/auth/user';

export const BackstageRequestDatatableFilters: BackendFilter[] = [
  new BackendFilter({
    type: FilterControlType.Select,
    key: 'type',
    label: message('Type'),
    description: message('Type of the request'),
    defaultValue: 'become-artist',
    defaultOperator: FilterOperator.eq,
    options: [
      {
        key: 'become-artist',
        label: message('Become artist'),
        value: 'become-artist',
      },
      {
        key: 'verify-artist',
        label: message('Verify artist'),
        value: 'verify-artist',
      },
      {
        key: 'claim-artist',
        label: message('Claim artist'),
        value: 'claim-artist',
      },
    ],
  }),
  new BackendFilter({
    type: FilterControlType.Select,
    key: 'status',
    label: message('Status'),
    description: message('Status of the request'),
    defaultValue: 'pending',
    defaultOperator: FilterOperator.eq,
    options: [
      {
        key: 'pending',
        label: message('Pending'),
        value: 'pending',
      },
      {
        key: 'approved',
        label: message('Approved'),
        value: 'approved',
      },
      {
        key: 'denied',
        label: message('Denied'),
        value: 'denied',
      },
    ],
  }),
  new BackendFilter({
    type: FilterControlType.SelectModel,
    model: USER_MODEL,
    key: 'user_id',
    label: message('Requester'),
    description: message('User that submitted the request'),
  }),
  new CreatedAtFilter({
    description: message('Date request was created'),
  }),
  new UpdatedAtFilter({
    description: message('Date request was last updated'),
  }),
];
