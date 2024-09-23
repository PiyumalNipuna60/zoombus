<template>
    <div v-if="!isLoading">
        <avatar-cropper
            @success="handleSuccess"
            @error="handlerError"
            upload-form-name="avatar"
            action-name="setAvatar"
            output-mime="image/jpeg"
            :modalTitle="modalTitle"
            trigger="#pick-avatar"
        />
        <Header :title="title" :parent="parent" :hide-back="isWizard" :showLogo="false" :is-child="isWizard"/>
        <section class="personal">
            <wizard-steps v-if="isWizard"/>
            <div class="w-90">
                <v-alert type="error" v-if="error">
                    {{ error }}
                </v-alert>
                <v-alert type="success" v-if="success">
                    {{ success }}
                </v-alert>
            </div>
            <div class="personal_photo">
                <div class="file-upload">
                    <button id="pick-avatar" class="file-upload_label">
                        <v-icon color="white" size="18">{{ camera }}</v-icon>
                    </button>
                </div>
                <img :src="formParams.items[9].value" :alt="formParams.items[1].value">
            </div>
            <div class="name_title">
                <h4>{{ formParams.items[1].value }}</h4>
                <p class="country_from">
                    {{ cityAndCountry }}
                </p>
            </div>
            <vzForm formId="profileUpdate" formClass="borderless-form" v-bind="formParams"/>
        </section>
        <Footer :key="$store.state.new_notifications"/>

    </div>
    <div v-else>
        <vLoading/>
    </div>
</template>

<script>
import {imagesPathRewrite} from '../../config'
import lang from '../../translations'
import Countries from '../../countries'
import validations from '../../validations'
import {email} from '../../validationFunctions'

import Header from '../../components/Header'
import Footer from '../../components/Footer'
import vzForm from '../../components/Form'
import vLoading from '../../components/Loading'

import {mdiCamera} from '@mdi/js'
import WizardSteps from '../../components/WizardSteps'
import AvatarCropper from "../../components/AvatarCropper"

const minBirthDate = new Date();
minBirthDate.setFullYear(minBirthDate.getFullYear() - 100);

const maxBirthDate = new Date();
maxBirthDate.setFullYear(maxBirthDate.getFullYear() - 18);

