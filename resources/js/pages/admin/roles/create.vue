<template>
    <div v-if="can('create_role')">
        <ContentHeader title="Ajouter un nouveau rôle" />
        <ContentBody>
            <NLForm :action="create" :form="form">
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
            permissionsList: [],
            form: new Form({
                name: '',
                code: '',
                // permissions: []
            })
        }
    },
    // computed: {
    //     ...mapGetters({
    //         permissions: 'permissions/all'
    //     })
    // },
    // created() {
    //     this.$store.dispatch('settings/updatePageLoading', true)
    //     this.$store.dispatch('permissions/fetchAll').then(() => {
    //         this.permissions.all.forEach(permission => {
    //             permission = {
    //                 id: permission.id,
    //                 label: permission.name
    //             }
    //             this.permissionsList.push(permission)
    //         })
    //         this.$store.dispatch('settings/updatePageLoading', false)
    //     })
    // },

    methods: {
        create() {
            this.form.post('roles').then(response => {
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
