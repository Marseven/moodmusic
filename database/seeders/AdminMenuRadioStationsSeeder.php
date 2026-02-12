<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminMenuRadioStationsSeeder extends Seeder
{
    public function run()
    {
        $row = DB::table('settings')->where('name', 'menus')->first();

        if (!$row) {
            $this->command->info('No menus setting found, skipping.');
            return;
        }

        $menus = json_decode($row->value, true);
        if (!is_array($menus)) {
            $this->command->info('Menus value is not valid JSON, skipping.');
            return;
        }

        // Lucide "Antenna" icon SVG paths
        $radioStationsItem = [
            'id' => 'rdStns',
            'label' => 'Stations Radio',
            'action' => '/admin/radio-stations',
            'type' => 'route',
            'target' => '_self',
            'icon' => [
                [
                    'tag' => 'path',
                    'attr' => [
                        'd' => 'M2 12 7 2',
                    ],
                ],
                [
                    'tag' => 'path',
                    'attr' => [
                        'd' => 'M22 12 17 2',
                    ],
                ],
                [
                    'tag' => 'path',
                    'attr' => [
                        'd' => 'M7 20v-6a5 5 0 0 1 10 0v6',
                    ],
                ],
                [
                    'tag' => 'path',
                    'attr' => [
                        'd' => 'M12 14v8',
                    ],
                ],
            ],
        ];

        $updated = false;

        foreach ($menus as &$menu) {
            if (!isset($menu['positions']) || !in_array('admin-sidebar', $menu['positions'])) {
                continue;
            }

            // Check if Radio Stations already exists
            foreach ($menu['items'] as $item) {
                if (isset($item['action']) && $item['action'] === '/admin/radio-stations') {
                    $this->command->info('Radio Stations menu item already exists, skipping.');
                    return;
                }
            }

            // Insert after "Ad Spots" item, or at the end
            $insertIndex = count($menu['items']);
            foreach ($menu['items'] as $index => $item) {
                if (isset($item['action']) && $item['action'] === '/admin/ad-spots') {
                    $insertIndex = $index + 1;
                    break;
                }
            }

            array_splice($menu['items'], $insertIndex, 0, [$radioStationsItem]);
            $updated = true;
            break;
        }
        unset($menu);

        if ($updated) {
            DB::table('settings')
                ->where('name', 'menus')
                ->update(['value' => json_encode($menus)]);
            $this->command->info('Radio Stations menu item added to admin sidebar.');
        } else {
            $this->command->info('Admin sidebar menu not found.');
        }
    }
}
