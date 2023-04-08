import {Album} from '../albums/album';
import {Lyric} from './lyrics/lyric';
import {Genre} from '../genres/genre';
import {Artist} from '../artists/artist';
import {Tag} from '@common/tags/tag';

export const TRACK_MODEL = 'track';

export interface Track {
  id: number;
  name: string;
  duration?: number;
  artists?: Artist[];
  youtube_id?: string;
  plays?: number;
  popularity?: number;
  url?: string;
  image?: string;
  lyric?: Lyric;
  album?: Album;
  owner_id?: number;
  description?: string;
  tags: Tag[];
  genres?: Genre[];
  likes_count?: number;
  reposts_count?: number;
  comments_count?: number;
  updated_at?: string;
  created_at?: string;
  // available in library tracks page only
  added_at?: string;
  model_type: 'track';
}
