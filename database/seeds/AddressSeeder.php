<?php

use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function run()
    {
        //
        $json = File::get('database/seeds/addresses.json');
        $dc = json_decode($json, true);
        \App\Address::insert($dc);

        $json = File::get('database/seeds/addressesTranslations.json');
        $dc = json_decode($json, true);
        \App\AddressTranslatable::insert($dc);

    }
}
