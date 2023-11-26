<template>
    <div v-if="can('create_task')">
        <ContentHeader title="Ajouter une nouvelle tâche" />
        <ContentBody>
            <NLForm :action="update" :form="form">
                <!-- Title -->
                <NLColumn lg="6" md="6">
                    <NLInput v-model="form.title" :form="form" name="title" label="Titre" label-required :length="255" />
                </NLColumn>

                <!-- Priority -->
                <NLColumn lg="6" md="6">
                    <NLSelect v-model="form.priority" :options="priorities" :form="form" name="priority" label="Prioritée"
                        label-required />
                </NLColumn>

                <!-- Content -->
                <NLColumn>
                    <NLWysiwyg v-model="form.content" :form="form" name="content" label="Contenu" :length="1000" />
                </NLColumn>

                <!-- Assignation -->
                <NLColumn lg="6" md="6" v-if="canAssignTask">
                    <NLSelect v-model="form.assigned_to_id" :options="usersList" :form="form" name="assigned_to_id"
                        label="Assigné à" />
                </NLColumn>

                <!-- Deadline -->
                <NLColumn lg="6" md="6">
                    <NLInput type="date" v-model="form.deadline" :form="form" name="deadline" label="Date fin" />
                </NLColumn>

                <NLColumn>
                    <NLFlex lgJustifyContent="end">
                        <NLButton :loading="form.busy" label="Mettre à jour" />
                    </NLFlex>
                </NLColumn>
            </NLForm>
        </ContentBody>
    </div>
</template>

<script>
import { Form } from 'vform'
import { mapGetters } from 'vuex'
import { hasRole } from '../../plugins/user'
export default {
    layout: 'MainLayout',
    middleware: [ 'auth' ],
    data() {
        return {
            form: new Form({
                title: null,
                content: null,
                priority: 1,
                deadline: null,
                assigned_to_id: null,
            }),
            usersList: []
        }
    },
    computed: {
        ...mapGetters({
            users: 'users/all',
            task: 'tasks/current'
        }),
        priorities() {
            return [
                {
                    id: 1,
                    label: 'Normal'
                },
                {
                    id: 2,
                    label: 'Moyenne'
                },
                {
                    id: 3,
                    label: 'Urgente'
                },
            ]
        },
        canAssignTask() {
            return hasRole([ 'manager', 'admin' ])
        }
    },
    created() {
        this.$store.dispatch('settings/updatePageLoading', true)
        this.$store.dispatch('tasks/fetch', this.$route.params.task).then(() => {
            if (this.canAssignTask) {
                this.$store.dispatch('users/fetchAll').then(() => {
                    this.usersList = this.users.all
                })
            }
            this.form.title = this.task.current.title
            this.form.content = this.task.current.content
            this.form.priority = this.task.current.priority
            this.form.deadline = this.task.current.deadline
            this.form.assigned_to_id = this.task.current.assigned_to_id
            this.$store.dispatch('settings/updatePageLoading', false)
        })
    },
    methods: {
        update() {
            this.form.put('tasks/' + this.task.current.id).then(response => {
                if (response.data.status) {
                    this.$swal.toast_success(response.data.message)
                } else {
                    this.$swal.alert_error(response.data.message)
                }
                console.log(response.data.additional);
            }).catch(error => {
                this.$swal.catchError(error)
            })
        }
    }
}
</script>
