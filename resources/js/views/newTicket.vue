<template>
    <div>
        <Header :title="title" :showLogo="false"/>
        <section class="chat_section">
            <div class="team_box">
                <div class="first_box not_rounded">
                    <img :src="imagesPathRewrite('logo.png')" alt="Zoombus">
                </div>
                <div class="second_box">
                    <h3 class="support_name">Zoombus</h3>
                    <p class="description">{{ lang.supportTicket.support }}</p>
                </div>
                <div class="chat_logo">
                    <img :src="imagesPathRewrite('chat-1.svg')" alt="chat">
                </div>
            </div>

            <div class="chat_room">
                <div :class="(message.admin > 0) ? 'admin' :  'customer'" v-for="(message, key) in messages" :key="key" :id="(messages.length === key+1) ? 'lastOne' : null">
                    <div class="box1 not_rounded" v-if="message.admin > 0">
                        <img :src="imagesPathRewrite('logo.png')" alt="Zoombus">
                    </div>
                    <div class="box2">
                        <div class="message_area" v-html="message.message"></div>
                        <span class="data">{{ message.date }}</span>
                    </div>
                    <div class="box1" v-if="message.admin === 0">
                        <img :src="message.avatar" alt="Customer">
                    </div>
                </div>
            </div>
        </section>
        <div class="footer_chat" v-if="!hideNewChat">
            <textarea class="text_area" rows="1" cols="0" v-model="response" title=""></textarea>
            <v-btn color="secondary" @click="add" :loading="buttonLoading">
                <v-icon color="white">{{ send }}</v-icon>
            </v-btn>
        </div>
    </div>
</template>
<script>
import lang from '../translations'

import Header from '../components/Header'

import {imagesPathRewrite} from '../config'
import {scroller} from 'vue-scrollto/src/scrollTo'
import {mdiSend} from '@mdi/js'

export default {
    components: {Header},
    data() {
        return {
            listLoading: false,
            response: null,
            send: mdiSend,
            hideNewChat: false,
            avatar: null,
            messages: null,
            imagesPathRewrite: imagesPathRewrite,
            lang: lang[this.$store.state.locale],
            title: lang[this.$store.state.locale].newTicket.title,
            buttonLoading: false,
            newMessageInterval: null
        }
    },
    methods: {
        scrollTo(div) {
            const scr = scroller()
            scr('#' + div)
        },
        add() {
            if (this.response && this.response.length) {
                this.buttonLoading = true
                this.$store.dispatch('apiCall', {
                    actionName: 'supportTicket',
                    data: {
                        lang: this.$store.state.locale,
                        message: this.response
                    }
                }).then(data => {
                    this.messages = [
                        {
                            message: this.response,
                            avatar: data.data.avatar,
                            date: data.data.date,
                            admin: 0
                        },
                        {
                            message: data.data.text,
                            date: data.data.date,
                            admin: 1
                        }
                    ]
                    this.response = null
                    this.hideNewChat = true
                    this.buttonLoading = false
                    this.$nextTick(function () {
                        this.scrollTo('lastOne')
                    })
                }).catch(e => {
                    console.log(e)
                    this.messages = [
                        {
                            message: e.response.data.text,
                            admin: 1
                        }
                    ]
                    this.buttonLoading = false
                })
            }
        }
    },
    mounted() {
        document.title = this.title
    },
    destroyed() {
        clearInterval(this.newMessageInterval)
    }
}
</script>
<style scoped src="./css/SupportTicket.css"/>
