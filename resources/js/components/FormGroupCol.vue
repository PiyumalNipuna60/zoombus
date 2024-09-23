<template>
    <div :class="{row: !inline, col: inline, inline: inline, paddingRightZero: inline}">
        <v-col :class="{hidden: (item.field === 'hidden'), inline: inline}" v-for="(item, i) in items" :key="i" :cols="item.column || 12">
            <div class="form-group" :class="{hidden: item.hideGroup || (item.field === 'hidden'), relative: !!(item.arrowRight)}">
                <label :class="{img_label: item.labelImage, plb: item.plb, selectLabel: (item.field === 'select')}" v-if="!item.hideLabel && !item.hardLabel" :for="item.id">
                    <img :src="imagesPathRewrite(item.labelImage)" :alt="item.name" v-if="item.labelImage">
                    <span v-else>{{ item.label }}</span>
                </label>
                <input type="hidden" :name="item.name" :value="item.value" v-if="item.field === 'hidden'">
                <v-text-field
                    v-else-if="item.field === 'input'"
                    :rules="item.rules"
                    v-model="item.value"
                    :type="item.type || 'text'"
                    :class="item.class || 'form-control'"
                    :name="item.name"
                    :label="item.hardLabel"
                    :placeholder="item.placeholder"
                    :spellcheck="item.spellcheck || false"
                    :id="item.id"
                    :value="item.value"
                    :ref="item.name"
                    autocomplete="off"
                    :disabled="item.disabled || false"
                />
                <template v-else-if="item.field === 'autocomplete'">
                    <div class="interlink" v-if="item.interlinked">
                        <img :src="imagesPathRewrite('driver/devider.svg')" alt="Interlinked">
                    </div>
                    <v-combobox
                        :rules="item.rules"
                        v-model="item.value"
                        :spellcheck="item.spellcheck || false"
                        :placeholder="item.placeholder"
                        :id="item.id"
                        :name="item.name"
                        :append-icon="null"
                        @input.native="e => item.value = e.target.value"
                        :value="item.value"
                        :ref="item.name"
                        :label="item.hardLabel"
                        :disabled="item.disabled || false"
                        :class="item.class || 'form-control'"
                        :loading="item.isLoading"
                        :items="item.values"
                        hide-selected
                        @keyup="(e) => item.change(e, item.name)"
                    />
                    <div class="rightArrow" v-if="item.arrowRight">
                        <img :src="imagesPathRewrite('driver/right_strela.svg')" alt="Right arrow">
                    </div>
                </template>
                <vue-tel-input
                    v-else-if="item.field === 'phone_number'"
                    v-model="item.value"
                    :name="item.name"
                    :disabled="item.disabled || false"
                    v-bind="telProps"
                />
                <v-select
                    v-else-if="item.field === 'select'"
                    :items="item.values"
                    :label="item.hardLabel"
                    :placeholder="item.placeholder"
                    v-model="item.value"
                    :single-line="item.singleLine || false"
                    :value="item.value"
                    :rules="item.rules"
                    :append-icon="dropdownIcon"
                    :hide-details="item.hideDetails || false"
                    :name="item.name"
                    :disabled="item.disabled || false"
                    :id="item.id || item.name"
                    :class="item.class || 'form-control'"
                    @change="() => { updateSelect(item.name, item.value); if(typeof item.change === 'function') { item.change(item.value) } }"
                />
                <v-file-input
                    v-else-if="item.field === 'file'"
                    :name="item.name"
                    :id="item.id || item.name"
                    :ref="item.name"
                    :disabled="item.disabled || false"
                    :class="item.class"
                />
                <div class="seat_box" v-else-if="item.field === 'seats'" :class="item.class">
                    <div :class="{minivan_scheme: (item.routeType === 1), bus_scheme: (item.routeType === 2), car_scheme: (item.routeType === 3)}">
                        <div class="scheme_start"></div>
                        <div class="scheme_container" :style="'height: '+ maximumHeightFromScheme(item.values, item.routeType, item.reserving) + 'px;'" v-if="item.values">
                            <div
                                class="vehicle_seat"
                                :class="vehicleSeatClass(item, iv.value)"
                                v-for="(iv, k) in item.values"
                                :key="k"
                                @click="preserveSeat(item, iv.value)"
                                :style="'top:'+iv.top+'px; right:'+rightIf(iv.right, k, item.routeType)+'px;'"
                            >
                                <span>{{ iv.value }}</span>
                                <div
                                    class="filler"
                                    v-if="vehicleSeats.find(d => d.id === item.routeType).min < item.values.length && !item.reserving">
                                </div>
                                <v-icon
                                    size="26"
                                    color="error"
                                    @click="item.removeSeat(item.values, iv.value)"
                                    v-if="vehicleSeats.find(d => d.id === item.routeType).min < item.values.length && !item.reserving"
                                    class="removeSeat">
                                    {{ deleteRowIcon }}
                                </v-icon>
                            </div>
                        </div>
                        <div class="scheme_end">
                            <v-btn color="primary"
                                   @click="item.addNew(item.routeType, item.values)"
                                   v-if="vehicleSeats.find(d => d.id === item.routeType).max > Object.keys(item.values).length && !item.reserving">
                                {{ newRow }}
                            </v-btn>
                        </div>
                    </div>
                </div>
                <div v-else-if="item.field === 'textBreak'" class="new_section">
                    <h3 class="title_section">{{ item.text }}</h3>
                </div>
                <v-row v-else-if="item.field === 'checkboxes'">
                    <v-col v-for="(v, i) in item.values" :key="i" :cols="6">
                        <v-checkbox
                            :label="v.text"
                            :value="v.value"
                            @change="addCurrentValueToParentArray(item.name)"
                            v-model="theCheckedCheckboxes.find(d => d.id === item.name).values"
                        />
                    </v-col>
                </v-row>
                <v-radio-group
                    v-else-if="item.field === 'radioGroup'"
                    v-model="theCheckedRadio"
                    :hide-details="item.hideDetails"
                    :class="item.class"
                    :disabled="item.disabled || false"
                >
                    <div v-for="(itm,k) in item.items" :key="k" class="radioGroupPadding">
                        <v-radio
                            :key="itm.value"
                            :name="itm.name"
                            :label="itm.label"
                            :class="itm.class"
                            :value="itm.value"
                            @change="addCurrentValueToParent(itm.value, i)"
                        />
                        <v-flex v-if="itm.hasDelete" class="justify-end text-right">
                            <v-btn
                                class="nopaddingauto"
                                type="button"
                                tile
                                color="error"
                                @click="deleteCallback(itm.value)"
                            >
                                <v-icon size="24" color="white">{{ close }}</v-icon>
                            </v-btn>
                        </v-flex>
                        <router-link v-if="itm.link" :to="{name: itm.link.name}" class="button">
                            <span v-if="!itm.link.textRight"> {{ itm.link.text }} </span>
                            <img v-if="itm.link.image" :src="itm.link.image.src" :alt="itm.link.image.alt">
                            <span v-if="itm.link.textRight"> {{ itm.link.text }} </span>
                            <div class="financialArrow text-right" v-if="itm.link.hasArrow">
                                <img :src="imagesPathRewrite('right_arrow.svg')" alt="Right arrow">
                            </div>
                        </router-link>
                    </div>
                </v-radio-group>
                <div v-else-if="item.field === 'image'" :class="item.class" class="h-15-label">
                    <v-file-input
                        :rules="item.rules"
                        :class="item.classChild || 'hidden-input'"
                        :label="item.hardLabel"
                        :id="item.id"
                        :disabled="item.disabled || false"
                        :multiple="item.multiple"
                        :hide-details="true"
                        :accept="item.allowedFormats"
                        @change="(e) => triggerImageChange(e, item.id, item.allowedFormats, item.multiple, item.forceArray || false)"
                        :error-messages="(customError.find(d => d[item.id])) ? 'format' : ''"
                    />
                    <div class="file-upload" :class="{redBorder: (customError.find(d => d[item.id]))}">
                        <div class="upload_box">
                            <label @click="triggerFileClick(item.id)" class="file-upload_label no-transform">
                                <img :src="imagesPathRewrite('driver/photo_aparat.svg')" alt="Upload Photo">
                            </label>
                        </div>
                        <div :id="'preUploaded-' + item.id" class="uploadImages">
                            <swiper :ref="item.id" class="swiper-main" :options="swiperOptions(item.id)">
                                <swiper-slide v-for="(ite,i) in uploadedImages.filter(d => d.id === item.id)" :key="i">
                                    <img :src="ite.value" :alt="i" class="uploadedImage">
                                </swiper-slide>
                            </swiper>
                            <div class="swiper-navigation" v-if="uploadedImages.filter(d => d.id === item.id).length > 1">
                                <v-icon color="greyOne" size="30" @click="prevSlide(item.id)" class="prev" v-if="displayPrev.find(d => d.id === item.id).value">
                                    {{ prevIcon }}
                                </v-icon>
                                <v-icon color="greyOne" size="30" @click="nextSlide(item.id)" class="next" v-if="displayNext.find(d => d.id === item.id).value">
                                    {{ nextIcon }}
                                </v-icon>
                            </div>
                        </div>
                    </div>
                    <div class="v-text-field__details">
                        <div class="v-messages theme--light error--text" role="alert" v-if="customError && customError.find(d => d[item.id])">
                            <div class="v-messages__wrapper">
                                <div class="v-messages__message">
                                    {{ customError.find(d => d[item.id])[item.id] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <v-dialog
                    v-else-if="item.field === 'time'"
                    ref="dialogTime"
                    v-model="item.modal"
                    :disabled="item.disabled || false"
                    transition="scale-transition"
                    offset-y
                    :class="item.class"
                    min-width="290px"
                    :retain-focus="false"
                    persistent
                >
                    <template v-slot:activator="{ on }">
                        <v-text-field
                            v-model="item.value"
                            v-on="on"
                            :class="item.class"
                            :label="item.hardLabel"
                            readonly
                        />
                    </template>
                    <v-time-picker
                        v-if="item.modal"
                        v-model="item.value"
                        full-width
                        :format="item.format || '24hr'"
                    >
                        <v-btn class="submit smaller" color="primary" @click="item.modal = false">
                            <v-icon color="white" size="36">
                                {{ tickIcon }}
                            </v-icon>
                        </v-btn>
                    </v-time-picker>
                </v-dialog>
                <div v-else-if="item.field === 'datepicker'">
                    <v-text-field
                        :value="convertToReadableDate(item.value)"
                        @click.prevent="openDialog(item)"
                        :class="item.class"
                        :label="item.hardLabel"
                        :placeholder="item.placeholder"
                        readonly
                    />
                    <v-dialog
                        ref="dialog"
                        v-model="item.modal"
                        :disabled="item.disabled || false"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        :retain-focus="false"
                        offset-y
                        :class="item.class"
                        min-width="290px"
                        multiple
                    >
                        <v-date-picker
                            first-day-of-week="1"
                            v-model="item.value"
                            :ref="item.name+'_datepicker'"
                            :type="item.calendarType"
                            :min="item.min || null"
                            :max="item.max || null"
                            @input="(!item.multiple) ? item.modal = false : item.modal = true"
                            :multiple="item.multiple || false"
                            :month-format="getMonthFormat"
                            :title-date-format="getTitleDateFormat"
                            :header-date-format="getHeaderDateFormat"
                        >
                            <v-btn class="submit smaller" color="primary" @click="item.modal = false" v-if="item.multiple">
                                <v-icon color="white" size="36">
                                    {{ tickIcon }}
                                </v-icon>
                            </v-btn>
                        </v-date-picker>
                    </v-dialog>
                </div>
            </div>
        </v-col>
    </div>
</template>
<script>
import {imagesPathRewrite, vehicleSeats} from '../config'
import lang from '../translations'
import {VueTelInput} from 'vue-tel-input'
import {mdiChevronDown, mdiChevronLeft, mdiChevronRight, mdiClose, mdiCloseCircle, mdiCheck} from '@mdi/js'
import validations from '../validations'


import {Swiper, SwiperSlide, directive} from 'vue-awesome-swiper'

export default {
    components: {VueTelInput, Swiper, SwiperSlide},
    directives: {
        swiper: directive
    },
    props: {
        defaultVal: {
            type: String,
            default: ''
        },
        items: {
            type: Array,
            required: true
        },
        deleteCall: {
            type: Function
        },
        checkedRadio: {
            type: Number
        },
        formId: {
            type: String
        },
        checkedCheckboxes: {
            type: Array
        },
        inline: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            tickIcon: mdiCheck,
            uploadedImages: [],
            displayPrev: [],
            displayNext: [],
            nextIcon: mdiChevronRight,
            prevIcon: mdiChevronLeft,
            vehicleSeats: vehicleSeats,
            newRow: lang[this.$store.state.locale].newRow,
            dropdownIcon: mdiChevronDown,
            deleteRowIcon: mdiCloseCircle,
            customError: [],
            loadingButton: false,
            theCheckedRadio: this.checkedRadio,
            theCheckedCheckboxes: this.checkedCheckboxes,
            ind: null,
            close: mdiClose,
            imagesPathRewrite: imagesPathRewrite,
            telProps: {
                mode: 'international',
                defaultCountry: 'GE',
                placeholder: lang[this.$store.state.locale].login.fields.placeholders.phone_number,
                required: true,
                enabledCountryCode: false,
                enabledFlags: true,
                onlyCountries: ['GE'],
                autocomplete: 'off'
            }
        }
    },
    mounted() {
        const images = this.items.filter(d => d.field === 'image')
        images.forEach(item => {
            if (item.value) {
                if (Array.isArray(item.value)) {
                    item.value.forEach(d => {
                        this.uploadedImages.push({id: item.id, value: d})
                    })
                } else {
                    this.uploadedImages.push({id: item.id, value: item.value})
                }
            }
        })
    },
    methods: {
        openDialog(item) {
            item.modal = !item.modal
            if(['birth_date','date_of_registration'].includes(item.name)) {
                setTimeout(() => {
                    this.$refs[item.name+'_datepicker'][0].activePicker = 'YEAR'
                })
            }
        },
        preserveSeat(item, value) {
            if (item.reserving) {
                const dataObject = {
                    gender_id: 1,
                    seat_number: value
                }
                if (this.vehicleSeatClass(item, value) === 'available') {
                    item.preserved.push({seat_number: value})
                    this.$emit('preserveAction', 'add', dataObject)
                } else if (this.vehicleSeatClass(item, value) === 'preserved') {
                    item.preserved.splice(item.preserved.findIndex(d => d.seat_number === value), 1)
                    this.$emit('preserveAction', 'remove', dataObject)
                }
            }
        },
        vehicleSeatClass(item, value) {
            if (item.reserving) {
                if (item.boughts && item.boughts.length && item.boughts.find(d => d.seat_number === value)) {
                    return 'purchased'
                } else if (item.reserving && item.preserved && item.preserved.length && item.preserved.find(d => d.seat_number === value)) {
                    return 'preserved'
                } else {
                    return 'available'
                }
            } else {
                return 'available'
            }
        },
        rightIf(iv, k, routeType) {
            if (parseInt(k) === 1) {
                const configRoute = this.vehicleSeats.find(d => d.id === routeType)
                return iv + configRoute.firstMinus
            } else {
                return iv
            }
        },
        swiperOptions(id) {
            return {
                on: {
                    init: () => {
                        this.displayPrev.push({id: id, value: false})
                        this.displayNext.push({id: id, value: true})
                    },
                    slideChange: () => {
                        if (this.$refs[id][0].$swiper.isBeginning) {
                            this.displayPrev.find(d => d.id === id).value = false
                            this.displayNext.find(d => d.id === id).value = true
                        } else if (this.$refs[id][0].$swiper.isEnd) {
                            this.displayPrev.find(d => d.id === id).value = true
                            this.displayNext.find(d => d.id === id).value = false
                        } else {
                            this.displayPrev.find(d => d.id === id).value = true
                            this.displayNext.find(d => d.id === id).value = true
                        }
                    }
                }
            }
        },
        prevSlide(id) {
            this.$refs[id][0].$swiper.slidePrev()
        },
        nextSlide(id) {
            this.$refs[id][0].$swiper.slideNext()
        },
        maximumHeightFromScheme(values, type, preserving = false) {
            if (!type || type === 3) {
                return 0
            } else {
                const maxHeightArray = []
                const seatsObject = this.vehicleSeats.find(d => d.id === type)
                Object.keys(values).forEach(d => {
                    maxHeightArray.push(parseInt(values[d].top))
                })
                return Math.max(...maxHeightArray) + seatsObject.seat.height + ((!preserving) ? seatsObject.seat.separator : 5) - seatsObject.seat.top
            }
        },
        triggerFileClick(id) {
            document.getElementById(id).click()
        },
        displayImage(event, id) {
            const fr = new FileReader()
            fr.onload = () => {
                this.uploadedImages.push({id: id, value: fr.result})
            }
            fr.readAsDataURL(event)
        },
        showSelectedImage(event, id) {
            this.uploadedImages = this.uploadedImages.filter(d => d.id !== id)
            if (Array.isArray(event)) {
                event.forEach(ev => {
                    this.displayImage(ev, id)
                })
            } else {
                this.displayImage(event, id)
            }
        },
        triggerImageChange(event, id, allowedFormats, forceArray = false) {
            if (id && document.getElementById(id).value) {
                if (!Array.isArray(event) && !allowedFormats.includes(event.type.toLowerCase())) {
                    if (!this.customError.find(d => d[id])) {
                        this.customError.push({[id]: validations[this.$store.state.locale][this.formId][id].wrongFormat})
                    }
                } else if (Array.isArray(event)) {
                    event.forEach(ev => {
                        if (!allowedFormats.includes(ev.type.toLowerCase())) {
                            if (!this.customError.find(d => d[id])) {
                                this.customError.push({[id]: validations[this.$store.state.locale][this.formId][id].wrongFormat})
                            }
                        }
                    })
                }

                this.$emit('addToFiles', event, id, forceArray)
                this.customError.splice(this.customError.indexOf(id), 1)
                this.showSelectedImage(event, id)
            } else if (!this.uploadedImages.find(d => d.id === id).value) {
                this.customError.push({[id]: validations[this.$store.state.locale][this.formId][id].required})
            }
        },
        deleteCallback(id) {
            this.ind = id
            this.deleteCall(id)
        },
        addCurrentValueToParent(value, index) {
            this.items[index].value = value
        },
        addCurrentValueToParentArray(name) {
            this.items.find(d => d.name === name).value = this.checkedCheckboxes.find(d => d.id === name).values
        },
        updateSelect(name, value) {
            this.items.filter(n => n.name === name)[0].value = value
        },
        getMonthFormat(isoDate) {
            const splitter = isoDate.split('-')
            return lang[this.$store.state.locale].dates[parseInt(splitter[1])].short
        },
        getHeaderDateFormat(isoDate) {
            if (typeof isoDate === 'string') {
                const splitter = isoDate.split('-')
                if (typeof splitter[1] !== 'undefined') {
                    return lang[this.$store.state.locale].dates[parseInt(splitter[1])].long + ' ' + splitter[0]
                } else {
                    return isoDate
                }
            } else if (isoDate && isoDate.length) {
                return isoDate.length + ' ' + lang[this.$store.state.locale].dates_selected
            }
        },
        getTitleDateFormat(isoDate) {
            if (typeof isoDate === 'string') {
                const splitter = isoDate.split('-')
                if (typeof splitter[1] !== 'undefined' && typeof splitter[2] !== 'undefined') {
                    return splitter[2] + ' ' + lang[this.$store.state.locale].dates[parseInt(splitter[1])].long + ' ' + splitter[0]
                }
            } else if (isoDate && isoDate.length) {
                return isoDate.length + ' ' + lang[this.$store.state.locale].dates_selected
            }
        },
        convertToReadableDate(date) {
            if (date && typeof date === 'string') {
                const splitter = date.split('-')
                if (typeof splitter[1] !== 'undefined' && typeof splitter[2] !== 'undefined') {
                    const day = (splitter[2].startsWith(0)) ? splitter[2].charAt(1) : splitter[2]
                    return day + ' ' + lang[this.$store.state.locale].dates[parseInt(splitter[1])].long + ' ' + splitter[0]
                }
            } else if (date && date.length) {
                return date.length + ' ' + lang[this.$store.state.locale].dates_selected
            }
        }
    }
}
</script>
<style scoped src="./css/FormGroupCol.css"/>
