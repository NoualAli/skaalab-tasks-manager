<template>
    <button class="btn" :class="[{ 'is-loading': loading }, finalType]" :disabled="loading">
        <NLFlex alignItems="center" direction="row" justifyContent="center" lgJustifyContent="center" gap="2"
            extraClass="w-100">
            <slot v-if="!loading"></slot>
            <NLFlex lgJustifyContent="start" v-if="loading">
                <i class="las la-spinner la-spin icon"></i>
                <span class="btn-loader">Envoie en cours</span>
            </NLFlex>
            <span class="btn-text" v-else>
                {{ label }}
            </span>
        </NLFlex>
    </button>
</template>

<script>
import NLFlex from '../Grid/NLFlex'
export default {
    components: { NLFlex },
    name: 'NLButton',
    props: {
        loading: { type: Boolean, default: false },
        label: { type: String, default: 'Valider' },
        type: { type: String, default: '' }
    },
    computed: {
        finalType() {
            return this.type ? 'btn-' + this.type : null
        }
    }
}
</script>
