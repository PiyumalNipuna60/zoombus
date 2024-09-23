<template>
    <div v-if="!isLoading">
        <Header :title="title" :showLogo="false"/>
        <section class="chat_section">
            <div class="team_box">
                <div class="first_box">
                    <img :src="data.admin_user.avatar" :alt="data.admin_user.name">
                </div>
                <div class="second_box">
                    <h3 class="support_name">{{ data.admin_user.name }}</h3>
                    <p class="description">{{ lang.supportTicket.support }}</p>
                </div>
                <div class="chat_logo">
                    <img :src="imagesPathRewrite('chat-1.svg')" alt="chat">
                </div>
            </div>

            <div class="chat_room">
                <div :class="(message.admin > 0) ? 'admin' :  'customer'" v-for="(message, key) in data.messages_asc" :key="key" :id="(data.messages_asc.length === key+1) ? 'lastOne' : null">
                    <div class="box1" v-if="message.admin > 0">
                        <img :src="data.admin_user.avatar" :alt="data.admin_user.name">
                    </div>
                    <div class="box2">
                        <div class="message_area" v-html="message.message"></div>
                        <span class="data">{{ message.date }}</span>
                    </div>
                    <div class="box1" v-if="message.admin === 0">
                        <img :src="avatar" alt="Customer">
                    </div>
                </div>
            </div>
        </section>
        <div class="footer_chat">
            <textarea class="text_area" rows="1" cols="0" v-model="response" title=""></textarea>
            <v-btn color="secondary" @click="reply" :loading="buttonLoading">
                <v-icon color="white">{{ send }}</v-icon>
            </v-btn>
        </div>
    </div>
    <div v-else>
        <v-loading/>
    </div>
</template>
<script>
import lang from '../translations'

import Header from '../components/Header'
import VLoading from '../components/Loading'

import {imagesPathRewrite} from '../config'
import {scroller} from 'vue-scrollto/src/scrollTo'
import {mdiSend} from '@mdi/js'

export default {
    components: {VLoading, Header},
    data() {
        return {
            listLoading: false,
            response: null,
            send: mdiSend,
            avatar: null,
            data: null,
            imagesPathRewrite: imagesPathRewrite,
            lang: lang[this.$store.state.locale],
            title: lang[this.$store.state.locale].supportTicket.title,
            isLoading: true,
            buttonLoading: false,
            newMessageInterval: null
        }
    },
    methods: {
        scrollTo(div) {
            const scr = scroller()
            scr('#' + div)
        },
        reply() {
            if (this.response && this.response.length) {
                this.buttonLoading = true
                this.$store.dispatch('apiCall', {
                    actionName: 'supportTicketReply',
                    data: {
                        lang: this.$store.state.locale,
                        id: this.$route.params.id,
                        message: this.response,
                        mobile: true
                    }
                }).then(data => {
                    this.data.messages_asc = data.data.messages_asc
                    this.response = null
                    this.buttonLoading = false
                    this.$nextTick(function () {
                        this.scrollTo('lastOne')
                    })
                }).catch(e => {
                    console.log(e)
                    this.buttonLoading = false
                })
            }
        },
        mountedAction() {
            this.$store.dispatch('apiCall', {actionName: 'getTicketMessages', data: {lang: this.$store.state.locale, id: this.$route.params.id}}).then(data => {
                this.data = data.data
                this.avatar = data.data.avatar
                this.isLoading = false
                this.$nextTick(function () {
                    this.scrollTo('lastOne')
                })
            }).catch(e => {
                console.log(e)
            })
        }
    },
    mounted() {
        document.title = this.title
        this.mountedAction()
        this.newMessageInterval = setInterval(() => {
            this.mountedAction()
        }, 30000)
    },
    destroyed() {
        clearInterval(this.newMessageInterval)
    }
}
</script>
<style scoped src="./css/SupportTicket.css"/>
