<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Database\Seeders\PermissionTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionTableSeeder::class);

        // Eloquent::unguard();
        // $path = 'database/dumps/initial.sql';
        // \DB::unprepared(file_get_contents($path));
    }
}
