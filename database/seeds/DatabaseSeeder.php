<?php

use Illuminate\Database\Seeder;

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
            'name' => 'superadmin',
            'email' => 'jvscript@yopmail.com',
            'password' => bcrypt('superadmin'),
            'admin' => true
        ]);

//        $this->call(UsersTableSeeder::class);
    }
}
