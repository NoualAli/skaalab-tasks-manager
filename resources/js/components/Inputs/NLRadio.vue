<template>
  <div class="form-check">
    <label :class="{ 'radio-hidden': hideRadio }">
      <input :id="id" class="input-checkable" type="radio" :checked="isChecked" :value="modelValue" :name="name"
        v-on="$listeners" @input="$emit('update:modelValue', $event.target.value)">
      <div class="checkable is-radio" />
      {{ label }}
    </label>
  </div>
</template>

<script>
export default {
  name: 'NLRadio',
  props: {
    form: { type: Object, required: false },
    name: { type: String },
    label: { type: String, required: true },
    modelValue: { type: [ String, Number, Boolean ], default: undefined },
    checkedValue: { type: String, default: '' },
    hideRadio: { type: Boolean, default: false }
  },
  computed: {
    isChecked() {
      return this.checkedValue === this.modelValue
    },
    id() {
      return `${this.name}-${this.modelValue}`
    }
  }
}
</script>

<style lang="scss" scoped></style>
