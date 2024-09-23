<template>
    <div v-if="!isLoading">
        <Header :title="title" :parent="parent" :showLogo="false"/>
        <section>
            <div v-if="items && items.length">
                <div class="transport" v-for="(item,k) in items" :key="k">
                    <div class="first_transport_box">
                        <img :class="item.route_types.key" :src="imagesPathRewrite('cars/' + item.route_types.key +'.svg')" :alt="item.route_types.key">
                    </div>
                    <div class="second_transport_box">
                        <div class="zoom_tr_title">
                            <h3 class="transport_title">{{ item.route_types.translated.name.split('(')[0] }}</h3>
                        </div>
                        <div class="zoom_transport_type">
                            <h4 class="title_vehicle">{{ item.manufacturers.manufacturer_name + ' ' + item.model + ' / ' + item.license_plate }}</h4>
                        </div>
                    </div>
                    <div class="third_transport_box">
                        <p class="date_add">{{ item.created_at_formatted }}</p>
                        <router-link :to="{name: 'vehicleEdit', params: {id: item.id}}" class="button_view">
                            <v-icon color="white" size="16">{{ editIcon }}</v-icon>
                            {{ lang[$store.state.locale].vehicleList.change }}
                        </router-link>
                    </div>
                </div>
                <div v-if="items.length >= vehiclesPerPage && items.length < total">
                    <v-btn class="submit gradiented" :loading="listLoading" @click="showMore">
                        {{ lang[$store.state.locale].showMore }}
                    </v-btn>
                </div>
            </div>
            <div v-else>
                <h1 class="no_results">{{ lang[$store.state.locale].no_results }}</h1>
            </div>
        </section>
        <Footer :key="$store.state.new_notifications"/>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>
<script>
import lang from '../../translations'

import Header from '../../components/Header'
import Footer from '../../components/Footer'
import VLoading from '../../components/Loading'

import {imagesPathRewrite, vehiclesPerPage} from '../../config'

import {mdiPencilBoxOutline} from '@mdi/js'

export default {
    components: {VLoading, Header, Footer},
    methods: {
        showMore() {
            this.listLoading = true
            this.skip = this.skip + this.vehiclesPerPage
            this.$store.dispatch('apiCall', {actionName: 'getVehicleList', data: {lang: this.$store.state.locale, skip: this.skip}}).then(data => {
                this.items = this.items.concat(data.data.items)
                this.listLoading = false
            }).catch(e => {
                console.log(e)
            })
        }
    },
    data() {
        return {
            listLoading: false,
            vehiclesPerPage: vehiclesPerPage,
            skip: 0,
            editIcon: mdiPencilBoxOutline,
            imagesPathRewrite: imagesPathRewrite,
            parent: this.$route.meta.parent,
            lang: lang,
            title: lang[this.$store.state.locale].vehicleList.title,
            isLoading: true,
            items: [],
            total: 0
        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {actionName: 'getVehicleList', data: {lang: this.$store.state.locale, skip: this.skip}}).then(data => {
            this.items = data.data.items
            this.total = data.data.total
            this.isLoading = false
        }).catch(e => {
            console.log(e)
        })
    }
}
</script>
<style scoped src="../css/vehicleList.css"/>
