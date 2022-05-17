<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionsSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('options')->truncate();
        Option::create([
            'key' => 'single_session',
            'value' => '1'
        ]);
        Option::create([
            'key' => 'default_role_id',
            'value' => '2' //user
        ]);
    }
}
