<template>
    <div v-if="can('create_permission')">
        <ContentHeader title="Ajouter une nouvelle permission" />
        <ContentBody>
            <NLForm :action="create" :form="form">
                <!-- Name -->
                <NLColumn lg="6" md="6">
                    <NLInput v-model="form.name" :form="form" name="name" label="Nom" label-required />
                </NLColumn>

                <!-- Rôles -->
                <NLColumn lg="6" md="6">
                    <NLSelect v-model="form.roles" :form="form" name="roles" label="Rôles" :options="rolesList"
                        :multiple="true" label-required />
                </NLColumn>
                <NLColumn>
                    <NLFlex lgJustifyContent="end">
                        <NLButton :loading="form.busy" label="Ajouter" />
                    </NLFlex>
                </NLColumn>
            </NLForm>
        </ContentBody>
    </div>
</template>

<script>
import { Form } from 'vform'
import { mapGetters } from 'vuex'

export default {
    layout: 'MainLayout',
    middleware: [ 'auth', 'admin' ],
    data() {
        return {
            rolesList: [],
            form: new Form({
                name: '',
                roles: []
            })
        }
    },
    computed: {
        ...mapGetters({
            roles: 'roles/all'
        })
    },
    created() {
        this.$store.dispatch('settings/updatePageLoading', true)
        this.$store.dispatch('roles/fetchAll').then(() => {
            this.rolesList = this.roles.all
            this.$store.dispatch('settings/updatePageLoading', false)
        })
    },

    methods: {
        create() {
            this.form.post('permissions').then(response => {
                if (response.data.status) {
                    this.$swal.toast_success(response.data.message)
                    this.form.reset()
                } else {
                    this.$swal.alert_error(response.data.message)
                }
            }).catch(error => {
                console.log(error)
            })
        }
    }
}
</script>
