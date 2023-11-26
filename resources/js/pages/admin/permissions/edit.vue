<template>
    <div v-if="can('edit_permission')">
        <ContentBody>
            <NLForm :action="update" :form="form">
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

export default {
    layout: 'MainLayout',
    middleware: [ 'auth', 'admin' ],
    computed: {
        ...mapGetters({
            permission: 'permissions/current',
            roles: 'roles/all'
        })
    },
    created() {
        this.$store.dispatch('settings/updatePageLoading', true)
        this.$store.dispatch('roles/fetchAll').then(() => {
            this.rolesList = this.roles.all
            this.$store.dispatch('permissions/fetch', this.$route.params.permission).then(() => {
                const data = this.permission.current
                this.form.fill(data)
                this.form.roles = this.permission.current.roles.map(item => item.id)
                this.$store.dispatch('settings/updatePageLoading', false)
            })
        })
    },
    data() {
        return {
            rolesList: [],
            form: new Form({
                name: '',
                roles: []
            })
        }
    },
    methods: {
        update() {
            this.form.put('permissions/' + this.$route.params.permission).then(response => {
                if (response.data.status) {
                    this.$swal.toast_success(response.data.message)
                    this.$router.push({ name: 'permissions-index' })
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
