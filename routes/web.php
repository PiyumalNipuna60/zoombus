<?php

Route::group(['middleware' => ['web']], function () {

    Route::group([
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => (!(new \Jenssegers\Agent\Agent())->isMobile()) ? ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'] : []
    ], function () {

        Route::get('/', 'HomePageController')->name('index');

        //registration
        Route::get('/register-as-driver', 'Auth\RegisterAsDriverController@view')->name('register_as_driver');
        Route::get('/register-as-partner', 'Auth\RegisterAsPartnerController@view')->name('register_as_partner');

        Route::get('/driver-registration', 'Auth\RegisterAsDriverController@viewLoggedIn')->name('driver_registration');
        Route::get('/partner-registration', 'Auth\RegisterAsPartnerController@viewLoggedIn')->name('partner_registration');

        //other pages

        Route::get('/support', 'SupportController@viewAll')->name('support');
        Route::get('/support/ticket/{id}', 'SupportController@view')->name('support_ticket');
        Route::get('/support-secure/ticket/{id}/{latest_message}', 'SupportController@viewSecure')->name('support_ticket_secure');
        Route::get('/forgot-password', 'Auth\ForgotPasswordController@view')->name('forgot_password');
        Route::get('/terms-of-use', 'PageController@viewBlank')->name('terms_of_use');
        Route::get('/faq', 'Pages\FAQsController@viewAll')->name('faqs');
        Route::get('/privacy', 'PageController@viewBlank')->name('privacy');
        Route::get('/notifications', 'Pages\NotificationsController@viewAll')->name('notifications');



        //listings
        Route::get('/listings/{id}-{from}-{to}-date-{departure_date}', 'ListingController@view')->name('single_listing');
        Route::get('/listings', 'ListingController@viewAll')->name('listings');
        Route::get('/listings/{route_type}', 'ListingController@viewAll')->name('listings_rt');

        Route::post('/listings/item', 'ListingController@singleItem')->name('listing_more');
        Route::post('/notifications/item', 'Pages\NotificationsController@singleItem')->name('notifications_more');




        //cart
        Route::get('/cart', 'CartController@view')->name('cart');


        //users
        Route::get('/profile', 'User\ProfileController@viewProfile')->name('profile');
        Route::get('/edit-password', 'User\ProfileController@viewChangePassword')->name('edit_password');
        Route::get('/bought-tickets', 'User\TicketsController@viewAll')->name('bought_tickets');
        Route::get('/ticket/{id}', 'User\TicketsController@view')->name('single_ticket');
        Route::get('/t/s/{id}', 'User\TicketsController@secureView')->name('secure_ticket');

        //drivers
        Route::get('/driver/wizard', 'Driver\WizardController@view')->name('driver_wizard');
        Route::post('/driver/wizard/step-1', 'Driver\WizardController@step1')->name('driver_step_1');
        Route::post('/driver/wizard/step-2', 'Driver\WizardController@step2')->name('driver_step_2');
        Route::post('/driver/wizard/step-3', 'Driver\WizardController@step3')->name('driver_step_3');
        Route::post('/driver/wizard/step-4', 'Driver\WizardController@step4')->name('driver_step_4');
        Route::post('/driver/wizard/step-4/skip', 'Driver\WizardController@skipRoute')->name('wizard_skip_route');
        Route::get('/driver', 'Driver\ProfitController@view')->name('driver_profit');
        Route::get('/financial', 'FinancialController@view')->name('financial');
        Route::get('/financial/add', 'FinancialController@viewAdd')->name('financial_add');
        Route::get('/driver/license', 'Driver\LicenseController@view')->name('drivers_license');
        Route::get('/driver/vehicle-registration', 'Driver\VehiclesController@viewAdd')->name('vehicle_registration');
        Route::get('/driver/vehicle/edit/{id}', 'Driver\VehiclesController@viewEdit')->name('vehicle_edit');
        Route::get('/driver/vehicles', 'Driver\VehiclesController@viewAll')->name('vehicles_list');
        Route::get('/driver/route-registration', 'Driver\RoutesController@viewAdd')->name('route_registration');
        Route::get('/driver/routes', 'Driver\RoutesController@viewAll')->name('routes_list');
        Route::get('/driver/route/edit/{id}', 'Driver\RoutesController@viewEdit')->name('route_edit');
        Route::get('/driver/payouts', 'Driver\DriverPayoutController@view')->name('driver_payouts');
        Route::get('/driver/current-sales', 'Driver\SalesController@viewCurrent')->name('current_sales');
        Route::get('/driver/sales', 'Driver\SalesController@viewAll')->name('sales');
        Route::get('/driver/sales-history', 'Driver\SalesController@viewAllHistory')->name('sales_history');
        Route::get('/driver/fines', 'Driver\FinesController@view')->name('fines');


        //rate
        Route::get('/rate/{id}', 'RatingController@view')->name('rate_driver');

        //partners
        Route::get('/partner/code-generator', 'Partners\GeneratorController@view')->name('partner_code');
        Route::get('/partner/list', 'Partners\ListController@view')->name('partner_list');
        Route::get('/partner', 'Partners\ProfitController@view')->name('partner_profit');
        Route::get('/partner/payouts', 'Partners\PartnerPayoutController@view')->name('partner_payouts');
        Route::get('/partner/sales', 'Partners\SalesController@view')->name('partner_sales');


        //Zoombus ajax
        Route::post('/search/validator', 'SearchController@doValidate')->name('search_validator');
        Route::post('/user/choose-seat', 'ListingController@chooseSeat')->name('choose_seat');
        Route::post('/listing/showMoreRatings', 'ListingController@showMoreRatings')->name('show_more_ratings');
        Route::post('/user/booking', 'BookingController@start')->name('booking');
        Route::post('/routes/reserve', 'Driver\RoutesController@reserve')->name('reserve');

        Route::post('/user/set-password', 'User\TicketsController@storePasswordAndVerify')->name('user_set_password');
        Route::post('/financial/data', 'FinancialController@allFinancialData')->name('financial_data');
        Route::post('/financial/doAdd', 'FinancialController@add')->name('financial_add_action');
        Route::post('/financial/doRemove', 'FinancialController@remove')->name('financial_remove');
        Route::post('/financial/doActivate', 'FinancialController@activate')->name('financial_activate');
        Route::post('/partners/data', 'Partners\ListController@allPartnersListData')->name('partners_list');
        Route::post('/partners/sales', 'Partners\SalesController@allSalesData')->name('partners_sales');
        Route::post('/driver/profits', 'Driver\ProfitController@chart')->name('driver_profit_data');
        Route::post('/partners/profits', 'Partners\ProfitController@chart')->name('partner_profit_data');
        Route::post('/notifications/readAll', 'NotificationController@readAll')->name('notifications_read_all');
        Route::post('/driver/sales', 'Driver\SalesController@allSalesData')->name('driver_sales');
        Route::post('/driver/current-sales', 'Driver\SalesController@allCurrentSalesData')->name('driver_current_sales');
        Route::post('/driver/sales-history', 'Driver\SalesController@allSalesHistoryData')->name('driver_sales_history');
        Route::post('/payout-data', 'PayoutController@allPayoutData')->name('payout_data');
        Route::post('/payout/submit', 'PayoutController@request')->name('payout_submit');
        Route::post('/cart/checkout', 'CartController@checkout')->name('cart_checkout');
        Route::post('/cart/remove', 'CartController@remove')->name('remove_cart');
        Route::post('/listings/sort', 'ListingController@sorting')->name('listings_sort');
        Route::post('/current-country-code', 'Auth\LoginController@current_locale')->name('current_currency_code');
        Route::post('/auth/forgot', 'Auth\ForgotPasswordController@action')->name('auth_forgot');
        Route::post('/auth/support', 'SupportController@action')->name('auth_support');
        Route::post('/auth/support-reply-secure', 'SupportController@actionReplySecure')->name('auth_support_reply_secure');
        Route::post('/auth/support-reply', 'SupportController@actionReply')->name('auth_support_reply');
        Route::post('/auth/support-close', 'SupportController@close')->name('auth_support_close');
        Route::post('/auth/support-close-secure', 'SupportController@closeSecure')->name('auth_support_close_secure');


        Route::post('/auth/login', 'Auth\LoginController')->name('auth_login');
        Route::post('/auth/logout', 'Auth\LogoutController')->name('auth_logout');
        Route::post('/auth/register', 'Auth\RegisterController')->name('auth_register');
        Route::post('/auth/register-driver', 'Auth\RegisterAsDriverController')->name('auth_register_driver');
        Route::post('/auth/register-partner', 'Auth\RegisterAsPartnerController')->name('auth_register_partner');
        Route::post('/auth/register-driver-loggedin', 'Auth\RegisterAsDriverController@loggedIn')->name('register_driver');
        Route::post('/auth/register-partner-loggedin', 'Auth\RegisterAsPartnerController@loggedIn')->name('register_partner');
        Route::post('/user/tickets', 'User\TicketsController@allTicketData')->name('user_tickets_data');
        Route::post('/user/edit-profile', 'User\ProfileController@profile')->name('user_edit_profile');
        Route::post('/user/change-avatar', 'User\ProfileController@avatar')->name('user_change_avatar');
        Route::post('/user/delete-avatar', 'User\ProfileController@removeAvatar')->name('user_remove_avatar');
        Route::post('/user/change-password', 'User\ProfileController@password')->name('user_change_password');
        Route::post('/driver/license', 'Driver\LicenseController')->name('driver_license');
        Route::post('/driver/license/delete-front', 'Driver\LicenseController@deleteFront')->name('driver_license_delete_front');
        Route::post('/driver/license/delete-back', 'Driver\LicenseController@deleteBack')->name('driver_license_delete_back');
        Route::post('/driver/rate', 'RatingController@rate')->name('rate_ajax');
        Route::post('/driver/vehicles', 'Driver\VehiclesController@allVehicleData')->name('driver_vehicles_data');
        Route::post('/driver/fines/data', 'Driver\FinesController@allFinesData')->name('driver_fines_data');

        Route::post('/driver/vehicle/add', 'Driver\VehiclesController@add')->name('driver_vehicle_add');
        Route::post('/driver/vehicle/edit', 'Driver\VehiclesController@edit')->name('driver_vehicle_edit');
        Route::post('/driver/vehicle/delete-front', 'Driver\VehiclesController@deleteFront')->name('driver_vehicle_delete_front');
        Route::post('/driver/vehicle/delete-back', 'Driver\VehiclesController@deleteBack')->name('driver_vehicle_delete_back');
        Route::post('/driver/vehicle/delete-image', 'Driver\VehiclesController@deleteSingle')->name('driver_vehicle_delete_image');
        Route::post('/driver/vehicle/delete', 'Driver\VehiclesController@actionDelete')->name('driver_vehicle_delete');
        Route::post('/driver/vehicle/scheme', 'Driver\VehiclesController@scheme')->name('driver_vehicle_scheme');
        Route::post('/driver/routes/data', 'Driver\RoutesController@allRoutesData')->name('driver_routes_data');
        Route::post('/driver/routes/addVehicle', 'Driver\RoutesController@addVehicle')->name('diver_routes_add_vehicle');
        Route::post('/driver/routes/type', 'Driver\RoutesController@typeChange')->name('driver_route_type');
        Route::post('/driver/routes/sales', 'Driver\RoutesController@allRoutesSalesData')->name('driver_routes_sales');
        Route::post('/driver/routes/sales/cancel', 'Driver\RoutesController@cancelRoute')->name('driver_routes_sales_cancel');
        Route::post('/driver/routes/vehicle', 'Driver\RoutesController@vehicleChange')->name('driver_routes_vehicle');
        Route::post('/driver/routes/edit', 'Driver\RoutesController@edit')->name('driver_routes_edit');
        Route::post('/driver/routes/registration', 'Driver\RoutesController@add')->name('driver_routes_add');
        Route::post('/driver/routes/actionDelete', 'Driver\RoutesController@actionDelete')->name('driver_routes_delete');
        Route::post('/driver/routes/actionDisableFutureRouteSales', 'Driver\RoutesController@actionDisableFutureRouteSales')->name('driver_routes_disable_future_sales');
        Route::post('/driver/routes/actionEnableFutureRouteSales', 'Driver\RoutesController@actionEnableFutureRouteSales')->name('driver_routes_enable_future_sales');

        //cities and addresses search
        Route::get('/search/cities/{q}.json', 'Driver\RoutesController@searchCity');
        Route::get('/cities/tenByCountry.json', 'Driver\RoutesController@tenCitiesByCountry');
        Route::get('/search/addresses/{q}.json', 'Driver\RoutesController@searchAddress');


        //payments test
        Route::get('/cardConfirmTest/{transaction_id}/{amount}', 'Payments\CreditCardController@confirmTest');
        Route::get('/PayPalExecute', 'Payments\PayPalClient@captureOrder')->name('paypal_execute');



        Route::post('ticket/refund', 'BookingController@refund')->name('cancel_ticket');

        Route::get('/page/{slug}', 'PageController@view')->name('page');
    });


    //Custom mobile pages
    Route::get('/languages', 'Mobile\LanguagesController@view');
    Route::get('/settings', 'Mobile\SettingsController@view');
    Route::get('/login', 'Mobile\LoginController@view')->name('mobile.login');
    Route::get('/signup', 'Mobile\RegisterController@view')->name('mobile.signup');
    Route::get('/main', 'Mobile\MainController@view');
    Route::get('/drivers-area', 'Mobile\MainController@view');
    Route::get('/driver/vehicles/list', 'Mobile\MainController@view');
    Route::get('/driver/routes/list', 'Mobile\MainController@view');
    Route::get('/partners-area', 'Mobile\MainController@view');
    Route::get('/partner/details/{user_id}', 'Mobile\MainController@view');
    Route::get('/financial/paypal', 'Mobile\MainController@view');
    Route::get('/financial/paypal/add', 'Mobile\MainController@view');
    Route::get('/financial/creditcard', 'Mobile\MainController@view');
    Route::get('/financial/creditcard/add', 'Mobile\MainController@view');
    Route::get('/financial/bank', 'Mobile\MainController@view');
    Route::get('/financial/bank/add', 'Mobile\MainController@view');
    Route::get('/qrscanner', 'Mobile\MainController@view');

    //Get Countries formatted in JS
    Route::get('/languages/jsFormatted', 'Mobile\LanguagesController@getJSFormatted');

    //non-localized ajax
    Route::post('/listings/search', 'SearchController@view')->name('listings_search');
    Route::post('setPreferredLocale', 'Mobile\LanguagesController@setPreferredLocale')->name('set_preferred_locale');
    Route::post('/driver/vehicle/maxSeats', 'Driver\VehiclesController@seatsMax')->name('driver_vehicle_seats_max');

    //Admin
    Route::get('/admin', 'Admin\AdminController@view')->name('admin_dashboard');
    Route::get('/admin/users/support-tickets', 'Admin\Users\SupportTicketsController@view')->name('admin_users_support_tickets');
    Route::get('/admin/users/support-tickets/{id}', 'Admin\Users\SupportTicketsController@viewEdit')->name('admin_users_support_ticket_edit');
    Route::get('/admin/users/administrators', 'Admin\Users\AdministratorsController@view')->name('admin_users_administrators');
    Route::get('/admin/users/drivers', 'Admin\Users\DriversController@view')->name('admin_users_drivers');
    Route::get('/admin/users/partners', 'Admin\Users\PartnersController@view')->name('admin_users_partners');
    Route::get('/admin/users/passengers', 'Admin\Users\PassengersController@view')->name('admin_users_passengers');

    Route::get('/admin/vehicles/all', 'Admin\VehiclesController@view')->name('admin_vehicles_all');
    Route::get('/admin/vehicles/{type}', 'Admin\VehiclesController@view')->name('admin_vehicles');
    Route::get('/admin/vehicles/edit/{id}', 'Admin\VehiclesController@viewEdit')->name('admin_vehicle_edit');

    Route::get('/admin/routes', 'Admin\RoutesController@viewAll')->name('admin_routes');
    Route::get('/admin/routes/edit/{id}', 'Admin\RoutesController@viewEdit')->name('admin_route_edit');

    Route::get('/admin/user/{id}', 'Admin\Users\ProfileController@viewUser')->name('admin_user_edit');

    Route::get('/admin/pages', 'Admin\PagesController@view')->name('admin_pages');
    Route::get('/admin/pages/add', 'Admin\PagesController@viewAdd')->name('admin_pages_add');
    Route::get('/admin/pages/edit/{id}', 'Admin\PagesController@viewEdit')->name('admin_pages_edit');


    Route::get('/admin/cities', 'Admin\Misc\CitiesController@view')->name('admin_cities');
    Route::get('/admin/cities/add', 'Admin\Misc\CitiesController@viewAdd')->name('admin_cities_add');
    Route::get('/admin/cities/edit/{id}', 'Admin\Misc\CitiesController@viewEdit')->name('admin_cities_edit');

    Route::get('/admin/addresses', 'Admin\Misc\AddressController@view')->name('admin_address');
    Route::get('/admin/addresses/add', 'Admin\Misc\AddressController@viewAdd')->name('admin_address_add');
    Route::get('/admin/addresses/edit/{id}', 'Admin\Misc\AddressController@viewEdit')->name('admin_address_edit');

    Route::get('/admin/vehicle-specifications', 'Admin\Misc\VehicleSpecsController@view')->name('admin_specs');
    Route::get('/admin/vehicle-specifications/add', 'Admin\Misc\VehicleSpecsController@viewAdd')->name('admin_specs_add');
    Route::get('/admin/vehicle-specifications/edit/{id}', 'Admin\Misc\VehicleSpecsController@viewEdit')->name('admin_specs_edit');


    Route::get('/admin/sales', 'Admin\SalesController@view')->name('admin_sales');

    Route::get('/admin/withdraws', 'Admin\PayoutsController@view')->name('admin_payouts');
    Route::get('/admin/withdraw/edit/{id}', 'Admin\PayoutsController@viewEdit')->name('admin_payout_edit');


    //unsubscribe
    Route::get('/unsubscribe/{id}', 'NotificationController@unsubscribe')->name('unsubscribe');

    //test
    Route::get('/test', 'PS5Controller@scheduled');


    //Admin AJAX
    Route::post('/admin/users/editProfile', 'Admin\Users\ProfileController@profile')->name('admin_users_edit_profile');
    Route::post('/admin/users/drivers/licenseChange', 'Admin\Users\DriversController@licenseChange')->name('admin_drivers_license_change');



    //Admin datatable actions
    Route::post('/admin/users/administrators/allAdminsData', 'Admin\Users\AdministratorsController@viewData')->name('admin_admistrators_data');
    Route::post('/admin/users/administrators/delete', 'Admin\Users\AdministratorsController@delete')->name('admin_administrator_delete');

    Route::post('/admin/users/support-tickets/allSupportTicketData', 'Admin\Users\SupportTicketsController@viewData')->name('admin_users_support_ticket_data');
    Route::post('/admin/users/support-tickets/reply', 'Admin\Users\SupportTicketsController@reply')->name('admin_support_reply');
    Route::post('/admin/users/support-tickets/delete', 'Admin\Users\SupportTicketsController@delete')->name('admin_users_support_ticket_delete');
    Route::post('/admin/users/support-tickets/close', 'Admin\Users\SupportTicketsController@close')->name('admin_users_support_ticket_close');

    Route::post('/admin/users/drivers/allDriverData', 'Admin\Users\DriversController@viewData')->name('admin_drivers_data');
    Route::post('/admin/users/drivers/suspend', 'Admin\Users\DriversController@suspend')->name('admin_driver_suspend');
    Route::post('/admin/users/drivers/unsuspend', 'Admin\Users\DriversController@unsuspend')->name('admin_driver_unsuspend');
    Route::post('/admin/users/drivers/approve', 'Admin\Users\DriversController@approve')->name('admin_driver_approve');
    Route::post('/admin/users/drivers/delete', 'Admin\Users\DriversController@delete')->name('admin_driver_delete');

    Route::post('/admin/users/partners/allPartnerData', 'Admin\Users\PartnersController@viewData')->name('admin_partners_data');
    Route::post('/admin/users/partners/userData', 'Admin\Users\PartnersController@viewByUser')->name('admin_partners_user_data');
    Route::post('/admin/users/partners/suspend', 'Admin\Users\PartnersController@suspend')->name('admin_partner_suspend');
    Route::post('/admin/users/partners/unsuspend', 'Admin\Users\PartnersController@unsuspend')->name('admin_partner_unsuspend');
    Route::post('/admin/users/partners/delete', 'Admin\Users\PartnersController@delete')->name('admin_partner_delete');

    Route::post('/admin/misc/cities/data', 'Admin\Misc\CitiesController@viewData')->name('admin_cities_data');
    Route::post('/admin/misc/cities/add', 'Admin\Misc\CitiesController@add')->name('admin_cities_add_action');
    Route::post('/admin/misc/cities/edit', 'Admin\Misc\CitiesController@edit')->name('admin_cities_edit_action');
    Route::post('/admin/misc/cities/delete', 'Admin\Misc\CitiesController@delete')->name('admin_cities_delete');
    Route::post('/admin/misc/cities/delete-image/{id}/{extension}', 'Admin\Misc\CitiesController@deleteImage')->name('admin_cities_delete_image');


    Route::post('/admin/misc/addresses/data', 'Admin\Misc\AddressController@viewData')->name('admin_address_data');
    Route::post('/admin/misc/addresses/add', 'Admin\Misc\AddressController@add')->name('admin_address_add_action');
    Route::post('/admin/misc/addresses/edit', 'Admin\Misc\AddressController@edit')->name('admin_address_edit_action');
    Route::post('/admin/misc/addresses/delete', 'Admin\Misc\AddressController@delete')->name('admin_address_delete');


    Route::post('/admin/misc/vehicle-specs/data', 'Admin\Misc\VehicleSpecsController@viewData')->name('admin_specs_data');
    Route::post('/admin/misc/vehicle-specs/add', 'Admin\Misc\VehicleSpecsController@add')->name('admin_specs_add_action');
    Route::post('/admin/misc/vehicle-specs/edit', 'Admin\Misc\VehicleSpecsController@edit')->name('admin_specs_edit_action');
    Route::post('/admin/misc/vehicle-specs/delete', 'Admin\Misc\VehicleSpecsController@delete')->name('admin_specs_delete');
    Route::post('/admin/misc/vehicle-specs/delete-image/{id}/{extension}', 'Admin\Misc\VehicleSpecsController@deleteImage')->name('admin_specs_delete_image');

    Route::post('/admin/users/passengers/allPassengerData', 'Admin\Users\PassengersController@viewData')->name('admin_passengers_data');
    Route::post('/admin/users/passengers/suspend', 'Admin\Users\PassengersController@suspend')->name('admin_user_suspend');
    Route::post('/admin/users/passengers/unsuspend', 'Admin\Users\PassengersController@unsuspend')->name('admin_user_unsuspend');
    Route::post('/admin/users/passengers/approve', 'Admin\Users\PassengersController@unsuspend')->name('admin_user_approve');
    Route::post('/admin/users/passengers/delete', 'Admin\Users\PassengersController@delete')->name('admin_user_delete');


    Route::post('/admin/vehicles/data', 'Admin\VehiclesController@viewData')->name('admin_vehicle_data');
    Route::post('/admin/vehicles/add', 'Admin\VehiclesController@add')->name('admin_vehicle_add_action');
    Route::post('/admin/vehicles/edit', 'Admin\VehiclesController@edit')->name('admin_vehicle_edit_action');
    Route::post('/admin/vehicles/licenseChange', 'Admin\VehiclesController@licenseChange')->name('admin_vehicle_license_change');
    Route::post('/admin/vehicles/suspend', 'Admin\VehiclesController@suspend')->name('admin_vehicle_suspend');
    Route::post('/admin/vehicles/unsuspend', 'Admin\VehiclesController@unsuspend')->name('admin_vehicle_unsuspend');
    Route::post('/admin/vehicles/approve', 'Admin\VehiclesController@approve')->name('admin_vehicle_approve');
    Route::post('/admin/vehicles/delete', 'Admin\VehiclesController@delete')->name('admin_vehicle_delete');

    Route::post('/admin/routes/data', 'Admin\RoutesController@viewData')->name('admin_route_data');
    Route::post('/admin/routes/add', 'Admin\RoutesController@add')->name('admin_route_add_action');
    Route::post('/admin/routes/edit', 'Admin\RoutesController@edit')->name('admin_route_edit_action');
    Route::post('/admin/routes/suspend', 'Admin\RoutesController@suspend')->name('admin_route_suspend');
    Route::post('/admin/routes/unsuspend', 'Admin\RoutesController@unsuspend')->name('admin_route_unsuspend');
    Route::post('/admin/routes/approve', 'Admin\RoutesController@approve')->name('admin_route_approve');
    Route::post('/admin/routes/pause', 'Admin\RoutesController@pause')->name('admin_route_pause');
    Route::post('/admin/routes/delete', 'Admin\RoutesController@delete')->name('admin_route_delete');


    Route::post('/admin/withdrawal/data', 'Admin\PayoutsController@viewData')->name('admin_payout_data');
    Route::post('/admin/withdrawal/edit', 'Admin\PayoutsController@edit')->name('admin_payout_edit_action');
    Route::post('/admin/withdrawal/approve', 'Admin\PayoutsController@approve')->name('admin_payout_approve');
    Route::post('/admin/withdrawal/decline', 'Admin\PayoutsController@decline')->name('admin_payout_decline');
    Route::post('/admin/withdrawal/delete', 'Admin\PayoutsController@delete')->name('admin_payout_delete');

    Route::post('/admin/sales/data', 'Admin\SalesController@viewData')->name('admin_sale_data');
    Route::post('/admin/sales/partners/data', 'Admin\SalesController@viewPartnerSalesData')->name('admin_partners_sale_data');
    Route::post('/admin/sales/refund', 'Admin\SalesController@refund')->name('admin_sale_refund');
    Route::post('/admin/sales/approve', 'Admin\SalesController@approve')->name('admin_sale_approve');
    Route::post('/admin/sales/delete', 'Admin\SalesController@delete')->name('admin_sale_delete');

    Route::post('/admin/pages/allPagesData', 'Admin\PagesController@viewData')->name('admin_pages_data');
    Route::post('/admin/pages/add', 'Admin\PagesController@add')->name('admin_pages_add_action');
    Route::post('/admin/pages/edit', 'Admin\PagesController@edit')->name('admin_pages_edit_action');
    Route::post('/admin/pages/publish', 'Admin\PagesController@publish')->name('admin_page_publish');
    Route::post('/admin/pages/draft', 'Admin\PagesController@draft')->name('admin_page_draft');
    Route::post('/admin/pages/delete', 'Admin\PagesController@delete')->name('admin_page_delete');

    Route::post('/admin/logout', 'Admin\AdminController@logout')->name('admin_logout');



});
