<template>
    <InputContainer :id="getId" :form="form" :label="label" :name="name" :label-required="labelRequired"
        :help-text="helpText">
        <Treeselect :id="getId" v-bind="$attrs" ref="treeselect" v-model="selected" :key="forcedKey"
            :class="[{ 'is-danger': form?.errors.has(name) }, 'select']" :value="modelValue" :name="name"
            :multiple="multiple" :options="options" :placeholder="placeholder" :loading-text="loadingText"
            :no-options-text="noOptionsText" search-nested :disable-branch-nodes="disableBranchNodes" :showCount="true" />
    </InputContainer>
</template>

<script>
import Treeselect from '@veigit/vue3-treeselect'
export default {
    name: 'NLSelect',
    components: { Treeselect },
    props: {
        form: { type: Object, required: false },
        name: { type: String },
        id: { type: String },
        label: { type: String, default: '' },
        labelRequired: { type: Boolean, default: false },
        placeholder: { type: String, default: 'Choisissez une option' },
        loadingText: { type: String, default: 'Chargement en cours...' },
        noOptionsText: { type: String, default: 'Aucune option disponible' },
        multiple: { type: Boolean, default: false },
        modelValue: { type: [ String, Array, Number, Boolean ], default: () => [] },
        options: { type: Array, required: true },
        helpText: { type: String, default: null },
        disableBranchNodes: { type: Boolean, default: false }
    },
    data() {
        return {
            forcedKey: -1,
            selected: this.modelValue
        }
    },
    computed: {
        getId() {
            if (this.id) {
                return this.id
            } else if (!this.id && this.name) {
                return this.name
            } else {
                return ''
            }
        }
    },
    watch: {
        selected: {
            immediate: true,
            deep: true,
            handler(newValue, oldValue) {
                if (newValue !== oldValue) {
                    this.$emit('update:modelValue', newValue)
                }
            }
        },
        modelValue: {
            immediate: true,
            deep: true,
            handler(newValue, oldValue) {
                if (!newValue || newValue.length === 0) {
                    this?.$refs?.treeselect?.clear()
                    this.forcedKey = -1
                }

                if (newValue !== oldValue && newValue !== null && newValue !== undefined) {
                    if (typeof newValue == 'object' && Object.entries(newValue)?.length) {
                        this.selected = newValue
                        this.forcedKey = 1
                        // console.log('Array | Object : ', newValue);
                    } else if (typeof newValue == 'string' && newValue.length) {
                        this.selected = newValue
                        this.forcedKey = 1
                        // console.log('String : ', newValue);
                    } else if (typeof newValue == 'number') {
                        this.selected = newValue
                        this.forcedKey = 1
                        // console.log('Number : ', newValue);
                    }
                }

            }
        }
    },
}
</script>
