<template>
    <div class="block_container">
        <div class="box_parent" v-for="(chunk, k) in itemChunks" :key="k">
            <router-link :to="{name: (box.name), url: box.url}" v-for="(box, i) in chunk" :key="i">
                <div class="box_item">
                    <span class="border_line" :style="'border-top:2px solid '+box.color" />
                    <img :src="imagesPathRewrite(box.image)" :alt="box.title">
                    <h2 class="title_box">{{ box.title }}</h2>
                    <p>{{ box.subTitle }}</p>
                </div>
            </router-link>
        </div>
    </div>
</template>
<style scoped src="./css/MenuInline.css" />
<script>
import { imagesPathRewrite } from '../config'

export default {
        props: {
            boxes: {
                type: Array,
                required: true
            }
        },
        computed: {
            itemChunks() {
                let R = []
                for (let i = 0; i < Object.values(this.boxes).length; i += 2) {
                    R.push(Object.values(this.boxes).slice(i, i + 2))
                }
                return R
            }
        },
        data() {
            return {
                imagesPathRewrite: imagesPathRewrite
            }
        }
}
</script>
