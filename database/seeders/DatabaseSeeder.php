<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(EmailsTableSeeder::class);
        $this->call(MetatagsTableSeeder::class);
        $this->call(PagesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(BannersTableSeeder::class);
    }
}
