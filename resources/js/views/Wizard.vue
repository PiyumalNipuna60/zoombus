<template>
    <div v-if="!isLoading">
        <Profile v-bind="profileParams" v-if="$store.state.wizardStep === 1"/>
        <DriversLicense v-bind="licenseParams" v-else-if="$store.state.wizardStep === 2"/>
        <VehicleAdd v-bind="vehicleParams" v-else-if="$store.state.wizardStep === 3 && $store.state.wizardVehicleId === 0"/>
        <VehicleEdit v-bind="vehicleParams" v-else-if="$store.state.wizardStep === 3 && $store.state.wizardVehicleId > 0"/>
        <RouteAdd v-bind="routeParams" v-else-if="$store.state.wizardStep === 4"/>
        <div v-else class="wizard_complete">
            <div class="text-center">
                <v-icon size="60" color="success">
                    {{ check }}
                </v-icon>
            </div>
            <h1>{{ lang.wizard.complete.title }}</h1>
            <p>
                {{ lang.wizard.complete.text }}
            </p>
            <v-btn color="blackOne" class="submit" @click="$router.push({name: 'home'})">
                {{ lang.wizard.complete.goHome }}
            </v-btn>
        </div>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>
<script>

import Profile from './profile/Profile'
import DriversLicense from './profile/DriversLicense'
import VehicleAdd from './vehicles-routes/VehicleAdd'
import VehicleEdit from './vehicles-routes/VehicleEdit'
import RouteAdd from './vehicles-routes/RouteAdd'
import translations from '../translations'
import {mdiCheckboxMarkedCircleOutline} from '@mdi/js'
import VLoading from "../components/Loading";

export default {
    components: {VLoading, RouteAdd, VehicleAdd, VehicleEdit, DriversLicense, Profile},
    data() {
        return {
            lang: translations[this.$store.state.locale],
            check: mdiCheckboxMarkedCircleOutline,
            profileParams: {
                isWizard: true
            },
            licenseParams: {
                isWizard: true
            },
            vehicleParams: {
                isWizard: true
            },
            routeParams: {
                isWizard: true
            },
            isLoading: true,
        }
    },
    mounted() {
        this.$store.dispatch('apiCall', {actionName: 'getResumedWizard'}).then(d => {
            this.$store.commit('setWizardStep', d.data.step)
            if (!localStorage.getItem('wizardVehicleId') && d.data.vehicleId && d.data.vehicleId > 0) {
                this.$store.commit('setWizardVehicleId', d.data.vehicleId)
            }
            this.isLoading = false
        }).catch(e => {
            this.isLoading = false
            console.log(e);
        })

    }
}
</script>
<style scoped src="./css/Wizard.css"/>
