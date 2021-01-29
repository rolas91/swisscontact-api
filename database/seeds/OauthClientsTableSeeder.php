<?php

use Illuminate\Database\Seeder;

class OauthClientsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('oauth_clients')->delete();
        
        \DB::table('oauth_clients')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => NULL,
                'name' => 'Competencias Para Ganar Personal Access Client',
                'secret' => 'GuYVU18qnYOpnUMdt0C2fFSjdfi2WUtJ8oqKAVG7',
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
                'created_at' => '2019-07-29 18:20:11',
                'updated_at' => '2019-07-29 18:20:11',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => NULL,
                'name' => 'Competencias Para Ganar Password Grant Client',
                'secret' => 'RmjIKVjI8JFjfBwmX8ss8cyLfo2EqJoegHww2qx7',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
                'created_at' => '2019-07-29 18:20:11',
                'updated_at' => '2019-07-29 18:20:11',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => NULL,
                'name' => 'Competencias Para Ganar Personal Access Client',
                'secret' => 'J44gJZxjHw05r5QU6GRsMbQ3VGjij8VcgCaIsLPh',
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
                'created_at' => '2019-10-18 21:05:22',
                'updated_at' => '2019-10-18 21:05:22',
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => NULL,
                'name' => 'Competencias Para Ganar Password Grant Client ',
                'secret' => 'sgM4ieTwdSKHJIM0srIVbtfBXFxI5aoMAJs4bryt',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
                'created_at' => '2019-10-18 21:05:22',
                'updated_at' => '2019-10-18 21:05:22',
            ),
        ));
        
        
    }
}