<?php

use Illuminate\Database\Seeder;
use App\VehicleSpecs;
use App\VehicleSpecsTranslatable;

class VehicleSpecsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get('database/seeds/vehicle_specs.json');
        $dc = json_decode($json, true);
        VehicleSpecs::insert($dc);

        $json = File::get('database/seeds/vehicle_specs_translations.json');
        $dc = json_decode($json, true);
        VehicleSpecsTranslatable::insert($dc);
    }
}
