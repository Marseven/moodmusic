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
  ad_spot: CreateAdSpotPayload;
}

export interface CreateAdSpotPayload {
  name: string;
  audio_url: string;
  image_url?: string;
  click_url?: string;
  duration: number;
  active: boolean;
  priority: number;
  start_date?: string;
  end_date?: string;
}

export function useCreateAdSpot(form: UseFormReturn<CreateAdSpotPayload>) {
  const {trans} = useTrans();
  return useMutation(
    (payload: CreateAdSpotPayload) => createAdSpot(payload),
    {
      onSuccess: () => {
        toast(trans(message('Ad spot created')));
        queryClient.invalidateQueries(DatatableDataQueryKey('ad-spots'));
      },
      onError: err => onFormQueryError(err, form),
    },
  );
}

function createAdSpot(payload: CreateAdSpotPayload): Promise<Response> {
  return apiClient.post('ad-spots', payload).then(r => r.data);
}
