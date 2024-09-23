<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Driver;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function run()
    {

        $json = File::get('database/seeds/users.json');
        $dc = json_decode($json, true);
        foreach($dc as $val) {
            User::create($val);
        }
    }
}
