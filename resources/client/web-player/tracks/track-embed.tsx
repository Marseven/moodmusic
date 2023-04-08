import React, {
  memo,
  MutableRefObject,
  useContext,
  useMemo,
  useRef,
} from 'react';
import {playerStoreOptions} from '@app/web-player/state/player-store-options';
import {PlayerContext, PlayerStoreContext} from '@common/player/player-context';
import {TrackListItem} from '@app/web-player/tracks/track-list/track-list-item';
import {useTrack} from '@app/web-player/tracks/requests/use-track';
import {FullPageLoader} from '@common/ui/progress/full-page-loader';
import {Track} from '@app/web-player/tracks/track';
import {PlayerStoreOptions} from '@common/player/player-store-options';
import {trackToMediaItem} from '@app/web-player/tracks/utils/track-to-media-item';

export function TrackEmbed() {
  const {data} = useTrack({
    autoUpdate: false,
  });
  return (
    <div className="rounded border bg-alt p-14 h-[174px]">
      {!data?.track ? <FullPageLoader /> : <EmbedContent track={data.track} />}
    </div>
  );
}

interface EmbedContentProps {
  track: Track;
}
function EmbedContent({track}: EmbedContentProps) {
  const playerRef = useRef<HTMLDivElement>(null);
  const options: PlayerStoreOptions = useMemo(() => {
    const mediaItem = trackToMediaItem(track);
    return {
      ...playerStoreOptions,
      ref: playerRef,
      initialData: {
        queue: [mediaItem],
        cuedMediaId: mediaItem.id,
        state: {
          repeat: false,
        },
      },
    };
  }, [track]);
  return (
    <PlayerContext id="web-player" options={options}>
      <div className="flex gap-24">
        <div className="flex-shrink-0 rounded bg-black overflow-hidden">
          <PlayerContainer playerRef={playerRef} />
        </div>
        <TrackListItem track={track} hideArtwork hideActions linksInNewTab />
      </div>
    </PlayerContext>
  );
}

interface PlayerContainerProps {
  playerRef: MutableRefObject<HTMLDivElement | null>;
}
const PlayerContainer = memo(({playerRef}: PlayerContainerProps) => {
  const {getState} = useContext(PlayerStoreContext);

  return (
    <div
      className="w-144 h-144 player-container"
      ref={el => {
        if (el) {
          playerRef.current = el;
          getState().init();
        } else {
          getState().destroy();
        }
      }}
    />
  );
});
