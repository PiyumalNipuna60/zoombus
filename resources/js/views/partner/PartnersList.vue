<template>
    <div v-if="!isLoading">
        <Header :title="title" :show-back="true" :show-logo="false" :hideLogoText="true" :parent="parent"/>
        <section v-if="data.items && data.items.length">
            <div class="list" v-if="data.items && data.items.length">
                <router-link :to="{name: 'partnerDetails', params: { user_id: item.user.id }}" v-for="(item, k) in data.items" :key="k">
                    <div class="partner">
                        <img :src="item.avatar" :alt="item.user.name">
                        <div class="description">
                            <h3 class="transport_title">{{ item.user.name }}</h3>
                            <div class="car_description">{{ item.roles }}</div>
                        </div>
                        <div class="last_content">
                            <div class="percent">{{ item.percent }}</div>
                            <div class="date">{{ item.date }}</div>
                        </div>
                    </div>
                </router-link>
            </div>
            <div v-if="data.items.length >= perPage && data.items.length < total">
                <v-btn class="submit gradiented" :loading="listLoading" @click="showMore">
                    {{ lang.showMore }}
                </v-btn>
            </div>
        </section>
        <div class="centered" v-else>
            <h2>{{ lang.partnerList.no_records }}</h2>
        </div>
        <Footer :key="$store.state.new_notifications"/>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>
<style scoped src="../css/PartnerList.css"/>
<script>
import Header from '../../components/Header'
import lang from '../../translations'
import Footer from '../../components/Footer'
import {imagesPathRewrite, partnerListPerPage} from '../../config'
import VLoading from '../../components/Loading'
import SaleStats from '../../components/SaleStats'

export default {
    components: {SaleStats, VLoading, Footer, Header},
    methods: {
        showMore() {
            this.listLoading = true
            this.skip = this.skip + this.perPage
            this.$store.dispatch('apiCall', {actionName: 'getPartnerList', data: {lang: this.$store.state.locale, skip: this.skip, mobile: true}}).then(data => {
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
            perPage: partnerListPerPage,
            skip: 0,
            title: lang[this.$store.state.locale].partnerList.title,
            lang: lang[this.$store.state.locale],
            imagesPathRewrite: imagesPathRewrite,
            partnerListStats: [
                {
                    label: lang[this.$store.state.locale].partnerList.total,
                    value: null
                }
            ]
        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {actionName: 'getPartnerList', data: {lang: this.$store.state.locale, skip: this.skip, mobile: true}}).then(data => {
            this.data = data.data
            this.total = data.data.total
            this.partnerListStats[0].value = data.data.total
            this.isLoading = false
        }).catch(e => {
            console.log(e)
        })
    }
}
</script>
