import {useQuery} from '@tanstack/react-query';
import {apiClient} from '@common/http/query-client';
import {BackendResponse} from '@common/http/backend-response/backend-response';
import {useParams} from 'react-router-dom';
import {PaginationResponse} from '@common/http/backend-response/pagination-response';
import {Track} from '@app/web-player/tracks/track';
import {Playlist} from '@app/web-player/playlists/playlist';

interface GetPlaylistResponse extends BackendResponse {
  playlist: Playlist;
  tracks: PaginationResponse<Track>;
  totalDuration: number;
}

export function usePlaylist() {
  const {playlistId} = useParams();
  return useQuery(['playlists', +playlistId!], () =>
    fetchPlaylist(playlistId!)
  );
}

function fetchPlaylist(
  playlistId: number | string
): Promise<GetPlaylistResponse> {
  return apiClient
    .get(`playlists/${playlistId}`)
    .then(response => response.data);
}
