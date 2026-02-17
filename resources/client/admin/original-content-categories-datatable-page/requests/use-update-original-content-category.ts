import {useMutation} from '@tanstack/react-query';
import {apiClient, queryClient} from '@common/http/query-client';
import {useTrans} from '@common/i18n/use-trans';
import {BackendResponse} from '@common/http/backend-response/backend-response';
import {toast} from '@common/ui/toast/toast';
import {message} from '@common/i18n/message';
import {DatatableDataQueryKey} from '@common/datatable/requests/paginated-resources';
import {onFormQueryError} from '@common/errors/on-form-query-error';
import {UseFormReturn} from 'react-hook-form';
import {CreateOriginalContentCategoryPayload} from './use-create-original-content-category';

interface Response extends BackendResponse {
  category: UpdateOriginalContentCategoryPayload;
}

export interface UpdateOriginalContentCategoryPayload
  extends CreateOriginalContentCategoryPayload {
  id: number;
}

export function useUpdateOriginalContentCategory(
  form: UseFormReturn<UpdateOriginalContentCategoryPayload>,
) {
  const {trans} = useTrans();
  return useMutation(
    ({id, ...payload}: UpdateOriginalContentCategoryPayload) =>
      apiClient
        .put<Response>(`admin/original-content-categories/${id}`, payload)
        .then(r => r.data),
    {
      onSuccess: () => {
        toast(trans(message('Category updated')));
        queryClient.invalidateQueries(
          DatatableDataQueryKey('admin/original-content-categories'),
        );
      },
      onError: err => onFormQueryError(err, form),
    },
  );
}
