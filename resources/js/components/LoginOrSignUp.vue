<template>
    <div class="login_signup">
        <router-link :to="{ name: item.routeName }"  v-for="(item, k) in items" :key="k">
            <p :class="{active: item.routeName === route}">{{ item.name }}</p>
        </router-link>
    </div>
</template>
<script>
import lang from '../translations'
export default {
        props: {
            route: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                items: [
                    { name: lang[this.$store.state.locale].loginOrSignUp.login, routeName: 'login' },
                    { name: lang[this.$store.state.locale].loginOrSignUp.signUp, routeName: 'signup' }
                ]
            }
        }
}
</script>
<style scoped src="./css/LoginOrSignUp.css" />
