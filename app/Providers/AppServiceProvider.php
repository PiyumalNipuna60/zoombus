<?php

namespace App\Providers;

use Carbon\Translator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Validator::extendImplicit('current_password',
            function ($attribute, $value, $parameters, $validator) {
                return \Hash::check($value, auth()->user()->password);
            });

        Schema::defaultStringLength(191);

        Translator::get('ka')->setTranslations([
            'months' => ['იანვარს', 'თებერვალს', 'მარტს', 'აპრილს', 'მაისს', 'ივნისს', 'ივლისს', 'აგვისტოს', 'სექტემბერს', 'ოქტომბერს', 'ნოემბერს', 'დეკემბერს'],
        ]);


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
    }
}
