<template>
    <div class="container" :class="[{ 'is-fluid': isFluid }, extraClass]">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: 'NLContainer',
    props: {
        isFluid: {
            type: Boolean,
            required: false,
            default: false,
        },
        extraClass: {
            type: String,
            required: false,
            default: null,
        },
    },
}
</script>
