<template>
    <div v-if="!isLoading">
        <Header :title="title" :parent="parent" :showLogo="false"/>
        <section>
            <v-alert type="error" v-if="error">
                {{ error }}
            </v-alert>
            <v-alert type="success" v-if="success">
                {{ success }}
            </v-alert>
            <div v-if="items && items.length" class="list">
                <router-link :to="{name: 'routeEdit', params: {id: item.id}}" :key="i" v-for="(item, i) in items">
                    <div class="item">
                        <div class="image_block">
                            <img :src="imagesPathRewrite('transport/minibus.svg')" alt="Bus">
                        </div>
                        <div class="description">
                            <p class="route_title">ID: <span>{{ item.cities_from.city_code + item.id }}</span></p>
                            <p class="route_destination">
                                {{ item.cities_from.translated.name }}
                                <img :src="imagesPathRewrite('arrow-right.svg')" alt="Arrow">
                                {{ item.cities_to.translated.name }}
                            </p>
                        </div>
                        <div class="content">
                            <div class="amount">{{ item.departure_date }}</div>
                            <div class="dest_time dest_time_routes_list">
                                <v-icon color="black" size="16" class="mr-3">
                                    {{ clock }}
                                </v-icon>
                                <h3 class="time">{{ item.arrival_time }}</h3>
                            </div>
                        </div>
                    </div>
                </router-link>
            </div>
            <div v-if="items.length >= routesPerPage && items.length < total">
                <v-btn class="submit gradiented" :loading="listLoading" @click="showMore">
                    {{ lang.showMore }}
                </v-btn>
            </div>
            <div v-else>
                <h1 class="no_results">{{ lang.no_results }}</h1>
            </div>
        </section>
        <Footer :key="$store.state.new_notifications"/>
        <v-dialog v-model="dialog" max-width="290">
            <v-card>
                <v-card-title class="headline" v-if="modalTitle && modalTitle.length">{{ modalTitle }}</v-card-title>
                <v-card-text>
                    {{ modalText }}
                </v-card-text>
                <v-card-actions>
                    <v-row class="clearfix no-margins">
                        <v-col :cols="6">
                            <v-btn color="primary" class="popupButton" @click="dialog = false">
                                {{ lang.no }}
                            </v-btn>
                        </v-col>
                        <v-col :cols="6">
                            <v-btn outlined class="popupButton" :loading="isLoadingDelete" @click="deleteRoute(modalId)">
                                {{ lang.yes }}
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>
<script>
import lang from '../../translations'
import modals from '../../modals'

import {scroller} from 'vue-scrollto/src/scrollTo'

import Header from '../../components/Header'
import Footer from '../../components/Footer'
import VLoading from '../../components/Loading'

import {imagesPathRewrite, routesPerPage} from '../../config'

import {mdiPencilBox, mdiDeleteCircle, mdiArrowDown, mdiClockOutline} from '@mdi/js'
import validations from '../../validations'

export default {
    components: {VLoading, Header, Footer},
    methods: {
        scrollTo(div) {
            const scr = scroller()
            scr('#' + div)
        },
        showMore() {
            this.listLoading = true
            this.skip = this.skip + this.routesPerPage
            this.$store.dispatch('apiCall', {actionName: 'getRoutesList', data: {lang: this.$store.state.locale, skip: this.skip}}).then(data => {
                this.items = this.items.concat(data.data.items)
                this.listLoading = false
            }).catch(e => {
                console.log(e)
            })
        },
    },
    data() {
        return {
            listLoading: false,
            routesPerPage: routesPerPage,
            skip: 0,
            imagesPathRewrite: imagesPathRewrite,
            clock: mdiClockOutline,
            parent: this.$route.meta.parent,
            lang: lang[this.$store.state.locale],
            title: lang[this.$store.state.locale].routesList.title,
            isLoading: true,
            items: [],
            total: 0
        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {actionName: 'getRoutesList', data: {lang: this.$store.state.locale, skip: this.skip}}).then(data => {
            this.items = data.data.items
            this.total = data.data.total
            this.isLoading = false
        }).catch(e => {
            console.log(e)
        })
    }
}
</script>
<style scoped src="../css/routeList.css"/>
