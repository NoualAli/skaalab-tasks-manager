<template>
    <div class="input-base-container">
        <NLFlex extraClass="w-100">
            <div>
                <label v-if="label" class="form-label" :for="id"
                    :class="{ 'text-danger': form?.errors.has(name), 'is-required': labelRequired }">
                    {{ label }}
                </label>
                <i v-if="helpText" class="las la-question-circle text-medium help-text" :class="{ 'ml-4': labelRequired }"
                    :title="helpText" />
            </div>
            <slot name="additional"></slot>
        </NLFlex>
        <slot />
        <div class="d-flex justify-end align-center is-column is-lg-row"
            :class="{ 'justify-between': form?.errors.has(name) }">
            <has-error v-if="form" :form="form" :field="name" class="text-danger d-inline" />
            <div v-if="length !== null && currentLength !== null"
                class="text-small text-bold d-flex justify-end align-center"
                :class="{ 'text-danger': currentLength >= length }">
                {{ currentLength }} / {{ length }}
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'InputContainer',
    props: {
        form: { type: Object, required: false },
        name: { type: String },
        id: { type: String, required: true },
        label: { type: String, default: '' },
        labelRequired: { type: Boolean, default: false },
        length: { type: [ Number, String ], default: null },
        currentLength: { type: [ Number, String ], default: null },
        helpText: { type: String, default: null }
    },
    methods: {
        getFieldErrors(fieldName) {
            const errors = this.form?.errors.any() ? this.form.errors.errors : false
            const fieldErrors = []
            // console.log(errors)
            if (errors) {
                for (const error of errors) {
                    console.log(error?.field?.startsWith(fieldName), error, fieldName)
                    if (error?.field?.startsWith(fieldName)) {
                        fieldErrors.push(error.msg)
                    }
                }
            }
            return fieldErrors
        }
    }
}
</script>
