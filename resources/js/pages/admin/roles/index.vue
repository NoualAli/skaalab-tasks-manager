<template>
    <div v-if="can('view_roles')">
        <ContentHeader>
            <template #actions>
                <router-link v-if="can('create_role')" :to="{ name: 'roles-create' }" class="btn btn-info">
                    Ajouter
                </router-link>
                <a href="/excel-export?export=roles" target="_blank" class="btn btn-office-excel has-icon">
                    <i class="las la-file-excel icon" />
                    Exporter
                </a>
            </template>
        </ContentHeader>
        <ContentBody>
            <NLDatatable :columns="columns" :actions="actions" title="Liste des rôles" urlPrefix="roles" @edit="edit"
                @delete="destroy" :refresh="refresh"
                @dataLoaded="() => this.$store.dispatch('settings/updatePageLoading', false)">
                <template #actions-before="{ item }">
                    <a class="btn btn-office-excel" :href="'/excel-export?export=roles&id=' + item.id" target="_blank">
                        <i class="las la-file-excel icon" />
                    </a>
                </template>
            </NLDatatable>
        </ContentBody>
    </div>
</template>

<script>
export default {
    layout: 'MainLayout',
    middleware: [ 'auth', 'admin' ],
    metaInfo() {
        return { title: 'Rôles' }
    },
    created() {
        this.$store.dispatch('settings/updatePageLoading', true)
    },
    data() {
        return {
            refresh: 1,
            columns: [
                {
                    label: 'Code',
                    field: 'code',
                    sortable: true
                },
                {
                    label: 'Nom',
                    field: 'name',
                    sortable: true
                }
            ],
            actions: {
                edit: (item) => {
                    return this.can('edit_role')
                },
                delete: (item) => {
                    return this.can('delete_role') && item.code !== 'root'
                }
            }
        }
    },
    methods: {
        /**
         * Redirige vers la page d'edition
         * @param {Object} item
         */
        edit(item) {
            this.$router.push({ name: 'roles-edit', params: { role: item.id } })
        },

        /**
         * Supprime la ressource
         * @param {Object} item
         */
        destroy(item) {
            this.$swal.confirm_destroy().then((action) => {
                if (action.isConfirmed) {
                    api.delete('roles/' + item.id).then(response => {
                        if (response.data.status) {
                            this.refresh += 1
                            this.$swal.toast_success(response.data.message)
                        } else {
                            this.$swal.toast_error(response.data.message)
                        }
                    })
                }
            }).catch(error => {
                this.$swal.alert_error()
            })
        },
    }
}
</script>
