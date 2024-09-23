<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/getAllUser', [RegisterController::class, 'getAllUser']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('profileGet', 'Api\ProfileController@get');
    Route::post('profileUpdate', 'Api\ProfileController@update');
    Route::post('changePassword', 'Api\ChangePasswordController@update');

    Route::post('financial/data', 'Api\FinancialController@getData');
    Route::post('financial/delete', 'Api\FinancialController@delete');
    Route::post('financial/setPrimary', 'Api\FinancialController@setPrimary');
    Route::get('financial/getPrimary', 'Api\FinancialController@getPrimary');
    Route::post('financial/getByType', 'Api\FinancialController@getByType');
    Route::post('financial/setByType', 'Api\FinancialController@setByType');
    Route::post('financial/delete', 'Api\FinancialController@delete');
    Route::post('financial/add', 'Api\FinancialController@addAction');

    Route::group(['middleware' => 'api_driver'], function () {
        //Drivers License
        Route::post('driver/license/add', 'Api\DriversLicenseController@add')->middleware('api_driver_license');
        Route::get('driver/license/get', 'Api\DriversLicenseController@get');
        Route::get('driver/license/getDriverStatus', 'Api\DriversLicenseController@driverStatus');
        //Vehicles
        Route::get('driver/getVehicleCount', 'Api\VehiclesRoutesController@getVehicleCount');
        Route::get('driver/getRouteCount', 'Api\VehiclesRoutesController@getRouteCount');
        Route::get('driver/vehicle/list', 'Api\VehiclesController@list');
        Route::get('driver/vehicle/constructor', 'Api\VehiclesController@constructor')->middleware('api_can_edit_vehicle');
        Route::post('driver/vehicle/add', 'Api\VehiclesController@addAction');
        Route::post('driver/vehicle/edit', 'Api\VehiclesController@editAction')->middleware('api_can_edit_vehicle');
        //Routes

        Route::get('driver/route/constructor', 'Api\RoutesController@constructor');
        Route::get('driver/route/changeType', 'Api\RoutesController@changeType');
        Route::get('driver/route/changeVehicle', 'Api\RoutesController@changeVehicle');
        Route::get('driver/route/list', 'Api\RoutesController@list');
        Route::post('driver/route/add', 'Api\RoutesController@addAction');
        Route::post('driver/route/edit', 'Api\RoutesController@editAction')->middleware('api_can_edit_route');
        Route::post('driver/route/preserve', 'Api\RoutesController@preserve')->middleware('api_can_edit_route');
        Route::post('driver/route/delete', 'Api\RoutesController@deleteAction')->middleware('api_can_edit_route');
        Route::post('driver/route/cancel', 'Api\RoutesController@cancelAction')->middleware('api_can_edit_route');


        Route::get('driver/fines/get', 'Api\FinesController@get');

        Route::get('salesHistory/get', 'Api\SalesHistoryController@get');
        Route::get('salesByRoute/get', 'Api\SalesByRouteController@get');

        Route::get('wizard/get', 'Api\WizardController@get')->middleware('api_driver_not_active');
        Route::get('wizard/getResumed', 'Api\WizardController@wizardResume')->middleware('api_driver_not_active');
        Route::post('wizard/getByStep', 'Api\WizardController@getByStep')->middleware('api_driver_not_active');

        Route::post('parseQR', 'Api\QRScannerController@parseQR');
        Route::post('parseTicket', 'Api\QRScannerController@parseTicket');
        Route::post('decodeQR', 'Api\QRScannerController@decodeQR');

    });

    Route::group(['middleware' => 'api_partner'], function () {
        Route::get('partnerCode/get', 'Api\PartnerCodeController@get');
        Route::get('partnerSales/get', 'Api\PartnerSalesController@get');
        Route::get('partnerList/get', 'Api\PartnerListController@get');
        Route::get('partnerDetails/get', 'Api\PartnerListController@getDetails');
    });

    Route::post('driver/become', 'Api\BecomeDriverController@post');
    Route::post('partner/become', 'Api\BecomePartnerController@post');

    Route::get('payouts/get', 'Api\PayoutsController@get');
    Route::post('payouts/add', 'Api\PayoutsController@add');

    Route::get('cities/search', 'Api\RoutesController@searchCities');
    Route::get('addresses/search', 'Api\RoutesController@searchAddresses');

    Route::get('faq', 'Api\FAQsController@get');

    Route::get('notificationCount', 'Api\NotificationsController@getCount');
    Route::get('notificationsList', 'Api\NotificationsController@list');

    Route::get('tickets/get', 'Api\SupportController@get')->middleware('api_support_ticket_exists');
    Route::post('tickets/reply', 'Api\SupportController@reply')->middleware('api_support_ticket_exists');
    Route::post('tickets/new', 'Api\SupportController@newOne');


    Route::get('ticket/get', 'Api\TicketsController@single');
    Route::get('ticket/getSecure', 'Api\TicketsController@singleSecure');
    Route::get('ticket/list', 'Api\TicketsController@listTickets');

    Route::post('ticket/checkRefund', 'BookingController@checkRefundAmount');
    Route::post('ticket/refund', 'BookingController@refund');

    Route::post('setAvatar', 'Api\ProfileController@avatarSet');
    Route::post('setPreferredLocale', 'Mobile\LanguagesController@setPreferredLocale');
    Route::get('routeRating/get', 'Api\RatingController@get')->middleware('api_can_rate');
    Route::get('routeRating/rate', 'Api\RatingController@rate')->middleware('api_can_rate');


});
Route::post('registration', 'Api\RegistrationController@create');
Route::post('registration-partner', 'Api\RegistrationController@createAsPartner');
Route::post('forgot', 'Api\ForgotPasswordController@request');


