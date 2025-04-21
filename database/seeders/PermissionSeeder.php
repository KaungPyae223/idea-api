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
            ['permission' => 'Update User'],
            ['permission' => 'Reset Password'],
            ['permission' => 'Manage Department'],
            ['permission' => 'View User Logs'],
            ['permission' => 'Manage System Setting'],
            ['permission' => 'Remove Idea'],
            ['permission' => 'Remove Comments'],
            ['permission' => 'Manage Category'],
            ['permission' => 'View Reports'],
            ['permission' => 'Create Idea'],
            ['permission' => 'Create Comment'],
            ['permission' => 'Banned User'],
            ['permission' => 'Hide Ideas']

        ]);
    }

}
