<template>
    <NLContainer isFluid>
        <label class="form-label checkable-title" v-if="title"
            :class="{ 'text-danger': form?.errors.has(name), 'is-required': labelRequired }">{{ title }}</label>
        <div class="checkable-container">
            <label class="radio-container" v-for="(item, index) in data" :key="item[label] + '-' + index">
                <input type="radio" :value="item[value]" :name="name" :checked="isChecked(item[value])"
                    :id="`${name}-${item[label]}-${item[value]}`" @input="$emit('update', item[value])" v-on="$listeners">
                <span class="checkable-label">
                    {{ item[label] }}
                </span>
                <div class="radio" :class="{ 'is-checked': isChecked(item[value]) }"></div>
            </label>
        </div>
        <has-error :form="form" :field="name" class="text-danger d-inline" v-if="form" />
    </NLContainer>
</template>

<script>
export default {
    name: "NLRadios",
    props: {
        form: { type: Object, required: false },
        name: { type: String },
        title: { type: String, required: false },
        labelRequired: { type: Boolean, default: false },
        checkedValue: { type: String | Boolean | Number, default: null },
        data: { type: Object | Array, default: () => [], required: false },
        label: { type: String, required: false, default: 'label' },
        value: { type: String, required: false, default: 'value' }
    },
    model: {
        prop: "checkedValue",
        event: "update"
    },

    methods: {
        isChecked(itemValue) {
            return this.checkedValue == itemValue;
        }
    }
}
</script>
