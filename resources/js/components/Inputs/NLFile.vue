<template>
    <InputContainer :id="getId" :name="name" :form="form" :label="label" :label-required="labelRequired"
        :help-text="helpText">
        <template #additional>
            <NLFlex lgAlignItems="center" alignItems="center">
                <p class="text-bold" v-if="Object.values(this.files).length > 1">Fichiers total ({{
                    Object.values(this.files).length }})</p>
                <i class="las la-trash-alt icon text-danger is-clickable" title="Tout supprimer" @click="deleteItems"
                    v-if="!isDeletingMultiple && canDelete && !readonly && Object.values(this.files).length > 1"></i>
                <i class="las la-spinner la-spin icon" v-else-if="isDeletingMultiple"></i>
            </NLFlex>
        </template>
        <input v-if="!readonly" :id="getId" type="file" :name="name" :multiple="multiple" :accept="accept"
            class="file-input" @input="onChange($event)">
        <div :class="[{ 'is-danger': form?.errors.has(name), 'has-files': hasFiles, 'is-readonly': readonly, 'is-flat': isFlat }, 'file-input-area']"
            :draggable="true" @dragover="dragover" @dragleave="dragleave" @drop="drop"
            @click.stop="openFileBrowser($event)">
            <p v-if="!inProgress && !readonly" class="text-medium file-uploader">
                {{ placeholder }} <i class="las la-cloud-upload-alt text-large" />
            </p>
            <p v-if="inProgress" class="text-medium file-uploader">
                <i class="las la-spinner la-spin text-large" />
                {{ visibleLoadingText }}{{ progress }} {{ progress ? '%' : '' }}
            </p>
            <div class="files-list list text-medium" @click.stop="(e) => e.stopPropagation()" v-if="getFilesList.length">
                <div v-for="(file, index) in getFilesList" :key="name + '-' + index" class="list-file-item my-1">
                    <NLGrid gap="4" extraClass="list-item-content w-100">
                        <div class="col-11 d-flex justify-between align-center align-lg-center">
                            <a :href="file.link" target="_blank" class="file_name_link"
                                v-if="['jpg', 'png', 'jpeg', 'tif', 'svg', 'pdf'].includes(file.extension)">
                                <i class="icon" :class="file.icon"></i>
                                {{ file.name }}
                            </a>
                            <p class="file_name_link" v-else>
                                <i class="icon" :class="file.icon"></i>
                                {{ file.name }}
                            </p>
                            <span>{{ file.size }}</span>
                        </div>
                        <div class="col-1 d-flex justify-end align-center gap-4">
                            <a :href="file.link" :download="file.name">
                                <i class="las la-download text-info icon" />
                            </a>
                            <i v-if="canDelete && file.isOwner && !readonly"
                                class="las la-trash text-danger icon is-clickable" @click.stop="deleteItem(file, index)" />
                        </div>
                    </NLGrid>
                </div>
            </div>
        </div>
    </InputContainer>
</template>

