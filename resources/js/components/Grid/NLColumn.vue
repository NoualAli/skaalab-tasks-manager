<template>
    <div :class="finalStyle">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: 'NLColumn',
    props: {
        extraClass: {
            type: String,
            required: false,
            default: null,
        },
        sm: {
            type: [ String, Number ],
            required: false,
            default: '12',
            validator: (value) => {
                value = Number(value)
                return value <= 12 || value >= 1
            }
        },
        md: {
            type: [ String, Number ],
            required: false,
            default: '12',
            validator: (value) => {
                value = Number(value)
                return value <= 12 || value >= 1
            }
        },
        lg: {
            type: [ String, Number ],
            required: false,
            default: '12',
            validator: (value) => {
                value = Number(value)
                return value <= 12 || value >= 1
            }
        }
    },
    computed: {
        smPrefixed() {
            return 'col-' + this.sm
        },
        mdPrefixed() {
            return 'col-md-' + this.md
        },
        lgPrefixed() {
            return 'col-lg-' + this.lg
        },
        finalStyle() {
            let finalStyle = `${this.smPrefixed} ${this.mdPrefixed} ${this.lgPrefixed}`
            if (this.extraClass) {
                finalStyle += ' ' + this.extraClass
            }

            return finalStyle
        }
    }
}
</script>
