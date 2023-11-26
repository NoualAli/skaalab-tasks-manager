<template>
    <NLContainer isFluid>
        <label class="form-label checkable-title" v-if="title"
            :class="{ 'text-danger': form?.errors.has(name), 'is-required': labelRequired }">{{ title }}</label>
        <div class="checkable-container">
            <label class="checkbox-container" v-for="(item, index) in data" :key="item[label] + '-' + index">
                <input type="checkbox" :value="item[value]" :name="name" :checked="isChecked" :id="getId"
                    @change="updateValue">
                <span class="checkable-label">
                    {{ item[label] }}
                </span>
                <div class="checkbox" :class="{ 'is-checked': isChecked }"></div>
            </label>
        </div>
        <has-error :form="form" :field="name" class="text-danger d-inline" v-if="form" />
    </NLContainer>
</template>

<script>
export default {
    name: "NLCheckboxes",
    props: {
        form: { type: Object, required: false },
        name: { type: String },
        title: { type: String, required: false },
        labelRequired: { type: Boolean, default: false },
        checkedValues: { type: Array, default: () => [] }, // Provide a default empty array
        data: { type: [ Array, Object ], default: () => { }, required: false },
        label: { type: String, required: false, default: 'label' },
        value: { type: String, required: false, default: 'value' }
    },
    model: {
        prop: 'checkedValues',
        event: 'change'
    },
    data() {
        return {
            selected: [ ...this.checkedValues ]
        };
    },
    computed: {
        getId() {
            if (this.data[ this.label ]) {
                return this.data[ this.label ]
            } else if (!this.data[ this.label ] && this.name) {
                return this.name
            } else {
                return ''
            }
        }
    },
    methods: {
        updateValue(event) {
            const value = event.target.value;
            const isChecked = event.target.checked;
            const id = event.target.id;
            if (isChecked) {
                this.selected.push({ id, value, isChecked });
            } else {
                const index = this.selected.indexOf(this.selected.find((item) => item.id == id));
                if (index !== -1) {
                    this.selected.splice(index, 1);
                }
            }
            this.$emit('change', this.selected);
        },
        isChecked(id) {
            const value = event.target.value;
            const isChecked = event.target.checked;
            // const id = event.target.id;
            // console.log(isChecked);
            return isChecked
            // return itemValue !== undefined && Object.values(this.checkedValues).includes(itemValue.id);
        }
        // isChecked(itemValue) {
        //     return this.checkedValue == itemValue;
        // }
    }
};
</script>

