<template>
    <v-app style="background-color:#FFFFFF;">
        <router-view data-app/>
    </v-app>
</template>
<script>
export default {
    methods: {
        newNotifications() {
            if (this.$store.state.isLoggedIn && localStorage.getItem('user')) {
                this.$store.dispatch('apiCall', {actionName: 'getNotificationCount'}).then(data => {
                    this.$store.commit('setNewNotifications', data.data)
                }).catch(e => {
                    console.log(e)
                })
            }
        }
    },
    mounted() {
        this.newNotifications()
        setInterval(() => {
            this.newNotifications()
        }, 30000)
    }
}
</script>
<style>
@import "css/App.css";
</style>
