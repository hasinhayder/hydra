<?php

namespace Database\Seeders;

use App\Models\Role
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        $user = User::create([
            'email'=>'admin@hydra.project',
            'password'=>Hash::make('hydra'),
            'name'=>'Hydra Admin'
        ]);
        $user->roles()->attach(Role::firstOrCreate([
            'slug' => config('hydra.default_user_role_slug', 'user')
        ],[
            'name' => Str::title(config('hydra.default_user_role_slug', 'user'))
        ]));
    }
}
