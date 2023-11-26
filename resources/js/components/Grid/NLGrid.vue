<template>
    <div class="grid" :class="[gapPrefixed, extraClass]">
        <slot></slot>
    </div>
</template>

<script>

export default {
    name: 'NLGrid',
    props: {
        extraClass: {
            type: String,
            required: false,
            default: null,
        },
        gap: {
            type: String,
            required: false,
            default: '6',
            validator(value) {
                return [ '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', ].includes(value)
            }
        },
    },
    computed: {
        gapPrefixed() {
            return 'gap-' + this.gap
        }
    }
}
</script>
