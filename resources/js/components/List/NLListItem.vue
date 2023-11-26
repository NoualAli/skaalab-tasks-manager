<template>
    <NLColumn :lg="lg" :md="md" :sm="sm" :extraClass="extraClass + ' list-item'">
        <div class="list-item-label">
            <slot name="label"></slot>
        </div>
        <div class="list-item-content" v-if="this.$slots.content">
            <slot name="content"></slot>
        </div>
    </NLColumn>
</template>

<script>
export default {
    name: "NLListItem",
    props: {
        extraClass: {
            type: String,
            required: false,
            default: null,
        },
        sm: {
            type: String,
            required: false,
            default: '12',
            validator(value) {
                return [ '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', ].includes(value)
            }
        },
        md: {
            type: String,
            required: false,
            default: '12',
            validator(value) {
                return [ '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', ].includes(value)
            }
        },
        lg: {
            type: String,
            required: false,
            default: '6',
            validator(value) {
                return [ '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', ].includes(value)
            }
        }
    },
}
</script>
