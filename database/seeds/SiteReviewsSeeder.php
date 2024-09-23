<?php

use Illuminate\Database\Seeder;

class SiteReviewsSeeder extends Seeder
{

    public function run()
    {
        $json = File::get('database/seeds/site_reviews.json');
        $dc = json_decode($json, true);
        \App\SiteReviews::insert($dc);
    }
}
