import {useQuery} from '@tanstack/react-query';
import {apiClient} from '@common/http/query-client';

export interface RadioStation {
  id: number;
  name: string;
  image: string | null;
  stream_url: string;
  frequency: string | null;
  description: string | null;
  genre: string | null;
  is_active: boolean;
  sort_order: number;
  listeners_count: number;
}

interface RadioStationsResponse {
  stations: RadioStation[];
}

export function useRadioStations() {
  return useQuery({
    queryKey: ['radio-stations'],
    queryFn: () => fetchRadioStations(),
  });
}

function fetchRadioStations() {
  return apiClient
    .get<RadioStationsResponse>('radio-stations')
    .then(r => r.data);
}
