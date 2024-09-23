<template>
    <v-form ref="form" v-model="valid" :class="formClass" :lazy-validation="lazy" :form-id="formId" @submit.prevent="validate">
        <v-alert type="error" v-if="error">
            {{ error }}
            <router-link v-if="errorUrlCustom" :to="errorUrlCustom">
                {{ errorDetails }}
            </router-link>
        </v-alert>
        <v-alert type="success" v-if="success">
            {{ success }}
        </v-alert>
        <v-alert type="warning" v-if="pending">
            {{ pending }}
        </v-alert>
        <div :class="{disabledForm: disabledFields}">
            <v-container>
                <v-row v-if="inline">
                    <FormGroupCol v-bind="formGroupColParams" @addToFiles="addToFiles" @preserveAction="preServe"/>
                    <v-col :cols="submit.column || 12" :class="{paddingLeftZero: inline}">
                        <v-btn v-if="submit" v-bind="submitParams" :disabled="!valid || disabledFields || submitDisable">
                            <v-icon left v-if="submit.icon === 'mdi-content-save'">{{ save }}</v-icon>
                            {{ submit.text }}
                            <v-icon right v-if="submit.icon === 'next'" size="24" class="largeIconNext">{{ next }}</v-icon>
                        </v-btn>
                    </v-col>
                    <slot/>
                </v-row>
                <div v-else>
                    <FormGroupCol v-bind="formGroupColParams" @addToFiles="addToFiles" @preserveAction="preServe"/>
                    <slot/>
                    <v-row class="clearfix">
                        <v-col v-if="goBack" :cols="goBack.column || 12">
                            <v-btn v-bind="goBackParams" @click="goBack.action" color="blackOne" class="largeIconPrev">
                                <v-icon left size="24">{{ prev }}</v-icon>
                                {{ goBack.text }}
                            </v-btn>
                        </v-col>
                        <v-col :cols="submit.column || 12">
                            <v-btn v-if="submit" v-bind="submitParams" :disabled="!valid || disabledFields || submitDisable">
                                <v-icon left v-if="submit.icon === 'mdi-content-save'">{{ save }}</v-icon>
                                {{ submit.text }}
                                <v-icon right v-if="submit.icon === 'next'" size="24" class="largeIconNext">{{ next }}</v-icon>
                            </v-btn>
                        </v-col>
                        <v-col v-if="remove" :cols="remove.column || 12">
                            <v-btn v-if="remove" v-bind="removeParams" @click="remove.action">
                                <v-icon left v-if="submit.icon">{{ trash }}</v-icon>
                                {{ remove.text }}
                            </v-btn>
                        </v-col>
                    </v-row>
                </div>
            </v-container>
        </div>
    </v-form>
</template>

<script>
import {imagesPathRewrite} from '../config'
import FormGroupCol from './FormGroupCol'
import {mdiContentSave, mdiChevronLeft, mdiChevronRight, mdiDelete} from '@mdi/js'
import {scroller} from 'vue-scrollto/src/scrollTo'
import validations from '../validations'
import lang from '../translations'

const props = {
    scrollToDiv: {
        type: String
    },
    disableSubmit: {
        type: Boolean,
        default: false
    },
    disabledFields: {
        type: Boolean,
        default: false
    },
    formClass: {
        type: String,
        default: 'form'
    },
    formId: {
        type: String,
        required: true
    },
    commit: {
        type: String
    },
    onSuccessRedirect: {
        type: String
    },
    onSuccessRedirectQuery: {
        type: Object
    },
    submit: {
        type: Object
    },
    goBack: {
        type: Object
    },
    remove: {
        type: Object
    },
    lazy: {
        type: Boolean,
        default: false
    },
    files: {
        type: Array
    },
    fileNames: {
        type: Array
    },
    errorProp: {
        type: String
    },
    errorLink: {
        type: String
    },
    successProp: {
        type: String
    },
    pendingProp: {
        type: String
    },
    loadingButton: {
        type: Boolean,
        default: false
    },
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
    checkboxes: {
        type: Array
    },
    successText: {
        type: String
    },
    customSuccess: {
        type: Function
    },
    inline: {
        type: Boolean,
        default: false
    }
}

