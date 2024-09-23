<template>
    <div>
        <v-dialog v-model="dialog" max-width="290">
            <v-card>
                <v-card-title class="headline-larger" v-if="modalTitle && modalTitle.length">{{ modalTitle }}</v-card-title>
                <v-card-text class="cropper-container">
                    <img :src="dataUrl" @load.stop="createCropper" alt ref="img">
                </v-card-text>
                <v-card-actions>
                    <v-row class="clearfix no-margins">
                        <v-col :cols="6">
                            <v-btn outlined class="popupButton" @click="cancel">
                                {{ lang.cancel }}
                            </v-btn>
                        </v-col>
                        <v-col :cols="6">
                            <v-btn color="primary" class="popupButton" :loading="isLoadingAvatarSet" @click="uploadImage">
                                {{ lang.set }}
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <input
            :accept="mimes"
            class="avatar-cropper-img-input hidden"
            ref="input"
            @change="fileSelected"
            type="file"
        >
    </div>
</template>

<script>
import Cropper from 'cropperjs'
import translations from "../translations"
import validations from "../validations"

export default {
    props: {
        trigger: {
            type: [String, Element],
            required: true
        },
        uploadHandler: {
            type: Function
        },
        actionName: {
            type: String
        },
        modalTitle: {
            type: String
        },
        requestMethod: {
            type: String,
            default: 'POST'
        },
        uploadHeaders: {
            type: Object
        },
        uploadFormName: {
            type: String,
            default: 'file'
        },
        uploadFormData: {
            type: Object,
            default() {
                return {}
            }
        },
        cropperOptions: {
            type: Object,
            default() {
                return {
                    aspectRatio: 1,
                    autoCropArea: 1,
                    minCropBoxWidth: 200,
                    minCropBoxHeight: 200,
                    viewMode: 1,
                    movable: true,
                    zoomable: false
                }
            }
        },
        outputOptions: {
            type: Object
        },
        outputMime: {
            type: String,
            default: null
        },
        outputQuality: {
            type: Number,
            default: 0.9
        },
        mimes: {
            type: String,
            default: 'image/png, image/jpeg'
        },
        labels: {
            type: Object,
            default() {
                return {
                    submit: 'Save',
                    cancel: 'Cancel'
                }
            }
        },
        withCredentials: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            cropper: undefined,
            dataUrl: undefined,
            filename: undefined,
            validations: validations[this.$store.state.locale],
            isLoadingAvatarSet: false,
            dialog: false,
            lang: translations[this.$store.state.locale]
        }
    },
    watch: {
        dialog: function (val) {
            if (!val) {
                this.cancel();
            }
        },
    },
    methods: {
        destroy() {
            if (this.cropper) {
                this.cropper.destroy()
            }
            this.$refs.input.value = null
            this.dataUrl = undefined
        },
        cancel() {
            this.dialog = false
            this.destroy();
        },
        pickImage(e) {
            this.$refs.input.click()
            e.preventDefault()
            e.stopPropagation()
        },
        fileSelected() {
            let fileInput = this.$refs.input
            if (fileInput.files != null && fileInput.files[0] != null) {
                let file, img
                let _URL = window.URL || window.webkitURL;
                if ((file = fileInput.files[0])) {
                    img = new Image();
                    img.onload = () => {
                        if (img.width < 200 || img.height < 200) {
                            this.cancel()
                            this.$emit('error', this.validations.profileUpdate.avatar.imageSizeSmall)
                        }
                    }
                    img.src = _URL.createObjectURL(file)
                }

                this.$emit('error', null)
                let reader = new FileReader()
                reader.onload = e => {
                    this.dataUrl = e.target.result
                }
                reader.readAsDataURL(fileInput.files[0])

                this.dialog = true
                this.filename = fileInput.files[0].name || 'unknown'
                this.mimeType = this.mimeType || fileInput.files[0].type
                this.$emit('changed', fileInput.files[0], reader)
            }
        },
        createCropper() {
            this.cropper = new Cropper(this.$refs.img, this.cropperOptions)
        },
        uploadImage() {
            this.isLoadingAvatarSet = true
            this.cropper.getCroppedCanvas(this.outputOptions).toBlob(
                blob => {
                    let form = new FormData()
                    let data = Object.assign({}, this.uploadFormData)
                    for (let key in data) {
                        form.append(key, data[key])
                    }
                    form.append(this.uploadFormName, blob, this.filename)
                    form.append('lang', this.$store.state.locale)
                    this.$store.dispatch('apiCall', {
                        actionName: this.actionName,
                        data: form
                    }).then(d => {
                        this.isLoadingAvatarSet = false
                        this.cancel()
                        this.$emit('success', d.data.text)
                    }).catch(e => {
                        this.isLoadingAvatarSet = false
                        this.cancel()
                        this.$emit('error', this.validations.avatar.failed)
                    })
                },
                this.outputMime,
                this.outputQuality
            )
        }
    },
    mounted() {
        let trigger =
            typeof this.trigger == 'object'
                ? this.trigger
                : document.querySelector(this.trigger)
        if (!trigger) {
            this.$emit('error', 'No avatar make trigger found.', 'user')
        } else {
            trigger.addEventListener('click', this.pickImage)
        }
    }
}
</script>
<style scoped src="./css/AvatarCropper.css"/>
