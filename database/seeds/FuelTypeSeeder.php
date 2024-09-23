<?php

use Illuminate\Database\Seeder;
use App\FuelTypes;
use App\FuelTypesTranslatable;

class FuelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get('database/seeds/fuel_types.json');
        $dc = json_decode($json, true);
        FuelTypes::insert($dc);

        $json = File::get('database/seeds/fuel_types_translations.json');
        $dc = json_decode($json, true);
        FuelTypesTranslatable::insert($dc);
    }
}
