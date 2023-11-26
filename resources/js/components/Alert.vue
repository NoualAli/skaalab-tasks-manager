<template>
    <div :class="[type, { 'is-row': isInline, 'is-column': !isInline }, extraClass]"
        class="notification d-flex gap-2 align-start">
        <NLFlex alignItems="center" gap="1" class="text-bold">
            <span v-html="icon" v-if="icon"></span>
            <slot name="title" />
        </NLFlex>
        <slot />
    </div>
</template>
<script>
export default {
    name: 'Alert',
    props: {
        type: {
            type: String, default: ''
        },
        defaultIcon: {
            type: String, default: ''
        },
        errorsCount: {
            type: Number,
            default: 0
        },
        isInline: {
            type: Boolean,
            default: false,
        },
        extraClass: {
            type: String,
            required: false,
            default: '',
        },
    },
    computed: {
        icon() {
            let icon = ''
            switch (this.type) {
                case 'is-success':
                    icon = 'check-circle'
                    break;
                case 'is-warning':
                    icon = 'exclamation-circle'
                    break;
                case 'is-info':
                    icon = 'info-circle'
                    break;
                case 'is-danger':
                    icon = 'times-circle'
                    break;
                default:
                    icon = this.defaultIcon
                    break;
            }
            return icon ? `<i class="las la-${icon} icon"></i>` : ''
        }
    }
}
</script>
