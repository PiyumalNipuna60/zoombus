<template>
    <div v-if="!isLoading">
        <Header :title="title" :parent="parent" :hide-back="isWizard" :showLogo="false" :is-child="isWizard"/>
        <section>
            <div class="header_icon" v-if="!isWizard">
                <img :src="imagesPathRewrite('driver/route-creation.svg')" :alt="title">
                <p class="description">{{ caption2 }}</p>
            </div>
            <wizard-steps v-if="isWizard"/>
            <vzForm v-bind="formParams" @backAction="goBackAction"/>
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
import vzForm from '../../components/Form'
import VLoading from '../../components/Loading'
import {imagesPathRewrite} from '../../config'
import WizardSteps from '../../components/WizardSteps'
import validations from "../../validations";

export default {
    components: {WizardSteps, VLoading, vzForm, Header, Footer},
    props: {
        isWizard: {
            type: Boolean
        }
    },
    methods: {
        goBackAction() {
            this.$store.commit('setWizardStep', 3)
        },
        typeChange(type) {
            this.$store.dispatch('apiCall', {
                actionName: 'getVehiclesByRouteType',
                data: {
                    lang: this.$store.state.locale,
                    route_type: type
                }
            }).then(data => {
                this.formParams.items.find(d => d.id === 'model').values = data.data.models.map(d => {
                    return {value: d.id, text: d.name}
                })
                this.formParams.items.find(d => d.id === 'model').value = this.formParams.items.find(d => d.id === 'model').values[0].value
                this.formParams.items.find(d => d.name === 'vehicle_id').values = data.data.license_plates.map(d => {
                    return {value: d.id, text: d.name}
                })
                this.formParams.items.find(d => d.name === 'vehicle_id').value = this.formParams.items.find(d => d.name === 'vehicle_id').values[0].value
            }).catch(e => {
                console.log(e)
            })
        },
        modelChange(model) {
            this.$store.dispatch('apiCall', {
                actionName: 'getLicensesByVehicle',
                data: {
                    lang: this.$store.state.locale,
                    model: model
                }
            }).then(data => {
                this.formParams.items.find(d => d.name === 'vehicle_id').values = data.data.map(d => {
                    return {value: d.id, text: d.license_plate}
                })
                this.formParams.items.find(d => d.name === 'vehicle_id').value = this.formParams.items.find(d => d.name === 'vehicle_id').values[0].value
            }).catch(e => {
                console.log(e)
            })
        },
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
        routeTypeChange(value) {
            this.formParams.items.find(d => d.name === 'departure_date').multiple = (value !== 1)
            this.formParams.items.find(d => d.name === 'departure_date').hardLabel = (value !== 1) ? lang[this.$store.state.locale].routeAdd.fields.labels.departure_dates : lang[this.$store.state.locale].routeAdd.fields.labels.departure_date
            this.formParams.items.find(d => d.name === 'departure_date').value = (value === 1) ? null : []
        },
        afterData(data) {
            this.formParams.items.find(d => d.id === 'type').values = data.route_types.map(d => {
                return {value: d.id, text: d.translated.name}
            })
            this.formParams.items.find(d => d.id === 'model').values = data.models.map(d => {
                return {value: d.id, text: d.name}
            })
            this.formParams.items.find(d => d.name === 'vehicle_id').values = data.license_plates.map(d => {
                return {value: d.id, text: d.name}
            })

            this.formParams.items.find(d => d.name === 'currency_id').values = [
                {value: 1, text: 'GEL'} // Hardcoded
            ]


            this.formParams.items.find(d => d.id === 'type').value = this.formParams.items.find(d => d.id === 'type').values[0].value
            this.formParams.items.find(d => d.id === 'model').value = this.formParams.items.find(d => d.id === 'model').values[0].value
            this.formParams.items.find(d => d.name === 'vehicle_id').value = this.formParams.items.find(d => d.name === 'vehicle_id').values[0].value
            this.formParams.items.find(d => d.name === 'from').values = data.tenCities.map(d => d.translated.name)
            this.formParams.items.find(d => d.name === 'to').values = data.tenCities.map(d => d.translated.name)
            this.formParams.items.find(d => d.name === 'from').defaults = data.tenCities.map(d => d.translated.name)
            this.formParams.items.find(d => d.name === 'to').defaults = data.tenCities.map(d => d.translated.name)
        }
    },
    data() {
        return {
            imagesPathRewrite: imagesPathRewrite,
            parent: this.$route.meta.parent,
            title: lang[this.$store.state.locale].routeAdd.title,
            caption2: lang[this.$store.state.locale].routeAdd.caption2,
            isLoading: true,
            formParams: {
                formId: 'routeAdd',
                formClass: 'default-form',
                hasRow: true,
                submit: {
                    icon: (this.isWizard) ? 'next' : 'mdi-content-save',
                    class: 'submit',
                    large: true,
                    block: true,
                    loading: false,
                    column: (this.isWizard) ? 6 : 12,
                    text: (this.isWizard) ? lang[this.$store.state.locale].wizard.finish : lang[this.$store.state.locale].routeAdd.saveButton
                },
                items: [
                    {
                        hardLabel: lang[this.$store.state.locale].routeAdd.fields.labels.route_type,
                        field: 'select',
                        id: 'type',
                        name: 'type_r',
                        excludeFromData: true,
                        change: this.typeChange,
                        values: [],
                        value: null
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].routeAdd.fields.labels.vehicle,
                        field: 'select',
                        id: 'model',
                        name: 'model',
                        excludeFromData: true,
                        change: this.modelChange,
                        values: [],
                        value: null
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].routeAdd.fields.labels.license_number,
                        field: 'select',
                        name: 'vehicle_id',
                        values: [],
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
                        hardLabel: lang[this.$store.state.locale].routeAdd.fields.labels.type,
                        field: 'select',
                        name: 'type',
                        id: 'routeType',
                        change: this.routeTypeChange,
                        values: lang[this.$store.state.locale].routeAdd.routeTypes,
                        value: 2,
                        column: 12
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].routeAdd.fields.labels.departure_dates,
                        field: 'datepicker',
                        modal: false,
                        name: 'departure_date',
                        min: new Date().toISOString(),
                        multiple: true,
                        value: [],
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
                        value: '00:00',
                        values: lang[this.$store.state.locale].stoppingTimes,
                        modal: false,
                        column: 12
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
        if (!this.isWizard) {
            this.$store.dispatch('apiCall', {
                actionName: 'routeConstructor',
                data: {lang: this.$store.state.locale}
            }).then(data => {
                this.afterData(data.data)
                this.isLoading = false
            }).catch(e => {
                console.log(e)
            })
        } else {
            this.$store.dispatch('apiCall', {
                actionName: 'wizardByStep',
                data: {
                    lang: this.$store.state.locale,
                    step: 4
                }
            }).then(data => {
                this.$store.commit('setWizardStep', 4)
                this.afterData(data.data)
                this.formParams.items.push({
                    field: 'hidden',
                    name: 'isWizard',
                    value: true
                })
                this.formParams.goBack = {
                    icon: 'prev',
                    class: 'submit',
                    large: true,
                    block: true,
                    loading: false,
                    text: lang[this.$store.state.locale].wizard.back,
                    column: 6,
                    action: this.goBackAction
                }
                this.isLoading = false
            }).catch(e => {
                this.isLoading = false
                console.log(e)
            })
        }
    }
}
</script>
<style scoped src="../css/routeAdd.css"/>
