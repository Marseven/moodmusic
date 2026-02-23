<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MoodIdentitySeeder extends Seeder
{
    /**
     * Mood Identity restructuring: rename homepage channels
     * and sidenav menu items to Gabonese identity names.
     */
    public function run(): void
    {
        $this->renameChannels();
        $this->renameMenuItems();

        // Clear the settings cache so changes take effect immediately
        Cache::forget('settings.public');
    }

    private function renameChannels(): void
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

    private function renameMenuItems(): void
    {
        $row = DB::table('settings')->where('name', 'menus')->first();
        if (!$row) return;

        $menus = json_decode($row->value, true);
        if (!is_array($menus)) return;

        // Map route actions to Gabonese labels
        $labelsByAction = [
            '/popular-albums' => 'Ça chauffe au Gabon',
            '/popular-tracks' => 'Les titres du moment',
            '/new-releases'   => "Fiers d'être Gaboma",
        ];

        $changed = false;
        foreach ($menus as &$menu) {
            foreach ($menu['items'] as &$item) {
                $action = $item['action'] ?? null;
                if ($action && isset($labelsByAction[$action])) {
                    $item['label'] = $labelsByAction[$action];
                    $changed = true;
                }
            }
        }

        if ($changed) {
            DB::table('settings')
                ->where('name', 'menus')
                ->update(['value' => json_encode($menus, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
        }
    }
}
