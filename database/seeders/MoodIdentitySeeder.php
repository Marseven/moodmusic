<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoodIdentitySeeder extends Seeder
{
    /**
     * Mood Identity restructuring: rename homepage channels
     * to Gabonese identity names.
     */
    public function run(): void
    {
        $renames = [
            'popular-albums' => 'Ça chauffe au Gabon',
            'popular-tracks' => 'Les titres du moment',
            'new-releases'   => "Fiers d'être Gaboma",
        ];

        foreach ($renames as $slug => $newName) {
            DB::table('channels')
                ->where('slug', $slug)
                ->update(['name' => $newName]);
        }
    }
}
