<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->truncate();
        Role::insert(
            [
                ['name' => 'Administrator', 'slug' => 'admin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'User', 'slug' => 'user', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Customer', 'slug' => 'customer', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Editor', 'slug' => 'editor', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'All', 'slug' => '*', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Super Admin', 'slug' => 'super-admin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]
        );
    }
}
