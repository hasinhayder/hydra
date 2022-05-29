<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('user_roles')->truncate();
        Schema::enableForeignKeyConstraints();

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
