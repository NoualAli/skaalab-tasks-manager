<template>
    <transition name="page" mode="out-in">
        <component :is="layout" v-if="layout" />
    </transition>
</template>

<script>
// import Loading from './Loading'
import Loading from 'vue-loading-overlay'
import defaultLayout from '~/layouts/default.vue'
import { markRaw } from 'vue'

export default {
    el: '#app',

    components: {
        // Loading
    },

    data: () => ({
        layout: null,
        defaultLayout: markRaw(defaultLayout),
        isLoading: false
    }),

    metaInfo() {
        const { appName } = window.Laravel

        return {
            title: appName,
            titleTemplate: `%s · ${appName}`
        }
    },
    watch: {
        $route: {
            immediate: true,
            async handler(route) {
                try {
                    if (route.meta.layout) this.setLayout(route.meta.layout)
                } catch (e) {
                    this.layout = defaultLayout
                }
            }
        }
    },

    methods: {
        /**
         * Set the application layout.
         *
         * @param {String} layout
         */
        async setLayout(layout) {
            const component = await import(`~/layouts/${layout}.vue`)
            this.layout = markRaw(component?.default || this.defaultLayout)
        }
    }
}
</script>
