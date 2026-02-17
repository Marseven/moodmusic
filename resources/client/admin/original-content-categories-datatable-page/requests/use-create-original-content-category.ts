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
  category: CreateOriginalContentCategoryPayload;
}

export interface CreateOriginalContentCategoryPayload {
  name: string;
  display_name: string;
  description?: string;
  icon?: string;
  position: number;
  is_active: boolean;
}

export function useCreateOriginalContentCategory(
  form: UseFormReturn<CreateOriginalContentCategoryPayload>,
) {
  const {trans} = useTrans();
  return useMutation(
    (payload: CreateOriginalContentCategoryPayload) =>
      apiClient
        .post<Response>('admin/original-content-categories', payload)
        .then(r => r.data),
    {
      onSuccess: () => {
        toast(trans(message('Category created')));
        queryClient.invalidateQueries(
          DatatableDataQueryKey('admin/original-content-categories'),
        );
      },
      onError: err => onFormQueryError(err, form),
    },
  );
}
