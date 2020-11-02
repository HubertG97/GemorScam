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
        $this->call(RoleSeeder1::class);
        $this->call(RoleSeeder2::class);
        $this->call(RoleSeeder3::class);

    }
}
