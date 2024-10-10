<?php

use App\Model\Skin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->updateOrInsert(
            [
                'name' => 'superadmin',
                'email' => 'jvscript@yopmail.com',

            ],
            [
                'password' => bcrypt('superadmin'),
                'admin' => true
            ]
        );


        DB::table('skins')->updateOrInsert(
            [
                'slug'  => 'dark-mode-skin',
                'name'  => 'Dark Mode Skin',
            ],
            [

                'description' => 'A sleek and modern dark mode skin for users.',
                'autor'       => 'John Doe',
                'skin_url'    => 'https://userstyles.org/styles/78695/skin-jvc-rouge-noir-by-tiger',
                'repo_url'    => 'http://github.com/example/dark-mode-skin',
                'version'     => '1.0.0',
                'last_update' => '2023-10-01',
                'status'    => 1,
                'photo_url'   => null,
                'topic_url'   => 'http://www.jeuxvideo.com/forums/skin-topic',
                'website_url' => 'http://example.com/skins',
                'don_url'     => 'http://example.com/donate',
                'user_id'     => 1,  // Assuming a valid user ID exists in the users table
                'poster_user_id'     => 1,  // Assuming a valid user ID exists in the users table
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        );

        DB::table('scripts')->updateOrInsert(
            [
                'slug'  => 'dark-mode-script',
                'name'  => 'Dark Mode script',
            ],
            [

                'description' => 'A sleek and modern dark mode script for users.',
                'autor'       => 'John Doe',
                'js_url'      => 'https://userstyles.org/styles/78695/skin-jvc-rouge-noir-by-tiger.user.js',
                'repo_url'    => 'http://github.com/example/dark-mode-skin',
                'version'     => '1.0.0',
                'last_update' => '2023-10-01',
                'status'    => 1,
                // 'photo_url'   => 'http://example.com/images/dark-mode.png',
                'topic_url'   => 'http://www.jeuxvideo.com/forums/skin-topic',
                'website_url' => 'http://example.com/skins',
                'don_url'     => 'http://example.com/donate',
                'user_id'     => 1,  // Assuming a valid user ID exists in the users table
                'poster_user_id'     => 1,  // Assuming a valid user ID exists in the users table
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        );
    }
}
