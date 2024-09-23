<template>
    <div>
        <Header :title="title" :showLogo="!(success)" :hideLogoText="!(success)" :caption="caption" v-if="!showCamera"/>
        <section :class="{'mt-80': (success), 'mlr-20': !(showCamera)}">
            <div v-if="success" class="w100 list">
                <v-alert type="success">
                    {{ success }}
                </v-alert>
                <div class="ticket" v-if="ticket">
                    <div class="first_box">
                        <div class="passenger">
                            <p class="title_area">{{ lang.salesHistory.passenger }}</p>
                            <p class="passenger_name">{{ ticket.passenger }}</p>
                        </div>
                        <p class="price">{{ ticket.price }} <span>gel</span></p>
                    </div>
                    <div class="second_box">
                        <div class="from">
                            <p class="title_area">{{ lang.salesHistory.departure }}</p>
                            <p class="city_name">{{ ticket.from }}</p>
                        </div>
                        <div class="date">
                            <p class="title_area">{{ lang.salesHistory.date }}</p>
                            <p class="departure_date">{{ ticket.departure_date }}</p>
                        </div>
                        <div class="departure_time">
                            <p class="title_area">{{ lang.salesHistory.departure_time }}</p>
                            <p class="time">{{ ticket.departure_time }}</p>
                        </div>
                    </div>
                    <div class="third_box">
                        <div class="destination">
                            <p class="title_area">{{ lang.salesHistory.arrival }}</p>
                            <p class="city_destination">{{ ticket.to }}</p>
                        </div>
                        <div class="seat">
                            <p class="title_area">{{ lang.salesHistory.seat }}</p>
                            <p class="seat_number">{{ ticket.seat_number }}</p>
                        </div>
                        <div class="destination_time">
                            <p class="title_area">{{ lang.salesHistory.arrival_time }}</p>
                            <p class="time">{{ ticket.arrival_time }}</p>
                        </div>
                    </div>
                </div>
                <v-btn :loading="isLoading" color="blackOne" class="submit" @click.prevent="resetSuccess">
                    {{ lang.scanAgain }}
                </v-btn>
            </div>
            <div v-else>
                <div v-if="showCamera">
                    <qrcode-stream @decode="onDecode" @init="onInit"/>
                </div>
                <div v-else>
                    <vzForm formId="parseTicket" formClass="borderless-form bordered w100" v-bind="formParams"
                            :key="count"/>
                    <v-btn :loading="isLoading" color="blackOne" class="submit" @click.prevent="cameraOn">
                        {{ lang.toggleCamera }}
                    </v-btn>
                </div>
            </div>
        </section>
        <Footer :key="incrementCount" :items-prop="footerItems" @cameraOff="cameraOff"/>
    </div>
</template>
<style scoped src="./css/QRScanner.css"/>
<script>
import {QrcodeStream} from 'vue-qrcode-reader'
import lang from "../translations";
import Header from "../components/Header";
import Footer from "../components/Footer";
import VzForm from "../components/Form";
import validations from "../validations";
import {imagesPathRewrite} from "../config";
import SalesListByRoute from "../components/SalesListByRoute";

export default {
    name: "QRScanner",
    components: {
        SalesListByRoute,
        VzForm,
        QrcodeStream,
        Header,
        Footer
    },
    data() {
        return {
            parent: this.$route.meta.parent,
            title: lang[this.$store.state.locale].QRScanner.title,
            caption: lang[this.$store.state.locale].QRScanner.caption,
            lang: lang[this.$store.state.locale],
            showCamera: false,
            imagesPathRewrite: imagesPathRewrite,
            isLoading: false,
            count: 0,
            incrementCount: this.$store.state.new_notifications,
            footerItems: null,
            ticket: null,
            success: null,
            formParams: {
                inline: true,
                errorProp: null,
                submit: {
                    class: 'submit',
                    large: true,
                    block: true,
                    column: 5,
                    loading: false,
                    text: lang[this.$store.state.locale].QRScanner.saveButton,
                },
                items: [
                    {
                        placeholder: lang[this.$store.state.locale].QRScanner.fields.placeholders.ticketNumber,
                        field: 'input',
                        type: 'text',
                        class: 'forceEng',
                        name: 'ticket_number',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].QRScanner.ticket_number.required,
                        ],
                        plb: true,
                        column: 7,
                        value: '',
                        labelImage: 'form/unlock.svg',
                    },
                ],
            }
        }
    },
    methods: {
        resetSuccess() {
            this.success = null;
        },
        onDecode(result) {
            if (result && result.length) {
                this.cameraOff();
                this.isLoading = true;
                this.$store.dispatch('apiCall', {
                    actionName: 'parseQR',
                    data: {lang: this.$store.state.locale, QRData: result}
                }).then(data => {
                    this.isLoading = false;
                    this.ticket = data.data;
                    this.success = validations[this.$store.state.locale].QRScanner.success;
                }).catch(e => {
                    this.isLoading = false;
                    this.formParams.errorProp = e.response.data.text;
                    this.count++;
                });
            }
        },
        cameraOn() {
            this.showCamera = true;
            this.footerItems = [
                {
                    isCameraOff: true,
                    icon: 'cameraOff.png',
                    anchor: this.lang.turnOffCamera,
                },
            ];
            this.incrementCount++;
        },
        cameraOff() {
            this.showCamera = false;
            this.footerItems = null;
            this.incrementCount = this.$store.state.new_notifications;
        },
        async onInit(promise) {
            try {
                await promise
            } catch (error) {
                this.cameraOff();
                this.formParams.errorProp = validations[this.$store.state.locale].QRScanner.cameraErrors[error.name];
                this.count++;
            }
        }
    }
}
</script>
