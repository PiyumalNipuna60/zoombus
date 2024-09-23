<?php

use Illuminate\Database\Seeder;
use App\City;
use App\CityTranslatable;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get('database/seeds/cities.json');
        $dc = json_decode($json, true);
        City::insert($dc);

        $jsons = File::get('database/seeds/citiesTranslations.json');
        $dcs = json_decode($jsons, true);
        CityTranslatable::insert($dcs);

    }
}
