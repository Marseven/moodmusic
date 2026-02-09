import {
  BackendFilter,
  FilterControlType,
  FilterOperator,
} from '@common/datatable/filters/backend-filter';
import {message} from '@common/i18n/message';
import {createdAtFilter} from '@common/datatable/filters/timestamp-filters';

export const PurchasesDatatablePageFilters: BackendFilter[] = [
  {
    key: 'status',
    label: message('Status'),
    description: message('Purchase status'),
    defaultOperator: FilterOperator.eq,
    control: {
      type: FilterControlType.Select,
      defaultValue: 'completed',
      options: [
        {key: '01', label: message('Pending'), value: 'pending'},
        {key: '02', label: message('Completed'), value: 'completed'},
        {key: '03', label: message('Failed'), value: 'failed'},
        {key: '04', label: message('Refunded'), value: 'refunded'},
      ],
    },
  },
  {
    key: 'gateway_name',
    label: message('Gateway'),
    description: message('Payment gateway'),
    defaultOperator: FilterOperator.eq,
    control: {
      type: FilterControlType.Select,
      defaultValue: 'ebilling',
      options: [
        {key: '01', label: message('eBilling'), value: 'ebilling'},
        {key: '02', label: message('Stripe'), value: 'stripe'},
        {key: '03', label: message('PayPal'), value: 'paypal'},
      ],
    },
  },
  createdAtFilter({
    description: message('Date purchase was created'),
  }),
];
