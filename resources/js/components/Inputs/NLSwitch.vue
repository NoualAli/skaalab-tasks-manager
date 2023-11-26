<template>
    <NLCheckableContainer :id="getId" :name="name" :form="form" :label="label" :help-text="helpText">
        <input :id="getId" :key="forcedKey" v-model="selected" type="checkbox"
            :class="[{ 'is-danger': form?.errors.has(name) }, 'switch-input']" :name="name" @change="updateValue">
        <div class="switch" :class="type" :data-true-label="trueLabel" :data-false-label="falseLabel"
            @click="updateValue" />
    </NLCheckableContainer>
</template>

<script>
import NLCheckableContainer from './NLCheckableContainer'
export default {
    name: 'NLSwitch',
    components: { NLCheckableContainer },
    props: {
        form: { type: Object, required: false },
        name: { type: String },
        label: { type: String, required: true },
        modelValue: { type: Boolean, default: false },
        id: { type: [ String, null ], default: null },
        trueLabel: { type: [ String, null ], default: 'Oui' },
        falseLabel: { type: [ String, null ], default: 'Non' },
        helpText: { type: String, default: null },
        type: { type: String, default: null }
    },
    emits: [ 'update:modelValue' ],
    data() {
        return {
            forcedKey: false,
            selected: this.modelValue
        }
    },
    watch: {
        modelValue(newValue, oldValue) {
            if (newValue !== oldValue) {
                this.selected = newValue
                this.forcedKey = true
            }
        },
        selected(newValue, oldValue) {
            this.$emit('update:modelValue', this.selected)
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
    methods: {
        updateValue() {
            this.selected = !this.selected
        }
    }
}
</script>
