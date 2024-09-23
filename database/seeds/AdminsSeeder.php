<?php

use Illuminate\Database\Seeder;
use App\Admins;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = array(
            ['user_id' => 1, 'ip' => '192.168.10.1'],
            ['user_id' => 1, 'ip' => '94.43.189.56'],
            ['user_id' => 1, 'ip' => '94.43.189.56'],
            ['user_id' => 1, 'ip' => '188.129.177.8'],
            ['user_id' => 1, 'ip' => '188.121.218.233'],
            ['user_id' => 1, 'ip' => '148.251.49.35'],
            ['user_id' => 1, 'ip' => '149.3.32.92'],
            ['user_id' => 1, 'ip' => '178.134.12.170'],
            ['user_id' => 1, 'ip' => '188.121.218.214'],
            ['user_id' => 1, 'ip' => '149.3.32.92'],
        );
        Admins::insert($currencies);
    }
}
