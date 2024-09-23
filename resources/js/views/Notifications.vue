<template>
    <div v-if="!isLoading">
        <Header :title="title" :showLogo="false"/>
        <section>
            <div class="notification" v-for="(item,k) in items" :key="k">
                <div class="box">
                    <div class="first_box">
                        <div class="count_box" v-if="item.data.type === 'support'">
                            <p class="count_messages" v-if="!item.user_read">1</p>
                            <img class="circle" :src="imagesPathRewrite('circle_red.svg')" alt v-if="!item.user_read">
                        </div>
                        <img :src="notificationTypesPath(item.data.type + '.svg')" :alt="item.data.type"
                             v-if="item.data.type !== 'support'">
                        <img :src="item.user_avatar" :alt="item.data.type" class="avatar" v-else>
                    </div>
                    <div class="second_box">
                        <h3 class="support_name">{{ item.data['text_' + $store.state.locale] }}</h3>
                    </div>
                    <div class="third_box">
                        <p class="date">{{ item.created_at_formatted }}</p>
                        <router-link :to="item.url_path" v-if="item.url_path" class="read_more">
                            {{ lang.notifications.view }}
                        </router-link>
                    </div>
                </div>
            </div>
            <div v-if="items.length >= notificationsPerPage && items.length < total">
                <v-btn class="submit gradiented" :loading="listLoading" @click="showMore">
                    {{ lang.showMore }}
                </v-btn>
            </div>
        </section>
        <Footer :key="$store.state.new_notifications"/>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>
<script>
import lang from '../translations'

import Header from '../components/Header'
import Footer from '../components/Footer'
import VLoading from '../components/Loading'

import {imagesPathRewrite, notificationTypesPath, notificationsPerPage} from '../config'

export default {
    components: {VLoading, Header, Footer},
    methods: {
        showMore() {
            this.listLoading = true
            this.skip = this.skip + this.notificationsPerPage
            this.$store.dispatch('apiCall', {
                actionName: 'getNotificationsList',
                data: {lang: this.$store.state.locale, zSkip: this.skip}
            }).then(data => {
                this.items = this.items.concat(data.data.results)
                this.listLoading = false
            }).catch(e => {
                console.log(e)
            })
        }
    },
    data() {
        return {
            listLoading: false,
            notificationsPerPage: notificationsPerPage,
            skip: 0,
            imagesPathRewrite: imagesPathRewrite,
            notificationTypesPath: notificationTypesPath,
            lang: lang[this.$store.state.locale],
            title: lang[this.$store.state.locale].notifications.title,
            isLoading: true,
            items: [],
            total: 0
        }
    },
    mounted() {
        document.title = this.title
        this.$store.dispatch('apiCall', {
            actionName: 'getNotificationsList',
            data: {lang: this.$store.state.locale, zSkip: this.skip}
        }).then(data => {
            this.items = data.data.results
            this.total = data.data.total_results
            this.$store.commit('setNewNotifications', 0)
            this.isLoading = false
        }).catch(e => {
            this.isLoading = false
            console.log(e)
        })
    }
}
</script>
<style scoped src="./css/Notifications.css"/>
