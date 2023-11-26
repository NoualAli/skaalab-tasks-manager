<template>
    <div class="d-flex"
        :class="[gapPrefixed, fullCenter, getFormatedJustifyContent, getFormatedAlignItems, getFormatedDirection, extraClass]">
        <slot></slot>
    </div>
</template >

<script>
export default {
    name: 'NLFlex',
    props: {
        extraClass: {
            type: String,
            required: false,
            default: null,
        },
        alignItems: {
            type: String,
            default: 'start',
            required: false,
            validator(value) {
                return [
                    'center',
                    'start',
                    'end',
                    'self-start',
                    'self-end',
                    'baseline',
                    'initial',
                    'inherit',
                    'revert-layer',
                ].includes(value)
            },
        },
        mdAlignItems: {
            type: String,
            default: 'center',
            required: false,
            validator(value) {
                return [
                    'center',
                    'start',
                    'end',
                    'flex-start',
                    'flex-end',
                    'self-start',
                    'self-end',
                    'baseline',
                    'initial',
                    'inherit',
                    'revert-layer',
                ].includes(value)
            },
        },
        lgAlignItems: {
            type: String,
            default: 'center',
            required: false,
            validator(value) {
                return [
                    'center',
                    'start',
                    'end',
                    'flex-start',
                    'flex-end',
                    'self-start',
                    'self-end',
                    'baseline',
                    'initial',
                    'inherit',
                    'revert-layer',
                ].includes(value)
            },
        },
        justifyContent: {
            type: String,
            default: 'start',
            required: false,
            validator(value) {
                return [
                    'normal',
                    'center',
                    'start',
                    'end',
                    'left',
                    'right',
                    'between',
                    'around',
                    'evenly',
                    'stretch',
                    'revert-layer',
                ].includes(value)
            }
        },
        mdJustifyContent: {
            type: String,
            default: 'start',
            required: false,
            validator(value) {
                return [
                    'center',
                    'start',
                    'end',
                    'flex-start',
                    'flex-end',
                    'left',
                    'right',
                    'between',
                    'around',
                    'evenly',
                    'stretch',
                    'revert-layer',
                ].includes(value)
            }
        },
        lgJustifyContent: {
            type: String,
            default: 'between',
            required: false,
            validator(value) {
                return [
                    'center',
                    'start',
                    'end',
                    'flex-start',
                    'flex-end',
                    'left',
                    'right',
                    'between',
                    'around',
                    'evenly',
                    'stretch',
                    'revert-layer',
                ].includes(value)
            }
        },
        direction: {
            type: String,
            default: 'column',
            required: false,
            validator(value) {
                return [
                    'row',
                    'column',
                    'row-reverse',
                    'column-reverse',
                ].includes(value)
            },
        },
        mdDirection: {
            type: String,
            default: 'column',
            required: false,
            validator(value) {
                return [
                    'row',
                    'column',
                    'row-reverse',
                    'column-reverse',
                ].includes(value)
            },
        },
        lgDirection: {
            type: String,
            default: 'row',
            required: false,
            validator(value) {
                return [
                    'row',
                    'column',
                    'row-reverse',
                    'column-reverse',
                ].includes(value)
            },
        },
        isFullCentered: {
            type: Boolean,
            default: false,
            required: false,
        },
        gap: {
            type: String,
            required: false,
            default: '6',
            validator(value) {
                return [ '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' ].includes(value)
            }
        },
    },
    computed: {
        gapPrefixed() {
            return 'gap-' + this.gap
        },
        fullCenter() {
            return this.isFullCentered ? 'full-center' : null
        },
        getFormatedDirection() {
            return `${this.getDirection} ${this.getMdDirection} ${this.getLgDirection}`
        },
        getFormatedAlignItems() {
            return `${this.getAlignItems} ${this.getMdAlignItems} ${this.getLgAlignItems}`
        },
        getFormatedJustifyContent() {
            return `${this.getJustifyContent} ${this.getMdJustifyContent} ${this.getLgJustifyContent}`
        },
        getAlignItems() {
            return `align-${this.alignItems}`
        },
        getMdAlignItems() {
            return `align-md-${this.mdAlignItems}`
        },
        getLgAlignItems() {
            return `align-lg-${this.lgAlignItems}`
        },
        getJustifyContent() {
            return `justify-${this.justifyContent}`
        },
        getMdJustifyContent() {
            return `justify-md-${this.mdJustifyContent}`
        },
        getLgJustifyContent() {
            return `justify-lg-${this.lgJustifyContent}`
        },
        getDirection() {
            return `is-${this.direction}`
        },
        getMdDirection() {
            return `is-md-${this.mdDirection}`
        },
        getLgDirection() {
            return `is-lg-${this.lgDirection}`
        },
    },
}
</script>
