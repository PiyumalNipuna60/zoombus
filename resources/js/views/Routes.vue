<template>
    <div v-if="!isLoading">
        <Header :title="title" :show-back="true" :show-logo="true" :hideLogoText="true" :parent="parent" :caption="caption"/>
        <MenuBlock :blocks="blocks"/>
        <Footer :key="$store.state.new_notifications"/>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>
<script>
import Header from '../components/Header'
import lang from '../translations'
import MenuBlock from '../components/MenuBlock'
import Footer from '../components/Footer'
import VLoading from '../components/Loading'

export default {
    components: {Footer, MenuBlock, Header, VLoading},
    data() {
        return {
            isLoading: true,
            parent: this.$route.meta.parent,
            title: lang[this.$store.state.locale].routes.title,
            caption: lang[this.$store.state.locale].routes.caption,
            blocks: [
                {
                    name: 'routeAdd',
                    image: 'driver/route_registration.svg',
                    mrt: true,
                    showLine: true,
                    title: lang[this.$store.state.locale].routes.blocks.routeAdd,
                    subTitle: lang[this.$store.state.locale].routes.blocks.routeAdd_sub,
                    rightIcon: 'driver/plus.svg',
                    largerIcon: true
                },
                {
                    name: 'routesList',
                    image: 'driver/registered_transport.svg',
                    mrt: true,
                    jst: true,
                    showLine: true,
                    title: lang[this.$store.state.locale].routes.blocks.routesList,
                    subTitle: lang[this.$store.state.locale].routes.blocks.routesList_sub,
                    rightCount: 0
                }
            ]
        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {actionName: 'getRouteCount'}).then(data => {
            this.isLoading = false
            this.blocks.find(d => d.name === 'routesList').rightCount = data.data.count
        }).catch(e => {
            console.log(e)
            this.isLoading = false
        })
    }
}
</script>
