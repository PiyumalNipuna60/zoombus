<template>
    <div>
        <Header :title="title" :parent="parent" :showLogo="true"/>
        <section>
            <vzForm formId="changePassword" formClass="borderless-form" v-bind="formParams"/>
        </section>
        <Footer :key="$store.state.new_notifications"/>
    </div>
</template>

<script>
import lang from '../../translations'
import validations from '../../validations'

import Header from '../../components/Header'
import Footer from '../../components/Footer'
import vzForm from '../../components/Form'

export default {
    components: {vzForm, Header, Footer},
    data() {
        return {
            parent: this.$route.meta.parent,
            title: lang[this.$store.state.locale].changePassword.title,
            isLoading: true,
            formParams: {
                submit: {
                    icon: 'mdi-content-save',
                    class: 'submit',
                    large: true,
                    block: true,
                    loading: false,
                    text: lang[this.$store.state.locale].changePassword.saveButton
                },
                items: [
                    {
                        placeholder: lang[this.$store.state.locale].changePassword.fields.placeholders.oldPassword,
                        field: 'input',
                        type: 'password',
                        name: 'old_password',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].changePassword.old_password.required
                        ],
                        plb: true,
                        value: '',
                        labelImage: 'form/unlock.svg'
                    },
                    {
                        placeholder: lang[this.$store.state.locale].changePassword.fields.placeholders.newPassword,
                        field: 'input',
                        type: 'password',
                        name: 'password',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].changePassword.password.required
                        ],
                        labelImage: 'form/unlock.svg',
                        plb: true,
                        value: ''
                    },
                    {
                        placeholder: lang[this.$store.state.locale].changePassword.fields.placeholders.repeatNewPassword,
                        field: 'input',
                        type: 'password',
                        name: 'password_confirmation',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].changePassword.password.required,
                            this.passwordConfirmationRule
                        ],
                        value: '',
                        plb: true,
                        labelImage: 'form/unlock.svg'
                    }
                ]
            }
        }
    },
    mounted() {
        document.title = this.title
    },
    methods: {
        passwordConfirmationRule() {
            return (this.formParams.items[1].value === this.formParams.items[2].value) || validations[this.$store.state.locale].changePassword.password.match
        }
    }
}
</script>
<style scoped src="../css/changePassword.css"/>
