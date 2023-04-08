<?php

namespace Database\Seeders;

use App\Actions\Channel\UpdateChannelContent;
use App\Album;
use App\Artist;
use App\Channel;
use App\Genre;
use App\Track;
use Common\Settings\Settings;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DefaultChannelsSeeder extends Seeder
{
    public function run()
    {
        if (Channel::count() === 0) {
            $popularAlbums = Channel::create([
                'name' => 'Popular Albums',
                'slug' => 'popular-albums',
                'user_id' => 1,
                'config' => [
                    'contentType' => 'listAll',
                    'contentModel' => Album::MODEL_TYPE,
                    'contentOrder' => 'popularity:desc',
                    'layout' => 'grid',
                    'carouselWhenNested' => true,
                    'seoTitle' => 'Popular Albums',
                    'seoDescription' =>
                        'Most popular albums from hottest artists today.',
                ],
            ]);

            $newReleases = Channel::create([
                'name' => 'New Releases',
                'slug' => 'new-releases',
                'user_id' => 1,
                'config' => [
                    'contentType' => 'listAll',
                    'contentModel' => Album::MODEL_TYPE,
                    'contentOrder' => 'created_at:desc',
                    'layout' => 'grid',
                    'carouselWhenNested' => true,
                    'seoTitle' => 'Latest Releases',
                    'seoDescription' =>
                        'Browse and listen to newest releases from popular artists.',
                ],
            ]);

            $tracks = Channel::create([
                'name' => 'Popular Tracks',
                'slug' => 'popular-tracks',
                'user_id' => 1,
                'config' => [
                    'contentType' => 'listAll',
                    'contentModel' => Track::MODEL_TYPE,
                    'contentOrder' => 'popularity:desc',
                    'layout' => 'trackTable',
                    'seoTitle' => 'Popular Tracks',
                    'seoDescription' =>
                        'Global Top 50 chart of most popular songs.',
                ],
            ]);

            $mainGenreChannel = $this->seedGenreChannels();

            $discover = Channel::create([
                'name' => 'Discover',
                'slug' => 'discover',
                'user_id' => 1,
                'config' => [
                    'contentType' => 'manual',
                    'contentModel' => Channel::MODEL_TYPE,
                    'hideTitle' => true,
                    'contentOrder' => 'channelables.order|asc',
                    'layout' => 'grid',
                    'seoTitle' => '{{site_name}} - Listen to music for free',
                    'seoDescription' =>
                        'Find and listen to millions of songs, albums and artists, all completely free on {{SITE_NAME}}.',
                ],
            ]);

            DB::table('channelables')->insert([
                [
                    'channel_id' => $discover->id,
                    'channelable_type' => Channel::class,
                    'channelable_id' => $popularAlbums->id,
                    'order' => 1,
                ],
                [
                    'channel_id' => $discover->id,
                    'channelable_type' => Channel::class,
                    'channelable_id' => $tracks->id,
                    'order' => 2,
                ],
                [
                    'channel_id' => $discover->id,
                    'channelable_type' => Channel::class,
                    'channelable_id' => $newReleases->id,
                    'order' => 3,
                ],
                [
                    'channel_id' => $discover->id,
                    'channelable_type' => Channel::class,
                    'channelable_id' => $mainGenreChannel->id,
                    'order' => 4,
                ],
            ]);

            app(Settings::class)->save([
                'homepage.type' => 'channel',
                'homepage.value' => $discover->id,
            ]);

            collect([
                $newReleases,
                $tracks,
                $mainGenreChannel,
                $popularAlbums,
            ])->each(function (Channel $channel) {
                app(UpdateChannelContent::class)->execute($channel);
            });
        } else {
            // fix an issue with bad seeding in previous versions
            if (
                Channel::where('slug', 'genre-artists')->first() &&
                !Channel::where('slug', 'genre-tracks')->first()
            ) {
                Channel::where('slug', 'genre-artists')->update([
                    'slug' => 'genre',
                ]);
            }
        }
    }

    protected function seedGenreChannels(): Channel
    {
        $mainChannel = Channel::create([
            'name' => 'Genres',
            'slug' => 'genres',
            'user_id' => 1,
            'config' => [
                'contentType' => 'listAll',
                'contentModel' => Genre::MODEL_TYPE,
                'contentOrder' => 'popularity:desc',
                'layout' => 'grid',
                'seoTitle' => 'Popular Genres',
                'seo_description' =>
                    'Browse popular genres to discover new music.',
            ],
        ]);

        if (app(Settings::class)->get('artist_provider') !== 'spotify') {
            $parentChannel = Channel::create([
                'name' => '{{channel.genre.display_name}}',
                'slug' => 'genre',
                'user_id' => 1,
                'config' => [
                    'connectToGenreViaUrl' => true,
                    'lockSlug' => true,
                    'preventDeletion' => true,
                    'contentType' => 'manual',
                    'contentModel' => Channel::MODEL_TYPE,
                    'contentOrder' => 'channelables.order:asc',
                    'seoTitle' =>
                        '{{channel.genre.display_name}} - {{site_name}}',
                    'seoDescription' =>
                        'Popular {{channel.genre.display_name}} artists, albums and tracks.',
                ],
            ]);

            $genreArtists = $this->createGenreChildChannel(
                Artist::MODEL_TYPE,
                carouselWhenNested: true,
            );
            $genreAlbums = $this->createGenreChildChannel(
                Album::MODEL_TYPE,
                carouselWhenNested: true,
            );
            $genreTracks = $this->createGenreChildChannel(Track::MODEL_TYPE);
            DB::table('channelables')->insert([
                [
                    'channel_id' => $parentChannel->id,
                    'channelable_type' => Channel::class,
                    'channelable_id' => $genreArtists->id,
                    'order' => 1,
                ],
                [
                    'channel_id' => $parentChannel->id,
                    'channelable_type' => Channel::class,
                    'channelable_id' => $genreTracks->id,
                    'order' => 2,
                ],
                [
                    'channel_id' => $parentChannel->id,
                    'channelable_type' => Channel::class,
                    'channelable_id' => $genreAlbums->id,
                    'order' => 3,
                ],
            ]);
        } else {
            $this->createGenreChildChannel(Artist::MODEL_TYPE, nested: false);
        }

        return $mainChannel;
    }

    protected function createGenreChildChannel(
        string $modelType,
        $carouselWhenNested = false,
        $nested = true,
    ): Channel {
        $plural = Str::plural($modelType);
        $uppercase = Str::ucfirst($plural);

        return Channel::create([
            'name' => "{{channel.genre.display_name}} $uppercase",
            'slug' => $nested ? "genre-$plural" : 'genre',
            'user_id' => 1,
            'config' => [
                'connectToGenreViaUrl' => true,
                'lockSlug' => true,
                'preventDeletion' => true,
                'contentType' => 'listAll',
                'contentModel' => $modelType,
                'carouselWhenNested' => $carouselWhenNested,
                'contentOrder' => 'popularity:desc',
                'layout' =>
                    $modelType === Track::MODEL_TYPE ? 'trackTable' : 'grid',
                'seoTitle' => '{{channel.genre.display_name}} - {{site_name}}',
                'seoDescription' => "Popular {{channel.genre.display_name}} $plural.",
            ],
        ]);
    }
}
