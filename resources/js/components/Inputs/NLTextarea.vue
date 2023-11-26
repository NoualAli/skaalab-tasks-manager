<template>
    <InputContainer :id="getId" :form="form" :label="label" :name="name" :label-required="labelRequired" :length="length"
        :current-length="currentLength" :help-text="helpText">
        <textarea :id="getId" :class="{ 'is-danger': form?.errors.has(name) }" class="input" :name="name"
            :autofocus="autofocus" :placeholder="placeholder || label" :value="modelValue" :readonly="readonly"
            :disabled="disabled" :maxlength="length" v-bind="$attrs" @input="onInput($event)" />
    </InputContainer>
</template>

<script>

export default {
    name: 'NLTextarea',
    props: {
        form: { type: Object, required: false },
        autocomplete: { type: String, default: 'off' },
        autofocus: { type: Boolean, default: false },
        type: { type: String, default: 'text' },
        name: { type: String },
        id: { type: String },
        label: { type: String, default: '' },
        labelRequired: { type: Boolean, default: false },
        placeholder: { type: String, default: '' },
        modelValue: { type: String, default: '' },
        readonly: { type: Boolean, default: false },
        disabled: { type: Boolean, default: false },
        length: { type: [ Number, String ], default: null },
        helpText: { type: String, default: null }
    },
    data() {
        return {
            currentLength: 0
        }
    },
    created() {
        if (this.length && this.value) {
            this.currentLength = this.value.length
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
        onInput($event) {
            let value = $event.target.value
            this.currentLength = value.length
            if (this.length) {
                value = value.slice(0, this.length)
            }
            this.$emit('update:modelValue', value)
        }
    }
}
</script>
