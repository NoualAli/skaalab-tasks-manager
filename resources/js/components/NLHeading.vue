<template>
    <component :is="`h${level}`" :class="extraClass">
        <slot></slot>
    </component>
</template>

<script>
export default {
    name: 'NLHeading',
    props: {
        extraClass: {
            type: String,
            required: false,
            default: null,
        },
        type: {
            type: [ Number, String ],
            default: 1,
            required: false,
            validator(value) {
                return [ 1, 2, 3, 4, 5, 6 ].includes(Number(value))
            }
        },
    },
    computed: {
        level() {
            return Number(this.type)
        }
    }
}
</script>
