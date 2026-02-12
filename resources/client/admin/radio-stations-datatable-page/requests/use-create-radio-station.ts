import {useMutation} from '@tanstack/react-query';
import {apiClient, queryClient} from '@common/http/query-client';
import {useTrans} from '@common/i18n/use-trans';
import {BackendResponse} from '@common/http/backend-response/backend-response';
import {toast} from '@common/ui/toast/toast';
import {message} from '@common/i18n/message';
import {DatatableDataQueryKey} from '@common/datatable/requests/paginated-resources';
import {onFormQueryError} from '@common/errors/on-form-query-error';
import {UseFormReturn} from 'react-hook-form';

interface Response extends BackendResponse {
  station: CreateRadioStationPayload;
}

export interface CreateRadioStationPayload {
  name: string;
  stream_url: string;
  image?: string;
  frequency?: string;
  description?: string;
  genre?: string;
  is_active: boolean;
  sort_order: number;
}

export function useCreateRadioStation(
  form: UseFormReturn<CreateRadioStationPayload>,
) {
  const {trans} = useTrans();
  return useMutation(
    (payload: CreateRadioStationPayload) => createRadioStation(payload),
    {
      onSuccess: () => {
        toast(trans(message('Radio station created')));
        queryClient.invalidateQueries(
          DatatableDataQueryKey('admin/radio-stations'),
        );
      },
      onError: err => onFormQueryError(err, form),
    },
  );
}

function createRadioStation(
  payload: CreateRadioStationPayload,
): Promise<Response> {
  return apiClient.post('admin/radio-stations', payload).then(r => r.data);
}
