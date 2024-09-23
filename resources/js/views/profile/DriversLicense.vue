<template>
    <div v-if="!isLoading">
        <Header :title="title" :parent="parent" :hide-back="isWizard" :showLogo="false" :is-child="isWizard"/>
        <section>
            <wizard-steps v-if="isWizard"/>
            <vzForm v-bind="formParams" @backAction="goBackAction" :disabledFields="formParams.items[0].value === 1"/>
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
            this.$store.commit('setWizardStep', 1)
        }
    },
    data() {
        return {
            parent: this.$route.meta.parent,
            title: lang[this.$store.state.locale].driversLicense.title,
            isLoading: true,
            formParams: {
                formId: 'driversLicense',
                formClass: 'default-form',
                submit: {
                    icon: (this.isWizard) ? 'next' : 'mdi-content-save',
                    class: 'submit',
                    large: true,
                    block: true,
                    loading: false,
                    column: (this.isWizard) ? 6 : 12,
                    text: (this.isWizard) ? lang[this.$store.state.locale].wizard.next : lang[this.$store.state.locale].driversLicense.saveButton
                },
                items: [
                    {
                        field: 'hidden',
                        name: 'status'
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].driversLicense.fields.labels.license_number,
                        field: 'input',
                        name: 'license_number',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].driversLicense.license_number.required
                        ],
                        value: '',
                        labelImage: 'form/unlock.svg'
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].driversLicense.fields.labels.front_side,
                        field: 'image',
                        id: 'front_side',
                        name: 'front_side_path',
                        excludeFromData: true,
                        allowedFormats: ['image/jpg', 'image/png', 'image/jpeg'],
                        value: ''
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].driversLicense.fields.labels.back_side,
                        field: 'image',
                        id: 'back_side',
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
                actionName: 'licenseGet',
                data: {
                    ticket: ticketId,
                    message: messageId
                }
            }).then(data => {
                this.formParams.items.forEach(d => {
                    d.value = data.data[d.name]
                })
                if (data.data.status === 1) {
                    this.formParams.successProp = validations[this.$store.state.locale].driversLicense.active
                } else if (data.data.status === 2) {
                    this.formParams.pendingProp = validations[this.$store.state.locale].driversLicense.pending
                } else if (data.data.status === 3) {
                    if (data.data.errorMessage && data.data.errorMessage.length) {
                        this.formParams.errorProp = data.data.errorMessage
                        this.formParams.errorLink = this.$router.resolve({
                            name: 'support_ticket',
                            params: {id: ticketId, latest_message: messageId}
                        }).href
                    } else {
                        this.formParams.errorProp = validations[this.$store.state.locale].driversLicense.rejected
                    }
                }
                this.isLoading = false
            })
        } else {
            this.$store.dispatch('apiCall', {
                actionName: 'wizardByStep',
                data: {
                    lang: this.$store.state.locale,
                    step: 2
                }
            }).then(data => {
                this.$store.commit('setWizardStep', 2)
                this.formParams.items.forEach(d => {
                    d.value = data.data[d.name]
                })
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
<style scoped src="../css/driversLicense.css"/>
