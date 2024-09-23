<template>
    <div>
        <Header :title="title" :show-back="true" :show-logo="false" :hideLogoText="true" :parent="parent"/>
        <div class="header_icon">
            <img :src="imagesPathRewrite('sales.svg')" :alt="title">
            <p class="description">{{ caption2 }}</p>
        </div>
        <section>
            <v-alert type="error" v-if="error">
                {{ error }}
            </v-alert>
            <v-alert type="success" v-else-if="success">
                {{ success }}
            </v-alert>
        </section>
        <MenuBlock :blocks="blocks" v-if="$store.state.roles.includes('driver')"/>
        <div v-else>
            <section>
                <v-btn class="submit" color="blackOne" @click="becomeDriver" :loading="buttonLoading">
                    <v-icon left>
                        {{ registerIcon }}
                    </v-icon>
                    {{ lang.driverArea.becomeDriverButton }}
                </v-btn>
            </section>
        </div>
        <Footer :key="$store.state.new_notifications"/>
    </div>
</template>
<style scoped src="./css/DriverArea.css"/>
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
        becomeDriver() {
            this.buttonLoading = true
            this.$store.dispatch('apiCall', {
                actionName: 'becomeDriver',
                data: {lang: this.$store.state.locale, mobile: true}
            }).then(data => {
                this.success = data.data.text
                this.error = null
                this.buttonLoading = false
                this.$store.commit('addToRoles', 'driver')
                this.$store.commit('addToRoles', 'wizard')
                this.$router.push({name: 'wizard'})
                this.caption2 = lang[this.$store.state.locale].driverArea.caption2
            }).catch(e => {
                this.error = e.response.data.text
                this.buttonLoading = false
                this.$store.commit('addToRoles', 'wizard')
                this.$router.push({name: 'wizard'})
                this.success = null
            })
        }
    },
    data() {
        return {
            parent: this.$route.meta.parent,
            title: lang[this.$store.state.locale].driverArea.title,
            caption2: this.$store.state.roles.includes('driver') ? lang[this.$store.state.locale].driverArea.caption2 : lang[this.$store.state.locale].driverArea.becomeDriver,
            lang: lang[this.$store.state.locale],
            imagesPathRewrite: imagesPathRewrite,
            registerIcon: mdiAccountGroup,
            buttonLoading: false,
            success: null,
            error: null,
            blocks: [
                {
                    name: 'currentSales',
                    image: 'sales_ticket_icon.svg',
                    showLine: true,
                    showIfRole: 'driver',
                    title: lang[this.$store.state.locale].driverArea.blocks.currentSales,
                    subTitle: lang[this.$store.state.locale].driverArea.blocks.currentSales_sub,
                    rightIcon: 'right_arrow.svg'
                },
                {
                    name: 'salesHistory',
                    image: 'sales_history_icon.svg',
                    showLine: true,
                    showIfRole: 'driver',
                    title: lang[this.$store.state.locale].driverArea.blocks.salesHistory,
                    subTitle: lang[this.$store.state.locale].driverArea.blocks.salesHistory_sub,
                    rightIcon: 'right_arrow.svg'
                },
                {
                    name: 'fines',
                    image: 'fines.svg',
                    showLine: true,
                    showIfRole: 'driver',
                    imageClass: 'w30',
                    title: lang[this.$store.state.locale].driverArea.blocks.fines,
                    subTitle: lang[this.$store.state.locale].driverArea.blocks.fines_sub,
                    rightIcon: 'right_arrow.svg'
                },
                {
                    name: 'withdrawal',
                    image: 'icon5.svg',
                    showLine: true,
                    showIfRole: 'driver',
                    title: lang[this.$store.state.locale].driverArea.blocks.withdrawal,
                    subTitle: lang[this.$store.state.locale].driverArea.blocks.withdrawal_sub,
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
