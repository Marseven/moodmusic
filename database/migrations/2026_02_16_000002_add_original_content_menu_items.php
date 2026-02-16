<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('menus')) {
            return;
        }

        $menu = DB::table('menus')->where('name', 'mobile-bottom')->first();
        if (!$menu) {
            return;
        }

        $items = json_decode($menu->items, true) ?: [];

        // Check if original content link already exists
        foreach ($items as $item) {
            if (isset($item['action']) && str_contains($item['action'], '/original')) {
                return;
            }
        }

        $items[] = [
            'label' => 'Création Originale',
            'action' => '/original',
            'type' => 'route',
            'icon' => [
                ['tag' => 'icon', 'name' => 'music'],
            ],
        ];

        DB::table('menus')
            ->where('id', $menu->id)
            ->update(['items' => json_encode($items)]);
    }

    public function down(): void
    {
        if (!Schema::hasTable('menus')) {
            return;
        }

        $menu = DB::table('menus')->where('name', 'mobile-bottom')->first();
        if (!$menu) {
            return;
        }

        $items = json_decode($menu->items, true) ?: [];
        $items = array_filter($items, function ($item) {
            return !isset($item['action']) || !str_contains($item['action'], '/original');
        });

        DB::table('menus')
            ->where('id', $menu->id)
            ->update(['items' => json_encode(array_values($items))]);
    }
};
