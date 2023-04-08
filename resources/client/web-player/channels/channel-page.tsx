import {useChannel} from '@app/web-player/channels/requests/use-channel';
import React from 'react';
import {Helmet} from '@common/seo/helmet';
import {ChannelContent} from '@app/web-player/channels/channel-content';
import {PageStatus} from '@common/http/page-status';
import {AdHost} from '@common/admin/ads/ad-host';

interface ChannelPageProps {
  slugOrId?: string | number;
}
export function ChannelPage({slugOrId}: ChannelPageProps) {
  const query = useChannel(slugOrId);

  if (query.data) {
    return (
      <div>
        <Helmet tags={query.data.seo} />
        <div className="pb-24">
          <AdHost slot="general_top" className="mb-34" />
          <ChannelContent
            channel={query.data.channel}
            // set key to force re-render when channel changes
            key={query.data.channel.id}
          />
          <AdHost slot="general_bottom" className="mt-34" />
        </div>
      </div>
    );
  }

  return <PageStatus query={query} loaderClassName="absolute inset-0 m-auto" />;
}
