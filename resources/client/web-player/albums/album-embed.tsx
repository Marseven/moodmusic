import React, {
  memo,
  MutableRefObject,
  useContext,
  useMemo,
  useRef,
} from 'react';
import {playerStoreOptions} from '@app/web-player/state/player-store-options';
import {PlayerContext, PlayerStoreContext} from '@common/player/player-context';
import {FullPageLoader} from '@common/ui/progress/full-page-loader';
import {PlayerStoreOptions} from '@common/player/player-store-options';
import {
  tracksToMediaItems,
  trackToMediaItem,
} from '@app/web-player/tracks/utils/track-to-media-item';
import {useAlbum} from '@app/web-player/albums/requests/use-album';
import {Album} from '@app/web-player/albums/album';
import {AlbumListItem} from '@app/web-player/albums/album-list/album-list-item';

export function AlbumEmbed() {
  const {data} = useAlbum({
    autoUpdate: false,
    with: 'tracks',
  });
  return (
    <div className="rounded border bg-alt p-14 h-384">
      {!data?.album ? <FullPageLoader /> : <EmbedContent album={data.album} />}
    </div>
  );
}

interface EmbedContentProps {
  album: Album;
}
function EmbedContent({album}: EmbedContentProps) {
  const playerRef = useRef<HTMLDivElement>(null);
  const options: PlayerStoreOptions = useMemo(() => {
    return {
      ...playerStoreOptions,
      ref: playerRef,
      initialData: {
        queue: album.tracks?.length ? tracksToMediaItems(album.tracks) : [],
        cuedMediaId: album.tracks?.length
          ? trackToMediaItem(album.tracks[0]).id
          : undefined,
        state: {
          repeat: false,
        },
      },
    };
  }, [album]);
  return (
    <PlayerContext id="web-player" options={options}>
      <div className="flex gap-24 items-start h-full">
        <div className="flex-shrink-0 rounded bg-black overflow-hidden">
          <PlayerContainer playerRef={playerRef} />
        </div>
        <AlbumListItem
          album={album}
          maxHeight="h-full"
          hideArtwork
          hideActions
          linksInNewTab
        />
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
