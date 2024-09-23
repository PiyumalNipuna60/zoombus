<?php

use Illuminate\Database\Seeder;
use App\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = array(
            array('id' => 1, 'key' => 'GEL', 'code' => 981, 'value' => 2.9)
        );
        Currency::insert($currencies);
    }
}
