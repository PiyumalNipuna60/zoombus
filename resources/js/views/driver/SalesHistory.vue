<template>
    <div v-if="!isLoading">
        <Header :title="title" :show-back="true" :show-logo="false" :hideLogoText="true" :parent="parent"/>
        <section v-if="data.total_sold > 0">
            <div class="list" v-if="data.items && data.items.length">
                <h2 class="title_area">{{ lang.salesHistory.history }}</h2>
                <div class="ticket" :key="k" v-for="(item,k) in data.items">
                    <div class="first_box">
                        <div class="passenger">
                            <p class="title_area">{{ lang.salesHistory.passenger }}</p>
                            <p class="passenger_name">{{ item.customer }}</p>
                        </div>
                        <p class="price">{{ item.price }} <span>{{ item.currency }}</span></p>
                    </div>
                    <div class="status" :class="lang.salesHistory.status.find(d => d.id === item.status).class">
                        {{ lang.salesHistory.status.find(d => d.id === item.status).text }}
                    </div>
                    <div class="second_box">
                        <div class="from">
                            <p class="title_area">{{ lang.salesHistory.departure }}</p>
                            <p class="city_name">{{ item.from }}</p>
                        </div>
                        <div class="date">
                            <p class="title_area">{{ lang.salesHistory.departure_date }}</p>
                            <p class="departure_date">{{ item.departure_date }}</p>
                        </div>
                        <div class="departure_time">
                            <p class="title_area">{{ lang.salesHistory.departure_time }}</p>
                            <p class="time">{{ item.departure_time }}</p>
                        </div>
                    </div>
                    <div class="third_box">
                        <div class="destination">
                            <p class="title_area">{{ lang.salesHistory.arrival }}</p>
                            <p class="city_destination">{{ item.to }}</p>
                        </div>
                        <div class="seat">
                            <p class="title_area">{{ lang.salesHistory.seat }}</p>
                            <p class="seat_number">{{ item.seat_number }}</p>
                        </div>
                        <div class="destination_time">
                            <p class="title_area">{{ lang.salesHistory.arrival_time }}</p>
                            <p class="time">{{ item.arrival_time }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="data.items && data.items.length >= perPage && data.items.length < total">
                <v-btn class="submit gradiented no-margins-vertical" :loading="listLoading" @click="showMore">
                    {{ lang.showMore }}
                </v-btn>
            </div>
        </section>
        <div class="centered" v-else>
            <h2>{{ lang.salesHistory.no_sales }}</h2>
        </div>
        <Footer :key="$store.state.new_notifications"/>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>
<style scoped src="../css/SalesHistory.css"/>
<script>
import Header from '../../components/Header'
import lang from '../../translations'
import Footer from '../../components/Footer'
import {imagesPathRewrite, salesHistoryPerPage} from '../../config'
import VLoading from '../../components/Loading'

export default {
    components: {VLoading, Footer, Header},
    methods: {
        showMore() {
            this.listLoading = true
            this.skip = this.skip + this.perPage
            this.$store.dispatch('apiCall', {actionName: 'getSalesHistory', data: {lang: this.$store.state.locale, skip: this.skip, mobile: true}}).then(data => {
                this.data.items = this.data.items.concat(data.data.items)
                this.listLoading = false
            }).catch(e => {
                console.log(e)
            })
        }
    },
    data() {
        return {
            parent: this.$route.meta.parent,
            listLoading: false,
            isLoading: true,
            data: [],
            total: 0,
            perPage: salesHistoryPerPage,
            skip: 0,
            title: lang[this.$store.state.locale].salesHistory.title,
            lang: lang[this.$store.state.locale],
            imagesPathRewrite: imagesPathRewrite

        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {actionName: 'getSalesHistory', data: {lang: this.$store.state.locale, skip: this.skip, mobile: true}}).then(data => {
            this.data = data.data
            this.total = data.data.total_sold
            this.isLoading = false
        }).catch(e => {
            console.log(e)
        })
    }
}
</script>
