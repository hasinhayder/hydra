<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Schema::disableForeignKeyConstraints();
        DB::table('roles')->truncate();
        Schema::enableForeignKeyConstraints();

        $roles = [
            ['name' => 'Administrator', 'slug' => 'admin'],
            ['name' => 'User', 'slug' => 'user'],
            ['name' => 'Customer', 'slug' => 'customer'],
            ['name' => 'Editor', 'slug' => 'editor'],
            ['name' => 'All', 'slug' => '*'],
            ['name' => 'Super Admin', 'slug' => 'super-admin'],
        ];

        Role::insert(collect($roles));
    }
}
