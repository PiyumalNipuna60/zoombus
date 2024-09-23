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
        goBackAction() {
            this.$store.commit('setWizardStep', 2)
        },
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
            this.formParams.items.find(d => d.name === 'manufacturer').values = data.manufacturers.map(d => {
                return {value: d.id, text: d.name}
            })
            this.formParams.items.find(d => d.name === 'fuel_type').values = data.fuel_types.map(d => {
                return {value: d.id, text: d.translated.name}
            })
            this.formParams.items.find(d => d.name === 'country_id').values = data.countries.map(d => {
                return {value: d.id, text: d.translated.name}
            })
            this.formParams.items.find(d => d.name === 'year').values = data.year_manufactured.map(d => {
                return {value: d, text: d}
            })

            this.formParams.items.find(d => d.name === 'vehicle_features').values = data.vehicle_specs.map(d => {
                return {value: d.id, text: d.translated.name}
            })

            this.formParams.checkboxes.find(d => d.id === 'vehicle_features').values = data.vehicle.fullspecifications.map(d => d.vehicle_specification_id)

            this.formParams.items.forEach(d => {
                d.value = data.vehicle[d.name]
            })

            this.formParams.items.find(d => d.name === 'width').value = window.innerWidth
            this.schemes = data.scheme

            this.formParams.items.find(d => d.name === 'seat_positioning').routeType = data.vehicle.type
            this.formParams.items.find(d => d.name === 'seat_positioning').values = this.schemes[data.vehicle.type]
            this.formParams.items.find(d => d.name === 'seat_positioning').value = JSON.stringify(this.schemes[data.vehicle.type])
            this.formParams.items.find(d => d.name === 'vehicle_features').value = data.vehicle.fullspecifications.map(d => d.vehicle_specification_id)
            this.formParams.items.find(d => d.id === 'vehicle_license_front_side').value = data.front_side
            this.formParams.items.find(d => d.id === 'vehicle_license_back_side').value = data.back_side
            this.formParams.items.find(d => d.id === 'vehicle_images').value = data.vehicle_images
        }
    },
    data() {
        return {
            parent: this.$route.meta.parent,
            title: lang[this.$store.state.locale].vehicleEdit.title,
            isLoading: true,
            schemes: null,
            formParams: {
                formId: 'vehicleEdit',
                formClass: 'default-form',
                hasRow: true,
                submit: {
                    icon: (this.isWizard) ? 'next' : 'mdi-content-save',
                    class: 'submit',
                    large: true,
                    block: true,
                    loading: false,
                    column: (this.isWizard) ? 6 : 12,
                    text: (this.isWizard) ? lang[this.$store.state.locale].wizard.next : lang[this.$store.state.locale].vehicleEdit.saveButton
                },
                checkboxes: [{id: 'vehicle_features', values: []}],
                items: [
                    {
                        field: 'hidden',
                        name: 'id',
                        value: (this.isWizard) ? this.$store.state.wizardVehicleId : this.$route.params.id
                    },
                    {
                        field: 'hidden',
                        name: 'width',
                        value: null,
                    },
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
                        field: 'image',
                        id: 'vehicle_images',
                        name: 'vehicle_images_paths',
                        classChild: 'hidden',
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
                        hardLabel: lang[this.$store.state.locale].vehicleAdd.fields.labels.front_side,
                        field: 'image',
                        id: 'vehicle_license_front_side',
                        name: 'front_side_path',
                        excludeFromData: true,
                        allowedFormats: ['image/jpg', 'image/png', 'image/jpeg'],
                        value: ''
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].vehicleAdd.fields.labels.back_side,
                        field: 'image',
                        id: 'vehicle_license_back_side',
                        name: 'back_side_path',
                        excludeFromData: true,
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
            const urlParams = new URLSearchParams(window.location.search)
            const ticketId = urlParams.get('ticket')
            const messageId = urlParams.get('message')
            this.$store.dispatch('apiCall', {
                actionName: 'vehicleConstructor',
                data: {
                    lang: this.$store.state.locale,
                    id: this.$route.params.id,
                    ticket: ticketId,
                    message: messageId,
                    width: window.innerWidth
                }
            }).then(data => {
                if (data.data.driverStatus !== 1) {
                    this.$router.push({name: 'driversLicense'})
                }
                this.afterData(data.data)
                if (data.data.vehicle.status === 1) {
                    this.formParams.successProp = validations[this.$store.state.locale].vehicleEdit.active
                    const enabledExclusions = ['vehicle_features', 'vehicle_images_paths']
                    this.formParams.items.forEach(d => {
                        if (!enabledExclusions.includes(d.name)) {
                            d.class = (d.class) ? d.class + ' disabledForm' : 'disabledForm'
                        }
                    })
                } else if (data.data.vehicle.status === 2) {
                    this.formParams.pendingProp = validations[this.$store.state.locale].vehicleEdit.pending
                } else if (data.data.vehicle.status === 3) {
                    if (data.data.errorMessage && data.data.errorMessage.length) {
                        this.formParams.errorProp = data.data.errorMessage
                        this.formParams.errorLink = this.$router.resolve({
                            name: 'support_ticket',
                            params: {id: ticketId, latest_message: messageId}
                        }).href
                    } else {
                        this.formParams.errorProp = validations[this.$store.state.locale].vehicleEdit.rejected
                    }
                }

                this.isLoading = false
            }).catch(e => {
                console.log(e)
            })
        } else {
            this.$store.dispatch('apiCall', {
                actionName: 'wizardByStep',
                data: {
                    lang: this.$store.state.locale,
                    step: 3,
                    id: this.$store.state.wizardVehicleId
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
                this.$router.push({name: this.parent})
                console.log(e)
            })
        }
    }
}
</script>
<style scoped src="../css/vehicleEdit.css"/>
