<template>
    <div class="footer" v-if="this.$store.state.isLoggedIn">
        <div class="item" :class="{active: (item.name === $route.name)}" v-for="(item,k) in items" :key="k">
            <router-link :to="{name: item.name}" v-if="item.isRouter">
                <div class="icon">
                    <img :src="item.name === $route.name ? imagesPathRewrite(item.iconActive) : imagesPathRewrite(item.icon)" :alt="item.anchor">
                    <div class="circle_box" v-if="item.count">
                        <p class="count_messages">{{ item.count }}</p>
                    </div>
                </div>
                <p>{{ item.anchor}}</p>
            </router-link>
            <a :href="item.url" v-else-if="item.url">
                <div class="icon">
                    <img :src="item.name === $route.name ? imagesPathRewrite(item.iconActive) : imagesPathRewrite(item.icon)" :alt="item.anchor">
                    <div class="circle_box" v-if="item.count">
                        <p class="count_messages">{{ item.count }}</p>
                    </div>
                </div>
                <p>{{ item.anchor}}</p>
            </a>
            <a v-else-if="item.isCameraOff">
                <div class="icon" @click.prevent="$emit('cameraOff')">
                    <img :src="item.name === $route.name ? imagesPathRewrite(item.iconActive) : imagesPathRewrite(item.icon)" :alt="item.anchor">
                </div>
                <p>{{ item.anchor }}</p>
            </a>
            <a v-else>
                <div class="icon" @click.prevent="logout">
                    <img :src="imagesPathRewrite(item.icon)" :alt="item.anchor">
                </div>
                <p>{{ item.anchor }}</p>
            </a>
        </div>
    </div>
</template>

<script>
import lang from '../translations'
import { imagesPathRewrite } from '../config'

export default {
        props: {
            itemsProp: {
                type: Array
            }
        },
        methods: {
            logout() {
                this.$store.dispatch('logout')
            }
        },
        mounted() {
            if (this.itemsProp && this.itemsProp.length) {
                this.items = this.itemsProp
            } else {
                const itemAnchors = lang[this.$store.state.locale].menuItems
                const items = [
                    {
                        name: 'home',
                        isRouter: true,
                        icon: 'home.svg',
                        iconActive: 'home_active.svg',
                        anchor: itemAnchors[0]
                    },
                    {
                        name: 'scanner',
                        isRouter: true,
                        icon: 'driver/scannericon.svg',
                        iconActive: 'driver/scannericon_active.svg',
                        anchor: itemAnchors[1]
                    },
                    {
                        name: 'faq',
                        isRouter: true,
                        icon: 'faq.svg',
                        iconActive: 'faq_active.svg',
                        anchor: itemAnchors[2]
                    },
                    {
                        name: 'notifications',
                        isRouter: true,
                        icon: 'messages.svg',
                        iconActive: 'messages_active.svg',
                        count: this.$store.state.new_notifications,
                        anchor: itemAnchors[3]
                    },
                    {
                        name: 'logout',
                        isRouter: false,
                        icon: 'logout.svg',
                        type: 'logout',
                        anchor: itemAnchors[4]
                    }
                ]
                if (this.$store.state.roles.includes('driver')) {
                    this.items = items
                }
                else {
                    this.items = items.filter(d => d.name !== 'scanner')
                }
            }
        },
        data() {
            return {
                imagesPathRewrite: imagesPathRewrite,
                items: []
            }
        }

}
</script>

<style scoped src="./css/Footer.css"/>
