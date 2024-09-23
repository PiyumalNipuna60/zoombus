<template>
    <div v-if="!isLoading">
        <Header :title="title" :show-back="true" :show-logo="true" :caption="caption"/>
        <section>
            <router-link :to="{ name: 'newTicket' }" class="td-none-white">
                <v-btn type="button" class="addNew mt-12" :large="true" color="blackOne" :block="true">
                    {{ addButton }}
                </v-btn>
            </router-link>
            <v-expansion-panels :accordion="true" class="mt-5">
                <v-expansion-panel
                    v-for="(item, i) in items"
                    :key="i"
                >
                    <v-expansion-panel-header>
                        <v-icon color="black" size="20">
                            {{ fileIcon }}
                        </v-icon>
                        {{ item.translated.title }}
                    </v-expansion-panel-header>
                    <v-expansion-panel-content v-html="item.translated.text">
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </section>
        <Footer :key="$store.state.new_notifications"/>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>
<script>
import Header from '../components/Header'
import lang from '../translations'
import Footer from '../components/Footer'

import {mdiFile} from '@mdi/js'
import VLoading from '../components/Loading'

export default {
    components: {VLoading, Footer, Header},
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {actionName: 'getFAQ', data: {lang: this.$store.state.locale}}).then(data => {
            this.items = data.data
            this.isLoading = false
        }).catch(e => {
            console.log(e)
            this.isLoading = false
        })
    },
    data() {
        return {
            isLoading: true,
            fileIcon: mdiFile,
            title: lang[this.$store.state.locale].faqs.title,
            caption: null,
            items: [],
            addButton: lang[this.$store.state.locale].faqs.addButton
        }
    }
}
</script>
<style src="./css/FAQ.css" scoped/>
