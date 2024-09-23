<template>
    <div class="loading">
        <img :src="imagesPathRewrite('loading.gif')" alt="Loading...">
    </div>
</template>

<script>
import { imagesPathRewrite } from '../config'
export default {
        name: 'vLoading',
        data() {
            return {
                imagesPathRewrite: imagesPathRewrite
            }
        }
}
</script>
<style scoped src="./css/Loading.css" />
