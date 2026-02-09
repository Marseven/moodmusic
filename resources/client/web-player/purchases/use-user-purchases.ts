import {useQuery} from '@tanstack/react-query';
import {apiClient} from '@common/http/query-client';
import {PurchasedItem} from './purchase';
import {useAuth} from '@common/auth/use-auth';

interface PurchasedItemsResponse {
  purchases: PurchasedItem[];
}

export function useUserPurchases() {
  const {user} = useAuth();
  return useQuery(
    ['purchased-items', user?.id],
    () => fetchPurchasedItems(),
    {
      enabled: !!user,
      staleTime: 1000 * 60 * 5,
    },
  );
}

function fetchPurchasedItems(): Promise<PurchasedItemsResponse> {
  return apiClient.get('purchased-items').then(r => r.data);
}

export function isPurchased(
  purchases: PurchasedItem[] | undefined,
  type: string,
  id: number,
): boolean {
  if (!purchases) return false;
  const modelType = type === 'track' ? 'App\\Track' : 'App\\Album';
  return purchases.some(
    p => p.purchasable_type === modelType && p.purchasable_id === id,
  );
}
