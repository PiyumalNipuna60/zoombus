<template>
    <div>
        <Header :title="title" :additionalMarginTop="true" :largerLogo="true"/>
        <section class="login">
            <LoginOrSignUp route="login"/>
            <vzForm formId="login" formClass="borderless-form" v-bind="formParams">
                <router-link :to="{name: 'forgot'}" class="forgot">
                    {{ login.forgot_password }}
                </router-link>
            </vzForm>
        </section>
    </div>
</template>
<script>
import lang from '../translations'
import validations from '../validations'
import Header from '../components/Header'
import vzForm from '../components/Form'
import LoginOrSignUp from '../components/LoginOrSignUp'
import {client} from '../config'

export default {
    components: {LoginOrSignUp, vzForm, Header},
    data() {
        return {
            title: lang[this.$store.state.locale].login.title,
            login: lang[this.$store.state.locale].login,
            formParams: {
                lazy: true,
                successProp: (this.$route.query.success) ? validations[this.$store.state.locale].signup.success : null,
                submit: {
                    text: lang[this.$store.state.locale].login.button,
                    class: 'submit',
                    large: true,
                    block: true
                },
                commit: 'setUserData',
                onSuccessRedirect: 'home',
                items: [
                    {
                        name: '_token',
                        value: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    {
                        field: 'hidden',
                        name: 'grant_type',
                        value: 'password',
                        hideGroup: true,
                        hideLabel: true
                    },
                    {
                        field: 'hidden',
                        name: 'client_id',
                        value: client.id,
                        hideGroup: true,
                        hideLabel: true
                    },
                    {
                        field: 'hidden',
                        name: 'client_secret',
                        value: client.secret,
                        hideGroup: true,
                        hideLabel: true
                    },
                    {
                        placeholder: lang[this.$store.state.locale].login.fields.placeholders.phone_number,
                        field: 'input',
                        name: 'username',
                        class: 'forceEng',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].login.phone_number.required
                        ],
                        plb: true,
                        labelImage: 'form/phone.svg'
                    },
                    {
                        placeholder: lang[this.$store.state.locale].login.fields.placeholders.password,
                        field: 'input',
                        name: 'password',
                        class: 'forceEng',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].login.password.required
                        ],
                        type: 'password',
                        plb: true,
                        labelImage: 'form/lock.svg'
                    }
                ]
            }

        }
    },
    mounted() {
        document.title = this.title
    }
}
</script>
<style scoped src="./css/Login.css"/>
