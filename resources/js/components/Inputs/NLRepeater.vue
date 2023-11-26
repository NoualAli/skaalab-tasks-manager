<template>
  <div class="repeater">
    <h2 v-if="title" class="mb-6">
      {{ title }}
    </h2>
    <!-- Repeater row -->
    <div v-for="(item, index) in form[name]" :key="name + '-row-' + index" class="grid my-6 form-row">
      <div class="col-11">
        <div class="grid">
          <div v-for="(input, id) in rowSchema" :key="name + '-input-' + input.name + '-' + index + '-id'" class="col-12"
            :class="input.style">
            <!-- Defining different inputs -->
            <NLInput v-if="isInput(input.type)" :id="name + '-row-' + index + '-' + input.name"
              v-model="form[name][index][id][input.name]" :form="form" :label="input.label"
              :placeholder="input.placeholder" :type="input.type" :label-required="input.required"
              :name="name + '.' + index + '.' + id + '.' + input.name" />

            <NLTextarea v-if="input.type == 'textarea'" :id="name + '-row-' + index + '-' + input.name"
              v-model="form[name][index][id][input.name]" :form="form" :label="input.label"
              :placeholder="input.placeholder" :label-required="input.required"
              :name="name + '.' + index + '.' + id + '.' + input.name" />

            <NLWyswyg v-if="input.type == 'wyswyg'" :id="name + '-row-' + index + '-' + input.name"
              v-model="form[name][index][id][input.name]" :form="form" :label="input.label"
              :placeholder="input.placeholder" :label-required="input.required"
              :name="name + '.' + index + '.' + id + '.' + input.name" />

            <NLSelect v-if="input.type == 'select'" :id="name + '.' + index + '.' + id + '.' + input.name"
              v-model="form[name][index][id][input.name]" :label-required="input.required" :form="form"
              :label="input.label" :name="name + '.' + index + '.' + id + '.' + input.name" :options="input.options"
              :placeholder="input.placeholder || 'Choisissez une option...'" :multiple="input.multiple" />
          </div>
        </div>
      </div>
      <!-- Remove current row -->
      <div v-if="index >= 0" class="col-1 p-0 d-flex justify-start align-center">
        <div class="btn btn-danger" :class="{ removeButtonStyle }" @click="removeRow(index)">
          {{ removeButtonLabel }}
        </div>
      </div>
    </div>

    <!-- Add new row -->
    <div class="d-flex justify-start align-center">
      <span class="btn" :class="{ addButtonStyle }" @click="addRow">
        {{ addButtonLabel }}
      </span>
    </div>
    <has-error v-if="form" :form="form" :field="name" class="text-danger mt-5" />
  </div>
</template>

<script>
// import Treeselect from 'vue3-treeselect'
import NLSelect from './NLSelect'
import InputContainer from './InputContainer'
import NLInput from './NLInput'
// import Treeselect from '@riophae/vue-treeselect'
export default {
  name: 'NLRepeater',
  components: {
    NLSelect,
    InputContainer,
    NLInput
    // , Treeselect
  },
  props: {
    addButtonLabel: { type: String, default: 'Ajouter une ligne' },
    addButtonStyle: { type: String, default: null },
    removeButtonLabel: { type: String, default: 'Supprimer' },
    removeButtonStyle: { type: String, default: null },
    name: { type: String },
    title: { type: String, default: null },
    form: { type: Object, required: false },
    rowSchema: { type: Array, required: true }
  },
  methods: {
    addRow() {
      const schema = []
      for (let index = 0; index < this.rowSchema.length; index++) {
        const element = this.rowSchema[ index ]
        const name = element.name
        let defaultValue = element.default !== undefined ? element.default : null
        defaultValue = element.multiple ? [] : null
        schema.push({ [ name ]: defaultValue, value: element.label })
      }
      if (this.form[ this.name ]) this.form[ this.name ].push(schema)
    },
    isInput(value) {
      return [ 'text', 'date', 'datetime', 'time', 'week', 'number', 'tel', 'email', 'month', 'url' ].includes(value)
    },
    removeRow(index) {
      this.form[ this.name ].splice(index, 1)
    }
  }
}
</script>
