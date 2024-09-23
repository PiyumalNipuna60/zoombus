<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Session\Middleware\StartSession::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\EssentialMiddleware::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'localize' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
        'localizationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
        'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
        'localeCookieRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
        'localeViewPath' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\Guest::class,
        'can_view_ticket' => \App\Http\Middleware\CanViewTicket::class,
        'can_view_secure_ticket' => \App\Http\Middleware\CanViewSecureTicket::class,
        'customer' => \App\Http\Middleware\Customer::class,
        'driver' => \App\Http\Middleware\DriverMiddleware::class,
        'not_driver' => \App\Http\Middleware\NotADriverMiddleware::class,
        'driver_license' => \App\Http\Middleware\DriverLicenseMiddleware::class,
        'driver_active' => \App\Http\Middleware\DriverActiveMiddleware::class,
        'driver_not_active' => \App\Http\Middleware\DriverNotActiveMiddleware::class,
        'partner' => \App\Http\Middleware\PartnerMiddleware::class,
        'partner_active' => \App\Http\Middleware\PartnerActiveMiddleware::class,
        'not_partner' => \App\Http\Middleware\NotAPartnerMiddleware::class,
        'can_edit_vehicle' => \App\Http\Middleware\CanEditVehicleMiddleware::class,
        'can_edit_sale' => \App\Http\Middleware\CanEditSaleMiddleware::class,
        'can_edit_route' => \App\Http\Middleware\CanEditRouteMiddleware::class,
        'has_at_least_one_vehicle' => \App\Http\Middleware\HasAtLeastOneVehicleMiddleware::class,
        'admin' => \App\Http\Middleware\IsAdminMiddleware::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'new_user' => \App\Http\Middleware\NewUserMiddleware::class,
        'can_edit_financial' => \App\Http\Middleware\CanEditFinancialMiddleware::class,
        'can_rate' => \App\Http\Middleware\CanRateMiddleware::class,
        'can_view_support_ticket' => \App\Http\Middleware\CanViewSupportTicket::class,
        'support_ticket_exists' => \App\Http\Middleware\SupportTicketExists::class,

        'api_partner' => \App\Http\Middleware\API\PartnerExists::class,
        'api_driver' => \App\Http\Middleware\API\DriverExists::class,
        'api_not_driver' => \App\Http\Middleware\API\NotADriverMiddleware::class,
        'api_driver_license' => \App\Http\Middleware\API\DriverLicenseMiddleware::class,
        'api_driver_active' => \App\Http\Middleware\API\DriverActiveMiddleware::class,
        'api_driver_not_active' => \App\Http\Middleware\API\DriverNotActiveMiddleware::class,
        'api_can_edit_vehicle' => \App\Http\Middleware\API\CanEditVehicleMiddleware::class,
        'api_can_edit_route' => \App\Http\Middleware\API\CanEditRouteMiddleware::class,
        'api_at_least_one_vehicle' => \App\Http\Middleware\API\HasAtLeastOneVehicleMiddleware::class,
        'api_support_ticket_exists' => \App\Http\Middleware\API\SupportTicketExists::class,
        'api_can_rate' => \App\Http\Middleware\API\CanRateMiddleware::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
