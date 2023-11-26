<template>
    <ContentBody>
        <NLDatatable :columns="columns" title="Liste des notifications" urlPrefix="notifications"
            @dataLoaded="() => this.$store.dispatch('settings/updatePageLoading', false)">
            <template #actions-after="{ item }">
                <button class="btn btn-success has-icon" @click.stop="show(item)" v-if="!!item.url">
                    <i class="las la-eye icon"></i>
                </button>
            </template>
        </NLDatatable>
    </ContentBody>
</template>

<script>
import api from '../../plugins/api'
export default {
    layout: 'MainLayout',
    middleware: [ 'auth' ],
    metaInfo() {
        return { title: 'Centre de notification' }
    },
    data: () => {
        return {
            columns: [
                {
                    label: 'Titre',
                    field: 'title',
                    extraClass: {
                        td: (item) => {
                            return !item.read_at ? 'bg-primary-extra-light' : '';
                        },
                    },
                },
                {
                    label: 'Emis par',
                    field: 'emitted_by',
                    extraClass: {
                        td: (item) => {
                            return !item.read_at ? 'bg-primary-extra-light' : '';
                        },
                    },
                },
                {
                    label: 'Contenu',
                    field: 'short_content',
                    isHtml: true,
                    extraClass: {
                        td: (item) => {
                            return !item.read_at ? 'bg-primary-extra-light' : '';
                        },
                    },
                },
                {
                    label: 'Date',
                    field: 'created_at',
                    extraClass: {
                        td: (item) => {
                            return !item.read_at ? 'bg-primary-extra-light' : '';
                        },
                    },
                },
            ],
        }
    },
    created() {
        this.$store.dispatch('settings/updatePageLoading', true)
        this.markAsRead()
    },
    methods: {
        /**
         * Affiche l'élément seléctionner
         *
         * @param {Object} item
         */
        show(item) {
            const { pathname, search } = new URL(item.url)
            return this.$router.push({ path: pathname, query: Object.fromEntries(new URLSearchParams(search)) })
        },
        /**
         * Formate les différentes informations pour créer la route d'action
         *
         * @param {Object} item
         */
        getRoute(item) {
            if (item.url) {
                return item.url
            } else {
                const paramName = item.paramName
                const id = item.modelId
                const routeName = item.routeName
                return { name: routeName, params: { [ paramName ]: id } }
            }
        },
        /**
         * Marque les notifications comme lu
         */
        async markAsRead() {
            await api.put('notifications')
        }
    }
}
</script>
