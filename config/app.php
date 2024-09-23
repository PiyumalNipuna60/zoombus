<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Zoombus'),


    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', true),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Tbilisi',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Default Currency
    |--------------------------------------------------------------------------
    */


    /*
        |--------------------------------------------------------------------------
        | Application Fallback Locale
        |--------------------------------------------------------------------------
        |
        | The fallback locale determines the locale to use when the current one
        | is not available. You may change the value to correspond to any of
        | the language folders that are provided through your application.
        |
        */

    'fallback_locale' => 'en',


    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

   

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,


        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        Laravel\Passport\PassportServiceProvider::class,
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
        Propaganistas\LaravelPhone\PhoneServiceProvider::class,
        \Torann\GeoIP\GeoIPServiceProvider::class,
        Yajra\DataTables\DataTablesServiceProvider::class,
        App\Providers\AwsSnsProvider::class,
        \App\Providers\PassportServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Controller' => \App\Http\Controllers\Controller::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'DataTables' => Yajra\DataTables\Facades\DataTables::class,
        'Agent' => Jenssegers\Agent\Facades\Agent::class,

        'GeoIP' => \Torann\GeoIP\Facades\GeoIP::class,

    ],

    //custom variables, defined by zoombus


    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    */

    'currency' => 'GEL',


    /*
    |--------------------------------------------------------------------------
    | Website mode: development or live.
    | Development has limited access to only allowed IP addresses.
    | Live is accessible for everyone.
    |--------------------------------------------------------------------------
    */

    'mode' => 'live',

    /*
    |--------------------------------------------------------------------------
    | PayPal mode: sandbox or live.
    |--------------------------------------------------------------------------
    */

    'paypal_mode' => 'live',


    /*
    |--------------------------------------------------------------------------
    | Listings to be displayed per page
    |--------------------------------------------------------------------------
    */

    'listings_per_page' => 10,

    /*
    |--------------------------------------------------------------------------
    | Ratings to be displayed per page
    |--------------------------------------------------------------------------
    */

    'ratings_per_page' => 3,

    /*
    |--------------------------------------------------------------------------
    | Notifications to be displayed per page
    |--------------------------------------------------------------------------
    */

    'notifications_per_page' => 10,

    /*
    |--------------------------------------------------------------------------
    | Withdrawals per page
    |--------------------------------------------------------------------------
    */

    'withdrawals_per_page' => 10,

    /*
    |--------------------------------------------------------------------------
    | Sales history per page
    |--------------------------------------------------------------------------
    */

    'sales_history_per_page' => 10,

    /*
    |--------------------------------------------------------------------------
    | Sales by Route per page
    |--------------------------------------------------------------------------
    */

    'sales_by_route_per_page' => 10,

    /*
    |--------------------------------------------------------------------------
    | Current Sales by Route per page
    |--------------------------------------------------------------------------
    */

    'current_sales_by_route_per_page' => 10,

    /*
    |--------------------------------------------------------------------------
    | Fines per page
    |--------------------------------------------------------------------------
    */

    'fines_per_page' => 10,

    /*
    |--------------------------------------------------------------------------
    | Partner Sales per page
    |--------------------------------------------------------------------------
    */

    'partner_sales_per_page' => 10,


    /*
    |--------------------------------------------------------------------------
    | Partner list per page
    |--------------------------------------------------------------------------
    */

    'partner_list_per_page' => 10,

    /*
    |--------------------------------------------------------------------------
    | Partner list vehicles per page
    |--------------------------------------------------------------------------
    */

    'partner_list_vehicles_per_page' => 10,

    /*
    |--------------------------------------------------------------------------
    | Balance percentages
    |--------------------------------------------------------------------------
    */

    'tier1_affiliate' => 5, //percent
    'tier2_affiliate' => 2, //percent
    'passenger_affiliate' => 3, //percent


    /*
    |--------------------------------------------------------------------------
    | Zoombus percentage from each sale and commissions
    |--------------------------------------------------------------------------
    */

    'sale_commission' => 10, //percent
    'refund_commission' => 15, //percent
    'driver_fine_commission' => 10, //percent

    /*
    |--------------------------------------------------------------------------
    | Maximum payout methods allowed to add
    |--------------------------------------------------------------------------
    */

    'max_paypal' => 2,
    'max_bank' => 1,
    'max_card' => 2,


    /*
    |--------------------------------------------------------------------------
    | Vehicle minimum and maximum seats
    |--------------------------------------------------------------------------
    */

    'min_car_seats' => 4,
    'max_car_seats' => 4,

    'min_minibus_seats' => 7,
    'max_minibus_seats' => 30,

    'min_bus_seats' => 30,
    'max_bus_seats' => 70,

    /*
    |--------------------------------------------------------------------------
    | Vehicle maximum number of images user can upload
    |--------------------------------------------------------------------------
    */

    'max_vehicle_images' => 10,


];
