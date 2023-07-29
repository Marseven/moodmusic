import {
  SearchResponse,
  useSearchResults,
} from '@app/web-player/search/requests/use-search-results';
import {mainSearchModels} from '@app/web-player/search/search-autocomplete';
import {Link, useParams} from 'react-router-dom';
import {PageStatus} from '@common/http/page-status';
import React, {Fragment, ReactNode, useEffect, useMemo, useState} from 'react';
import {Tabs} from '@common/ui/tabs/tabs';
import {TabList} from '@common/ui/tabs/tab-list';
import {Tab} from '@common/ui/tabs/tab';
import {Trans} from '@common/i18n/trans';
import {TabPanel, TabPanels} from '@common/ui/tabs/tab-panels';
import {Track} from '@app/web-player/tracks/track';
import {TrackTable} from '@app/web-player/tracks/track-table/track-table';
import {KeyboardArrowRightIcon} from '@common/icons/material/KeyboardArrowRight';
import {ContentGrid} from '@app/web-player/playable-item/content-grid';
import {ArtistGridItem} from '@app/web-player/artists/artist-grid-item';
import {AlbumGridItem} from '@app/web-player/albums/album-grid-item';
import {PlaylistGridItem} from '@app/web-player/playlists/playlist-grid-item';
import {UserGridItem} from '@app/web-player/users/user-grid-item';
import {Artist} from '@app/web-player/artists/artist';
import {Album} from '@app/web-player/albums/album';
import {Playlist} from '@app/web-player/playlists/playlist';
import {User} from '@common/auth/user';
import {IllustratedMessage} from '@common/ui/images/illustrated-message';
import {SearchIcon} from '@common/icons/material/Search';
import {useSettings} from '@common/core/settings/use-settings';
import {UseQueryResult} from '@tanstack/react-query';
import {useIsMobileMediaQuery} from '@common/utils/hooks/is-mobile-media-query';
import {TextField} from '@common/ui/forms/input-field/text-field/text-field';
import {useTrans} from '@common/i18n/use-trans';
import {message} from '@common/i18n/message';
import {useNavigate} from '@common/utils/hooks/use-navigate';

export function SearchResultsPage() {
  const {searchQuery} = useParams();
  const query = useSearchResults({
    query: searchQuery,
    types: mainSearchModels,
    limit: 20,
  });

  return (
    <Fragment>
      <MobileSearchBar />
      <PageContent query={query} />
    </Fragment>
  );
}

function MobileSearchBar() {
  const isMobile = useIsMobileMediaQuery();
  const {searchQuery = ''} = useParams();
  const navigate = useNavigate();
  const {trans} = useTrans();
  if (!isMobile) {
    return null;
  }

  return (
    <TextField
      defaultValue={searchQuery}
      onChange={e => {
        navigate(`/search/${e.target.value}`, {replace: true});
      }}
      autoFocus
      className="w-full"
      size="lg"
      placeholder={trans(message('Search...'))}
    />
  );
}

interface PageContentProps {
  query: UseQueryResult<SearchResponse>;
}
function PageContent({query}: PageContentProps) {
  const {branding} = useSettings();

  if (query.data) {
    return <SearchResults results={query.data?.results} />;
  }

  if (query.fetchStatus === 'idle') {
    return (
      <IllustratedMessage
        className="mt-40"
        image={<SearchIcon size="xl" />}
        imageHeight="h-auto"
        imageMargin="mb-12"
        title={
          <Trans
            message="Search :siteName"
            values={{siteName: branding.site_name}}
          />
        }
        description={
          <Trans message="Find songs, artists, albums, playlists and more." />
        }
      />
    );
  }

  return <PageStatus query={query} loaderClassName="absolute inset-0 m-auto" />;
}

