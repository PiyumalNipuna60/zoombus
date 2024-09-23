<template>
    <div v-if="!isLoading">
        <Header :title="title" :show-back="true" :show-logo="false" :hideLogoText="true" :parent="parent"/>
        <section v-if="data.items && data.items.length">
            <SaleStats :items="partnerSaleStats"/>
            <div class="list" v-if="data.items && data.items.length">
                <div class="partner" v-for="(item,k) in data.items" :key="k">
                    <img :src="imagesPathRewrite('cars/'+item.vehicle_type_id+'.jpg')" alt="">
                    <div class="description">
                        <h3 class="transport_title">{{ item.user_name }}</h3>
                        <div class="car_description">{{ item.vehicle_type }} / <span>{{ item.license_plate }}</span></div>
                        <div class="route_description">
                            {{ item.from }}
                            <img :src="imagesPathRewrite('arrow-right.svg')" alt="Arrow"> {{ item.to }}
                        </div>
                    </div>
                    <div class="last_content">
                        <div class="percent">{{ item.percent }} / {{ item.amount }}</div>
                        <div class="route_id">{{ item.route_id }}</div>
                        <div class="date">{{ item.departure_date }}</div>
                    </div>
                </div>
            </div>
            <div v-if="data.items && data.items.length >= perPage && data.items.length < total">
                <v-btn class="submit gradiented" :loading="listLoading" @click="showMore">
                    {{ lang.showMore }}
                </v-btn>
            </div>
        </section>
        <div class="centered" v-else>
            <h2>{{ lang.partnerSales.no_sales }}</h2>
        </div>
        <Footer :key="$store.state.new_notifications"/>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>
<style scoped src="../css/PartnerSales.css"/>
<script>
import Header from '../../components/Header'
import lang from '../../translations'
import Footer from '../../components/Footer'
import {imagesPathRewrite, partnerSalesPerPage} from '../../config'
import VLoading from '../../components/Loading'
import SaleStats from '../../components/SaleStats'

export default {
    components: {SaleStats, VLoading, Footer, Header},
    methods: {
        showMore() {
            this.listLoading = true
            this.skip = this.skip + this.perPage
            this.$store.dispatch('apiCall', {actionName: 'getPartnerSales', data: {lang: this.$store.state.locale, skip: this.skip, mobile: true}}).then(data => {
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
            perPage: partnerSalesPerPage,
            skip: 0,
            title: lang[this.$store.state.locale].partnerSales.title,
            lang: lang[this.$store.state.locale],
            imagesPathRewrite: imagesPathRewrite,

            partnerSaleStats: [
                {
                    label: lang[this.$store.state.locale].partnerSales.total_sold,
                    value: null,
                    isCurrency: true
                }
            ]
        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {actionName: 'getPartnerSales', data: {lang: this.$store.state.locale, skip: this.skip, mobile: true}}).then(data => {
            this.data = data.data
            this.partnerSaleStats[0].value = data.data.total_sold_amount
            this.total = data.data.total_sold
            this.isLoading = false
        }).catch(e => {
            console.log(e)
        })
    }
}
</script>
