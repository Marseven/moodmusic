<?php

namespace Database\Seeders;

use App\Channel;
use App\Playlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopularPlaylistsChannelSeeder extends Seeder
{
    /**
     * Create a "Playlists populaires" channel and insert it
     * into the discover channel after "Fiers d'être Gaboma" (new-releases).
     */
    public function run(): void
    {
        // Skip if already exists
        if (Channel::where('slug', 'popular-playlists')->exists()) {
            $this->command->info('Channel popular-playlists already exists, skipping.');
            return;
        }

        // Create the playlists channel
        $playlistsChannel = Channel::create([
            'name' => 'Playlists populaires',
            'slug' => 'popular-playlists',
            'user_id' => 1,
            'config' => [
                'contentType' => 'listAll',
                'contentModel' => Playlist::MODEL_TYPE,
                'contentOrder' => 'popularity:desc',
                'layout' => 'grid',
                'carouselWhenNested' => true,
                'seoTitle' => 'Playlists populaires',
                'seoDescription' => 'Les playlists les plus écoutées sur Mood Music.',
            ],
        ]);

        // Find the discover channel
        $discover = Channel::where('slug', 'discover')->first();
        if (!$discover) {
            $this->command->warn('Discover channel not found, cannot insert popular-playlists.');
            return;
        }

        // Find new-releases order to insert after it
        $newReleasesRow = DB::table('channelables')
            ->where('channel_id', $discover->id)
            ->where('channelable_type', Channel::class)
            ->whereIn('channelable_id', function ($q) {
                $q->select('id')->from('channels')->where('slug', 'new-releases');
            })
            ->first();

        $insertOrder = $newReleasesRow ? $newReleasesRow->order + 1 : 4;

        // Push down any channels at or after that order
        DB::table('channelables')
            ->where('channel_id', $discover->id)
            ->where('order', '>=', $insertOrder)
            ->increment('order');

        // Insert the playlists channel
        DB::table('channelables')->insert([
            'channel_id' => $discover->id,
            'channelable_type' => Channel::class,
            'channelable_id' => $playlistsChannel->id,
            'order' => $insertOrder,
        ]);

        $this->command->info("Channel 'Playlists populaires' created and inserted at order {$insertOrder} in discover.");
    }
}
