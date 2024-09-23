<template>
    <div v-if="!isLoading">
        <Header :title="title" :show-back="true" :show-logo="false" :parent="parent"/>
        <section>
            <div v-if="data">
                <div class="personal_photo">
                    <img :src="imagesPathRewrite('cars/'+data.routes.vehicles.route_types.key+'.svg')" alt="Route">
                </div>
                <div class="route_info">
                    <h2>#{{ data.routes.cities_from.city_code + data.routes.id }} - {{ data.routes.cities_from.translated.name }} - {{ data.routes.cities_to.translated.name }}</h2>
                    <h3>{{ data.routes.vehicles.manufacturer.name }} {{ data.routes.vehicles.model }} - {{ data.routes.vehicles.license_plate }}</h3>
                    <span v-if="data.rating"></span>
                </div>
                <v-rating v-model="rating" size="48"></v-rating>
            </div>
        </section>
        <Footer :key="$store.state.new_notifications"/>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>

<script>
import Header from "../components/Header"
import Footer from "../components/Footer"
import VLoading from "../components/Loading"
import lang from "../translations"
import {imagesPathRewrite} from "../config"

export default {
    name: "RateRoute",
    components: {VLoading, Header, Footer},
    data() {
        return {
            isLoading: true,
            parent: this.$route.meta.parent,
            title: lang[this.$store.state.locale].routeRating.title,
            data: null,
            rating: 0,
            imagesPathRewrite: imagesPathRewrite,
        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {
            actionName: 'getRouteRating',
            data: {
                lang: this.$store.state.locale,
                id: this.$route.params.id,
            }
        }).then(data => {
            this.isLoading = false
            this.data = data.data
            console.log(this.data)
        }).catch(e => {
            console.log(e)
            this.isLoading = false
        })
    }
}
</script>

<style scoped src="./css/RateRoute.css"/>
