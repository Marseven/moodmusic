import {useQuery} from '@tanstack/react-query';
import {apiClient, queryClient} from '@common/http/query-client';
import {BackendResponse} from '@common/http/backend-response/backend-response';
import {useAuth} from '@common/auth/use-auth';
import {getBootstrapData} from '@common/core/bootstrap-data/use-backend-bootstrap-data';

interface Response extends BackendResponse {
  minutesLeft: number | null;
}

export function resetMinutesLimitQuery() {
  const {user} = getBootstrapData();
  if (user?.id) {
    queryClient.resetQueries(['minutesLimit', user.id]);
  }
}

export function useUserMinutesLimit() {
  const {user} = useAuth();
  const userId = user?.id!;
  return useQuery(['minutesLimit', userId], () => fetchLimit(userId), {
    enabled: userId != null,
  });
}

function fetchLimit(userId: number): Promise<Response> {
  return apiClient
    .get(`users/${userId}/minutes-left`)
    .then(response => response.data);
}
