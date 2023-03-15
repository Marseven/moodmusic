<?php

use Illuminate\Database\Seeder;

class MetatagsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('metatags')->delete();
        
        \DB::table('metatags')->insert(array (
            0 => 
            array (
                'id' => 1,
                'priority' => 22,
                'url' => '/*',
                'info' => 'User profile',
                'page_keywords' => NULL,
                'page_title' => '{{name}} music collection and more.',
                'page_description' => 'Check out {{name}} music collection and more.',
                'auto_keyword' => 0,
                'created_at' => NULL,
                'updated_at' => '2020-09-14 03:13:07',
            ),
            1 => 
            array (
                'id' => 2,
                'priority' => 17,
                'url' => '/trending/month',
                'info' => 'title trending month',
                'page_keywords' => '',
                'page_title' => 'Top Songs This Month',
                'page_description' => 'Trending music chart ranks the most popular songs of the month based on streaming activity.',
                'auto_keyword' => 0,
                'created_at' => NULL,
                'updated_at' => '2020-09-14 03:23:12',
            ),
            2 => 
            array (
                'id' => 4,
                'priority' => 20,
                'url' => '/*/playlists/subscribers',
                'info' => 'User subscribed page',
                'page_keywords' => '',
                'page_title' => '{{name}}\'s subscribed playlist',
                'page_description' => 'Check out all playlists subscribed by {{name}}',
                'auto_keyword' => 0,
                'created_at' => NULL,
                'updated_at' => '2020-09-14 03:13:07',
            ),
            3 => 
            array (
                'id' => 5,
                'priority' => 19,
                'url' => '/discover',
                'info' => 'Discover page',
                'page_keywords' => '',
                'page_title' => 'Discover new music',
                'page_description' => 'Discover new bands and artists, find out all about the music and the people behind it.',
                'auto_keyword' => 0,
                'created_at' => NULL,
                'updated_at' => '2020-09-14 03:17:04',
            ),
            4 => 
            array (
                'id' => 6,
                'priority' => 16,
                'url' => '/trending/week',
                'info' => 'Trending week page',
                'page_keywords' => '',
                'page_title' => 'Top Songs This Week',
                'page_description' => 'Trending music chart ranks the most popular songs of the week based on streaming activity.',
                'auto_keyword' => 0,
                'created_at' => NULL,
                'updated_at' => '2020-09-14 03:23:28',
            ),
            5 => 
            array (
                'id' => 7,
                'priority' => 18,
                'url' => '/trending',
                'info' => 'Trending page',
                'page_keywords' => '',
                'page_title' => 'Top Songs Today',
                'page_description' => 'Trending music chart ranks the most popular songs of the day based on streaming activity.',
                'auto_keyword' => 0,
                'created_at' => NULL,
                'updated_at' => '2020-09-14 03:22:48',
            ),
            6 => 
            array (
                'id' => 8,
                'priority' => 21,
                'url' => '/*/playlists',
                'info' => 'User playlists page',
                'page_keywords' => '',
                'page_title' => 'Check out the latest Playlists from {{name}}',
                'page_description' => 'Check out the latest Playlists from {{name}} and more...',
                'auto_keyword' => 0,
                'created_at' => NULL,
                'updated_at' => '2020-09-14 03:13:07',
            ),
            7 => 
            array (
                'id' => 10,
                'priority' => 10,
                'url' => '/song/*',
                'info' => 'Song page',
                'page_keywords' => '',
                'page_title' => '{{title}} by {{artist}}',
                'page_description' => 'Listen to {{title}} by {{artist}} for free, and see the artwork, lyrics and similar artists.',
                'auto_keyword' => 0,
                'created_at' => NULL,
                'updated_at' => '2020-09-14 03:13:07',
            ),
            8 => 
            array (
                'id' => 11,
                'priority' => 15,
                'url' => '/artist/*',
                'info' => 'Artist page',
                'page_keywords' => '',
                'page_title' => '{{name}} on MusicEngine',
                'page_description' => 'Listen to music from {{name}} & more. Find the latest tracks, albums, and images on MusicEngine',
                'auto_keyword' => 0,
                'created_at' => NULL,
                'updated_at' => '2020-09-14 03:13:07',
            ),
            9 => 
            array (
                'id' => 12,
                'priority' => 9,
                'url' => '/search/song?q=*',
                'info' => 'Search song page',
                'page_keywords' => NULL,
                'page_title' => 'Search song for {{term}}',
                'page_description' => 'awagawgawgwg',
                'auto_keyword' => 0,
                'created_at' => NULL,
                'updated_at' => '2020-09-14 03:13:07',
            ),
            10 => 
            array (
                'id' => 13,
                'priority' => 12,
                'url' => '/artist/*/similar-artists',
                'info' => 'Similar artists',
                'page_keywords' => NULL,
                'page_title' => 'Similar artists - {{name}}',
                'page_description' => 'Find similar artists to {{name}} and discover new music. Scrobble songs to get recommendations on tracks, albums, and artists you\'ll love.',
                'auto_keyword' => 0,
                'created_at' => NULL,
                'updated_at' => '2020-09-14 03:13:07',
            ),
            11 => 
            array (
                'id' => 14,
                'priority' => 11,
                'url' => '/album/*',
                'info' => 'Album page',
                'page_keywords' => '',
                'page_title' => '{{title}} by {{artist}}',
                'page_description' => 'Listen free to {{title}} - {{artist}}. Discover more music, concerts, videos, and pictures with the largest catalogue online.',
                'auto_keyword' => 0,
                'created_at' => NULL,
                'updated_at' => '2020-09-14 03:13:07',
            ),
            12 => 
            array (
                'id' => 15,
                'priority' => 13,
                'url' => '/artist/*/albums',
                'info' => 'Artist albums page',
                'page_keywords' => NULL,
                'page_title' => '{{name}} albums and discography',
                'page_description' => 'Listen to music from {{name}}. Find the latest tracks, albums, and images from {{name}}.',
                'auto_keyword' => 0,
                'created_at' => NULL,
                'updated_at' => '2020-09-14 03:13:07',
            ),
            13 => 
            array (
                'id' => 16,
                'priority' => 14,
                'url' => '/artist/*/events',
                'info' => 'Artist events page',
                'page_keywords' => NULL,
                'page_title' => '{{name}} tours, tickets, shows',
                'page_description' => 'Find {{name}} tour dates, {{name}} tickets, concerts, and gigs, as well as other events you\'ll be interested in',
                'auto_keyword' => 0,
                'created_at' => NULL,
                'updated_at' => '2020-09-14 03:13:07',
            ),
            14 => 
            array (
                'id' => 17,
                'priority' => 23,
                'url' => '/',
                'info' => 'Homepage',
                'page_keywords' => 'music,streaming,networking,social,share music',
                'page_title' => 'Music Social Network - Discover New Music',
                'page_description' => 'Music social network for music lovers, artists, bands, musicians and more! Connect with fans, discover new music, and share the music you love with your friends.',
                'auto_keyword' => 0,
                'created_at' => '2020-07-06 00:23:17',
                'updated_at' => '2020-09-14 03:13:07',
            ),
            15 => 
            array (
                'id' => 18,
                'priority' => 8,
                'url' => '/playlist/*/*',
                'info' => 'Playlist page',
                'page_keywords' => '',
                'page_title' => '{{title}} playlist by {{user}} - Listen now on MusicEngine',
                'page_description' => '{{title}} playlist by {{user}} on MusicEngine',
                'auto_keyword' => 0,
                'created_at' => '2020-09-14 02:30:42',
                'updated_at' => '2020-09-14 03:13:07',
            ),
            16 => 
            array (
                'id' => 19,
                'priority' => 7,
                'url' => '/radio',
                'info' => 'Radio page',
                'page_keywords' => '',
                'page_title' => 'Free Internet Radio | Live News, Sports, Music, and Podcasts',
                'page_description' => 'Listen to free internet radio, news, sports, music, and podcasts. Stream live CNN, FOX News Radio, and MSNBC.',
                'auto_keyword' => 0,
                'created_at' => '2020-09-14 03:04:00',
                'updated_at' => '2020-09-14 03:13:07',
            ),
            17 => 
            array (
                'id' => 20,
                'priority' => 1,
                'url' => '/radio/regions',
                'info' => 'Radio By Location',
                'page_keywords' => 'radio,online,talk,music',
                'page_title' => 'Stream Radio By Location',
                'page_description' => 'Stream Radio By Location free online.',
                'auto_keyword' => 0,
                'created_at' => '2020-09-14 03:06:13',
                'updated_at' => '2020-09-14 03:13:07',
            ),
            18 => 
            array (
                'id' => 21,
                'priority' => 2,
                'url' => '/radio/region/*',
                'info' => 'Radio by continent',
                'page_keywords' => '',
                'page_title' => 'Stream Radio from {{name}}',
                'page_description' => 'Stream Radio from {{name}} free online.',
                'auto_keyword' => 0,
                'created_at' => '2020-09-14 03:07:32',
                'updated_at' => '2020-09-14 03:13:07',
            ),
            19 => 
            array (
                'id' => 22,
                'priority' => 3,
                'url' => '/radio/languages',
                'info' => 'Radio By Language',
                'page_keywords' => '',
                'page_title' => 'Stream Radio By Language',
                'page_description' => 'Stream Radio By Language free online.',
                'auto_keyword' => 0,
                'created_at' => '2020-09-14 03:09:31',
                'updated_at' => '2020-09-14 03:13:07',
            ),
            20 => 
            array (
                'id' => 23,
                'priority' => 4,
                'url' => '/radio/language/*',
                'info' => 'Stream Radio in {{name}}',
                'page_keywords' => '',
                'page_title' => 'Stream Radio in {{name}} free online.',
                'page_description' => NULL,
                'auto_keyword' => 0,
                'created_at' => '2020-09-14 03:10:51',
                'updated_at' => '2020-09-14 03:13:07',
            ),
            21 => 
            array (
                'id' => 24,
                'priority' => 5,
                'url' => '/radio/countries',
                'info' => 'By countries',
                'page_keywords' => '',
                'page_title' => 'By countries',
                'page_description' => NULL,
                'auto_keyword' => 0,
                'created_at' => '2020-09-14 03:11:39',
                'updated_at' => '2020-09-14 03:13:07',
            ),
            22 => 
            array (
                'id' => 25,
                'priority' => 6,
                'url' => '/radio/country/*',
                'info' => 'By country',
                'page_keywords' => '',
                'page_title' => 'Stream Radio from {{name}}',
                'page_description' => 'Stream Radio from {{name}} free online.',
                'auto_keyword' => 0,
                'created_at' => '2020-09-14 03:12:53',
                'updated_at' => '2020-09-14 03:13:07',
            ),
            23 => 
            array (
                'id' => 26,
                'priority' => NULL,
                'url' => '/station/*/*',
                'info' => 'Station page',
                'page_keywords' => '',
                'page_title' => 'Listen to {{title}} Radio on MusicEngine',
                'page_description' => 'Listen to {{title}} Radio on MusicEngine. Stream by location and language.',
                'auto_keyword' => 0,
                'created_at' => '2020-09-14 03:15:18',
                'updated_at' => '2020-09-14 03:15:18',
            ),
            24 => 
            array (
                'id' => 27,
                'priority' => NULL,
                'url' => '/community',
                'info' => 'Community page',
                'page_keywords' => '',
                'page_title' => 'Community page',
                'page_description' => 'Community page',
                'auto_keyword' => 0,
                'created_at' => '2020-09-14 03:16:39',
                'updated_at' => '2020-09-14 03:16:39',
            ),
            25 => 
            array (
                'id' => 28,
                'priority' => NULL,
                'url' => '/blog',
                'info' => 'Blog page',
                'page_keywords' => '',
                'page_title' => 'Music Blog - Music Discovery, Music Trends, and More!',
                'page_description' => 'Read our latest articles on music related topics such as the music industry, new trends, and music discovery.',
                'auto_keyword' => 0,
                'created_at' => '2020-09-14 03:17:54',
                'updated_at' => '2020-09-14 03:17:54',
            ),
            26 =>
                array (
                    'id' => 29,
                    'priority' => NULL,
                    'url' => '/podcast/*/*',
                    'info' => 'Podcast show page',
                    'page_keywords' => '',
                    'page_title' => 'Listen to the show {{title}} by {{artist}}',
                    'page_description' => 'Listen to the show {{title}} by {{artist}}',
                    'auto_keyword' => 0,
                    'created_at' => '2020-09-14 03:17:54',
                    'updated_at' => '2020-09-14 03:17:54',
                ),
            27 =>
                array (
                    'id' => 30,
                    'priority' => NULL,
                    'url' => '/podcast/*/*/episode/*',
                    'info' => 'Podcast\'s episode page',
                    'page_keywords' => '',
                    'page_title' => 'Listen to {{podcast}}\'s episode {{title}} by {{artist}}',
                    'page_description' => 'Listen to {{podcast}}\'s episode {{title}} by {{artist}}',
                    'auto_keyword' => 0,
                    'created_at' => '2020-09-14 03:17:54',
                    'updated_at' => '2020-09-14 03:17:54',
                ),
        ));
        
        
    }
}