<?php

namespace Database\Seeders;

use App\Models\Idea;
use App\Models\SystemSetting;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        SystemSetting::factory(1)->create();
        Idea::factory(5)->create();
    }
}
