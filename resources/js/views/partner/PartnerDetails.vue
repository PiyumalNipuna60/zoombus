<template>
    <div v-if="!isLoading">
        <Header :title="title" :show-back="true" :show-logo="false" :hideLogoText="true" :parent="parent"/>
        <section>
            <div class="partner_info">
                <div class="info" v-for="(key,k) in userKeys" :key="k">
                    <h2>{{ lang.partnerDetails.fields[key] }}</h2>
                    <div class="total">{{ data.user[key] }}</div>
                </div>
            </div>
            <div class="list" v-if="data.vehicles && data.vehicles.length">
                <div class="item">
                    <div class="transport" v-for="(item,k) in data.vehicles" :key="k">
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
                        </div>
                    </div>
                </div>
                <div v-if="data.vehicles.length >= perPage && data.vehicles.length < total">
                    <v-btn class="submit gradiented" :loading="listLoading" @click="showMore">
                        {{ lang.showMore }}
                    </v-btn>
                </div>
            </div>
        </section>
        <Footer :key="$store.state.new_notifications"/>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>
<style scoped src="../css/PartnerDetails.css"/>
<script>
import Header from '../../components/Header'
import lang from '../../translations'
import Footer from '../../components/Footer'
import {imagesPathRewrite, partnerListVehiclesPerPage} from '../../config'
import VLoading from '../../components/Loading'

export default {
    components: {VLoading, Footer, Header},
    methods: {
        showMore() {
            this.listLoading = true
            this.skip = this.skip + this.perPage
            this.$store.dispatch('apiCall', {
                actionName: 'getPartnerDetails',
                data: {
                    lang: this.$store.state.locale,
                    skip: this.skip,
                    mobile: true,
                    user_id: this.$route.params.user_id
                }
            }).then(data => {
                this.data.vehicles = this.data.vehicles.concat(data.data.vehicles)
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
            userKeys: [],
            total: 0,
            perPage: partnerListVehiclesPerPage,
            skip: 0,
            title: lang[this.$store.state.locale].partnerDetails.title,
            lang: lang[this.$store.state.locale],
            imagesPathRewrite: imagesPathRewrite

        }
    },
    mounted() {
        document.title = this.title
        this.isLoading = false
        this.$store.dispatch('apiCall', {
            actionName: 'getPartnerDetails',
            data: {
                lang: this.$store.state.locale,
                skip: this.skip,
                mobile: true,
                user_id: this.$route.params.user_id
            }
        }).then(data => {
            this.data = data.data
            this.userKeys = Object.keys(data.data.user)
            this.total = data.data.total
            this.isLoading = false
        }).catch(e => {
            console.log(e)
        })
    }
}
</script>
