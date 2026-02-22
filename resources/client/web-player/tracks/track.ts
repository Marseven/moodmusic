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
  plays?: number;
  popularity?: number;
  src?: string;
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
  price?: number;
  currency?: string;
  is_original_content?: boolean;
  original_content_category_id?: number;
  is_live?: boolean;
  preview_path?: string;
  mood?: string;
  updated_at?: string;
  created_at?: string;
  // available in library tracks page only
  added_at?: string;
  model_type: 'track';
}
