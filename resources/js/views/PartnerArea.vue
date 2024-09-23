<template>
    <div>
        <Header :title="title" :show-back="true" :show-logo="false" :hideLogoText="true" :parent="parent"/>
        <div class="header_icon">
            <img :src="imagesPathRewrite('sales.svg')" :alt="title">
            <p class="description">{{ caption2 }}</p>
        </div>
        <MenuBlock :blocks="blocks" v-if="$store.state.roles.includes('partner')"/>
        <div v-else>
            <section>
                <v-alert type="error" v-if="error">
                    {{ error }}
                </v-alert>
                <v-alert type="success" v-else-if="success">
                    {{ success }}
                </v-alert>
                <v-btn class="submit" color="blackOne" @click="becomePartner" :loading="buttonLoading">
                    <v-icon left>
                        {{ registerIcon }}
                    </v-icon>
                    {{ lang.partnerArea.becomePartnerButton }}
                </v-btn>
            </section>
        </div>
        <Footer :key="$store.state.new_notifications"/>
    </div>
</template>
<style scoped src="./css/PartnerArea.css"/>
<script>
import Header from '../components/Header'
import lang from '../translations'
import MenuBlock from '../components/MenuBlock'
import Footer from '../components/Footer'
import {imagesPathRewrite} from '../config'
import {mdiAccountGroup} from '@mdi/js'

export default {
    components: {Footer, MenuBlock, Header},
    methods: {
        becomePartner() {
            this.buttonLoading = true
            this.$store.dispatch('apiCall', {actionName: 'becomePartner', data: {lang: this.$store.state.locale, mobile: true}}).then(data => {
                this.success = data.data.text
                this.buttonLoading = false
                this.error = null
                this.$store.commit('addToRoles', 'partner')
                this.caption2 = lang[this.$store.state.locale].partnerArea.caption2
            }).catch(e => {
                this.error = e.response.data.text
                this.$store.commit('addToRoles', 'partner')
                this.caption2 = lang[this.$store.state.locale].partnerArea.caption2
                this.buttonLoading = false
                this.success = null
            })
        }
    },
    data() {
        return {
            parent: this.$route.meta.parent,
            title: lang[this.$store.state.locale].partnerArea.title,
            lang: lang[this.$store.state.locale],
            caption2: this.$store.state.roles.includes('partner') ? lang[this.$store.state.locale].partnerArea.caption2 : lang[this.$store.state.locale].partnerArea.becomePartner,
            imagesPathRewrite: imagesPathRewrite,
            registerIcon: mdiAccountGroup,
            buttonLoading: false,
            success: null,
            error: null,
            blocks: [
                {
                    name: 'partnersRegister',
                    image: 'icon2.svg',
                    showLine: true,
                    title: lang[this.$store.state.locale].partnerArea.blocks.register,
                    subTitle: lang[this.$store.state.locale].partnerArea.blocks.register_sub,
                    rightIcon: 'right_arrow.svg'
                },
                {
                    name: 'partnersList',
                    image: 'icon3.svg',
                    showLine: true,
                    title: lang[this.$store.state.locale].partnerArea.blocks.list,
                    subTitle: lang[this.$store.state.locale].partnerArea.blocks.list_sub,
                    rightIcon: 'right_arrow.svg'
                },
                {
                    name: 'partnerSales',
                    image: 'icon4.svg',
                    showLine: true,
                    title: lang[this.$store.state.locale].partnerArea.blocks.sales,
                    subTitle: lang[this.$store.state.locale].partnerArea.blocks.sales_sub,
                    rightIcon: 'right_arrow.svg'
                },
                {
                    name: 'withdrawalPartner',
                    image: 'icon5.svg',
                    showLine: true,
                    title: lang[this.$store.state.locale].partnerArea.blocks.withdrawal,
                    subTitle: lang[this.$store.state.locale].partnerArea.blocks.withdrawal_sub,
                    rightIcon: 'right_arrow.svg'
                }
            ]
        }
    },
    mounted() {
        document.title = this.title
    }
}
</script>
