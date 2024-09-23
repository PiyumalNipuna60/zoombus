<template>
    <div v-if="data && data.length" class="list">
        <div class="ticket" v-for="(item,k) in data" :key="k">
            <div class="first_box">
                <div class="passenger">
                    <p class="title_area">{{ lang.salesHistory.passenger }}</p>
                    <p class="passenger_name">{{ item.users.name }}</p>
                </div>
                <p class="price">{{ item.price }} <span>gel</span></p>
            </div>
            <div class="second_box">
                <div class="from">
                    <p class="title_area">{{ lang.salesHistory.departure }}</p>
                    <p class="city_name">{{ route.cities_from.translated.name }}</p>
                </div>
                <div class="date">
                    <p class="title_area">{{ lang.salesHistory.date }}</p>
                    <p class="departure_date">{{ item.purchase_date }}</p>
                </div>
                <div class="departure_time">
                    <p class="title_area">{{ lang.salesHistory.departure_time }}</p>
                    <p class="time">{{ route.departure_time }}</p>
                </div>
            </div>
            <div class="third_box">
                <div class="destination">
                    <p class="title_area">{{ lang.salesHistory.arrival }}</p>
                    <p class="city_destination">{{ route.cities_to.translated.name }}</p>
                </div>
                <div class="seat">
                    <p class="title_area">{{ lang.salesHistory.seat }}</p>
                    <p class="seat_number">{{ item.seat_number }}</p>
                </div>
                <div class="destination_time">
                    <p class="title_area">{{ lang.salesHistory.arrival_time }}</p>
                    <p class="time">{{ route.arrival_time }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped src="./css/SalesListByRoute.css"/>
<script>
import translations from '../translations'

export default {
        name: 'SalesListByRoute',
        props: {
            data: {
                type: Array
            },
            route: {
                type: Object
            }
        },
        data() {
            return {
                lang: translations[this.$store.state.locale]
            }
        }
}
</script>
