<template>
  <div class="form-check-container" :class="{ 'is-inline': isInline }">
    <label v-if="label" class="form-label" :for="id"
      :class="{ 'text-danger': form?.errors.has(name), 'is-required': labelRequired }">
      {{ label }}
    </label>
    <slot />
    <has-error v-if="form" :form="form" :field="name" class="text-danger" />
  </div>
</template>

<script>

import InputContainer from './InputContainer'
export default {
  name: 'NLCheckableContainer',
  components: { InputContainer },
  props: {
    form: { type: Object, required: false },
    name: { type: String },
    id: { type: String, required: true },
    label: { type: String, default: '' },
    labelRequired: { type: Boolean, default: false },
    isInline: { type: Boolean, default: true },
    helpText: { type: String, default: null }
  }
}
</script>
