<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->truncate();
        UserRole::create([
            'user_id'=>1,
            'role_id'=>1
        ]); //admin
        UserRole::create([
            'user_id'=>1,
            'role_id'=>5
        ]);//all
    }
}
