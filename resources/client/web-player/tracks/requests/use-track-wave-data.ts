import {useQuery} from '@tanstack/react-query';
import {apiClient, queryClient} from '@common/http/query-client';
import {BackendResponse} from '@common/http/backend-response/backend-response';
import {Comment} from '@common/comments/comment';
import {showHttpErrorToast} from '@common/utils/http/show-http-error-toast';

interface WaveDataResponse extends BackendResponse {
  waveData: number[][];
  comments: Comment[];
}

function queryKey(trackId: number | string) {
  return ['tracks', +trackId, 'wave-data'];
}

export function invalidateWaveData(trackId: number | string) {
  queryClient.invalidateQueries(queryKey(trackId));
}

export function useTrackWaveData(
  trackId: number | string,
  {enabled}: {enabled?: boolean} = {}
) {
  return useQuery(queryKey(trackId), () => fetchWaveData(trackId), {
    onError: err => showHttpErrorToast(err),
    enabled,
  });
}

function fetchWaveData(trackId: number | string) {
  return apiClient
    .get<WaveDataResponse>(`tracks/${trackId}/wave`)
    .then(response => response.data);
}
