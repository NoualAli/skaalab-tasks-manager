<template>
    <div v-if="can('view_tasks')">
        <ContentHeader>
            <template #actions>
                <router-link v-if="can('create_task')" :to="{ name: 'tasks-create' }" class="btn btn-info">
                    Ajouter
                </router-link>
                <!-- <a href="/excel-export?export=tasks" target="_blank" class="btn btn-office-excel has-icon">
                    <i class="las la-file-excel icon" />
                    Exporter
                </a> -->
            </template>
        </ContentHeader>
        <ContentBody>
            <NLDatatable :columns="columns" :details="details" :actions="actions" title="Liste des tâches" urlPrefix="tasks"
                @edit="edit" @delete="destroy" :refresh="refresh"
                @dataLoaded="() => this.$store.dispatch('settings/updatePageLoading', false)">
                <template #actions-before="{ item }">
                    <button class="btn btn-success" v-if="!item.is_resolved" @click.stop="validate(item)">
                        <i class="las la-check-circle icon" />
                    </button>
                </template>
            </NLDatatable>
        </ContentBody>
    </div>
</template>

<script>
import { user } from '../../plugins/user';

export default {
    layout: 'MainLayout',
    middleware: [ 'auth' ],
    metaInfo() {
        return { title: 'Tâches' }
    },
    created() {
        this.$store.dispatch('settings/updatePageLoading', true)
    },
    data() {
        return {
            refresh: 0,
            columns: [
                {
                    label: '#',
                    field: 'id',
                    sortable: true
                },
                {
                    label: 'Titre',
                    field: 'title',
                    sortable: true
                },
                // {
                //     label: 'Assigné à',
                //     field: 'executer.full_name',
                //     sortable: true
                // },
                {
                    label: 'Deadline',
                    field: 'deadline',
                    sortable: true
                },
                {
                    label: 'Prioritée',
                    field: 'priority',
                    sortable: true
                },
            ],
            details: [
                {
                    label: '#',
                    field: 'id',
                    cols: {
                        lg: 3
                    }
                },
                {
                    label: 'Deadline',
                    field: 'deadline',
                    cols: {
                        lg: 3
                    }
                },
                {
                    label: 'Date fin',
                    field: 'finished_at_formatted',
                    cols: {
                        lg: 3
                    }
                },
                {
                    label: 'Prioritée',
                    field: 'priority_str',
                    cols: {
                        lg: 3
                    }
                },
                {
                    label: 'Titre',
                    field: 'title',
                    cols: {
                        lg: 12
                    }
                },
                {
                    label: 'Contenu',
                    field: 'content',
                    isHtml: true,
                    cols: {
                        lg: 12
                    }
                },
                {
                    label: 'Créateur',
                    field: 'creator_name',
                    cols: {
                        lg: 4
                    }
                },
                {
                    label: 'Assignée à',
                    field: 'executer_name',
                    cols: {
                        lg: 4
                    }
                },
                {
                    label: 'Validée par',
                    field: 'validator_name',
                    cols: {
                        lg: 4
                    }
                },
            ],
            actions: {
                edit: {
                    show: (item) => {
                        const userId = user().id
                        return this.can('edit_task') && !item.is_resolved && (userId == item?.created_by_id || (item.assigned_to_id == null || item.assigned_to_id == userId))
                    },
                    apply: this.edit
                },
                delete: {
                    show: (item) => {
                        const userId = user().id
                        return this.can('delete_task') && !item.is_resolved && (userId == item?.created_by_id || (item.assigned_to_id == null || item.assigned_to_id == userId))
                    },
                    apply: this.destroy
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
            this.$router.push({ name: 'tasks-edit', params: { task: item.item.id } })
        },

        /**
         * Valide la ressource
         * @param {Object} item
         */
        validate(item) {
            this.$swal.confirm_update('Voulez-vous marquer cette tâche comme résolue').then((action) => {
                if (action.isConfirmed) {
                    api.put('tasks/' + item.id + '/validate').then(response => {
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

        /**
         * Supprime la ressource
         * @param {Object} item
         */
        destroy(item) {
            this.$swal.confirm_destroy().then((action) => {
                if (action.isConfirmed) {
                    api.delete('tasks/' + item.item.id).then(response => {
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
