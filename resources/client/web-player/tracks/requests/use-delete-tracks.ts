import {BackendResponse} from '@common/http/backend-response/backend-response';
import {useMutation} from '@tanstack/react-query';
import {toast} from '@common/ui/toast/toast';
import {message} from '@common/i18n/message';
import {apiClient, queryClient} from '@common/http/query-client';
import {showHttpErrorToast} from '@common/utils/http/show-http-error-toast';
import {useLocation} from 'react-router-dom';
import {useNavigate} from '@common/utils/hooks/use-navigate';
import {useAuth} from '@common/auth/use-auth';

interface Response extends BackendResponse {}

interface Payload {
  trackIds: number[];
}

export function useDeleteTracks() {
  const {pathname} = useLocation();
  const navigate = useNavigate();
  const {getRedirectUri} = useAuth();

  return useMutation((payload: Payload) => deleteTracks(payload), {
    onSuccess: (response, {trackIds}) => {
      toast(
        message('[one track|other :count tracks] deleted', {
          values: {count: trackIds.length},
        })
      );
      // navigate to homepage if we are on this track page currently
      if (trackIds.some(trackId => pathname.startsWith(`/track/${trackId}`))) {
        navigate(getRedirectUri());
      }
      queryClient.invalidateQueries(['tracks']);
    },
    onError: r => showHttpErrorToast(r),
  });
}

function deleteTracks({trackIds}: Payload): Promise<Response> {
  return apiClient.delete(`tracks/${trackIds.join(',')}`).then(r => r.data);
}
