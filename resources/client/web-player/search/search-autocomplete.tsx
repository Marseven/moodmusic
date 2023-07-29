import {SearchIcon} from '@common/icons/material/Search';
import {message} from '@common/i18n/message';
import {Item} from '@common/ui/forms/listbox/item';
import {ComboBox} from '@common/ui/forms/combobox/combobox';
import React, {cloneElement, ReactElement, useState} from 'react';
import {useTrans} from '@common/i18n/use-trans';
import {useSearchResults} from '@app/web-player/search/requests/use-search-results';
import {ARTIST_MODEL} from '@app/web-player/artists/artist';
import {ALBUM_MODEL} from '@app/web-player/albums/album';
import {TRACK_MODEL} from '@app/web-player/tracks/track';
import {USER_MODEL} from '@common/auth/user';
import {PLAYLIST_MODEL} from '@app/web-player/playlists/playlist';
import {Section} from '@common/ui/forms/listbox/section';
import {Trans} from '@common/i18n/trans';
import {SmallArtistImage} from '@app/web-player/artists/artist-image/small-artist-image';
import {ArtistLinks} from '@app/web-player/artists/artist-links';
import {TrackImage} from '@app/web-player/tracks/track-image/track-image';
import {
  getUserProfileLink,
  UserProfileLink,
} from '@app/web-player/users/user-profile-link';
import {AlbumLink, getAlbumLink} from '@app/web-player/albums/album-link';
import {ArtistLink, getArtistLink} from '@app/web-player/artists/artist-link';
import {getTrackLink, TrackLink} from '@app/web-player/tracks/track-link';
import {UserImage} from '@app/web-player/users/user-image';
import {PlaybackToggleButton} from '@app/web-player/playable-item/playback-toggle-button';
import {PlayableModel} from '@app/web-player/playable-item/playable-model';
import clsx from 'clsx';
import {queueGroupId} from '@app/web-player/queue-group-id';
import {usePlayerStore} from '@common/player/hooks/use-player-store';
import {AlbumImage} from '@app/web-player/albums/album-image/album-image';
import {useNavigate} from '@common/utils/hooks/use-navigate';
import {
  getPlaylistLink,
  PlaylistLink,
} from '@app/web-player/playlists/playlist-link';
import {PlaylistImage} from '@app/web-player/playlists/playlist-image';
import {PlaylistOwnerName} from '@app/web-player/playlists/playlist-grid-item';
import {useListboxContext} from '@common/ui/forms/listbox/listbox-context';
import {AnimatePresence, m} from 'framer-motion';
import {opacityAnimation} from '@common/ui/animation/opacity-animation';
import {useParams} from 'react-router-dom';
import {DialogTrigger} from '@common/ui/overlays/dialog/dialog-trigger';
import {ArtistContextDialog} from '@app/web-player/artists/artist-context-dialog';
import {AlbumContextDialog} from '@app/web-player/albums/album-context-dialog';
import {TrackContextDialog} from '@app/web-player/tracks/context-dialog/track-context-dialog';
import {PlaylistContextDialog} from '@app/web-player/playlists/playlist-context-dialog';

export const mainSearchModels = [
  ARTIST_MODEL,
  ALBUM_MODEL,
  TRACK_MODEL,
  USER_MODEL,
  PLAYLIST_MODEL,
];

