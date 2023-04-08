import {apiClient, queryClient} from '@common/http/query-client';
import {BackendResponse} from '@common/http/backend-response/backend-response';
import {Track} from '@app/web-player/tracks/track';

interface Response extends BackendResponse {
  results: {title: string; id: string}[];
}

const endpoint = (track: Track) => {
  const artistName =
    track.artists?.[0]?.name || track.album?.artists?.[0]?.name;
  return `search/audio/${track.id}/${doubleEncode(artistName!)}/${doubleEncode(
    track.name
  )}`;
};

export async function findYoutubeVideosForTrack(
  track: Track
): Promise<Response['results']> {
  const query = {
    queryKey: [endpoint(track)],
    queryFn: async () => findMatch(track),
    staleTime: Infinity,
  };

  const response =
    queryClient.getQueryData<Response>(query.queryKey) ??
    (await queryClient.fetchQuery(query));

  return response?.results || [];
}

function findMatch(track: Track): Promise<Response> {
  return apiClient.get(endpoint(track)).then(response => response.data);
}

function doubleEncode(value: string) {
  return encodeURIComponent(encodeURIComponent(value));
}
