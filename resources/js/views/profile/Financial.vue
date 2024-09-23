<template>
    <div>
        <Header :title="lang.financial.title" :parent="parent" :showLogo="true" :hide-logo-text="true" :caption="lang.financial.caption" :caption2="lang.financial.caption2"/>
        <section id="financial" v-if="!isLoading">
            <vzForm v-bind="formParams"/>
        </section>
        <div v-else>
            <vLoading/>
        </div>
        <Footer :key="$store.state.new_notifications"/>
    </div>
</template>

<script>
import lang from '../../translations'

import Header from '../../components/Header'
import Footer from '../../components/Footer'
import vzForm from '../../components/Form'
import vLoading from '../../components/Loading'
import {imagesPathRewrite} from '../../config'

import {scroller} from 'vue-scrollto/src/scrollTo'

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
            title: lang[this.$store.state.locale].financial.title,
            imagesPathRewrite: imagesPathRewrite,

            lang: lang[this.$store.state.locale],
            formParams: {
                formId: 'financialPrimarySet',
                error: null,
                success: null,
                class: 'payout_box',
                checkedRadio: null,
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
                        name: 'type',
                        field: 'radioGroup',
                        value: '',
                        hideDetails: true,
                        class: 'radio-custom',
                        hideLabel: true,
                        items: [
                            {
                                value: 1,
                                class: 'form_label',
                                hideLabel: true,
                                label: lang[this.$store.state.locale].financial.primary,
                                link: {
                                    name: 'financialCreditCard',
                                    text: lang[this.$store.state.locale].financial.credit_card,
                                    image: {
                                        src: imagesPathRewrite('form/card.svg'),
                                        alt: 'Credit Card'
                                    },
                                    hasArrow: true
                                }
                            },
                            {
                                value: 2,
                                class: 'form_label',
                                hideLabel: true,
                                label: lang[this.$store.state.locale].financial.primary,
                                link: {
                                    name: 'financialPayPal',
                                    image: {
                                        src: imagesPathRewrite('form/paypal.svg'),
                                        alt: 'PayPal'
                                    },
                                    text: lang[this.$store.state.locale].financial.paypal,
                                    textRight: true,
                                    hasArrow: true
                                }
                            },
                            {
                                value: 3,
                                class: 'form_label',
                                hideLabel: true,
                                label: lang[this.$store.state.locale].financial.primary,
                                link: {
                                    name: 'financialBank',
                                    text: lang[this.$store.state.locale].financial.bank,
                                    textRight: true,
                                    image: {
                                        src: imagesPathRewrite('form/transfer.svg'),
                                        alt: 'Bank'
                                    },
                                    hasArrow: true
                                }
                            }
                        ]
                    }
                ]
            }

        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {actionName: 'financialPrimaryGet'}).then(data => {
            this.isLoading = false
            this.formParams.items[0].value = data.data
            this.formParams.checkedRadio = data.data
        }).catch(() => {
            this.isLoading = false
        })
    }
}
</script>
<style scoped src="../css/Financial.css"/>
