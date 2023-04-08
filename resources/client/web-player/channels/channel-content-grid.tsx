import {usePaginatedChannelContent} from '@app/web-player/channels/requests/use-paginated-channel-content';
import {ContentGrid} from '@app/web-player/playable-item/content-grid';
import {InfiniteScrollSentinel} from '@common/ui/infinite-scroll/infinite-scroll-sentinel';
import React, {Fragment} from 'react';
import {ChannelContentProps} from '@app/web-player/channels/channel-content';
import {ChannelContentGridItem} from '@app/web-player/channels/channel-content-grid-item';
import {ChannelHeading} from '@app/web-player/channels/channel-heading';
import {Channel, ChannelContentItem} from '@app/web-player/channels/channel';

export function ChannelContentGrid(props: ChannelContentProps) {
  return (
    <Fragment>
      <ChannelHeading {...props} />
      {props.isNested || props.channel.config.contentType !== 'listAll' ? (
        <SimpleGrid {...props} />
      ) : (
        <PaginatedGrid {...props} />
      )}
    </Fragment>
  );
}

function SimpleGrid({channel}: ChannelContentProps) {
  const content = (channel.content?.data || []) as Exclude<
    ChannelContentItem,
    Channel
  >[];
  return (
    <ContentGrid>
      {content.map(item => (
        <ChannelContentGridItem
          key={`${item.id}-${item.model_type}`}
          item={item}
          items={content}
        />
      ))}
    </ContentGrid>
  );
}

function PaginatedGrid({channel}: ChannelContentProps) {
  const query = usePaginatedChannelContent(channel);
  const content = (query.items || []) as Exclude<ChannelContentItem, Channel>[];
  return (
    <div>
      <ContentGrid>
        {content.map(item => (
          <ChannelContentGridItem
            key={`${item.id}-${item.model_type}`}
            item={item}
            items={content}
          />
        ))}
      </ContentGrid>
      <InfiniteScrollSentinel query={query} />
    </div>
  );
}
