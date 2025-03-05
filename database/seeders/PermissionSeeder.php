<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            ['permission' => 'Create User'],
            ['permission' => 'View User'],
            ['permission' => 'Update User'],
            ['permission' => 'Create Role'],
            ['permission' => 'Update Role'],
            ['permission' => 'Delete Role'],
        ]);
    }

}
