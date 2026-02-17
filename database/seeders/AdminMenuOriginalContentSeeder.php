<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminMenuOriginalContentSeeder extends Seeder
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

        // Lucide "Sparkles" icon SVG paths
        $originalContentItem = [
            'id' => 'origCnt',
            'label' => 'Création Originale',
            'action' => '/admin/original-content-categories',
            'type' => 'route',
            'target' => '_self',
            'icon' => [
                [
                    'tag' => 'path',
                    'attr' => [
                        'd' => 'M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z',
                    ],
                ],
                [
                    'tag' => 'path',
                    'attr' => [
                        'd' => 'M20 2v4',
                    ],
                ],
                [
                    'tag' => 'path',
                    'attr' => [
                        'd' => 'M22 4h-4',
                    ],
                ],
            ],
        ];

        $updated = false;

        foreach ($menus as &$menu) {
            if (!isset($menu['positions']) || !in_array('admin-sidebar', $menu['positions'])) {
                continue;
            }

            // Check if item already exists
            foreach ($menu['items'] as $item) {
                if (isset($item['action']) && $item['action'] === '/admin/original-content-categories') {
                    $this->command->info('Original Content menu item already exists, skipping.');
                    return;
                }
            }

            // Insert after "Radio Stations" item, or at the end
            $insertIndex = count($menu['items']);
            foreach ($menu['items'] as $index => $item) {
                if (isset($item['action']) && $item['action'] === '/admin/radio-stations') {
                    $insertIndex = $index + 1;
                    break;
                }
            }

            array_splice($menu['items'], $insertIndex, 0, [$originalContentItem]);
            $updated = true;
            break;
        }
        unset($menu);

        if ($updated) {
            DB::table('settings')
                ->where('name', 'menus')
                ->update(['value' => json_encode($menus)]);
            $this->command->info('Original Content menu item added to admin sidebar.');
        } else {
            $this->command->info('Admin sidebar menu not found.');
        }
    }
}
