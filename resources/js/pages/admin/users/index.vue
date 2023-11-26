<template>
    <div v-if="can('view_users')">
        <ContentHeader>
            <template #actions>
                <router-link v-if="can('create_user')" :to="{ name: 'users-create' }" class="btn btn-info has-icon">
                    <i class="las la-plus icon"></i>
                    Ajouter
                </router-link>
                <a href="/excel-export?export=users" target="_blank" class="btn btn-office-excel has-icon">
                    <i class="las la-file-excel icon" />
                    Exporter
                </a>
            </template>
        </ContentHeader>
        <ContentBody>
            <NLDatatable :columns="columns" :actions="actions" :details="details" title="Liste des utilisateurs"
                urlPrefix="users" @edit="edit" @delete="destroy" :refresh="refresh"
                @dataLoaded="() => this.$store.dispatch('settings/updatePageLoading', false)">
                <template #actions-before="{ item }">
                    <a class="btn btn-office-excel" :href="'/excel-export?export=users&id=' + item.id" target="_blank">
                        <i class="las la-file-excel icon" />
                    </a>
                </template>
            </NLDatatable>
        </ContentBody>
    </div>
</template>

<script>
import { hasRole, user } from '../../../plugins/user'
import api from '../../../plugins/api'
export default {
    layout: 'MainLayout',
    middleware: [ 'auth' ],
    created() {
        this.$store.dispatch('settings/updatePageLoading', true)
    },
    data() {
        return {
            refresh: 1,
            columns: [
                {
                    label: "Nom d'utilisateur",
                    field: 'username',
                    sortable: true
                },
                {
                    label: 'Nom complet',
                    field: 'full_name'
                },
                {
                    label: 'Adresse email',
                    field: 'email',
                    sortable: true
                },
                {
                    label: 'N° de téléphone',
                    field: 'phone',
                    sortable: true
                },
                {
                    label: 'Rôle',
                    field: 'role',
                },
                {
                    label: 'Actif',
                    field: 'is_active',
                    isHtml: true,
                    methods: {
                        showField(item) {
                            if (item.is_active) {
                                return '<i class="las la-check-circle icon text-success"></i>'
                            }
                            return '<i class="las la-times-circle icon text-danger"></i>'
                        }
                    }
                },
                {
                    label: 'Dernière connexion',
                    field: 'last_login',
                },
                {
                    label: 'Date de création',
                    field: 'created_at',
                    sortable: true,
                },
            ],
            details: [
                {
                    label: "Nom d'utilisateur",
                    field: 'username',
                },
                {
                    label: 'Nom complet',
                    field: 'full_name'
                },
                {
                    label: 'Genre',
                    field: 'gender_str'
                },
                {
                    label: 'Adresse email',
                    field: 'email',
                },
                {
                    label: 'N° de téléphone',
                    field: 'phone',
                },
                {
                    label: 'Rôle',
                    field: 'role_name'
                },
                {
                    label: 'Dernière connexion',
                    field: 'last_login_last_activity',
                },
                {
                    label: 'Date de création',
                    field: 'created_at',
                },
            ],
            actions: {
                edit: {
                    show: (item) => {
                        let condition = !this.isCurrent(item) && this.can('edit_user') && item?.role_code !== 'admin'
                        return condition
                    },
                    apply: this.edit
                },
                delete: {
                    show: (item) => {
                        return !this.isCurrent(item) && this.can('delete_user') && item?.role_code !== 'admin' && hasRole([ 'admin' ]) && item.id !== user()?.id
                    },
                    apply: this.destroy
                }
            }
        }
    },
    methods: {
        /**
         * Vérifie si la ligne correspond à l'utilisateur actuel
         * @param {Object} item
         */
        isCurrent(item) {
            return item?.id === user()?.id
        },
        /**
         * Redirige vers la page d'edition
         * @param {Object} e
         */
        edit(e) {
            return this.$router.push({ name: 'users-edit', params: { user: e.item.id } })
        },

        /**
         * Supprime la ressource
         * @param {Object} e
         */
        destroy(e) {
            return this.$swal.confirm_destroy().then((action) => {
                if (action.isConfirmed) {
                    return api.delete('users/' + e.item.id).then(response => {
                        if (response.data.status) {
                            this.refresh += 1
                            return this.$swal.toast_success(response.data.message)
                        } else {
                            return this.$swal.toast_error(response.data.message)
                        }
                    })
                }
            }).catch(error => {
                console.error(error)
                this.$swal.alert_error()
            })
        },
    }
}
</script>
