<template>
    <div>
        <Header :title="title" :parent="parent" :showLogo="false"/>
        <section class="financial">
            <div class="paypal_logo">
                <img :src="imagesPathRewrite('paypal_logo.svg')" alt="PayPal">
                <p class="paypal_caption">{{ addInfo }}</p>
            </div>
            <vzForm v-bind="formParams"/>
        </section>
        <Footer :key="$store.state.new_notifications"/>
    </div>
</template>
<script>
import lang from '../../../translations'
import validations from '../../../validations'
import Header from '../../../components/Header'
import Footer from '../../../components/Footer'
import vzForm from '../../../components/Form'
import {email} from '../../../validationFunctions'
import {imagesPathRewrite} from '../../../config'

export default {
    components: {vzForm, Header, Footer},
    data() {
        return {
            parent: this.$route.meta.parent,
            imagesPathRewrite: imagesPathRewrite,
            title: lang[this.$store.state.locale].paypal.title,
            addInfo: lang[this.$store.state.locale].paypal.addInfo,
            formParams: {
                formId: 'financialAdd',
                formClass: 'borderless-form',
                onSuccessRedirect: 'financialPayPal',
                submit: {
                    text: lang[this.$store.state.locale].paypal.button,
                    class: 'submit',
                    large: true,
                    block: true
                },
                items: [
                    {
                        field: 'hidden',
                        name: 'type',
                        value: 2
                    },
                    {
                        placeholder: lang[this.$store.state.locale].paypal.fields.placeholders.email,
                        field: 'input',
                        class: 'forceEng',
                        name: 'paypal_email',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].profileUpdate.email.required,
                            e => email(e) || validations[this.$store.state.locale].profileUpdate.email.valid
                        ],
                        plb: true,
                        labelImage: 'form/mail.svg'
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
<style scoped src="../../css/PayPalAdd.css"/>>
