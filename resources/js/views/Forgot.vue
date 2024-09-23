<template>
    <div>
        <Header :title="title" :additionalMarginTop="true" :largerLogo="true"/>
        <section class="forgot">
            <div class="forgot_box">
                <h3 class="forgot_title">{{ title }}</h3>
                <p class="forgot_description">{{ title_sub }}</p>
            </div>
            <vzForm formId="forgot" formClass="borderless-form" v-bind="formParams"/>
        </section>
    </div>
</template>
<script>
import lang from '../translations'
import validations from '../validations'
import Header from '../components/Header'
import vzForm from '../components/Form'

export default {
    components: {vzForm, Header},
    data() {
        return {
            title: lang[this.$store.state.locale].forgot.title,
            title_sub: lang[this.$store.state.locale].forgot.title_sub,
            formParams: {
                lazy: true,
                submit: {
                    text: lang[this.$store.state.locale].forgot.button,
                    class: 'submit',
                    large: true,
                    block: true
                },
                items: [
                    {
                        placeholder: lang[this.$store.state.locale].forgot.fields.placeholders.phone_number,
                        field: 'input',
                        class: 'forceEng',
                        name: 'phone_number',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].signup.phone_number.required
                        ],
                        plb: true,
                        labelImage: 'form/phone.svg'
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
<style scoped src="./css/Forgot.css"/>
