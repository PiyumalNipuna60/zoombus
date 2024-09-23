<template>
    <section id="car_section" v-if="!$store.state.routeEditHidePreserve">
        <h3 class="reverse_time" v-if="theCountdown">
            <countdown :time="theCountdown" :transform="transform" :interval="100" tag="p">
                <template slot-scope="props">
                    {{ props.days || null }}
                    <span v-if="props.days > 0">{{ getDaysText(props.days) }}</span>
                    {{ props.hours }}:{{ props.minutes }}:{{ props.seconds }}</template>
            </countdown>
        </h3>
        <vzForm v-bind="formParams" />
        <div class="seat_description">
            <div class="zoom_available">
                <img :src="imagesPathRewrite('transport/grey_seat.svg')" alt="Grey seat">
                <p class="title_area">{{ lang.routePreserve.free }}</p>
            </div>
            <div class="zoom_taken">
                <img :src="imagesPathRewrite('transport/blue_seat.svg')" alt="Blue seat">
                <p class="title_area">{{ lang.routePreserve.taken }}</p>
            </div>
            <div class="zoom_taken_by_driver">
                <img :src="imagesPathRewrite('transport/orange_seat.svg')" alt="Orange seat">
                <p class="title_area">{{ lang.routePreserve.takenByDriver }}</p>
            </div>
        </div>
    </section>
</template>

<script>
import vzForm from '../components/Form'
import lang from '../translations'
import {imagesPathRewrite} from "../config";

export default {
        name: 'RouteCountdown',
        components: { vzForm },
        props: {
            data: {
                type: Object
            },
            removeRoute: {
                type: Function
            }
        },
        methods: {
            getDaysText(day) {
                return (parseInt(day) === 1) ? lang[this.$store.state.locale].day : lang[this.$store.state.locale].days
            },
            transform(props) {
                Object.entries(props).forEach(([key, value]) => {
                    if (key === 'days') {
                        if (value > 0) {
                            props[key] = `${value}`
                        }
                    } else {
                        const digits = value < 10 ? `0${value}` : value
                        props[key] = `${digits}`
                    }
                })

                return props
            }
        },
        data() {
            return {
                schemes: null,
                theCountdown: null,
                imagesPathRewrite: imagesPathRewrite,
                lang: lang[this.$store.state.locale],
                formParams: {
                    scrollToDiv: 'car_section',
                    disableSubmit: true,
                    formId: 'preserveRoute',
                    formClass: 'default-form',
                    hasRow: true,
                    submit: {
                        icon: 'mdi-content-save',
                        class: 'submit',
                        large: true,
                        block: true,
                        loading: false,
                        text: lang[this.$store.state.locale].routePreserve.saveButton
                    },
                    items: [
                        {
                            hideLabel: true,
                            field: 'seats',
                            reserving: true,
                            name: 'seat_positioning',
                            excludeFromData: true,
                            class: 'forceEng',
                            value: [],
                            values: {},
                            boughts: [],
                            preserved: [],
                            routeType: 1
                        }
                    ]
                }
            }
        },
        mounted() {
            this.theCountdown = this.data.time * 1000
            this.schemes = this.data.scheme
            this.formParams.items.find(d => d.name === 'seat_positioning').routeType = this.data.vehicle.type
            this.formParams.items.find(d => d.name === 'seat_positioning').values = this.schemes[this.data.vehicle.type]
            this.formParams.items.find(d => d.name === 'seat_positioning').boughts = this.data.sales
            this.formParams.items.find(d => d.name === 'seat_positioning').preserved = this.data.preserved
            this.formParams.items.find(d => d.name === 'seat_positioning').value = JSON.stringify(this.schemes[this.data.vehicle.type])
        }
}
</script>

<style scoped src="./css/RouteCountdown.css"/>
