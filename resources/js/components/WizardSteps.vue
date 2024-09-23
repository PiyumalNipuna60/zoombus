<template>
    <div class="steps_container">
        <div v-for="(step,k) in steps" :class="{active: stepProp === step}" :key="k">
            <span @click="setStep(step)" :class="getColor(step)">{{ stepLang }} {{ step }}</span>
            <v-icon v-if="step < steps.length" size="24" :color="getIconColor(step)">
                {{ next }}
            </v-icon>
        </div>
    </div>
</template>
<style scoped src="./css/WizardSteps.css" />
<script>
import lang from '../translations'
import { mdiChevronRight } from '@mdi/js'

export default {
        methods: {
            setStep(step) {
                if (step < this.stepProp) {
                    this.$store.commit('setWizardStep', step)
                }
            },
            getColor(step) {
                if (step < this.stepProp) {
                    return 'blackOne--text'
                } else if (step === this.stepProp) {
                    return 'error--text'
                } else {
                    return 'greyOne--text'
                }
            },
            getIconColor(step) {
                if (step > this.stepProp || step === this.stepProp) {
                    return 'greyOne'
                } else {
                    return 'blackOne'
                }
            }
        },
        data() {
            return {
                steps: [1, 2, 3, 4],
                stepLang: lang[this.$store.state.locale].wizard.step,
                next: mdiChevronRight,
                stepProp: this.$store.state.wizardStep
            }
        }
}
</script>
