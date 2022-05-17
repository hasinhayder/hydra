<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('roles')->truncate();
        Role::insert(
            [
                ['name' => 'Administrator', 'slug' => 'admin'],
                ['name' => 'User', 'slug' => 'user'],
                ['name' => 'Customer', 'slug' => 'customer'],
                ['name' => 'Editor', 'slug' => 'editor'],
                ['name' => 'All', 'slug' => '*'],
                ['name' => 'Super Admin', 'slug' => 'super-admin'],
            ]
        );
    }
}
