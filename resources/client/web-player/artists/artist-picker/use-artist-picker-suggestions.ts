import {useQuery} from '@tanstack/react-query';
import {BackendResponse} from '@common/http/backend-response/backend-response';
import {NormalizedModel} from '@common/datatable/filters/normalized-model';
import {apiClient} from '@common/http/query-client';

interface Response extends BackendResponse {
  results: NormalizedModel[];
}

interface Params {
  query?: string;
  perPage?: number;
}

export function useArtistPickerSuggestions(queryParams: Params) {
  return useQuery(
    ['artists', 'search-suggestions', queryParams],
    () => fetchArtists(queryParams),
    {
      keepPreviousData: true,
    }
  );
}

async function fetchArtists(params: Params) {
  return apiClient
    .get<Response>('search/suggestions/artist', {params})
    .then(r => r.data);
}
