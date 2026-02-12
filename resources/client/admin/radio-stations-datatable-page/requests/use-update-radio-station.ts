import {useMutation} from '@tanstack/react-query';
import {apiClient, queryClient} from '@common/http/query-client';
import {useTrans} from '@common/i18n/use-trans';
import {BackendResponse} from '@common/http/backend-response/backend-response';
import {toast} from '@common/ui/toast/toast';
import {message} from '@common/i18n/message';
import {DatatableDataQueryKey} from '@common/datatable/requests/paginated-resources';
import {onFormQueryError} from '@common/errors/on-form-query-error';
import {UseFormReturn} from 'react-hook-form';
import {CreateRadioStationPayload} from './use-create-radio-station';

interface Response extends BackendResponse {
  station: UpdateRadioStationPayload;
}

export interface UpdateRadioStationPayload extends CreateRadioStationPayload {
  id: number;
}

export function useUpdateRadioStation(
  form: UseFormReturn<UpdateRadioStationPayload>,
) {
  const {trans} = useTrans();
  return useMutation(
    (payload: UpdateRadioStationPayload) => updateRadioStation(payload),
    {
      onSuccess: () => {
        toast(trans(message('Radio station updated')));
        queryClient.invalidateQueries(
          DatatableDataQueryKey('admin/radio-stations'),
        );
      },
      onError: err => onFormQueryError(err, form),
    },
  );
}

function updateRadioStation({
  id,
  ...payload
}: UpdateRadioStationPayload): Promise<Response> {
  return apiClient
    .put(`admin/radio-stations/${id}`, payload)
    .then(r => r.data);
}