interface SearchResultsProps {
  results: SearchResponse['results'];
}
function SearchResults({results}: SearchResultsProps) {
  const {tabName = 'all', searchQuery} = useParams();
  const tabNames = useMemo(() => {
    const names = ['tracks', 'artists', 'albums', 'playlists', 'users'].filter(
      tabName => results[tabName as keyof SearchResponse['results']]?.length
    );
    return ['all', ...names];
  }, [results]);

  const tabIndex = tabNames.indexOf(tabName as any);

  const [selectedTab, setSelectedTab] = useState(tabIndex > -1 ? tabIndex : 0);

  // change tab when url changes
  useEffect(() => {
    if (tabIndex !== selectedTab) {
      setSelectedTab(tabIndex);
    }
  }, [tabIndex, selectedTab]);

  const tabLink = (tabName?: string) => {
    let base = `/search/${searchQuery}`;
    if (tabName) {
      base += `/${tabName}`;
    }
    return base;
  };

  const haveResults = Object.entries(results).some(
    ([, items]) => items?.length
  );

  if (!haveResults) {
    return (
      <IllustratedMessage
        className="mt-40"
        image={<SearchIcon size="xl" />}
        imageHeight="h-auto"
        title={
          <Trans
            message="Not results for “:query“"
            values={{query: searchQuery}}
          />
        }
        description={<Trans message="Please try a different search query" />}
      />
    );
  }

  return (
    <Tabs selectedTab={selectedTab} onTabChange={setSelectedTab}>
      <TabList>
        <Tab elementType={Link} to={tabLink()}>
          <Trans message="Top results" />
        </Tab>
        {results.tracks?.length ? (
          <Tab elementType={Link} to={tabLink('tracks')}>
            <Trans message="Tracks" />
          </Tab>
        ) : null}
        {results.artists?.length ? (
          <Tab elementType={Link} to={tabLink('artists')}>
            <Trans message="Artists" />
          </Tab>
        ) : null}
        {results.albums?.length ? (
          <Tab elementType={Link} to={tabLink('albums')}>
            <Trans message="Albums" />
          </Tab>
        ) : null}
        {results.playlists?.length ? (
          <Tab elementType={Link} to={tabLink('playlists')}>
            <Trans message="Playlists" />
          </Tab>
        ) : null}
        {results.users?.length ? (
          <Tab elementType={Link} to={tabLink('users')}>
            <Trans message="Profiles" />
          </Tab>
        ) : null}
      </TabList>
      <TabPanels className="pt-8">
        <TabPanel>
          <TopResultsPanel results={results} />
        </TabPanel>
        {results.tracks?.length ? (
          <TabPanel>
            <TrackResults tracks={results.tracks} />
          </TabPanel>
        ) : null}
        {results.artists?.length ? (
          <TabPanel>
            <ArtistResults artists={results.artists} />
          </TabPanel>
        ) : null}
        {results.albums?.length ? (
          <TabPanel>
            <AlbumResults albums={results.albums} />
          </TabPanel>
        ) : null}
        {results.playlists?.length ? (
          <TabPanel>
            <PlaylistResults playlists={results.playlists} />
          </TabPanel>
        ) : null}
        {results.users?.length ? (
          <TabPanel>
            <ProfileResults users={results.users} />
          </TabPanel>
        ) : null}
      </TabPanels>
    </Tabs>
  );
}

function TopResultsPanel({
  results: {artists, albums, tracks, playlists, users},
}: SearchResultsProps) {
  return (
    <Fragment>
      {tracks?.length ? (
        <TrackResults tracks={tracks.slice(0, 5)} showMore />
      ) : null}
      {artists?.length ? (
        <ArtistResults artists={artists.slice(0, 5)} showMore />
      ) : null}
      {albums?.length ? (
        <AlbumResults albums={albums.slice(0, 5)} showMore />
      ) : null}
      {playlists?.length ? (
        <PlaylistResults playlists={playlists.slice(0, 5)} showMore />
      ) : null}
      {users?.length ? (
        <ProfileResults users={users.slice(0, 5)} showMore />
      ) : null}
    </Fragment>
  );
}

interface TracksPanelProps {
  tracks: Track[];
  showMore?: boolean;
}
function TrackResults({tracks, showMore}: TracksPanelProps) {
  return (
    <div className="py-24">
      <PanelTitle to={showMore ? 'tracks' : undefined}>
        <Trans message="Tracks" />
      </PanelTitle>
      <TrackTable tracks={tracks} />
    </div>
  );
}

interface ArtistResultsProps {
  artists: Artist[];
  showMore?: boolean;
}
function ArtistResults({artists, showMore}: ArtistResultsProps) {
  return (
    <div className="py-24">
      <PanelTitle to={showMore ? 'artists' : undefined}>
        <Trans message="Artists" />
      </PanelTitle>
      <ContentGrid>
        {artists.map(artist => (
          <ArtistGridItem key={artist.id} artist={artist} />
        ))}
      </ContentGrid>
    </div>
  );
}

interface AlbumResultsProps {
  albums: Album[];
  showMore?: boolean;
}
function AlbumResults({albums, showMore}: AlbumResultsProps) {
  return (
    <div className="py-24">
      <PanelTitle to={showMore ? 'albums' : undefined}>
        <Trans message="Albums" />
      </PanelTitle>
      <ContentGrid>
        {albums.map(album => (
          <AlbumGridItem key={album.id} album={album} />
        ))}
      </ContentGrid>
    </div>
  );
}

interface PlaylistResultsProps {
  playlists: Playlist[];
  showMore?: boolean;
}
function PlaylistResults({playlists, showMore}: PlaylistResultsProps) {
  return (
    <div className="py-24">
      <PanelTitle to={showMore ? 'playlists' : undefined}>
        <Trans message="Playlists" />
      </PanelTitle>
      <ContentGrid>
        {playlists.map(album => (
          <PlaylistGridItem key={album.id} playlist={album} />
        ))}
      </ContentGrid>
    </div>
  );
}

interface ProfileResultsProps {
  users: User[];
  showMore?: boolean;
}
function ProfileResults({users, showMore}: ProfileResultsProps) {
  return (
    <div className="py-24">
      <PanelTitle to={showMore ? 'users' : undefined}>
        <Trans message="Profiles" />
      </PanelTitle>
      <ContentGrid>
        {users.map(user => (
          <UserGridItem key={user.id} user={user} />
        ))}
      </ContentGrid>
    </div>
  );
}

interface PanelTitleProps {
  children: ReactNode;
  to?: string;
}
function PanelTitle({children, to}: PanelTitleProps) {
  return (
    <h2 className="text-2xl font-medium mb-24 w-max">
      {to ? (
        <Link to={to} className="hover:text-primary flex items-center gap-2">
          {children}
          <KeyboardArrowRightIcon className="mt-4" />
        </Link>
      ) : (
        children
      )}
    </h2>
  );
}
