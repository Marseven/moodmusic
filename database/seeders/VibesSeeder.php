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
                'emoji' => "\u{1F389}",
                'color' => '#E13300',
                'genre_slug' => 'afro-beat',
                'position' => 1,
            ],
            [
                'name' => 'embouteillage',
                'display_name' => 'Akanda / Owendo',
                'emoji' => "\u{1F697}",
                'color' => '#1E3264',
                'genre_slug' => 'hip-hop',
                'position' => 2,
            ],
            [
                'name' => 'douceur-bae',
                'display_name' => 'Douceur pour Bae',
                'emoji' => "\u{2764}\u{FE0F}",
                'color' => '#E8115B',
                'genre_slug' => 'r-b',
                'position' => 3,
            ],
            [
                'name' => 'sport',
                'display_name' => 'Force pour le Sport',
                'emoji' => "\u{1F3C3}",
                'color' => '#1DB954',
                'genre_slug' => 'hip-hop',
                'position' => 4,
            ],
            [
                'name' => 'eglise',
                'display_name' => "Direction l'Église",
                'emoji' => "\u{271D}\u{FE0F}",
                'color' => '#477D95',
                'genre_slug' => 'gospel',
                'position' => 5,
            ],
            [
                'name' => 'clashs',
                'display_name' => 'Le Coin des Clashs',
                'emoji' => "\u{1F525}",
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
