import {useUser} from '../../auth/ui/use-user';
import {queryClient} from '@common/http/query-client';

export function useBillingUser() {
  const query = useUser('me', {
    with: ['subscriptions.product', 'subscriptions.price'],
  });

  const subscriptions = query.data?.user.subscriptions;
  // Pick the best subscription: active (paid, no ends_at) first, then most recent
  const subscription = subscriptions?.find(s => s.paid_at && !s.ends_at)
    ?? subscriptions?.find(s => s.paid_at)
    ?? subscriptions?.[0];

  return {subscription, isLoading: query.isLoading, user: query.data?.user};
}

export function invalidateBillingUserQuery() {
  queryClient.invalidateQueries(['users']);
}
