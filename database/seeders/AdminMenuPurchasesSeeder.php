<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminMenuPurchasesSeeder extends Seeder
{
    public function run()
    {
        $prefix = config('database.connections.mysql.prefix', '');
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

        $purchasesItem = [
            'id' => 'p8rChs',
            'label' => 'Purchases',
            'action' => '/admin/purchases',
            'type' => 'route',
            'target' => '_self',
            'icon' => [
                [
                    'tag' => 'path',
                    'attr' => [
                        'd' => 'M18 6h-2c0-2.21-1.79-4-4-4S8 3.79 8 6H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-6-2c1.1 0 2 .9 2 2h-4c0-1.1.9-2 2-2zm6 16H6V8h2v2c0 .55.45 1 1 1s1-.45 1-1V8h4v2c0 .55.45 1 1 1s1-.45 1-1V8h2v12z',
                    ],
                ],
            ],
        ];

        $updated = false;

        foreach ($menus as &$menu) {
            // Find the admin-sidebar menu
            if (!isset($menu['positions']) || !in_array('admin-sidebar', $menu['positions'])) {
                continue;
            }

            // Check if Purchases already exists
            foreach ($menu['items'] as $item) {
                if (isset($item['action']) && $item['action'] === '/admin/purchases') {
                    $this->command->info('Purchases menu item already exists, skipping.');
                    return;
                }
            }

            // Insert after "Comments" item, or at the end
            $insertIndex = count($menu['items']);
            foreach ($menu['items'] as $index => $item) {
                if (isset($item['action']) && $item['action'] === '/admin/comments') {
                    $insertIndex = $index + 1;
                    break;
                }
            }

            array_splice($menu['items'], $insertIndex, 0, [$purchasesItem]);
            $updated = true;
            break;
        }
        unset($menu);

        if ($updated) {
            DB::table('settings')
                ->where('name', 'menus')
                ->update(['value' => json_encode($menus)]);
            $this->command->info('Purchases menu item added to admin sidebar.');
        } else {
            $this->command->info('Admin sidebar menu not found.');
        }
    }
}
