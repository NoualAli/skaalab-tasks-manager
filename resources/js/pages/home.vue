<template>
    <ContentBody>

    </ContentBody>
</template>

<script>

export default {
    layout: 'MainLayout',
    middleware: [ 'auth' ],
    data() {
        return {

        }
    },
    computed: {

    },
    created() {
        this.$store.dispatch('settings/updatePageLoading', false)
    },
    methods: {

    }

}
</script>
