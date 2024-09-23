<template>
    <div v-if="!isLoading">
        <Header :title="title" :showBack="true" :showLogo="false"/>
        <section>
            <a :href="($store.state.locale && $store.state.locale !== 'en') ? 'https://zoombus.net/'+$store.state.locale+'/listings' : 'https://zoombus.net/listings'">
                <v-btn class="submit gradiented no-margins-vertical">
                    {{ lang.buyTickets }}
                </v-btn>
            </a>
            <div class="list" v-if="data.items && data.items.length">
                <router-link :to="{name: 'singleTicket', params: {id: item.ticket_number }}" :key="i" v-for="(item, i) in data.items">
                    <div class="item">
                        <div class="image_block">
                            <img :src="imagesPathRewrite('transport/minibus.svg')" alt="Bus">
                        </div>
                        <div class="description">
                            <p class="route_title"><span>{{ item.departure_date }}</span></p>
                            <p class="route_destination">
                                {{ item.from }}
                                <img :src="imagesPathRewrite('arrow-right.svg')" alt="Arrow">
                                {{ item.to }}
                            </p>
                        </div>
                        <div class="content">
                            <div class="amount">{{ item.price }} <span>gel</span></div>
                            <p class="details error--text">{{ lang.ticketList.details }}</p>
                        </div>
                    </div>
                </router-link>
            </div>
            <div class="centered" v-else>
                <h2>{{ lang.ticketList.no_data }}</h2>
            </div>
            <div v-if="data.items && data.items.length >= perPage && data.items.length < total">
                <v-btn class="submit gradiented no-margins-vertical" :loading="listLoading" @click="showMore">
                    {{ lang.showMore }}
                </v-btn>
            </div>
        </section>
        <Footer :key="$store.state.new_notifications"/>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>

<script>
import lang from "../../translations"
import VLoading from "../../components/Loading"
import {boughtTicketsPerPage, imagesPathRewrite} from "../../config"
import Header from "../../components/Header"
import Footer from "../../components/Footer"

export default {
    name: "ticketList",
    components: {VLoading, Header, Footer},
    methods: {
        showMore() {
            this.listLoading = true
            this.skip = this.skip + this.perPage
            this.$store.dispatch('apiCall', {
                actionName: 'getTicketList',
                data: {lang: this.$store.state.locale, skip: this.skip, mobile: true}
            }).then(data => {
                this.data.items = this.data.items.concat(data.data.items)
                this.listLoading = false
            }).catch(e => {
                console.log(e)
            })
        },
    },
    data() {
        return {
            title: lang[this.$store.state.locale].ticketList.title,
            isLoading: true,
            listLoading: false,
            data: [],
            total: 0,
            perPage: boughtTicketsPerPage,
            skip: 0,
            imagesPathRewrite: imagesPathRewrite,
            lang: lang[this.$store.state.locale]
        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {actionName: 'getTicketList', data: {lang: this.$store.state.locale, skip: this.skip, mobile: true}}).then(data => {
            this.data = data.data
            this.total = data.data.total_sold
            this.isLoading = false
        }).catch(e => {
            console.log(e)
        })
    }
}
</script>
<style scoped src="../css/TicketList.css"/>
