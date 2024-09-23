<template>
    <div v-if="!isLoading">
        <Header :title="title" :show-back="true" :show-logo="false" :hideLogoText="true" :parent="parent"/>
        <section v-if="data.items && data.items.length">
            <SaleStats :items="saleStatsItems"/>
            <div class="list" v-if="data.items && data.items.length">
                <div class="item" :key="k" v-for="(item,k) in data.items">
                    <div class="image_block">
                        <img :src="imagesPathRewrite('transport/minibus.svg')" alt="Bus">
                    </div>
                    <div class="description">
                        <p class="route_title">{{ lang.salesByRoute.route }}: <span>{{ item.route.cities_from.code + item.route_id }}</span></p>
                        <p class="route_destination">
                            {{ item.route.cities_from.translated.name }}
                            <img :src="imagesPathRewrite('arrow-right.svg')" alt="Arrow">
                            {{ item.route.cities_to.translated.name }}
                        </p>
                    </div>
                    <div class="content">
                        <div class="amount error--text">{{ item.amount }} <span>gel</span></div>
                        <p class="date">{{ item.route.departure_date }}</p>
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
            <h2>{{ lang.driverFines.no_fines }}</h2>
        </div>
        <Footer :key="$store.state.new_notifications"/>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>
<style scoped src="../css/Fines.css"/>
<script>
import Header from '../../components/Header'
import lang from '../../translations'
import Footer from '../../components/Footer'
import {finesPerPage, imagesPathRewrite} from '../../config'
import VLoading from '../../components/Loading'
import SaleStats from '../../components/SaleStats'

export default {
    components: {SaleStats, VLoading, Footer, Header},
    methods: {
        showMore() {
            this.listLoading = true
            this.skip = this.skip + this.perPage
            this.$store.dispatch('apiCall', {
                actionName: 'getFines',
                data: {lang: this.$store.state.locale, skip: this.skip}
            }).then(data => {
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
            perPage: finesPerPage,
            skip: 0,
            title: lang[this.$store.state.locale].driverFines.title,
            lang: lang[this.$store.state.locale],
            imagesPathRewrite: imagesPathRewrite,
            saleStatsItems: [
                {
                    label: lang[this.$store.state.locale].driverFines.total_fined,
                    value: null,
                    isCurrency: true,
                    class: 'error--text'
                }
            ]
        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {actionName: 'getFines', data: {lang: this.$store.state.locale, skip: this.skip}}).then(data => {
            this.data = data.data
            this.total = data.data.total
            this.saleStatsItems[0].value = data.data.total_fined
            this.isLoading = false
        }).catch(e => {
            console.log(e)
        })
    }
}
</script>
