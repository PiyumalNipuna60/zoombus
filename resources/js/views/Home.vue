<template>
    <div>
        <Header :title="title" :show-back="true" :show-logo="true" :caption="caption" :hideLogoText="true"/>
        <MenuInline :boxes="boxes"/>
        <Footer :key="$store.state.new_notifications"/>
    </div>
</template>
<script>
import Header from '../components/Header'
import lang from '../translations'
import Footer from '../components/Footer'
import MenuInline from '../components/MenuInline'

export default {
    components: {MenuInline, Footer, Header},
    mounted() {
        document.title = this.title
        if (this.$store.state.roles.includes('wizard') && this.$store.state.wizardStep < 5) {
            this.$router.push({name: 'wizard'})
        }

        if (this.$store.state.roles.includes('partner') && !this.$store.state.roles.includes('driver')) {
            this.boxes = [
                {
                    name: 'settings',
                    image: 'icon1.svg',
                    color: '#2B2BAD',
                    title: lang[this.$store.state.locale].home.boxes.profile,
                    subTitle: lang[this.$store.state.locale].home.boxes.profile_sub
                },
                {
                    name: 'ticketList',
                    image: 'ticket_icon.svg',
                    color: '#F50052',
                    title: lang[this.$store.state.locale].home.boxes.ticketList,
                    subTitle: lang[this.$store.state.locale].home.boxes.ticketList_sub
                },
                {
                    name: 'partnerArea',
                    image: 'driver/partner.svg',
                    color: '#F79A1D',
                    title: lang[this.$store.state.locale].home.boxes.partners,
                    subTitle: lang[this.$store.state.locale].home.boxes.partners_sub
                },
                {
                    name: 'driverArea',
                    image: 'sales.svg',
                    color: '#14a969',
                    title: lang[this.$store.state.locale].home.boxes.become_driver,
                    subTitle: lang[this.$store.state.locale].home.boxes.become_driver_sub
                }
            ]
        } else if (this.$store.state.roles.includes('driver')) {
            this.boxes = [
                {
                    name: 'settings',
                    image: 'icon1.svg',
                    color: '#2B2BAD',
                    title: lang[this.$store.state.locale].home.boxes.profile,
                    subTitle: lang[this.$store.state.locale].home.boxes.profile_sub
                },
                {
                    name: 'vehicles',
                    image: 'driver/transport.svg',
                    color: '#F79A1D',
                    title: lang[this.$store.state.locale].home.boxes.vehicles,
                    subTitle: lang[this.$store.state.locale].home.boxes.vehicles_sub
                },
                {
                    name: 'routes',
                    image: 'driver/route_icon3x.svg',
                    color: '#f01df7',
                    title: lang[this.$store.state.locale].home.boxes.routes,
                    subTitle: lang[this.$store.state.locale].home.boxes.routes_sub
                },
                {
                    name: 'driverArea',
                    image: 'driver/bank.svg',
                    color: '#14A969',
                    title: lang[this.$store.state.locale].home.boxes.sales,
                    subTitle: lang[this.$store.state.locale].home.boxes.sales_sub
                },
                {
                    name: 'scanner',
                    image: 'driver/qricon.svg',
                    color: '#A05C00',
                    title: lang[this.$store.state.locale].home.boxes.scanner,
                    subTitle: lang[this.$store.state.locale].home.boxes.scanner_sub
                },
                {
                    name: 'partnerArea',
                    image: 'driver/partner.svg',
                    color: '#6B6BC9',
                    title: (this.$store.state.roles.includes('partner')) ?
                        lang[this.$store.state.locale].home.boxes.partners :
                        lang[this.$store.state.locale].home.boxes.become_partner,
                    subTitle: (this.$store.state.roles.includes('partner')) ?
                        lang[this.$store.state.locale].home.boxes.partners_sub :
                        lang[this.$store.state.locale].home.boxes.become_partner_sub,
                },
                {
                    name: 'faq',
                    image: 'driver/support.svg',
                    color: '#F50052',
                    title: lang[this.$store.state.locale].home.boxes.help,
                    subTitle: lang[this.$store.state.locale].home.boxes.help_sub,
                },
                {
                    name: 'ticketList',
                    image: 'ticket_icon.svg',
                    color: '#33881c',
                    title: lang[this.$store.state.locale].home.boxes.ticketList,
                    subTitle: lang[this.$store.state.locale].home.boxes.ticketList_sub
                }
            ]
        } else {
            this.boxes = [
                {
                    name: 'settings',
                    image: 'icon1.svg',
                    color: '#2B2BAD',
                    title: lang[this.$store.state.locale].home.boxes.profile,
                    subTitle: lang[this.$store.state.locale].home.boxes.profile_sub
                },
                {
                    name: 'ticketList',
                    image: 'ticket_icon.svg',
                    color: '#F50052',
                    title: lang[this.$store.state.locale].home.boxes.ticketList,
                    subTitle: lang[this.$store.state.locale].home.boxes.ticketList_sub
                },
                {
                    name: 'driverArea',
                    image: 'driver/bank.svg',
                    color: '#14A969',
                    title: lang[this.$store.state.locale].home.boxes.become_driver,
                    subTitle: lang[this.$store.state.locale].home.boxes.become_driver_sub
                },
                {
                    name: 'partnerArea',
                    image: 'driver/partner.svg',
                    color: '#6B6BC9',
                    title: lang[this.$store.state.locale].home.boxes.become_partner,
                    subTitle: lang[this.$store.state.locale].home.boxes.become_partner_sub
                },
            ]
        }
    },
    data() {
        return {
            title: lang[this.$store.state.locale].home.title,
            caption: lang[this.$store.state.locale].home.caption,
            boxes: []
        }
    }
}
</script>
