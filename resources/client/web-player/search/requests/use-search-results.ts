import {useQuery} from '@tanstack/react-query';
import {apiClient} from '@common/http/query-client';
import {BackendResponse} from '@common/http/backend-response/backend-response';
import {Artist} from '@app/web-player/artists/artist';
import {Track} from '@app/web-player/tracks/track';
import {Playlist} from '@app/web-player/playlists/playlist';
import {User} from '@common/auth/user';
import {Channel} from '@app/web-player/channels/channel';
import {Genre} from '@app/web-player/genres/genre';
import {Tag} from '@common/tags/tag';
import {Album} from '@app/web-player/albums/album';

export interface SearchResponse extends BackendResponse {
  query: string;
  results: {
    artists?: Artist[];
    albums?: Album[];
    tracks?: Track[];
    playlists?: Playlist[];
    users?: User[];
    channels?: Channel[];
    genres?: Genre[];
    tags?: Tag[];
  };
}

interface SearchParams {
  query?: string;
  limit?: number;
  flatten?: boolean;
  types: string[];
  localOnly?: boolean;
  normalize?: boolean;
}

export function useSearchResults(params: SearchParams) {
  return useQuery(['search', params], () => search(params), {
    enabled: !!params.query,
    keepPreviousData: !!params.query,
  });
}

function search(_params: SearchParams) {
  const params = {
    ..._params,
    types: _params.types.join(','),
  };
  return apiClient
    .get<SearchResponse>(`search`, {params})
    .then(response => response.data);
}
