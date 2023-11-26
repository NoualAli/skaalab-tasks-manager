<template>
    <td :class="tdClass" v-if="!hide" :data-th="label" :colspan="colspan" :align="align" :title="getField()"
        :style="{ 'max-width': length }">
        <slot :class="childClass"></slot>

        <span :class="childClass" v-html="showField" v-if="isHtml"></span>

        <span :class="childClass" title="" v-else>
            {{ showField }}
        </span>
    </td>
</template>

<script>
export default {
    name: 'TableData',
    props: {
        extraClass: {
            type: String,
            required: false,
            default: null
        },
        column: {
            type: [ Object, null ],
            required: false,
            default: {
                label: '',
                field: '',
                hide: false,
                type: 'text'
            },
        },
        item: {
            type: [ Object, null ],
            required: false,
            default: null,
        },
    },
    computed: {
        tdClass() {
            if (typeof this.column?.extraClass?.td == 'function') {
                return this.column?.extraClass?.td(this.item)
            }
            return this.column?.extraClass?.td || ''
        },
        hide() {
            return !!this.column.hide || false
        },
        label() {
            return this.column?.label || ''
        },
        field() {
            return this.column?.field || ''
        },
        length() {
            return this.column?.length || null
        },
        colspan() {
            return this.column?.colspan || 1
        },
        align() {
            return this.column?.align || 'left'
        },
        isHtml() {
            return this.column?.isHtml || false
        },
        methods() {
            return this.column.methods || {}
        },
        type() {
            return this.column.type || 'text'
        },
        /**
         * Check if there are slots or not
         *
         * @return {Number}
         */
        hasSlots() {
            let slots = this.$slots.default !== undefined && this.$slots !== undefined ? this.$slots.default : null
            if (slots !== null && slots !== undefined) {
                return slots.length;
            }
            return 0
        },
        /**
        * Show field value
        *
        */
        showField() {
            if (this.column !== undefined && this.item !== undefined) {
                if (Object.hasOwnProperty.call(this.column, 'methods')) {
                    if (Object.hasOwnProperty.call(this.methods, 'showField')) {
                        return this.methods[ 'showField' ](this.item);
                    }
                }
                return this.truncate(this.getField())
            }
            return '-'
        },
        /**
        * Apply specific class to children
        *
        */
        childClass() {
            if (this.column !== undefined && this.item !== undefined) {
                if (Object.hasOwnProperty.call(this.column, 'methods')) {
                    if (Object.hasOwnProperty.call(this.methods, 'style')) {
                        return this.methods[ 'style' ](this.item);
                    }
                }
            }

        },
    },
    methods: {
        /**
        * Get field value
        *
        * @param {Object} data
        * @param {String} field
        */
        getField() {
            let field = ''
            if (this.field) {
                if (this.field !== null && this.field !== undefined && this.field.includes('.')) {
                    let fields = this.field.split('.')
                    const relationshipName = fields[ 0 ]
                    const relationshipValue = fields[ 1 ]
                    if (relationshipName !== undefined && relationshipValue !== undefined && relationshipName !== null && relationshipValue !== null) {
                        field = this.item[ relationshipName ] !== null ? this.item[ relationshipName ][ relationshipValue ] : '-'
                    }
                } else {
                    field = this.item[ this.field ] !== undefined ? this.item[ this.field ] : '-'
                }
            }
            return field
        },
        /**
         * Truncate string for max length
         *
         * @param {String} string
         */
        truncate(string) {
            return this.length && string.length > this.length ? this.getField().slice(0, this.length) + '...' : string
        }
    },
}
</script>
