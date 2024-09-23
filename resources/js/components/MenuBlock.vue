<template>
    <div class="block_container">
        <div class="block" :key="k" v-for="(block, k) in blocks" v-if="block.showIfRole && $store.state.roles.includes(block.showIfRole) || !block.showIfRole">
            <router-link :to="{name: block.name}">
                <div class="icon" :class="((block.mrt) ? 'mr15' : '') + block.imageClass">
                    <img :src="imagesPathRewrite(block.image)" :class="{jst: block.jst}" alt="Icon">
                </div>
                <div v-if="block.showLine" class="vertical_line">
                    <img :src="imagesPathRewrite('vertical_line.svg')" alt="Line">
                </div>
                <div class="box_content">
                    <h3 class="title_box">{{ block.title }}</h3>
                    <p>{{ block.subTitle }}</p>
                </div>
                <div v-if="block.rightIcon" class="box_right">
                    <img :src="imagesPathRewrite(block.rightIcon)" alt="Right icon" :class="{largerIcon: block.largerIcon}">
                </div>
                <div v-else-if="block.rightCount" class="box_right">
                    <div class="circle" :class="{hasAtLeastOne: (block.rightCount && block.rightCount > 0)}">
                        <p class="count">{{ block.rightCount || 0 }}</p>
                    </div>
                </div>
            </router-link>
        </div>
    </div>
</template>
<style scoped src="./css/MenuBlock.css" />
<script>
import { imagesPathRewrite } from '../config'

export default {
        props: {
            blocks: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                imagesPathRewrite: imagesPathRewrite
            }
        }
}
</script>
