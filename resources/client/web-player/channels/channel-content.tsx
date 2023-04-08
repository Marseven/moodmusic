import {
  Channel,
  CHANNEL_MODEL,
  ChannelContentItem,
} from '@app/web-player/channels/channel';
import {Track, TRACK_MODEL} from '@app/web-player/tracks/track';
import React, {Fragment} from 'react';
import {ChannelContentGrid} from '@app/web-player/channels/channel-content-grid';
import {ChannelTrackTable} from '@app/web-player/channels/channel-track-table';
import {ChannelTrackList} from '@app/web-player/channels/channel-track-list';
import {ChannelContentCarousel} from '@app/web-player/channels/channel-content-carousel';
import {ChannelHeading} from '@app/web-player/channels/channel-heading';

export interface ChannelContentProps<
  T extends ChannelContentItem = ChannelContentItem
> {
  channel: Channel<T>;
  isNested?: boolean;
}
export function ChannelContent(props: ChannelContentProps) {
  const {channel, isNested} = props;
  const contentModel = channel.config.contentModel;
  const layout = channel.config.layout;
  if (!channel.content) {
    return null;
  }

  if (contentModel === TRACK_MODEL && layout === 'trackList') {
    return <ChannelTrackList {...(props as ChannelContentProps<Track>)} />;
  } else if (contentModel === TRACK_MODEL && layout === 'trackTable') {
    return <ChannelTrackTable {...(props as ChannelContentProps<Track>)} />;
  }

  if (contentModel === CHANNEL_MODEL) {
    return <NestedChannels {...(props as ChannelContentProps<Channel>)} />;
  } else {
    return channel.config.carouselWhenNested && isNested ? (
      <ChannelContentCarousel {...props} />
    ) : (
      <ChannelContentGrid {...props} />
    );
  }
}

function NestedChannels({channel}: ChannelContentProps<Channel>) {
  return (
    <Fragment>
      <ChannelHeading channel={channel} />
      {channel.content?.data.map(nestedChannel => (
        <div key={nestedChannel.id} className="mb-50">
          <ChannelContent channel={nestedChannel} isNested />
        </div>
      ))}
    </Fragment>
  );
}
