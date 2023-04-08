import {useQuery} from '@tanstack/react-query';
import {apiClient} from '@common/http/query-client';
import {BackendResponse} from '@common/http/backend-response/backend-response';
import {useParams} from 'react-router-dom';
import {Album} from '@app/web-player/albums/album';
import {assignAlbumToTracks} from '@app/web-player/albums/assign-album-to-tracks';

interface GetAlbumResponse extends BackendResponse {
  album: Album;
}

interface Params {
  autoUpdate?: boolean;
  forEditing?: boolean;
  defaultRelations?: boolean;
  with?: string;
}

export function useAlbum(params: Params) {
  const {albumId} = useParams();
  return useQuery(['albums', +albumId!], () => fetchAlbum(albumId!, params));
}

function fetchAlbum(albumId: number | string, params: Params) {
  return apiClient
    .get<GetAlbumResponse>(`albums/${albumId}`, {
      params,
    })
    .then(response => {
      response.data.album = assignAlbumToTracks(response.data.album);
      return response.data;
    });
}
