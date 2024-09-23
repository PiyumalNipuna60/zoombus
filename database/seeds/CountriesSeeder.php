<?php

use Illuminate\Database\Seeder;
use App\Country;
use App\CountryTranslatable;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get('database/seeds/countries.json');
        $dc = json_decode($json, true);
        Country::insert($dc);

        $json = File::get('database/seeds/countriesTranslations.json');
        $dc = json_decode($json, true);
        CountryTranslatable::insert($dc);
    }
}
