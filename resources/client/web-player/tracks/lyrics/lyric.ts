import {Track} from '../track';

export interface Lyric {
  id: number;
  text: string;
  track_id: number;
  track?: Track;
  updated_at: string;
}
