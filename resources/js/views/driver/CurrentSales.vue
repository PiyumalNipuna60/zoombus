<template>
    <div v-if="!isLoading">
        <Header :title="title" :show-back="true" :show-logo="false" :hideLogoText="true" :parent="parent"/>
        <section v-if="data.items && data.items.length">
            <div class="list" v-if="data.items && data.items.length">
                <router-link :to="{name: 'routeEdit', params: {id: item.id }}" :key="i" v-for="(item, i) in data.items" @click.native="setHideForm">
                    <div class="item">
                        <div class="image_block">
                            <img :src="imagesPathRewrite('transport/minibus.svg')" alt="Bus">
                        </div>
                        <div class="description">
                            <p class="route_title">ID: <span>{{ item.route_id }} / {{ item.departure_date }}</span></p>
                            <p class="route_destination">
                                {{ item.from }}
                                <img :src="imagesPathRewrite('arrow-right.svg')" alt="Arrow">
                                {{ item.to }}
                            </p>
                        </div>
                        <div class="content">
                            <div class="amount">{{ item.total_sold }} <span>gel</span></div>
                            <p class="details error--text">{{ lang.salesByRoute.details }}</p>
                        </div>
                    </div>
                </router-link>
            </div>
            <div v-if="data.items && data.items.length >= perPage && data.items.length < total">
                <v-btn class="submit gradiented no-margins-vertical" :loading="listLoading" @click="showMore">
                    {{ lang.showMore }}
                </v-btn>
            </div>
        </section>
        <div class="centered" v-else>
            <h2>{{ lang.salesByRoute.no_sales }}</h2>
        </div>
        <Footer :key="$store.state.new_notifications"/>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>
<style scoped src="../css/CurrentSales.css"/>
<script>
import Header from '../../components/Header'
import lang from '../../translations'
import Footer from '../../components/Footer'
import {imagesPathRewrite, currentRouteSalesPerPage} from '../../config'
import VLoading from '../../components/Loading'
import SaleStats from '../../components/SaleStats'

export default {
    components: {SaleStats, VLoading, Footer, Header},
    methods: {
        showMore() {
            this.listLoading = true
            this.skip = this.skip + this.perPage
            this.$store.dispatch('apiCall', {
                actionName: 'getSalesByRoute',
                data: {lang: this.$store.state.locale, skip: this.skip, mobile: true}
            }).then(data => {
                this.data.items = this.data.items.concat(data.data.items)
                this.listLoading = false
            }).catch(e => {
                console.log(e)
            })
        },
        setHideForm() {
            this.$store.commit('setRouteEditHideForm', 1)
            this.$store.commit('setRouteEditHidePreserve', 0)
        }
    },
    data() {
        return {
            parent: this.$route.meta.parent,
            listLoading: false,
            isLoading: true,
            data: [],
            total: 0,
            perPage: currentRouteSalesPerPage,
            skip: 0,
            title: lang[this.$store.state.locale].salesByRoute.title,
            lang: lang[this.$store.state.locale],
            imagesPathRewrite: imagesPathRewrite,

            saleStatsItems: [
                {
                    label: lang[this.$store.state.locale].salesByRoute.total_sold,
                    value: null,
                    isCurrency: true
                }
            ]
        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {actionName: 'getSalesByRoute', data: {lang: this.$store.state.locale, skip: this.skip, mobile: true}}).then(data => {
            this.data = data.data
            this.total = data.data.total_sold
            this.saleStatsItems[0].value = data.data.total_sold_amount
            this.isLoading = false
        }).catch(e => {
            console.log(e)
        })
    }
}
</script>
