<?php

use Illuminate\Database\Seeder;
use App\Manufacturer;

class ManufacturersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get('database/seeds/manufacturers.json');
        $dc = json_decode($json, true);
        Manufacturer::insert($dc);
    }
}
