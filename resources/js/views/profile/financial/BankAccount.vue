<template>
    <div>
        <Header :title="title" :parent="parent" :showLogo="false"/>
        <section v-if="!isLoading">
            <div class="transfer_logo">
                <img :src="imagesPathRewrite('brokerage.svg')" alt="Bank">
                <p class="logo_caption">{{ lang.bank.caption2 }}</p>
            </div>
            <vzForm v-bind="formParams"/>
        </section>
        <div v-else>
            <vLoading/>
        </div>
        <Footer :key="$store.state.new_notifications"/>
    </div>
</template>

<script>
import lang from '../../../translations'

import Header from '../../../components/Header'
import Footer from '../../../components/Footer'
import vzForm from '../../../components/Form'
import vLoading from '../../../components/Loading'
import {imagesPathRewrite} from '../../../config'

import {scroller} from 'vue-scrollto/src/scrollTo'
import validations from '../../../validations'

export default {
    components: {vzForm, Header, Footer, vLoading},
    methods: {
        scrollTo(div) {
            const scr = scroller()
            scr('#' + div)
        }
    },
    data() {
        return {
            parent: this.$route.meta.parent,
            isLoading: true,
            title: lang[this.$store.state.locale].bank.title,
            imagesPathRewrite: imagesPathRewrite,
            lang: lang[this.$store.state.locale],
            formParams: {
                formId: 'financialAdd',
                class: 'default-form',
                errorProp: null,
                successProp: null,
                submit: {
                    icon: 'mdi-content-save',
                    class: 'submit',
                    large: true,
                    block: true,
                    loading: false,
                    text: lang[this.$store.state.locale].financial.saveButton
                },
                items: [
                    {
                        field: 'hidden',
                        name: 'type',
                        value: 3
                    },
                    {
                        field: 'hidden',
                        name: 'id',
                        value: ''
                    },
                    {
                        field: 'input',
                        name: 'your_name',
                        class: 'forceEng',
                        rules: [
                            n => !!n || validations[this.$store.state.locale].financialAdd.bank.your_name.required
                        ],
                        hardLabel: lang[this.$store.state.locale].bank.fields.labels.name,
                        placeholder: lang[this.$store.state.locale].bank.fields.placeholders.name,
                        value: ''
                    },
                    {
                        field: 'input',
                        name: 'bank_name',
                        rules: [
                            n => !!n || validations[this.$store.state.locale].financialAdd.bank.bank_name.required
                        ],
                        class: 'forceEng',
                        hardLabel: lang[this.$store.state.locale].bank.fields.labels.bank,
                        value: ''
                    },
                    {
                        field: 'input',
                        name: 'account_number',
                        rules: [
                            n => !!n || validations[this.$store.state.locale].financialAdd.bank.account_number.required
                        ],
                        class: 'forceEng',
                        hardLabel: lang[this.$store.state.locale].bank.fields.labels.iban,
                        value: ''
                    },
                    {
                        field: 'input',
                        name: 'swift',
                        rules: [
                            n => !!n || validations[this.$store.state.locale].financialAdd.bank.swift.required
                        ],
                        class: 'forceEng',
                        hardLabel: lang[this.$store.state.locale].bank.fields.labels.swift,
                        value: ''
                    }
                ]
            }
        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {
            actionName: 'financialByTypeGet', data: {type: 3}
        }).then(data => {
            if ('id' in data.data[0]) {
                this.formParams.successText = validations[this.$store.state.locale].financialAdd.update
            }
            this.formParams.items.forEach(d => {
                d.value = data.data[0][d.name]
            })
            this.isLoading = false
        }).catch(e => {
            this.isLoading = false
        })
    }
}
</script>
<style scoped src="../../css/BankAccount.css"/>
