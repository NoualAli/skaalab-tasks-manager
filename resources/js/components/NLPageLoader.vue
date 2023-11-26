<template>
    <div class="page-loader-container" :class="{ 'is-active': isLoading }">
        <div class="page-loader">
            <i class="las la-tasks"></i>
        </div>
        <div class="page-loader-text">
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
