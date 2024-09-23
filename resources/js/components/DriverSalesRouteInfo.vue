<template>
    <div class="sales_header" v-if="data">
        <div v-if="$store.state.routeEditSmallHeader">
            <div class="header_info">
                <p class="transport_info">{{ data.vehicles.manufacturers.name }} / <span>{{ data.vehicles.license_plate }}</span></p>
                <div class="route_information">
                    {{ lang.driverSalesRouteInfo.route }}: <span>{{ data.cities_from.code + data.id }}</span>
                    {{ lang.driverSalesRouteInfo.departure_date }} <span>05.19.2018</span>
                </div>
            </div>
            <div class="balance_info">
                <p class="balance_title">{{ lang.driverSalesRouteInfo.total_sold_currency }}</p>
                <p class="balance">{{ data.total_sold_currency }} <span>gel</span></p>
                <p class="ticket_title">{{ lang.driverSalesRouteInfo.total_sold }}</p>
                <p class="ticket_amount">{{ data.total_sold }}</p>
            </div>
        </div>
        <div v-else>
            <div class="header_box">
                <div class="balance_box">
                    <p class="title_area">{{ lang.driverSalesRouteInfo.total_sold_currency }}</p>
                    <p class="today_balance">{{ data.total_sold_currency }} <span>gel</span></p>
                </div>
                <div class="ticket_box">
                    <p class="title_area">{{ lang.driverSalesRouteInfo.total_sold }}</p>
                    <p class="total_sold">{{ data.total_sold }}</p>
                </div>
            </div>
            <hr class="divider_line">
            <div class="transport_title">
                <div class="zoom_model">
                    <label>{{ lang.driverSalesRouteInfo.vehicle }}</label>
                    <h3 class="model">{{ data.vehicles.manufacturers.name }} / {{ data.vehicles.license_plate }} </h3>
                </div>
                <div class="zoom_day">
                    <label>{{ lang.driverSalesRouteInfo.route }}</label>
                    <h3 class="dep_day"><span>{{ data.cities_from.code }}</span>{{ data.id }}</h3>
                </div>
            </div>
            <div class="border_bottom"></div>
            <div class="zoom_destination">

                <span class="circle"></span>
                <span class="dot_line"></span>
                <v-icon color="primary" size="18" class="mlminus1">
                    {{ arrowDown }}
                </v-icon>

            </div>
            <div class="departure_title">
                <div class="zoom_dep_city">
                    <label>{{ lang.driverSalesRouteInfo.departure }}</label>
                    <h3 class="city_title">{{ data.cities_from.translated.name }}</h3>
                </div>
                <div class="zoom_dep_time">
                    <label>{{ lang.driverSalesRouteInfo.departure_time }}</label>
                    <div class="dep_time">
                        <v-icon color="black" size="16" class="mr-3">
                            {{ clock }}
                        </v-icon>
                        <h3 class="time">{{ data.departure_time }}</h3>
                    </div>
                </div>
            </div>
            <div class="between_border_bottom"></div>
            <div class="destination_title">
                <div class="zoom_dest_city">
                    <label>{{ lang.driverSalesRouteInfo.arrival }}</label>
                    <h3 class="city_title">{{ data.cities_to.translated.name }}</h3>
                </div>
                <div class="zoom_dest_time">
                    <label>{{ lang.driverSalesRouteInfo.arrival_time }}</label>
                    <div class="dest_time">
                        <v-icon color="black" size="16" class="mr-3">
                            {{ clock }}
                        </v-icon>
                        <h3 class="time">{{ data.arrival_time }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped src="./css/DriverSalesRouteInfo.css"/>
<script>
import translations from '../translations'
import { mdiArrowDown, mdiClockOutline } from '@mdi/js'

export default {
        name: 'DriverSalesRouteInfo',
        props: {
            data: {
                type: Object
            }
        },
        data() {
            return {
                lang: translations[this.$store.state.locale],
                clock: mdiClockOutline,
                arrowDown: mdiArrowDown
            }
        }
}
</script>
