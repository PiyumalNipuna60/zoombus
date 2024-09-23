<template>
    <div v-if="!isLoading">
        <Header :title="title" :show-back="true" :show-logo="false" :hideLogoText="true" :parent="parent"/>
        <section>
            <vzForm formId="signupAsPartner" formClass="borderless-form" v-bind="formParams"/>
        </section>
        <Footer :key="$store.state.new_notifications"/>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>
<style scoped src="../css/PartnerRegister.css"/>
<script>
import Header from '../../components/Header'
import lang from '../../translations'
import Footer from '../../components/Footer'
import VLoading from '../../components/Loading'
import VzForm from '../../components/Form'
import validations from '../../validations'

export default {
    name: 'PartnerRegister',
    components: {VzForm, VLoading, Footer, Header},
    data() {
        return {
            parent: this.$route.meta.parent,
            isLoading: true,
            title: lang[this.$store.state.locale].partnerRegister.title,
            lang: lang[this.$store.state.locale],
            formParams: {
                submit: {
                    class: 'submit',
                    large: true,
                    block: true,
                    loading: false,
                    text: lang[this.$store.state.locale].partnerRegister.saveButton
                },
                items: [
                    {
                        placeholder: lang[this.$store.state.locale].profile.fields.placeholders.name,
                        field: 'input',
                        name: 'name',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].profileUpdate.name.required
                        ],
                        plb: true,
                        value: '',
                        labelImage: 'form/user.svg'
                    },
                    {
                        placeholder: lang[this.$store.state.locale].profile.fields.placeholders.gender,
                        field: 'select',
                        singleLine: true,
                        value: '',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].profileUpdate.gender.required
                        ],
                        values: lang[this.$store.state.locale].profile.genders,
                        plb: true,
                        name: 'gender_id',
                        labelImage: 'form/user.svg'
                    },
                    {
                        placeholder: lang[this.$store.state.locale].profile.fields.placeholders.phone_number,
                        field: 'input',
                        name: 'phone_number',
                        class: 'forceEng',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].profileUpdate.phone_number.required
                        ],
                        labelImage: 'form/phone.svg',
                        plb: true,
                        value: ''
                    },
                    {
                        placeholder: lang[this.$store.state.locale].partnerRegister.fields.placeholders.accountType,
                        field: 'select',
                        singleLine: true,
                        value: 'driver',
                        values: lang[this.$store.state.locale].partnerRegister.accountTypes,
                        plb: true,
                        name: 'account_type',
                        labelImage: 'form/user.svg'
                    },
                    {
                        placeholder: lang[this.$store.state.locale].signup.fields.placeholders.affiliate_code,
                        field: 'input',
                        name: 'affiliate_code',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].partnerRegister.affiliateCode.required
                        ],
                        plb: true,
                        value: '',
                        labelImage: 'partner_icon.svg'
                    }
                ]
            }
        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {actionName: 'getPartnerCode'}).then(data => {
            this.formParams.items.find(d => d.name === 'affiliate_code').value = data.data.code
            this.isLoading = false
        }).catch(e => {
            console.log(e)
        })
    }
}
</script>
