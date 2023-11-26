<template>
    <div v-if="can('edit_role')">
        <ContentBody>
            <NLForm :action="update" :form="form">
                <!-- Name -->
                <NLColumn lg="6" md="6">
                    <NLInput v-model="form.name" :form="form" name="name" label="Nom" label-required />
                </NLColumn>
                <!-- Code -->
                <NLColumn lg="6" md="6">
                    <NLInput v-model="form.code" :form="form" name="code" label="Code" label-required />
                </NLColumn>

                <!-- Permissions -->
                <!-- <NLColumn lg="6" md="6">
                    <NLSelect v-model="form.permissions" :form="form" name="permissions" label="Permissions"
                        :options="permissionsList" :multiple="true" label-required />
                </NLColumn> -->
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
            // permissions: 'permissions/all',
            role: 'roles/current'
        })
    },
    created() {
        this.$store.dispatch('settings/updatePageLoading', true)
        this.$store.dispatch('permissions/fetchAll').then(() => {
            // this.permissions.all.forEach(permission => {
            //     permission = {
            //         id: permission.id,
            //         label: permission.name
            //     }
            //     this.permissionsList.push(permission)
            // })
            this.$store.dispatch('roles/fetch', this.$route.params.role).then(() => {
                const data = this.role.current
                this.form.fill(data)
                // this.form.permissions = this.role.current.permissions.map(item => item.id)
                this.$store.dispatch('settings/updatePageLoading', false)
            })
        })
    },
    data() {
        return {
            permissionsList: [],
            form: new Form({
                name: '',
                code: '',
                // permissions: []
            })
        }
    },
    methods: {
        update() {
            this.form.put('roles/' + this.$route.params.role).then(response => {
                if (response.data.status) {
                    this.$swal.toast_success(response.data.message)
                    this.$router.push({ name: 'roles-index' })
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
