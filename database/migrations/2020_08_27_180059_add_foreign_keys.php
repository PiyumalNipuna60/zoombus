<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreign('to')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('from')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('from_address')->references('id')->on('addresses')->onDelete('cascade');
            $table->foreign('to_address')->references('id')->on('addresses')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('country_id')->references('id')->on('countries');
        });


        Schema::table('drivers', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('type')->references('id')->on('route_types');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('manufacturer')->references('id')->on('manufacturers');
            $table->foreign('fuel_type')->references('id')->on('fuel_types');
        });

        Schema::table('cart', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->foreign('country_id')->references('id')->on('countries');
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('affiliate_codes', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_used')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tier_one_user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('driver_ratings', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('driver_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('payouts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('financial_id')->references('id')->on('financials');
        });

        Schema::table('balance_updates', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        });

        Schema::table('financials', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('city_translatables', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });

        Schema::table('fuel_types_translatables', function (Blueprint $table) {
            $table->foreign('fuel_id')->references('id')->on('fuel_types')->onDelete('cascade');
        });


        Schema::table('page_translatables', function (Blueprint $table) {
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });

        Schema::table('address_translatables', function (Blueprint $table) {
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
        });

        Schema::table('country_translatables', function (Blueprint $table) {
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });

        Schema::table('route_types_translatables', function (Blueprint $table) {
            $table->foreign('route_type_id')->references('id')->on('route_types')->onDelete('cascade');
        });

        Schema::table('route_date_types_translatables', function (Blueprint $table) {
            $table->foreign('route_date_type_id')->references('id')->on('route_date_types')->onDelete('cascade');
        });

        Schema::table('vehicle_specs_translatables', function (Blueprint $table) {
            $table->foreign('vehicle_spec_id')->references('id')->on('vehicle_specifications')->onDelete('cascade');
        });

        Schema::table('fines', function (Blueprint $table) {
            $table->foreign('route_id')->references('id')->on('routes');
        });

        Schema::table('remaining_seats', function (Blueprint $table) {
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
        });

        Schema::table('reserved_seats', function (Blueprint $table) {
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
        });

        Schema::table('support_tickets', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('support_ticket_messages', function (Blueprint $table) {
            $table->foreign('ticket_id')->references('id')->on('support_tickets')->onDelete('cascade');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('ticket_id')->references('id')->on('support_tickets')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routes', function(Blueprint $table) {
            $table->dropForeign('routes_user_id_foreign');
            $table->dropForeign('routes_vehicle_id_foreign');
            $table->dropForeign('routes_to_foreign');
            $table->dropForeign('routes_from_foreign');
            $table->dropForeign('routes_from_address_foreign');
            $table->dropForeign('routes_to_address_foreign');
            $table->dropForeign('routes_currency_id_foreign');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign('sales_user_id_foreign');
            $table->dropForeign('sales_route_id_foreign');
            $table->dropForeign('sales_currency_id_foreign');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_country_id_foreign');
        });


        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign('drivers_user_id_foreign');
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->dropForeign('partners_user_id_foreign');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign('vehicles_user_id_foreign');
            $table->dropForeign('vehicles_type_foreign');
            $table->dropForeign('vehicles_country_id_foreign');
            $table->dropForeign('vehicles_manufacturer_foreign');
            $table->dropForeign('vehicles_fuel_type_foreign');
        });

        Schema::table('cart', function (Blueprint $table) {
            $table->dropForeign('cart_user_id_foreign');
            $table->dropForeign('cart_sale_id_foreign');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign('cities_country_id_foreign');
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign('addresses_user_id_foreign');
        });

        Schema::table('affiliate_codes', function (Blueprint $table) {
            $table->dropForeign('affiliate_codes_user_id_foreign');
            $table->dropForeign('affiliate_codes_user_used_foreign');
            $table->dropForeign('affiliate_codes_tier_one_user_id_foreign');
        });

        Schema::table('driver_ratings', function (Blueprint $table) {
            $table->dropForeign('driver_ratings_user_id_foreign');
            $table->dropForeign('driver_ratings_driver_user_id_foreign');
            $table->dropForeign('driver_ratings_sale_id_foreign');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign('admins_user_id_foreign');
        });

        Schema::table('payouts', function (Blueprint $table) {
            $table->dropForeign('payouts_user_id_foreign');
            $table->dropForeign('payouts_currency_id_foreign');
            $table->dropForeign('payouts_financial_id_foreign');
        });

        Schema::table('balance_updates', function (Blueprint $table) {
            $table->dropForeign('balance_updates_user_id_foreign');
            $table->dropForeign('balance_updates_sale_id_foreign');
        });

        Schema::table('financials', function (Blueprint $table) {
            $table->dropForeign('financials_user_id_foreign');
        });

        Schema::table('city_translatables', function (Blueprint $table) {
            $table->dropForeign('city_translatables_city_id_foreign');
        });

        Schema::table('fuel_types_translatables', function (Blueprint $table) {
            $table->dropForeign('fuel_types_translatables_fuel_id_foreign');
        });


        Schema::table('page_translatables', function (Blueprint $table) {
            $table->dropForeign('page_translatables_page_id_foreign');
        });

        Schema::table('address_translatables', function (Blueprint $table) {
            $table->dropForeign('address_translatables_address_id_foreign');
        });

        Schema::table('country_translatables', function (Blueprint $table) {
            $table->dropForeign('country_translatables_country_id_foreign');
        });

        Schema::table('route_types_translatables', function (Blueprint $table) {
            $table->dropForeign('route_types_translatables_route_type_id_foreign');
        });

        Schema::table('route_date_types_translatables', function (Blueprint $table) {
            $table->dropForeign('route_date_types_translatables_route_date_type_id_foreign');
        });

        Schema::table('vehicle_specs_translatables', function (Blueprint $table) {
            $table->dropForeign('vehicle_specs_translatables_vehicle_spec_id_foreign');
        });

        Schema::table('fines', function (Blueprint $table) {
            $table->dropForeign('fines_route_id_foreign');
        });

        Schema::table('remaining_seats', function (Blueprint $table) {
            $table->dropForeign('remaining_seats_route_id_foreign');
        });

        Schema::table('reserved_seats', function (Blueprint $table) {
            $table->dropForeign('reserved_seats_route_id_foreign');
        });

        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropForeign('support_tickets_user_id_foreign');
        });

        Schema::table('support_ticket_messages', function (Blueprint $table) {
            $table->dropForeign('support_ticket_messages_ticket_id_foreign');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign('notifications_ticket_id_foreign');
        });

    }
}
