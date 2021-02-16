<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@123',
            'userable_id' => '1',
            'userable_type' => 'admin',
            'password' => Hash::make('123456'),
        ]);
        DB::table('tags')->insert([
            'name'=>'tag1'
        ]);

        DB::table('tags')->insert([
            'name'=>'tag2'
        ]);

        DB::table('tags')->insert([
            'name'=>'tag3'
        ]);

        DB::table('tags')->insert([
            'name'=>'tag4'
        ]);

        DB::table('tags')->insert([
            'name'=>'tag5'
        ]);
    }
}
