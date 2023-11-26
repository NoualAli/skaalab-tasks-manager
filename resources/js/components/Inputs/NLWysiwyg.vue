<template>
    <InputContainer :id="getId" :form="form" :label="label" :name="name" :label-required="labelRequired" :length="length"
        :current-length="currentLength" v-if="!readonly">
        <VueEditor :id="getId" :editor-toolbar="editorSettings" class="input"
            :class="[{ 'is-danger': form?.errors.has(name) }]" :name="name" :autocomplete="autocomplete"
            :autofocus="autofocus" v-model="currentValue" :max-length="length" :placeholder="placeholder || label"
            :value="currentValue" :help-text="helpText" @textChange="onInput" v-bind="$attrs"
            @ready="quill => { editorQuill = quill }" />
    </InputContainer>
    <NLContainer isFluid v-else>
        <label class="label">{{ label }}</label>
        <div class="content text-normal my-2" v-html="currentValue"></div>
    </NLContainer>
</template>
<script>
import InputContainer from './InputContainer'
import { VueEditor } from 'vue3-editor'
export default {
    name: 'NLWysiwyg',
    components: {
        InputContainer, VueEditor
    },
    emits: [ 'update:modelValue' ],
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
        modelValue: { type: [ String, Number, null ], default: null },
        readonly: { type: Boolean, default: false },
        length: { type: [ Number, null ], default: null },
        helpText: { type: String, default: null }
    },
    data() {
        return {
            editorQuill: null,
            forcedKey: -1,
            currentLength: 0,
            currentValue: this.modelValue,
            editorSettings: [
                [ { header: [ 1, 2, 3, false ] } ],
                // [ { 'font': [ 'Archivo' ] } ],
                [ { size: [ 'small', 'medium', 'large' ] } ],
                [ { align: [] } ],
                [ { list: 'ordered' }, { list: 'bullet' } ],
                [ 'bold', 'italic', 'underline', 'strike' ],
                [ 'blockquote' ],
                [ { script: 'sub' }, { script: 'super' } ],
                [ { indent: '-1' }, { indent: '+1' } ],
                [ { direction: 'ltr' }, { direction: 'rtl' } ],
                [
                    { color: [ '#000000', '#fcfcfc', '#FAFAFA', '#D9D9D9', '#717171', '#2d3436', '#3D4756', '#363636', '#3D2E2C', '#125741', '#b18028', '#CC0000', '#ff4444', '#ffbb33', '#FF8800', '#00C851', '#007E33', '#33b5e5', '#0099CC', 'transparent' ] },
                    { background: [ '#000000', '#fcfcfc', '#FAFAFA', '#D9D9D9', '#717171', '#2d3436', '#3D4756', '#363636', '#3D2E2C', '#125741', '#b18028', '#CC0000', '#ff4444', '#ffbb33', '#FF8800', '#00C851', '#007E33', '#33b5e5', '#0099CC', 'transparent' ] }
                ],
                [ 'clean' ]
            ]
        }
    },
    watch: {
        currentValue: {
            immediate: true,
            deep: true,
            handler(newValue, oldValue) {
                if (!!this.length && this.editorQuill.getLength() >= this.length) {
                    this.editorQuill.deleteText(this.length, this.editorQuill.getLength())
                }

                this.$emit('update:modelValue', this.trim(newValue))
            }
        },
        modelValue: {
            immediate: true,
            deep: true,
            handler(newValue, oldValue) {
                if (!newValue || newValue.length === 0) {
                    this.currentValue = newValue
                    this.forcedKey = -1
                }
                if (newValue && (newValue !== oldValue)) {
                    this.currentValue = newValue
                    this.forcedKey = 1
                    this.$emit('update:modelValue', this.trim(newValue))
                }
            }
        }
    },
    created() {
        this.currentValue = this.modelValue
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
        onInput(value) {
            // Should review this part
            // cause it's not being called at any moment
            // this.currentValue = value
            this.currentLength = this.editorQuill.getLength() - 1
            // this.$emit('update:modelValue', this.trim(this.currentValue))
        },
        /**
         * @param {HTMLDOMElement} html
         */
        trim(html) {
            if (html) {
                const tmp = document.createElement("div");
                tmp.innerHTML = html?.trim();
                // Remove <br> tag if it's the first or last child of the first tag
                const firstChild = tmp.firstChild;
                if (firstChild && firstChild.tagName) {
                    const firstChildFirstTag = firstChild.firstChild;
                    // console.log(firstChildFirstTag?.tagName);
                    const firstChildLastTag = firstChild.querySelector(":last-child");
                    if (firstChildFirstTag && firstChildFirstTag?.tagName === "BR") {
                        // console.log(firstChildFirstTag);
                        firstChildFirstTag.parentElement.removeChild(firstChildFirstTag)
                        // firstChild.removeChild();
                    }
                    if (firstChildLastTag && firstChildLastTag.tagName === "BR") {
                        // firstChild.removeChild(firstChildLastTag);
                    }
                }

                // Remove empty tags
                const emptyTags = tmp.querySelectorAll(":empty");
                emptyTags.forEach((tag) => {
                    tag.parentNode.removeChild(tag);
                });

                // Trim non-empty tags
                const nonEmptyTags = tmp.querySelectorAll("*");
                nonEmptyTags.forEach((tag) => {
                    if (tag.innerHTML.trim() === "") {
                        tag.parentNode.removeChild(tag);
                    } else {
                        tag.innerHTML = tag.innerHTML.trim();
                    }
                });

                // Return the updated HTML
                return tmp.innerHTML !== undefined ? tmp.innerHTML : null;
            }
            return html
        }


    }
}
</script>
