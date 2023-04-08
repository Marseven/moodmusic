import {useQuery} from '@tanstack/react-query';
import {apiClient} from '@common/http/query-client';
import {BackendResponse} from '@common/http/backend-response/backend-response';
import {useParams} from 'react-router-dom';
import {User} from '@common/auth/user';

interface getTrackResponse extends BackendResponse {
  user: User;
}

export function useUserProfile() {
  const {userId} = useParams();
  return useQuery(userProfileQueryKey(userId!), () => fetchUser(userId!));
}

function fetchUser(userId: number | string) {
  return apiClient
    .get<getTrackResponse>(`users/${userId}`)
    .then(response => response.data);
}

export function userProfileQueryKey(userId: number | string) {
  return ['users', +userId, 'profile'];
}
