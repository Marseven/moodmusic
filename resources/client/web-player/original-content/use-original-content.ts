import {useQuery} from '@tanstack/react-query';
import {apiClient} from '@common/http/query-client';
import {PaginatedBackendResponse} from '@common/http/backend-response/pagination-response';
import {Track} from '@app/web-player/tracks/track';

interface OriginalContentCategory {
  id: number;
  name: string;
  display_name: string;
  description: string | null;
  icon: string | null;
  position: number;
  is_active: boolean;
}

interface OriginalContentResponse extends PaginatedBackendResponse<Track> {
  category: OriginalContentCategory;
}

interface CategoriesResponse {
  categories: OriginalContentCategory[];
}

export function useOriginalContentCategories() {
  return useQuery({
    queryKey: ['original-content', 'categories'],
    queryFn: () => fetchCategories(),
  });
}

function fetchCategories() {
  return apiClient
    .get<CategoriesResponse>('original-content/categories')
    .then(r => r.data);
}

export function useOriginalContent(categoryName: string) {
  return useQuery({
    queryKey: ['original-content', categoryName],
    queryFn: () => fetchOriginalContent(categoryName),
    enabled: !!categoryName,
  });
}

function fetchOriginalContent(categoryName: string) {
  return apiClient
    .get<OriginalContentResponse>(`original-content/${categoryName}`)
    .then(r => r.data);
}

export type {OriginalContentCategory};
