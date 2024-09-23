<template>
    <div>
        <Header :title="title" :additionalMarginTop="true" :largerLogo="true"/>
        <section class="login">
            <LoginOrSignUp route="signup"/>
            <vzForm formId="signup" formClass="borderless-form" v-bind="formParams"/>
        </section>
    </div>
</template>
<script>
import lang from '../translations'
import validations from '../validations'
import Header from '../components/Header'
import vzForm from '../components/Form'
import LoginOrSignUp from '../components/LoginOrSignUp'
import {email} from '../validationFunctions'
import Countries from '../countries'

export default {
    components: {LoginOrSignUp, vzForm, Header},
    data() {
        return {
            title: lang[this.$store.state.locale].signup.title,
            formParams: {
                lazy: true,
                submit: {
                    text: lang[this.$store.state.locale].signup.button,
                    class: 'submit',
                    large: true,
                    block: true
                },
                onSuccessRedirect: 'login',
                onSuccessRedirectQuery: {success: true},
                items: [
                    {
                        placeholder: lang[this.$store.state.locale].signup.fields.placeholders.phone_number,
                        field: 'input',
                        class: 'forceEng',
                        name: 'phone_number',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].signup.phone_number.required
                        ],
                        plb: true,
                        labelImage: 'form/phone.svg'
                    },
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
                        placeholder: lang[this.$store.state.locale].profile.fields.placeholders.email,
                        field: 'input',
                        type: 'email',
                        class: 'forceEng',
                        name: 'email',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].profileUpdate.email.required,
                            e => email(e) || validations[this.$store.state.locale].profileUpdate.email.valid
                        ],
                        value: '',
                        plb: true,
                        labelImage: 'form/mail.svg'
                    },
                    {
                        placeholder: lang[this.$store.state.locale].signup.fields.placeholders.password,
                        field: 'input',
                        name: 'password',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].signup.password.required
                        ],
                        type: 'password',
                        plb: true,
                        labelImage: 'form/lock.svg'
                    },
                    {
                        placeholder: lang[this.$store.state.locale].profile.fields.placeholders.country,
                        field: 'select',
                        singleLine: true,
                        plb: true,
                        value: 80,
                        values: Countries[this.$store.state.locale],
                        name: 'country_id',
                        labelImage: 'form/marker.svg'
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
                        class: 'forceEng',
                        value: '',
                        plb: true,
                        labelImage: 'partner_icon.svg'
                    }
                ]
            }

        }
    },
    mounted() {
        document.title = this.title
        if (this.$store.state.isLoggedIn) {
            this.$router.push({name: 'main'})
        }
    }
}
</script>
<style scoped src="./css/Login.css"/>
