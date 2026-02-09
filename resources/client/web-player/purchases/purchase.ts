import {Track} from '../tracks/track';
import {Album} from '../albums/album';

export interface Purchase {
  id: number;
  user_id: number;
  purchasable_type: string;
  purchasable_id: number;
  purchasable?: Track | Album;
  gateway_name: 'ebilling' | 'stripe' | 'paypal';
  gateway_id?: string;
  amount: number;
  currency: string;
  commission_rate: number;
  commission_amount: number;
  artist_amount: number;
  status: 'pending' | 'completed' | 'failed' | 'refunded';
  reference: string;
  paid_at?: string;
  created_at?: string;
  updated_at?: string;
}

export interface PurchasedItem {
  purchasable_type: string;
  purchasable_id: number;
}
