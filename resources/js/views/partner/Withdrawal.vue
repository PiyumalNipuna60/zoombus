<template>
    <div v-if="!isLoading">
        <Header :title="title" :show-back="true" :show-logo="false" :hideLogoText="true" :parent="parent"/>
        <section v-if="data.balance > 0 || data.items.length">
            <SaleStats :items="withdrawalStats"/>
            <div class="withdraw_new" v-if="data.balance && data.balance > 0">
                <h2 class="title_area">{{ lang.withdrawal.withdraw_title }}</h2>
                <vzForm v-bind="withdrawParams"/>
            </div>

            <h2 class="title_area" v-if="data.items && data.items.length">{{ lang.withdrawal.history }}</h2>
            <div class="list" v-if="data.items && data.items.length">
                <div class="item" v-for="(item,k) in data.items" :key="k">
                    <img class="card" :src="imagesPathRewrite('transactions/' + item.image + '.svg')" alt="">
                    <div class="description">
                        <h3 class="title_area">{{ item.date }}</h3>
                        <p class="description_area">#{{ item.transaction_id }}</p>
                    </div>
                    <div class="last_content">
                        <div class="amount">{{ item.amount }} <span>{{ item.currency }}</span></div>
                        <div class="status" :class="lang.withdrawal.status.find(d => d.id === item.status).class">
                            {{ lang.withdrawal.status.find(d => d.id === item.status).text }}
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="data.items && data.items.length >= perPage && data.items.length < total">
                <v-btn class="submit gradiented" :loading="listLoading" @click="showMore">
                    {{ lang.showMore }}
                </v-btn>
            </div>
        </section>
        <div v-else class="centered">
            <h2>{{ lang.withdrawal.no_sales }}</h2>
        </div>
        <Footer :key="$store.state.new_notifications"/>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>
<style scoped src="../css/Withdrawal.css"/>
<script>
import Header from '../../components/Header'
import lang from '../../translations'
import Footer from '../../components/Footer'
import {imagesPathRewrite, withdrawalsPerPage} from '../../config'
import VLoading from '../../components/Loading'
import VzForm from '../../components/Form'
import validations from '../../validations'
import SaleStats from '../../components/SaleStats'

export default {
    components: {SaleStats, VzForm, VLoading, Footer, Header},
    methods: {
        getData(success = null) {
            this.isLoading = true
            this.$store.dispatch('apiCall', {actionName: 'getPayouts', data: {lang: this.$store.state.locale, type: this.type}}).then(data => {
                this.data = data.data
                this.withdrawParams.successProp = success
                this.total = data.data.total
                this.withdrawalStats[0].value = data.data.balance
                // this.withdrawalStats[1].value = data.data.total_requested;
                // this.withdrawalStats[2].value = data.data.total_withdrawn;
                this.isLoading = false
            }).catch(e => {
                console.log(e)
            })
        },
        showMore() {
            this.listLoading = true
            this.skip = this.skip + this.perPage
            this.$store.dispatch('apiCall', {actionName: 'getPayouts', data: {lang: this.$store.state.locale, type: this.type, skip: this.skip}}).then(data => {
                this.data.items = this.data.items.concat(data.data.items)
                this.listLoading = false
            }).catch(e => {
                console.log(e)
            })
        }
    },
    data() {
        return {
            parent: this.$route.meta.parent,
            title: lang[this.$store.state.locale].withdrawal.title,
            lang: lang[this.$store.state.locale],
            imagesPathRewrite: imagesPathRewrite,
            perPage: withdrawalsPerPage,
            data: [],
            skip: 0,
            isLoading: true,
            listLoading: false,
            total: 0,
            type: 'partner',
            withdrawalStats: [
                {
                    label: lang[this.$store.state.locale].withdrawal.balance,
                    value: null,
                    isCurrency: true
                }
                // {
                //     label: lang[this.$store.state.locale].withdrawal.total_requested,
                //     value: null,
                //     isCurrency: true,
                // },
                // {
                //     label: lang[this.$store.state.locale].withdrawal.total_withdrawn,
                //     value: null,
                //     isCurrency: true,
                // }
            ],
            withdrawParams: {
                formId: 'payoutAdd',
                formClass: 'default-form w100',
                customSuccess: this.getData,
                successProp: null,
                submit: {
                    text: lang[this.$store.state.locale].withdrawal.button,
                    class: 'submit mt-less',
                    large: true,
                    block: true
                },
                items: [
                    {
                        field: 'hidden',
                        name: 'type',
                        value: 'partner'
                    },
                    {
                        hardLabel: lang[this.$store.state.locale].withdrawal.amount,
                        field: 'input',
                        class: 'forceEng',
                        name: 'amount',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].payoutAdd.amount.required,
                            v => !isNaN(v) || validations[this.$store.state.locale].payoutAdd.amount.number,
                            v => v <= this.data.balance || validations[this.$store.state.locale].payoutAdd.amount.less
                        ],
                        value: ''
                    }
                ]
            }
        }
    },
    mounted() {
        document.title = this.title
        this.getData()
    }
}
</script>