export default {
    components: {WizardSteps, vzForm, Header, Footer, vLoading, AvatarCropper},
    props: {
        isWizard: {
            type: Boolean
        }
    },
    computed: {
        cityAndCountry() {
            let returnValue = ''
            const currentCityValue = this.formParams.items[8].value
            const currentCountry = this.formParams.items[7].value
            if (currentCityValue && currentCityValue.length) {
                returnValue += currentCityValue
            }
            if (currentCountry && currentCityValue) {
                returnValue += ', '
            }
            if (Countries[this.$store.state.locale] && Countries[this.$store.state.locale].length && Countries[this.$store.state.locale].find(d => d.value === currentCountry)) {
                returnValue += Countries[this.$store.state.locale].find(d => d.value === currentCountry).text
            }
            return returnValue
        }
    },
    data() {
        return {
            parent: this.$route.meta.parent,
            profilePicture: [],
            camera: mdiCamera,
            imagesPathRewrite: imagesPathRewrite,
            title: lang[this.$store.state.locale].profile.title,
            isLoading: true,
            dialog: false,
            imgSrc: null,
            error: null,
            success: null,
            modalTitle: lang[this.$store.state.locale].profile.avatar,
            lang: lang[this.$store.state.locale],
            isLoadingAvatarSet: false,
            formParams: {
                submit: {
                    icon: (this.isWizard) ? 'next' : 'mdi-content-save',
                    class: 'submit',
                    large: true,
                    block: true,
                    loading: false,
                    text: (this.isWizard) ? lang[this.$store.state.locale].wizard.next : lang[this.$store.state.locale].profile.saveButton
                },
                items: [
                    {
                        field: 'hidden',
                        name: 'id',
                        value: ''
                    },
                    {
                        placeholder: lang[this.$store.state.locale].profile.fields.placeholders.name,
                        field: 'input',
                        name: 'name',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].profileUpdate.name.required
                        ],
                        plb: true,
                        value: '',
                        labelImage: 'form/user.svg'
                    },
                    {
                        placeholder: lang[this.$store.state.locale].profile.fields.placeholders.phone_number,
                        field: 'input',
                        name: 'phone_number',
                        class: 'forceEng',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].profileUpdate.phone_number.required
                        ],
                        labelImage: 'form/phone.svg',
                        plb: true,
                        value: ''
                    },
                    {
                        placeholder: lang[this.$store.state.locale].profile.fields.placeholders.email,
                        field: 'input',
                        type: 'email',
                        class: 'forceEng',
                        name: 'email',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].profileUpdate.email.required,
                            e => email(e) || validations[this.$store.state.locale].profileUpdate.email.valid
                        ],
                        value: '',
                        plb: true,
                        labelImage: 'form/mail.svg'
                    },
                    {
                        placeholder: lang[this.$store.state.locale].profile.fields.placeholders.id_number,
                        field: 'input',
                        value: '',
                        class: 'forceEng',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].profileUpdate.id_number.required
                        ],
                        plb: true,
                        name: 'id_number',
                        labelImage: 'form/personal_id.svg'
                    },
                    {
                        placeholder: lang[this.$store.state.locale].profile.fields.placeholders.gender,
                        field: 'select',
                        singleLine: true,
                        value: '',
                        values: lang[this.$store.state.locale].profile.genders,
                        plb: true,
                        name: 'gender_id',
                        labelImage: 'form/user.svg'
                    },
                    {
                        placeholder: lang[this.$store.state.locale].profile.fields.placeholders.birth_date,
                        field: 'datepicker',
                        modal: false,
                        calendarType: 'date',
                        min: minBirthDate.toISOString(),
                        max: maxBirthDate.toISOString(),
                        value: '',
                        plb: true,
                        name: 'birth_date',
                        labelImage: 'form/data.svg'
                    },
                    {
                        placeholder: lang[this.$store.state.locale].profile.fields.placeholders.country,
                        field: 'select',
                        singleLine: true,
                        plb: true,
                        value: '',
                        values: Countries[this.$store.state.locale],
                        name: 'country_id',
                        labelImage: 'form/marker.svg'
                    },
                    {
                        placeholder: lang[this.$store.state.locale].profile.fields.placeholders.city,
                        field: 'input',
                        value: '',
                        rules: [
                            v => !!v || validations[this.$store.state.locale].profileUpdate.city.required
                        ],
                        plb: true,
                        name: 'city',
                        labelImage: 'form/marker.svg'
                    },
                    {
                        field: 'hidden',
                        name: 'avatar',
                        value: ''
                    }
                ]
            }

        }
    },
    mounted() {
        document.title = this.title
        if (!this.isWizard) {
            this.$store.dispatch('apiCall', {actionName: 'profileGet'}).then(data => {
                this.isLoading = false
                this.formParams.items.forEach(d => {
                    d.value = data.data[d.name]
                })
            })
        } else {
            this.$store.dispatch('apiCall', {
                actionName: 'wizardByStep',
                data: {
                    lang: this.$store.state.locale,
                    step: 1
                }
            }).then(data => {
                this.$store.commit('setWizardStep', 1)
                this.formParams.items.forEach(d => {
                    d.value = data.data[d.name]
                })
                this.formParams.items.push({
                    field: 'hidden',
                    name: 'isWizard',
                    value: true
                })
                this.isLoading = false
            }).catch(e => {
                this.isLoading = false
                console.log(e)
            })
        }
    },
    watch: {
        menu(val) {
            val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR'))
        },
    },
    methods: {
        handleSuccess(imageURL) {
            this.formParams.items[9].value = imageURL
            this.success = validations[this.$store.state.locale].profileUpdate.avatar.success
            this.error = null
            this.avatarCropper = false
        },
        handlerError(message) {
            this.error = message
            this.success = null
            this.avatarCropper = false
        }
    }
}
</script>
<style scoped src="../css/Profile.css"/>