export default {
    name: 'vzForm',
    components: {FormGroupCol},
    props: props,
    methods: {
        scrollTo(div) {
            const scr = scroller()
            scr('#' + div)
        },
        validate() {
            if (this.$refs.form.validate()) {
                this.onSubmit(this.formId)
            }
        },
        addToFiles(event, id, forceArray = false) {
            this.groupImages[id] = event
            if (forceArray) {
                this.forceArray[id] = true
            }
        },
        preServe(action, object) {
            if (action === 'add' && !this.preserves.find(d => d.seat_number === object.seat_number)) {
                this.preserves.push(object)
            } else {
                if (!this.preserves.find(d => d.seat_number === object.seat_number)) {
                    this.preserves.push(object)
                } else {
                    this.preserves.splice(this.preserves.indexOf(object), 1)
                }
            }
            this.submitDisable = !(this.preserves && this.preserves.length)
        },
        onSubmit(actionName) {
            this.error = null
            this.success = null
            this.submitParams.loading = true
            const data = {}
            const formData = new FormData()
            formData.append('lang', this.$store.state.locale)
            this.items.forEach(d => {
                if (!d.excludeFromData) {
                    if (d.multiple) {
                        data[d.name] = JSON.stringify(d.value)
                        console.log(data[d.name])
                    } else {
                        data[d.name] = d.value
                    }
                    if (d.name === 'phone_number' || d.name === 'username') {
                        if (d.value.substr(0, 4) !== '+995') {
                            formData.append(d.name, '+995' + d.value.replace(/\s/g, ''))
                        } else {
                            formData.append(d.name, d.value.replace(/\s/g, ''))
                        }
                    } else {
                        if (d.multiple) {
                            d.value.forEach(i => {
                                formData.append(d.name + '[]', i)
                            })
                        } else {
                            formData.append(d.name, d.value)
                        }
                    }
                }
            })
            if (typeof this.files !== 'undefined') {
                this.files.forEach((d, i) => {
                    formData.append(this.fileNames[i], d)
                })
            }
            if (this.groupImages) {
                Object.keys(this.groupImages).forEach(item => {
                    if (this.groupImages[item].length > 1 || this.forceArray[item]) {
                        this.groupImages[item].forEach(gi => {
                            formData.append(item + '[]', gi)
                        })
                    } else {
                        formData.append(item, this.groupImages[item][0])
                    }
                })
            }
            if (this.preserves && this.preserves.length) {
                formData.append('id', this.$route.params.id)
                formData.append('seats', JSON.stringify(this.preserves))
            }
            this.$store.dispatch('apiCall', {
                actionName: actionName,
                data: formData,
                commit: this.commit || null,
                onSuccessRedirect: this.onSuccessRedirect || null,
                onSuccessRedirectQuery: this.onSuccessRedirectQuery || null
            }).then((d) => {
                this.submitParams.loading = false
                if (d.data.step) {
                    if (d.data.step === 4 && actionName === 'vehicleAdd') {
                        this.$store.commit('setWizardVehicleId', d.data.id)
                    }
                    this.$store.commit('setWizardStep', d.data.step)
                } else {
                    if (typeof d.data !== 'undefined' && !d.data.text && (typeof d.data === 'object' || Array.isArray(d.data))) {
                        this.items.forEach(data => {
                            data.value = d.data[data.name]
                        })
                    }
                    if (!this.onSuccessRedirect) {
                        this.error = null
                        this.pending = null
                        this.success = d.data.successText || this.successText || validations[this.$store.state.locale][actionName].success
                        this.scrollTo(this.scrollToDiv || 'app')
                        if (this.customSuccess) {
                            this.customSuccess(this.success)
                        }
                    }
                    this.preserves = []
                    this.submitDisable = true
                }
            }).catch((e) => {
                this.submitParams.loading = false
                this.success = null
                this.pending = null
                this.error = (e.response && e.response.data) ? e.response.data.text || validations[this.$store.state.locale][actionName][e.response.data.error] : 'Unknown error, please contact administration'

                if (e.response && e.response.data && e.response.data.error) {
                    if (e.response.data.error.route) {
                        this.errorUrlCustom = this.$router.resolve({
                            name: e.response.data.error.route
                        }).href
                    } else if (e.response.data.error.link) {
                        this.errorUrlCustom = e.response.data.error.link
                    }
                    this.errorDetails = e.response.data.error.text
                }
                this.scrollTo(this.scrollToDiv || 'app')
            })
        }
    },
    data() {
        return {
            groupImages: {},
            forceArray: {},
            imagesPathRewrite: imagesPathRewrite,
            preserves: [],
            success: this.successProp,
            error: this.errorProp,
            pending: this.pendingProp,
            errorUrlCustom: this.errorLink,
            errorDetails: this.details,
            details: lang[this.$store.state.locale].details,
            valid: true,
            submitDisable: this.disableSubmit,
            save: mdiContentSave,
            next: mdiChevronRight,
            prev: mdiChevronLeft,
            trash: mdiDelete,
            formGroupColParams: {
                formId: this.formId,
                deleteCall: this.deleteCall,
                items: this.items,
                checkedRadio: this.checkedRadio,
                checkedCheckboxes: this.checkboxes,
                inline: this.inline
            },
            submitParams: {
                type: this.submit.type || 'submit',
                class: this.submit.class,
                large: this.submit.large,
                color: this.submit.color || 'primary',
                loading: false
            },
            removeParams: {
                type: (this.remove) ? this.remove.type || 'button' : 'button',
                class: (this.remove) ? this.remove.class : null,
                large: (this.remove) ? this.remove.large : null,
                color: (this.remove) ? this.remove.color || 'primary' : 'primary',
                loading: false
            },
            goBackParams: {
                type: (this.goBack) ? this.goBack.type || 'button' : 'button',
                class: (this.goBack) ? this.goBack.class : null,
                large: (this.goBack) ? this.goBack.large : null,
                color: (this.goBack) ? this.goBack.color || 'primary' : 'primary',
                loading: false
            }
        }
    }
}
</script>
<style scoped src="./css/Form.css"/>
