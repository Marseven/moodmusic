<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminMenuAdSpotsSeeder extends Seeder
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

        $adSpotsItem = [
            'id' => 'adSpTs',
            'label' => 'Ad Spots',
            'action' => '/admin/ad-spots',
            'type' => 'route',
            'target' => '_self',
            'icon' => [
                [
                    'tag' => 'path',
                    'attr' => [
                        'd' => 'M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4zM14 13h-3v3H9v-3H6v-2h3V8h2v3h3v2z',
                    ],
                ],
            ],
        ];

        $updated = false;

        foreach ($menus as &$menu) {
            if (!isset($menu['positions']) || !in_array('admin-sidebar', $menu['positions'])) {
                continue;
            }

            // Check if Ad Spots already exists
            foreach ($menu['items'] as $item) {
                if (isset($item['action']) && $item['action'] === '/admin/ad-spots') {
                    $this->command->info('Ad Spots menu item already exists, skipping.');
                    return;
                }
            }

            // Insert after "Purchases" item, or at the end
            $insertIndex = count($menu['items']);
            foreach ($menu['items'] as $index => $item) {
                if (isset($item['action']) && $item['action'] === '/admin/purchases') {
                    $insertIndex = $index + 1;
                    break;
                }
            }

            array_splice($menu['items'], $insertIndex, 0, [$adSpotsItem]);
            $updated = true;
            break;
        }
        unset($menu);

        if ($updated) {
            DB::table('settings')
                ->where('name', 'menus')
                ->update(['value' => json_encode($menus)]);
            $this->command->info('Ad Spots menu item added to admin sidebar.');
        } else {
            $this->command->info('Admin sidebar menu not found.');
        }
    }
}
