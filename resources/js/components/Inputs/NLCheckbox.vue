<!-- <template>
    <NLCheckableContainer :id="getId" :form="form" :name="name" :label-required="labelRequired">
      <div class="form-check">
        <label :for="getId">
          <input
            :id="getId"
            type="checkbox"
            :class="[{ 'is-danger': form?.errors.has(name) }, 'input-checkable']"
            :value="selected"
            :name="name"
            @input="updateValue"
          />
          <div class="checkable is-checkbox" />
          {{ label }}
        </label>
      </div>
    </NLCheckableContainer>
  </template>

  <script>
  import NLCheckableContainer from './NLCheckableContainer';

  export default {
    name: 'NLCheckbox',
    components: { NLCheckableContainer },
    model: {
      prop: 'value',
      event: 'change',
    },
    props: {
      form: { type: Object, required: false },
      name: { type: String },
      id: { type: String },
      label: { type: String, default: '' },
      labelRequired: { type: Boolean, default: false },
      value: { type: [String, Number, Boolean], default: false },
    },
    data() {
      return {
        selected: this.value,
      };
    },
    computed: {
      getId() {
        if (this.id) {
          return this.id;
        } else if (!this.id && this.name) {
          return this.name;
        } else {
          return '';
        }
      },
    },
    methods: {
      updateValue(event) {
        this.selected = event.target.checked;
        this.$emit('change', this.selected);
      },
    },
  };
  </script>
   -->
<template>
    <NLCheckableContainer :id="id" :form="form" :name="name" :label-required="labelRequired">
        <div class="form-check">
            <label :for="id">
                <input :id="id" type="checkbox" :class="[{ 'is-danger': form?.errors.has(name) }, 'input-checkable']"
                    :value="value" :name="name" :checked="isSelected" @change="updateValue" />
                <div class="checkable is-checkbox" />
                {{ label }}
            </label>
        </div>
    </NLCheckableContainer>
</template>

<script>
export default {
    name: 'NLCheckbox',
    props: {
        form: { type: Object, required: false },
        name: { type: String },
        id: { type: String },
        label: { type: String, default: '' },
        labelRequired: { type: Boolean, default: false },
        value: { type: [ String, Number, Boolean ], default: false },
        selectedValues: { type: Array, required: true },
    },
    computed: {
        isSelected() {
            return this.selectedValues.includes(this.value);
        },
    },
    methods: {
        updateValue() {
            if (this.isSelected) {
                // Remove the value from the selectedValues array if it's already selected
                const index = this.selectedValues.indexOf(this.value);
                if (index !== -1) {
                    this.selectedValues.splice(index, 1);
                }
            } else {
                // Add the value to the selectedValues array if it's not selected
                this.selectedValues.push(this.value);
            }

            // Emit the updated selectedValues array
            this.$emit('change', this.selectedValues);
        },
    },
};
</script>
