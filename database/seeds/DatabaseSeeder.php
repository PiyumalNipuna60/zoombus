<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CurrencySeeder::class,
            CountriesSeeder::class,
            ManufacturersSeeder::class,
            RouteTypeSeeder::class,
            FuelTypeSeeder::class,
            VehicleSpecsSeeder::class,
            UsersSeeder::class,
            GenderSeeder::class,
            RouteDateTypeSeeder::class,
            CitiesSeeder::class,
            AdminsSeeder::class,
            NotificationTypesSeeder::class,
            AddressSeeder::class,
            SiteReviewsSeeder::class,
        ]);
    }
}
