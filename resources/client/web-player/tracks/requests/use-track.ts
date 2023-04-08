import {useQuery} from '@tanstack/react-query';
import {apiClient} from '@common/http/query-client';
import {BackendResponse} from '@common/http/backend-response/backend-response';
import {useParams} from 'react-router-dom';
import {Track} from '@app/web-player/tracks/track';
import {assignAlbumToTracks} from '@app/web-player/albums/assign-album-to-tracks';

interface getTrackResponse extends BackendResponse {
  track: Track;
}

interface Params {
  autoUpdate?: boolean;
  forEditing?: boolean;
}

export function useTrack(params: Params) {
  const {trackId} = useParams();
  return useQuery(['tracks', +trackId!, params], () =>
    fetchTrack(trackId!, params)
  );
}

function fetchTrack(trackId: number | string, params: Params) {
  return apiClient
    .get<getTrackResponse>(`tracks/${trackId}`, {
      params: {defaultRelations: true, ...params},
    })
    .then(response => {
      if (response.data.track.album) {
        response.data.track = {
          ...response.data.track,
          album: assignAlbumToTracks(response.data.track.album),
        };
      }
      return response.data;
    });
}
