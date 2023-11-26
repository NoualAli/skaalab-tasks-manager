<template>
    <button v-if="show" :class="buttonClass" @click.stop="emitAction">
        <i class="las la-eye icon" v-if="type == 'show'" />
        <i class="las la-trash icon" v-else-if="type == 'delete'" />
        <i class="las la-edit icon" v-else-if="type == 'edit'" />
    </button>
</template>

<script>
export default {
    name: 'TableAction',
    emits: [ 'action' ],
    props: {
        action: {
            type: [ Function, Boolean, Object ],
            required: false,
            default: false
        },
        name: {
            type: String,
            required: true
        },
        item: {
            type: [ Object, Array ],
            required: false,
            default: () => { }
        },
        column: {
            type: [ Object, null ],
            required: false,
            default: () => { },
        },
    },
    computed: {
        type() {
            return this.name
        },
        show() {
            let show = false
            if (typeof this.action.show === 'function') {
                show = this.action.show(this.item)
            } else if (typeof this.action == 'boolean') {
                show = this.action
            } else if (typeof this.action == 'function') {
                show = this.action
            } else {
                show = this.action.show
            }
            return show
        },
        buttonClass() {
            let defaultClass = 'btn'
            switch (this.type) {
                case 'show':
                    defaultClass += ' btn-success'
                    break;
                case 'delete':
                    defaultClass += ' btn-danger'
                    break;
                case 'edit':
                    defaultClass += ' btn-warning'
                    break;
                default:
                    break;
            }

            return defaultClass
        }
    },
    methods: {
        emitAction() {
            let apply = typeof this.action == 'boolean' || typeof this.action == 'function' ? undefined : this.action.apply
            this.$emit('action', { type: this.type, item: this.item, apply })
        },
    }
}
</script>
