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
            title: lang[this.$store.state.locale].vehicles.title,
            caption: lang[this.$store.state.locale].vehicles.caption,
            blocks: [
                {
                    name: 'vehicleAdd',
                    image: 'driver/minibus_icon.svg',
                    mrt: true,
                    showLine: true,
                    title: lang[this.$store.state.locale].vehicles.blocks.vehicleAdd,
                    subTitle: lang[this.$store.state.locale].vehicles.blocks.vehicleAdd_sub,
                    rightIcon: 'driver/plus.svg',
                    largerIcon: true
                },
                {
                    name: 'vehiclesList',
                    image: 'driver/registered_transport.svg',
                    mrt: true,
                    jst: true,
                    showLine: true,
                    title: lang[this.$store.state.locale].vehicles.blocks.vehiclesList,
                    subTitle: lang[this.$store.state.locale].vehicles.blocks.vehiclesList_sub,
                    rightCount: 0
                }
            ]
        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {actionName: 'getVehicleCount'}).then(data => {
            this.isLoading = false
            this.blocks.find(d => d.name === 'vehiclesList').rightCount = data.data.count
        }).catch(e => {
            console.log(e)
            this.isLoading = false
        })
    }
}
</script>
