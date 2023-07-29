import {PaginationResponse} from '@common/http/backend-response/pagination-response';
import {User} from '@common/auth/user';

export const CHANNEL_MODEL = 'channel';

export type ChannelContentItem<T = unknown> = T & {
  channelable_id?: number;
  channelable_order?: number;
};

export interface ChannelConfig {
  autoUpdateMethod?: string;
  disablePagination?: boolean;
  disablePlayback?: boolean;
  restriction?: string;
  restrictionModelId?: 'urlParam' | number;
  contentModel: string;
  contentType: 'listAll' | 'manual' | 'autoUpdate';
  contentOrder: string;
  layout: string;
  nestedLayout: string;
  hideTitle?: boolean;
  lockSlug?: boolean;
  preventDeletion?: boolean;
  actions?: {tooltip: string; icon: string; route: string}[];
}

export interface Channel<T = ChannelContentItem> {
  id: number;
  name: string;
  internal: boolean;
  public: boolean;
  description?: string;
  type: string;
  slug: string;
  config: ChannelConfig;
  items?: T[];
  model_type: 'channel';
  items_count?: number;
  user?: User;
  updated_at?: string;
  restriction?: {name: string; model_type: string};
  content?: PaginationResponse<T>;
}
