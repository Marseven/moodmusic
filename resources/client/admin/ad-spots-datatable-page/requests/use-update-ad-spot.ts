import {useMutation} from '@tanstack/react-query';
import {apiClient, queryClient} from '@common/http/query-client';
import {useTrans} from '@common/i18n/use-trans';
import {BackendResponse} from '@common/http/backend-response/backend-response';
import {toast} from '@common/ui/toast/toast';
import {message} from '@common/i18n/message';
import {DatatableDataQueryKey} from '@common/datatable/requests/paginated-resources';
import {onFormQueryError} from '@common/errors/on-form-query-error';
import {UseFormReturn} from 'react-hook-form';
import {CreateAdSpotPayload} from './use-create-ad-spot';

interface Response extends BackendResponse {
  ad_spot: UpdateAdSpotPayload;
}

export interface UpdateAdSpotPayload extends CreateAdSpotPayload {
  id: number;
}

export function useUpdateAdSpot(form: UseFormReturn<UpdateAdSpotPayload>) {
  const {trans} = useTrans();
  return useMutation(
    (payload: UpdateAdSpotPayload) => updateAdSpot(payload),
    {
      onSuccess: () => {
        toast(trans(message('Ad spot updated')));
        queryClient.invalidateQueries(DatatableDataQueryKey('ad-spots'));
      },
      onError: err => onFormQueryError(err, form),
    },
  );
}

function updateAdSpot({
  id,
  ...payload
}: UpdateAdSpotPayload): Promise<Response> {
  return apiClient.put(`ad-spots/${id}`, payload).then(r => r.data);
}
