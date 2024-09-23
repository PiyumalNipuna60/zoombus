<template>
    <div v-if="!isLoading">
        <Header :title="title" :parent="parent" :hide-back="isWizard" :showLogo="false" :is-child="isWizard"/>
        <section>
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
import validations from '../../validations'

import Header from '../../components/Header'
import Footer from '../../components/Footer'
import vzForm from '../../components/Form'
import VLoading from '../../components/Loading'

import {addNewRow} from '../../config'
import WizardSteps from '../../components/WizardSteps'

export default {
    components: {WizardSteps, VLoading, vzForm, Header, Footer},
    props: {
        isWizard: {
            type: Boolean
        }
    },
    methods: {
        typeChange(d) {
            this.formParams.items.find(d => d.name === 'seat_positioning').routeType = d
            this.formParams.items.find(d => d.name === 'seat_positioning').values = this.schemes[d]
            this.formParams.items.find(d => d.name === 'seat_positioning').value = JSON.stringify(this.schemes[d])
        },
        addNewRowCall(routeType, values) {
            const newValues = addNewRow(routeType, values)
            this.formParams.items.find(d => d.name === 'seat_positioning').values = newValues
            this.formParams.items.find(d => d.name === 'seat_positioning').value = JSON.stringify(newValues)
        },
        removeSeat(rows, value) {
            const newRows = rows.filter(d => value !== d.value)
            this.formParams.items.find(d => d.name === 'seat_positioning').values = newRows
            this.formParams.items.find(d => d.name === 'seat_positioning').value = JSON.stringify(newRows)
        },
        afterData(data) {
            this.formParams.items.find(d => d.name === 'type').values = data.route_types.map(d => {
                return {value: d.id, text: d.translated.name}
            })
            this.formParams.items.find(d => d.name === 'type').value = 1
            this.formParams.items.find(d => d.name === 'fuel_type').values = data.fuel_types.map(d => {
                return {value: d.id, text: d.translated.name}
            })
            this.formParams.items.find(d => d.name === 'manufacturer').values = data.manufacturers.map(d => {
                return {value: d.id, text: d.name}
            })
            this.formParams.items.find(d => d.name === 'fuel_type').value = 1
            this.formParams.items.find(d => d.name === 'country_id').values = data.countries.map(d => {
                return {value: d.id, text: d.translated.name}
            })
            this.formParams.items.find(d => d.name === 'country_id').value = 80
            this.formParams.items.find(d => d.name === 'year').values = data.year_manufactured.map(d => {
                return {value: d, text: d}
            })
            this.schemes = data.scheme
            this.formParams.items.find(d => d.name === 'vehicle_features').values = data.vehicle_specs.map(d => {
                return {value: d.id, text: d.translated.name}
            })
            this.formParams.items.find(d => d.name === 'seat_positioning').values = this.schemes[1]
            this.formParams.items.find(d => d.name === 'seat_positioning').value = JSON.stringify(this.schemes[1])
        },
        goBackAction() {
            this.$store.commit('setWizardStep', 2)
        }
    },
    data() {
        return {
            parent: this.$route.meta.parent,
            title: lang[this.$store.state.locale].vehicleAdd.title,
            isLoading: true,
            schemes: null,
            formParams: {
                formId: 'vehicleAdd',
                formClass: 'default-form',
                hasRow: true,
                submit: {
                    icon: (this.isWizard) ? 'next' : 'mdi-content-save',
                    class: 'submit',
                    large: true,
                    block: true,
                    loading: false,
                    column: (this.isWizard) ? 6 : 12,
                    text: (this.isWizard) ? lang[this.$store.state.locale].wizard.next : lang[this.$store.state.locale].vehicleAdd.saveButton
                },
                checkboxes: [{id: 'vehicle_features', values: []}],
                items: [
                    {
                        hardLabel: lang[this.$store.state.locale].vehicleAdd.fields.labels.type,
                        field: 'select',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].vehicleAdd.type.required
                        ],
                        name: 'type',
                        values: [],
                        change: this.typeChange,
                        value: 0
                    },
                    {
                        field: 'hidden',
                        name: 'width',
                        value: window.innerWidth
                    },
                    {
                        field: 'image',
                        id: 'vehicle_images',
                        name: 'front_side_path',
                        multiple: true,
                        excludeFromData: true,
                        hideLabel: true,
                        forceArray: true,
                        allowedFormats: ['image/jpg', 'image/png', 'image/jpeg'],
                        value: ''
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].vehicleAdd.fields.labels.manufacturer,
                        field: 'select',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].vehicleAdd.manufacturer.required
                        ],
                        name: 'manufacturer',
                        column: 6,
                        class: 'forceEng',
                        values: [],
                        value: ''
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].vehicleAdd.fields.labels.model,
                        field: 'input',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].vehicleAdd.model.required
                        ],
                        name: 'model',
                        column: 6,
                        class: 'forceEng',
                        value: ''
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].vehicleAdd.fields.labels.registration_country,
                        field: 'select',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].vehicleAdd.registration_country.required
                        ],
                        name: 'country_id',
                        column: 6,
                        values: [],
                        value: ''
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].vehicleAdd.fields.labels.license_number,
                        field: 'input',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].vehicleAdd.license_number.required
                        ],
                        column: 6,
                        name: 'license_plate',
                        class: 'forceEng',
                        value: ''
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].vehicleAdd.fields.labels.year,
                        field: 'select',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].vehicleAdd.year.required
                        ],
                        name: 'year',
                        column: 6,
                        values: [],
                        class: 'forceEng',
                        value: ''
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].vehicleAdd.fields.labels.fuel_type,
                        field: 'select',
                        name: 'fuel_type',
                        values: [],
                        value: '',
                        column: 6
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].vehicleAdd.fields.labels.date_of_registration,
                        field: 'datepicker',
                        modal: false,
                        rules: [
                            v => !!v || validations[this.$store.state.locale].vehicleAdd.date_of_registration.required
                        ],
                        name: 'date_of_registration',
                        value: ''
                    },
                    {
                        field: 'checkboxes',
                        hideLabel: true,
                        name: 'vehicle_features',
                        values: [],
                        value: ''
                    },
                    {
                        hideLabel: true,
                        field: 'seats',
                        name: 'seat_positioning',
                        class: 'forceEng',
                        value: [],
                        values: {},
                        routeType: 1,
                        addNew: this.addNewRowCall,
                        removeSeat: this.removeSeat
                    },
                    {
                        field: 'textBreak',
                        class: 'title_section',
                        excludeFromData: true,
                        text: lang[this.$store.state.locale].vehicleAdd.pleaseAddFrontAndBackText
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].vehicleAdd.fields.labels.front_side,
                        field: 'image',
                        id: 'vehicle_license_front_side',
                        name: 'front_side_path',
                        excludeFromData: true,
                        rules: [
                            v => !!v || validations[this.$store.state.locale].vehicleAdd.front_side.required
                        ],
                        allowedFormats: ['image/jpg', 'image/png', 'image/jpeg'],
                        value: ''
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].vehicleAdd.fields.labels.back_side,
                        field: 'image',
                        id: 'vehicle_license_back_side',
                        name: 'back_side_path',
                        excludeFromData: true,
                        rules: [
                            v => !!v || validations[this.$store.state.locale].vehicleAdd.front_side.required
                        ],
                        allowedFormats: ['image/jpg', 'image/png', 'image/jpeg'],
                        value: ''
                    }
                ]
            }
        }
    },
    mounted() {
        document.title = this.title
        if (!this.isWizard) {
            this.$store.dispatch('apiCall', {
                actionName: 'vehicleConstructor',
                data: {
                    lang: this.$store.state.locale,
                    width: window.innerWidth
                }
            }).then(data => {
                if (data.data.driverStatus !== 1) {
                    this.$router.push({name: 'driversLicense'})
                }
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
                    width: window.innerWidth,
                    step: 3
                }
            }).then(data => {
                this.$store.commit('setWizardStep', 3)
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
<style scoped src="../css/vehicleAdd.css"/>