<script>
import InputContainer from './InputContainer'
export default {
    name: 'NLFile',
    components: { InputContainer },
    props: {
        form: { type: Object, required: false },
        name: { type: String },
        id: { type: String },
        label: { type: String, default: '' },
        labelRequired: { type: Boolean, default: false },
        placeholder: { type: String, default: 'Téléverser des fichiers' },
        loadingText: { type: String, default: 'Téléversement en cours... ' },
        multiple: { type: Boolean, default: false },
        modelValue: { type: [ String, Array, Object, null ], default: () => { } },
        attachableType: { type: String, default: '' },
        attachableId: { type: [ String, Number ], default: '' },
        accepted: { type: String, default: 'jpg,jpeg,png,doc,docx,xls,xlsx,pdf' },
        helpText: { type: String, default: null },
        canDelete: { type: Boolean, default: true },
        readonly: { type: Boolean, default: false },
        isFlat: { type: Boolean, default: false },
        folder: { type: String, default: '' }
    },
    emits: [ 'change:modelValue', 'deleted', 'loaded', 'uploaded' ],
    data() {
        return {
            isDragging: false,
            inProgress: false,
            progress: 0,
            files: [],
            isReadonly: false,
            visibleLoadingText: this.loadingText,
            isDeletingMultiple: false,
        }
    },
    computed: {
        hasFiles() {
            return this.files.length
        },
        accept() {
            return this.accepted.split(',').map(accept => '.' + accept).join(',')
        },
        getFilesList() {
            let files = Object.values(this.files).map((file) => {
                return {
                    id: file?.id,
                    name: file?.original_name,
                    size: file?.size_str,
                    type: file?.type,
                    extension: file?.extension,
                    link: file?.storage_link,
                    icon: file?.icon,
                    isOwner: file?.is_owner,
                };
            });
            return files
        },
        getId() {
            if (this.id) {
                return this.id
            } else if (!this.id && this.name) {
                return this.name
            } else {
                return ''
            }
        },
    },
    watch: {
        modelValue(newVal, oldVal) {
            if (newVal !== oldVal) this.loadFiles(newVal.join(','))
        },
    },
    mounted() {
        this.isReadonly = this.readonly
        if (Object.values(this.modelValue).length) {
            this.loadFiles(Object.values(this.modelValue).join(','))
        }
    },
    methods: {
        /**
         * Handle input change event
         *
         * @param {*} $event
         */
        onChange($event) {
            if ($event.target.files.length && !this.readonly) {
                this.inProgress = true
                this.upload($event.target.files, $event)
            }
        },
        /**
         * Handle dragover event
         * @param {*} e
         */
        dragover(e) {
            e.preventDefault()
            this.isDragging = true
        },
        /**
         * Handle drop leave event
         */
        dragleave() {
            this.isDragging = false
        },
        /**
         * Handle drop event
         *
         * @param {*} $event
         */
        drop($event) {
            this.isDragging = false
            $event.preventDefault()
            if (!this.readonly) {
                this.upload($event.dataTransfer.files)
            }
        },
        /**
         * Open file browser programatically
         *
         * @param {*} $event
         */
        openFileBrowser($event) {
            if (!this.readonly) {
                $event.target.parentNode.parentNode.querySelector('input[type=file]').click()
            }
            this.isDeletingMultiple = false
        },
        /**
         * Fetch exesting files
         *
         * @param {String} filesStr
         */
        loadFiles(filesStr) {
            if (!([ null, '', undefined ]).includes(filesStr) && filesStr.length) {
                this.progress = '';
                this.isLoading = !this.isLoading;
                this.inProgress = !this.inProgress;
                this.visibleLoadingText = 'Récupération des fichiers en cours...';
                this.$api.get('media/' + filesStr + '?all', {
                    onDownloadProgress: progressEvent => this.setProgress(progressEvent)
                }).then(response => {
                    this.files = response?.data?.data || response?.data;
                    this.inProgress = !this.inProgress;
                    this.isLoading = !this.isLoading;
                    console.log(this.files);
                    const files = { ...this.files.map((file) => file.id) }
                    this.$emit('loaded', files);
                }).catch(error => {
                    this.$swal.catchError(error)
                })
            }
        },
        /**
         * Delete file from server
         *
         * @param {Object} file
         * @param {Number} index
         */
        deleteItem(file, index) {
            this.$swal.confirm_destroy().then((action) => {
                if (action.isConfirmed) {
                    if (index !== -1) {
                        this.isLoading = true;
                        this.$api.delete('media/' + file.id).then(response => {
                            this.files.splice(index, 1);
                            const files = { ...this.files.map((file) => file.id) }
                            this.$emit('deleted', files);
                            this.$swal.toast_success(response.data.message)
                            this.inProgress = false;
                            this.isLoading = false;
                        }).catch(error => {
                            this.inProgress = false
                            this.isLoading = false;
                            this.$swal.catchError(error)
                        })
                    }
                }
            });
        },
        /**
         * Delete all files from server
         */
        deleteItems() {
            this.$swal.confirm_destroy().then((action) => {
                if (action.isConfirmed) {
                    this.isDeletingMultiple = true
                    const files = Object.values(this.modelValue).join(',')
                    this.$api.delete('media/' + files + '/multiple').then(response => {
                        this.files = [];
                        this.$emit('deleted', this.files);
                        this.inProgress = false;
                        this.isDeletingMultiple = false
                        this.$swal.toast_success(response.data.message)
                    }).catch(error => {
                        this.inProgress = false
                        this.isDeletingMultiple = false
                        this.isLoading = false;
                        this.$swal.catchError(error)
                    })
                }
            });
        },
        /**
         * Upload files to server
         *
         * @param {Array} files
         */
        upload(files, $event) {
            try {
                if (!this.readonly) {
                    this.visibleLoadingText = this.loadingText
                    const data = new FormData()

                    if (this.multiple) {
                        for (let i = 0; i < files.length; i++) {
                            data.append(`${this.name}[]`, files[ i ])
                        }
                    } else {
                        data.append(this.name, files[ 0 ])
                    }

                    if (files.length) {
                        data.append('accepted', this.accepted)
                        data.append('attachable[id]', this.attachableId)
                        data.append('attachable[type]', this.attachableType)
                        let folder = 'uploads'
                        if (this.folder.length) {
                            folder += '/' + this.folder
                        }
                        data.append('folder', folder)
                        this.$api.post('media', data, {
                            onUploadProgress: (progressEvent) => this.setProgress(progressEvent)
                        }).then(response => {
                            this.inProgress = false
                            this.files.push(...response.data)
                            const files = { ...this.files.map((file) => file.id) }
                            this.$emit('uploaded', files)
                            $event.target.value = ''

                        }).catch(error => {
                            this.inProgress = false
                            let i = 0

                            for (const key in error?.response?.data?.errors) {
                                if (Object.hasOwnProperty.call(error.response.data.errors, key)) {
                                    const element = error.response.data.errors[ key ];
                                    this.form.errors.set(this.name, element[ 0 ].replace(`du champ ${this.name}.${i}`, `${i + 1} du champ ${this.label.toLowerCase()}`))
                                    i += 1
                                }
                            }
                        })
                    }
                }
            } catch (error) {
                this.$swal.catchError(error)
            }
        },
        /**
         * Handle upload progress status
         * @param {*} progressEvent
         */
        setProgress(progressEvent) {
            if (progressEvent?.total) {
                this.progress = Math.round((progressEvent.loaded * 100) / progressEvent?.total)
            } else {
                this.progress = '0'
            }
        }
    }
}
</script>
