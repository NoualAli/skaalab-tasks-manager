<template>
    <div class="component-loader-container" v-if="isLoading">
        <div class="component-loader"></div>
        <div class="component-loader-text">
            Chargement en cours
        </div>
    </div>
</template>

<script>
export default {
    name: 'NLComponentLoader',
    props: {
        isLoading: { type: Boolean, default: false }
    }
}
</script>
