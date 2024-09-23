<template>
    <div>
        <Header :title="title" :parent="parent" :showLogo="false"/>
        <section id="financial" v-if="!isLoading">
            <div class="paypal_logo">
                <img :src="imagesPathRewrite('paypal_logo.svg')" alt="PayPal">
            </div>
            <router-link :to="{name: 'financialPayPalAdd'}" v-if="formParams.items[1].items.length < 2">
                <v-btn type="button" class="addNew mt-12" :large="true" color="blackOne" :block="true">
                    {{ addButton }}
                </v-btn>
            </router-link>
            <vzForm
                v-bind="formParams"
                v-if="formParams.items[1].items.length > 0"
                :class="{'mt-12': formParams.items[1].items.length > 1}"
            />
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
        },
        deleteCallback(id) {
            this.formParams.errorProp = null
            this.formParams.successProp = null
            const index = this.formParams.items[1].items.findIndex(i => i.value === id)
            this.isLoading = true
            this.$store.dispatch('apiCall', {
                actionName: 'deleteFinancial',
                data: {
                    lang: this.$store.state.locale,
                    id: id,
                    type: 2
                }
            }).then((d) => {
                this.isLoading = false
                this.formParams.errorProp = null
                this.formParams.successProp = validations[this.$store.state.locale].deleteFinancial.success
                this.formParams.items[1].items.splice(index, 1)
                if (typeof d.data === 'object' || Array.isArray(d.data)) {
                    this.formParams.checkedRadio = d.data.find(d => d.status_child === 1).id
                    this.formParams.items[1].value = d.data.find(d => d.status_child === 1).id
                }
                this.scrollTo('app')
            }).catch((e) => {
                console.log(e)
                this.isLoading = false
                this.formParams.successProp = null
                this.formParams.errorProp = e.response.data.text
                this.scrollTo('app')
            })
        }
    },
    data() {
        return {
            parent: this.$route.meta.parent,
            isLoading: true,
            title: lang[this.$store.state.locale].paypal.title,
            addButton: lang[this.$store.state.locale].financial.addButton,
            imagesPathRewrite: imagesPathRewrite,
            lang: lang[this.$store.state.locale],
            formParams: {
                formId: 'financialByTypeSet',
                deleteCall: this.deleteCallback,
                errorProp: null,
                successProp: null,
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
                        field: 'hidden',
                        name: 'type',
                        value: 2
                    },
                    {
                        field: 'radioGroup',
                        class: 'forceEng labelToo withDelete',
                        items: [],
                        name: 'id',
                        value: ''
                    }
                ]
            }
        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {
            actionName: 'financialByTypeGet', data: {type: 2}
        }).then(data => {
            data.data.forEach((d, k) => {
                this.formParams.items[1].items[k] = {
                    name: 'id',
                    value: d.id,
                    hideLabel: true,
                    label: d.paypal_email,
                    hasDelete: true
                }
                if (d.status_child === 1) {
                    this.formParams.checkedRadio = d.id
                    this.formParams.items[1].value = d.id
                }
            })
            this.isLoading = false
        }).catch(e => {
            this.isLoading = false
        })
    }
}
</script>
<style scoped src="../../css/PayPalList.css"/>
