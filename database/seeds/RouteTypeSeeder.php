<?php

use Illuminate\Database\Seeder;
use App\RouteTypes;

class RouteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get('database/seeds/route_types.json');
        $dc = json_decode($json, true);
        RouteTypes::insert($dc);

        $json = File::get('database/seeds/route_types_translations.json');
        $dc = json_decode($json, true);
        \App\RouteTypesTranslatable::insert($dc);
    }
}
