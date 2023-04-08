import {useQuery} from '@tanstack/react-query';
import {apiClient} from '@common/http/query-client';
import {BackendResponse} from '@common/http/backend-response/backend-response';
import {Artist} from '@app/web-player/artists/artist';

interface Response extends BackendResponse {
  artists: Artist[];
}

export function useLandingPageTrendingArtists() {
  return useQuery(['landing', 'trending-artists'], () => fetchArtists());
}

function fetchArtists() {
  return apiClient
    .get<Response>('landing/artists')
    .then(response => response.data);
}
