import {useQuery} from '@tanstack/react-query';
import {apiClient} from '@common/http/query-client';
import {BackendResponse} from '@common/http/backend-response/backend-response';
import {useParams} from 'react-router-dom';
import {Artist} from '@app/web-player/artists/artist';
import {PaginationResponse} from '@common/http/backend-response/pagination-response';
import {Album} from '@app/web-player/albums/album';
import {assignAlbumToTracks} from '@app/web-player/albums/assign-album-to-tracks';
import {Track} from '@app/web-player/tracks/track';

export const albumLayoutKey = 'artistPage.albumLayout';

export interface UseArtistResponse extends BackendResponse {
  artist: Artist;
  albums?: PaginationResponse<Album>;
  tracks?: PaginationResponse<Track>;
}

export interface UseArtistParams {
  autoUpdate?: boolean;
  forEditing?: boolean;
  with?: string | string[];
  withCount?: string | string[];
  loadAlbumTracks?: boolean;
  albumsPerPage?: number;
}

export function useArtist(params: UseArtistParams) {
  const {artistId} = useParams();
  return useQuery(['artists', artistId, params], () =>
    fetchArtist(artistId!, params)
  );
}

function fetchArtist(
  artistId: number | string,
  params: object
): Promise<UseArtistResponse> {
  return apiClient
    .get<UseArtistResponse>(`artists/${artistId}`, {params})
    .then(response => {
      if (response.data.albums) {
        response.data.albums.data = response.data.albums.data.map(album =>
          assignAlbumToTracks(album)
        );
      }
      return response.data;
    });
}
