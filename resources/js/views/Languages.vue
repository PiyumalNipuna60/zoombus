<template>
    <div v-if="!isLoading">
        <Header :title="title" :showBack="true" :showLogo="false"/>
        <section>
            <div class="lang_list">
                <a @click="changeLanguage(locale)" v-for="(locale,k) in localeKeys" :key="k" class="single_list">
                    <div class="lang" :class="locale">
                        <img :src="imagesPathRewrite(locales[locale].flag)" :alt="locales[locale].name">
                        <p class="lang_title">{{ locales[locale].name }}</p>
                    </div>
                </a>
            </div>
        </section>
        <Footer :key="componentKey"/>
    </div>
    <div v-else>
        <v-loading />
    </div>
</template>
<script>

import locales from '../languages'
import Header from '../components/Header'
import Footer from '../components/Footer'
import {imagesPathRewrite} from '../config'

import lang from '../translations'
import VLoading from "../components/Loading";

export default {
    components: {VLoading, Header, Footer},
    data() {
        return {
            componentKey: 0,
            imagesPathRewrite: imagesPathRewrite,
            locales: locales,
            currentLocale: '',
            localeKeys: Object.keys(locales),
            title: lang[this.$store.state.locale].languages.title,
            itemsAnchors: [],
            isLoading: false,
        }
    },
    methods: {
        forceFooterRerender() {
            this.componentKey += 1
        },
        changeLanguageVue(locale) {
            this.$store.dispatch('languageChange', locale).then(() => {
                this.$vuetify.lang.current = this.$store.state.locale
                const body = document.body
                if (this.$store.state.locale === 'ka') {
                    body.classList.add('language_ge')
                } else {
                    body.classList.remove('language_ge')
                }
            })
        },
        changeLanguage(locale) {
            if(this.$store.state.isLoggedIn) {
                this.isLoading = true
                this.$store.dispatch('apiCall', {
                    actionName: 'setPreferredLocale',
                    data: {
                        lang: locale
                    }
                }).then(() => {
                    this.changeLanguageVue(locale)
                    this.$router.go(-1)
                }).catch(() => {
                    this.isLoading = false
                })
            }
            else {
                this.changeLanguageVue(locale)
                this.$router.go(-1)
            }

        }
    },
    mounted() {
        document.title = this.title
    }
}
</script>
<style scoped src="./css/Languages.css"/>
