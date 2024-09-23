<template>
    <div v-if="!isLoading">
        <Header :title="title" :parent="parent" :showLogo="false"/>
        <section>
            <div v-if="!$store.state.routeEditHideForm">
                <div class="header_icon">
                    <img :src="imagesPathRewrite('driver/route-creation.svg')" :alt="title">
                    <p class="description">{{ caption2 }}</p>
                </div>
                <vzForm v-bind="formParams"/>
            </div>
            <div v-else>
                <DriverSalesRouteInfo v-bind="routeInfoParams"/>
            </div>
            <RouteCountdown v-bind="routeCountdownParams"/>
            <SalesListByRoute v-bind="salesListParams"/>
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
                            <v-btn outlined class="popupButton" :loading="isLoadingDelete" @click="deleteRoute">
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

import Header from '../../components/Header'
import Footer from '../../components/Footer'
import vzForm from '../../components/Form'
import VLoading from '../../components/Loading'
import {imagesPathRewrite} from '../../config'
import SalesListByRoute from '../../components/SalesListByRoute'
import DriverSalesRouteInfo from '../../components/DriverSalesRouteInfo'
import RouteCountdown from '../../components/RouteCountdown'
import modals from '../../modals'

export default {
    components: {RouteCountdown, DriverSalesRouteInfo, SalesListByRoute, VLoading, vzForm, Header, Footer},
    methods: {
        search(event, name, action) {
            const field = this.formParams.items.find(d => d.name === name)
            if (event.target.value.length > 1 && !field.isLoading) {
                field.isLoading = true
                this.$store.dispatch('apiCall', {
                    actionName: action,
                    data: {
                        q: event.target.value,
                        lang: this.$store.state.locale
                    }
                }).then(d => {
                    field.isLoading = false
                    field.values = d.data.map(data => {
                        return data.name
                    })
                }).catch(e => {
                    field.isLoading = false
                })
            } else {
                field.values = field.defaults
            }
        },
        searchCities(event, name) {
            this.search(event, name, 'searchCities')
        },
        searchAddresses(event, name) {
            this.search(event, name, 'searchAddresses')
        },
        deleteRoute() {
            let action
            let redirect
            if (this.salesListParams.data && this.salesListParams.data.length) {
                action = 'cancelRoute'
                redirect = 'fines'
            } else {
                action = 'deleteRoute'
                redirect = 'driverArea'
            }
            this.isLoadingDelete = true
            this.$store.dispatch('apiCall', {actionName: action, data: {lang: this.$store.state.locale, id: this.$route.params.id}}).then(data => {
                this.$router.push({name: redirect})
            }).catch(e => {
                this.scrollTo('app')
                this.success = null
                this.error = e.text
                this.dialog = false
                this.isLoadingDelete = false
            })
        },
        removeRoute() {
            this.dialog = true
            this.modalTitle = modals[this.$store.state.locale].routeCancel.title
            this.modalText = this.deleteAlertify['confirm-msg']
        }
    },
    data() {
        return {
            imagesPathRewrite: imagesPathRewrite,
            parent: this.$route.meta.parent,
            title: lang[this.$store.state.locale].routeEdit.title,
            caption2: lang[this.$store.state.locale].routeEdit.caption2,
            lang: lang[this.$store.state.locale],
            isLoading: true,
            isLoadingDelete: false,
            modalTitle: null,
            modalText: null,
            deleteAlertify: null,
            dialog: null,
            routeCountdownParams: {
                data: {
                    vehicle: {},
                    scheme: null,
                    time: null,
                    sales: [],
                    preserved: []
                },
                removeRoute: this.removeRoute
            },
            routeInfoParams: {
                data: {}
            },
            salesListParams: {
                data: [],
                route: {}
            },
            formParams: {
                formId: 'routeEdit',
                formClass: 'default-form',
                hasRow: true,
                submit: {
                    icon: 'mdi-content-save',
                    class: 'submit',
                    large: true,
                    block: true,
                    loading: false,
                    text: lang[this.$store.state.locale].routeEdit.saveButton,
                    column: 6,
                    color: 'primary'
                },
                remove: {
                    icon: true,
                    class: 'submit',
                    large: true,
                    block: true,
                    loading: false,
                    column: 6,
                    color: 'error',
                    text: lang[this.$store.state.locale].routeEdit.deleteButton,
                    action: this.removeRoute
                },
                items: [
                    {
                        field: 'hidden',
                        name: 'id',
                        value: this.$route.params.id
                    },
                    {
                        field: 'hidden',
                        name: 'vehicle_id',
                        value: null
                    },
                    {
                        field: 'hidden',
                        name: 'type',
                        value: null
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].routeAdd.fields.labels.from,
                        field: 'autocomplete',
                        name: 'from',
                        values: [],
                        defaults: [],
                        value: null,
                        change: this.searchCities,
                        arrowRight: true,
                        isLoading: false,
                        column: 6
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].routeAdd.fields.labels.to,
                        field: 'autocomplete',
                        name: 'to',
                        values: [],
                        defaults: [],
                        value: null,
                        change: this.searchCities,
                        isLoading: false,
                        column: 6
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].routeAdd.fields.labels.departure_date,
                        field: 'datepicker',
                        modal: false,
                        name: 'departure_date',
                        min: new Date().toISOString(),
                        value: null,
                        column: 6
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].routeAdd.fields.labels.departure_time,
                        field: 'time',
                        name: 'departure_time',
                        value: null,
                        modal: false,
                        column: 6
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].routeAdd.fields.labels.route_duration,
                        field: 'input',
                        name: 'route_duration_hour',
                        value: null,
                        modal: false,
                        column: 12
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].routeAdd.fields.labels.stoppage_time,
                        field: 'select',
                        name: 'stopping_time',
                        value: null,
                        values: lang[this.$store.state.locale].stoppingTimes
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].routeAdd.fields.labels.currency,
                        field: 'select',
                        name: 'currency_id',
                        values: [],
                        value: 1,
                        column: 6
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].routeAdd.fields.labels.price,
                        field: 'input',
                        name: 'price',
                        column: 6,
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].routeAdd.fields.labels.departure_address,
                        field: 'autocomplete',
                        name: 'from_address',
                        interlinked: true,
                        values: [],
                        value: null,
                        change: this.searchAddresses,
                        isLoading: false
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].routeAdd.fields.labels.arrival_address,
                        field: 'autocomplete',
                        name: 'to_address',
                        values: [],
                        value: null,
                        change: this.searchAddresses,
                        isLoading: false
                    }
                ]
            }
        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {actionName: 'routeConstructor', data: {lang: this.$store.state.locale, id: this.$route.params.id, width: window.innerWidth}}).then(data => {
            this.formParams.items.find(d => d.name === 'currency_id').values = [
                {value: 1, text: 'GEL'} // Hardcoded
            ]

            this.parent = (this.$store.state.routeEditHideForm) ? 'currentSales' : this.$route.meta.parent

            this.formParams.items.forEach(d => {
                d.value = data.data.route[d.name]
            })

            this.formParams.items.find(d => d.name === 'from').values = [data.data.route.cities_from.translated.name]
            this.formParams.items.find(d => d.name === 'from').value = data.data.route.cities_from.translated.name
            this.formParams.items.find(d => d.name === 'to').values = [data.data.route.cities_to.translated.name]
            this.formParams.items.find(d => d.name === 'to').value = data.data.route.cities_to.translated.name
            this.formParams.items.find(d => d.name === 'from').defaults = data.data.tenCities.map(d => d.translated.name)
            this.formParams.items.find(d => d.name === 'to').defaults = data.data.tenCities.map(d => d.translated.name)

            this.formParams.items.find(d => d.name === 'from_address').values = [data.data.route.address_from.translated.name]
            this.formParams.items.find(d => d.name === 'from_address').value = data.data.route.address_from.translated.name
            this.formParams.items.find(d => d.name === 'to_address').values = [data.data.route.address_to.translated.name]
            this.formParams.items.find(d => d.name === 'to_address').value = data.data.route.address_to.translated.name

            this.salesListParams.data = data.data.route.sales
            this.salesListParams.route = data.data.route

            this.routeInfoParams.data = data.data.route
            this.routeCountdownParams.data.scheme = data.data.route.vehicles.scheme
            this.routeCountdownParams.data.vehicle.type = data.data.route.vehicles.type
            this.routeCountdownParams.data.preserved = data.data.route.reserved_seats
            this.routeCountdownParams.data.sales = data.data.route.sales
            this.routeCountdownParams.data.time = data.data.route.countdownTimestamp

            this.deleteAlertify = data.data.route.deleteAlertify

            if (this.$store.state.routeEditHideForm) {
                this.title = lang[this.$store.state.locale].routesList.departure_date + ': ' + data.data.route.departure_date_header
            }

            this.isLoading = false
        }).catch(e => {
            this.$router.push({name: this.parent})
            console.log(e)
        })
    }
}
</script>
<style scoped src="../css/routeEdit.css"/>
