<?php

use App\NotificationTypes;
use Illuminate\Database\Seeder;

class NotificationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notification_types = array(
            array('id' => 1, 'key' => 'cart'),
            array('id' => 2, 'key' => 'rate'),
            array('id' => 3, 'key' => 'reminder'),
            array('id' => 4, 'key' => 'license'),
            array('id' => 5, 'key' => 'route'),
        );
        NotificationTypes::insert($notification_types);
    }
}
