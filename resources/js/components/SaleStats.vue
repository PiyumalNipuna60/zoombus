<template>
    <div class="sale_stats">
        <div class="info" v-for="(item,k) in items" :key="k">
            <h2>{{ item.label }}</h2>
            <div class="total" :class="item.class || 'primary--text'">{{ item.value }} <span v-if="item.isCurrency">gel</span></div>
        </div>
    </div>
</template>
<style scoped src="./css/SaleStats.css" />
<script>
export default {
        name: 'SaleStats',
        props: {
            items: {
                type: Array
            }
        }
}
</script>
