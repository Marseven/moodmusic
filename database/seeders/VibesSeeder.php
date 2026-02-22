<?php

namespace Database\Seeders;

use App\Genre;
use App\Vibe;
use Illuminate\Database\Seeder;

class VibesSeeder extends Seeder
{
    public function run(): void
    {
        $vibes = [
            [
                'name' => 'ambiance-snack',
                'display_name' => 'Ambiance au Snack',
                'icon' => 'party-popper',
                'color' => '#E13300',
                'genre_slug' => 'afro-beat',
                'position' => 1,
            ],
            [
                'name' => 'embouteillage',
                'display_name' => 'Akanda / Owendo',
                'icon' => 'car',
                'color' => '#1E3264',
                'genre_slug' => 'hip-hop',
                'position' => 2,
            ],
            [
                'name' => 'douceur-bae',
                'display_name' => 'Douceur pour Bae',
                'icon' => 'heart',
                'color' => '#E8115B',
                'genre_slug' => 'r-b',
                'position' => 3,
            ],
            [
                'name' => 'sport',
                'display_name' => 'Force pour le Sport',
                'icon' => 'dumbbell',
                'color' => '#1DB954',
                'genre_slug' => 'hip-hop',
                'position' => 4,
            ],
            [
                'name' => 'eglise',
                'display_name' => "Direction l'Église",
                'icon' => 'church',
                'color' => '#477D95',
                'genre_slug' => 'gospel',
                'position' => 5,
            ],
            [
                'name' => 'clashs',
                'display_name' => 'Le Coin des Clashs',
                'icon' => 'flame',
                'color' => '#AF2896',
                'genre_slug' => 'rap',
                'position' => 6,
            ],
        ];

        foreach ($vibes as $vibeData) {
            $genreSlug = $vibeData['genre_slug'] ?? null;
            unset($vibeData['genre_slug']);

            if ($genreSlug) {
                $genre = Genre::where('name', $genreSlug)->first();
                $vibeData['genre_id'] = $genre?->id;
            }

            Vibe::updateOrCreate(
                ['name' => $vibeData['name']],
                $vibeData,
            );
        }
    }
}