interface SearchAutocompleteProps {
  className?: string;
}
export function SearchAutocomplete({className}: SearchAutocompleteProps) {
  const {searchQuery} = useParams();
  const {trans} = useTrans();
  const navigate = useNavigate();
  const [query, setQuery] = useState(searchQuery || '');
  const [isOpen, setIsOpen] = useState(false);
  const {isFetching, data} = useSearchResults({
    query,
    types: mainSearchModels,
    limit: 3,
  });

  return (
    <form
      onSubmit={e => {
        e.preventDefault();
        if (query.trim().length) {
          setIsOpen(false);
          navigate(`/search/${query.trim()}`);
        }
      }}
      className={clsx('flex items-center gap-14 flex-auto', className)}
    >
      <button type="submit" aria-label={trans(message('Search'))}>
        <SearchIcon className="text-muted flex-shrink-0" />
      </button>
      <ComboBox
        unstyled
        className="w-full max-w-780 flex-auto"
        offset={12}
        inputClassName="w-full outline-none bg-transparent h-42 placeholder:text-muted"
        hideEndAdornment
        inputBorder="border-none"
        isAsync
        placeholder={trans(message('Search'))}
        isLoading={isFetching}
        inputValue={query}
        onInputValueChange={setQuery}
        clearInputOnItemSelection
        blurReferenceOnItemSelection
        selectionMode="none"
        openMenuOnFocus
        floatingMaxHeight={670}
        isOpen={isOpen}
        onOpenChange={setIsOpen}
      >
        {Object.entries(data?.results || {}).map(([groupName, results]) => (
          <Section key={groupName} label={<Trans message={groupName} />}>
            {results.map(result => {
              const key = `${groupName}-${result.id}`;
              switch (result.model_type) {
                case ARTIST_MODEL:
                  return (
                    <Item
                      key={key}
                      value={key}
                      onSelected={() => {
                        navigate(getArtistLink(result));
                      }}
                      startIcon={
                        <PlayableImage
                          model={result}
                          className="rounded-full"
                          value={key}
                        >
                          <SmallArtistImage artist={result} />
                        </PlayableImage>
                      }
                      description={<Trans message="Artist" />}
                      textLabel={result.name}
                    >
                      <DialogTrigger type="popover" triggerOnContextMenu>
                        <div>
                          <ArtistLink artist={result} />
                        </div>
                        <ArtistContextDialog artist={result} />
                      </DialogTrigger>
                    </Item>
                  );
                case ALBUM_MODEL:
                  return (
                    <Item
                      key={key}
                      value={key}
                      onSelected={() => {
                        navigate(getAlbumLink(result));
                      }}
                      startIcon={
                        <PlayableImage model={result} value={key}>
                          <AlbumImage album={result} />
                        </PlayableImage>
                      }
                      description={<ArtistLinks artists={result.artists} />}
                      textLabel={result.name}
                    >
                      <DialogTrigger type="popover" triggerOnContextMenu>
                        <div>
                          <AlbumLink album={result} />
                        </div>
                        <AlbumContextDialog album={result} />
                      </DialogTrigger>
                    </Item>
                  );
                case TRACK_MODEL:
                  return (
                    <Item
                      key={key}
                      value={key}
                      onSelected={() => {
                        navigate(getTrackLink(result));
                      }}
                      startIcon={
                        <PlayableImage model={result} value={key}>
                          <TrackImage track={result} />
                        </PlayableImage>
                      }
                      description={<ArtistLinks artists={result.artists} />}
                      textLabel={result.name}
                    >
                      <DialogTrigger type="popover" triggerOnContextMenu>
                        <div>
                          <TrackLink track={result} />
                        </div>
                        <TrackContextDialog tracks={[result]} />
                      </DialogTrigger>
                    </Item>
                  );
                case USER_MODEL:
                  return (
                    <Item
                      key={key}
                      value={key}
                      onSelected={() => {
                        navigate(getUserProfileLink(result));
                      }}
                      startIcon={
                        <UserImage className="w-48 h-48" user={result} />
                      }
                      description={
                        result.followers_count ? (
                          <Trans
                            message=":count followers"
                            values={{count: result.followers_count}}
                          />
                        ) : null
                      }
                      textLabel={result.display_name}
                    >
                      <UserProfileLink user={result} />
                    </Item>
                  );
                case PLAYLIST_MODEL:
                  return (
                    <Item
                      key={key}
                      value={key}
                      onSelected={() => {
                        navigate(getPlaylistLink(result));
                      }}
                      startIcon={
                        <PlayableImage model={result} value={key}>
                          <PlaylistImage playlist={result} />
                        </PlayableImage>
                      }
                      description={<PlaylistOwnerName playlist={result} />}
                      textLabel={result.name}
                    >
                      <DialogTrigger type="popover" triggerOnContextMenu>
                        <div>
                          <PlaylistLink playlist={result} />
                        </div>
                        <PlaylistContextDialog playlist={result} />
                      </DialogTrigger>
                    </Item>
                  );
              }
            })}
          </Section>
        ))}
      </ComboBox>
    </form>
  );
}

interface PlayableImageProps {
  children: ReactElement<{size: string; className?: string}>;
  model: PlayableModel;
  className?: string;
  value: string;
}
function PlayableImage({
  children,
  model,
  className,
  value,
}: PlayableImageProps) {
  const {
    collection,
    state: {activeIndex},
  } = useListboxContext();
  const index = collection.get(value)?.index;
  const isActive = activeIndex === index;

  const queueId = queueGroupId(model);
  const isPlaying = usePlayerStore(
    s => s.isPlaying && s.originalQueue[0]?.groupId === queueId
  );

  return (
    <div
      className={clsx(className, 'relative w-48 h-48 overflow-hidden')}
      onClick={e => {
        e.preventDefault();
        e.stopPropagation();
      }}
    >
      {cloneElement(children, {
        size: 'w-full h-full',
      })}
      <AnimatePresence>
        {isActive || isPlaying ? (
          <m.div
            key="play-overlay"
            {...opacityAnimation}
            transition={{duration: 0.24}}
            className="absolute w-full h-full inset-0 bg-black/60 m-auto flex items-center justify-center"
          >
            <PlaybackToggleButton
              buttonType="icon"
              color="white"
              equalizerColor="white"
              track={model.model_type === TRACK_MODEL ? model : undefined}
              queueId={queueId}
            />
          </m.div>
        ) : null}
      </AnimatePresence>
    </div>
  );
}
