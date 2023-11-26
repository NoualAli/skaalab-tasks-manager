<!-- eslint-disable vue/multi-word-component-names -->
<template>
    <router-view :key="$route.url" v-slot="{ Component }">
        <component :is="Component" />
        <!-- <NLPageLoader :isLoading="pageLoadingState" /> -->
    </router-view>
</template>

<script>
import NLPageLoader from './NLPageLoader'
import { mapGetters } from 'vuex';
export default {
    components: { NLPageLoader },
    // eslint-disable-next-line vue/multi-word-component-names
    name: 'Child',
    // computed: {
    //     ...mapGetters({
    //         pageLoadingState: 'settings/pageIsLoading'
    //     }),
    // },
}
</script>
