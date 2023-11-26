<template>
    <div v-if="can('create_user')">
        <ContentHeader title="Ajouter un utilisateur" />
        <ContentBody>
            <NLForm :action="create" :form="form">
                <!-- Firstname -->
                <NLColumn lg="6" md="6">
                    <NLInput v-model="form.first_name" :form="form" name="firstname" label="Prénom" />
                </NLColumn>

                <!-- Lastname -->
                <NLColumn lg="6" md="6">
                    <NLInput v-model="form.last_name" :form="form" name="last_name" label="Nom de famille" />
                </NLColumn>

                <!-- Username -->
                <NLColumn lg="6" md="6">
                    <NLInput v-model="form.username" :form="form" name="username" label="Nom d'utilisateur"
                        label-required />
                </NLColumn>


                <!-- Email -->
                <NLColumn lg="6" md="6">
                    <NLInput v-model="form.email" :form="form" name="email" label="Adresse e-mail" label-required
                        type="email" />
                </NLColumn>

                <!-- Phone -->
                <NLColumn lg="6" md="6">
                    <NLInput v-model="form.phone" :form="form" name="phone" label="N° de téléphone" type="phone" />
                </NLColumn>


                <!-- Role -->
                <NLColumn lg="6" md="6">
                    <NLSelect v-model="form.role" :form="form" name="role" label="Rôle" placeholder="Choisissez un rôle"
                        :options="rolesList" labelRequired />
                </NLColumn>

                <!-- Gender -->
                <NLColumn lg="6" md="6">
                    <NLSelect v-model="form.gender" :form="form" name="gender" label="Genre"
                        placeholder="Choisissez un genre" :options="gendersList" labelRequired />
                </NLColumn>

                <NLColumn>
                    <NLGrid>
                        <!-- Password -->
                        <NLColumn lg="4" md="4">
                            <NLInput v-model="form.password" :form="form" label="Mot de passe" name="password"
                                type="password" label-required />
                        </NLColumn>
                        <!-- Password Confirmation -->
                        <NLColumn lg="4" md="4">
                            <NLInput v-model="form.password_confirmation" :form="form" label="Confirmation du mot de passe"
                                name="password_confirmation" type="password" label-required />
                        </NLColumn>
                    </NLGrid>
                </NLColumn>

                <!-- Active -->
                <NLColumn v-if="showIsActiveSwitch">
                    <NLSwitch v-model="form.is_active" name="is_active" :form="form" label="Le compte est activé ?"
                        type="is-success" />
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
import { hasRole } from '../../../plugins/user'
export default {
    layout: 'MainLayout',
    middleware: [ 'auth' ],
    data() {
        return {
            form: new Form({
                username: null,
                email: null,
                first_name: null,
                last_name: null,
                gender: null,
                password: null,
                password_confirmation: null,
                role: null,
                is_active: false,
                gender: 1,
            }),
            rolesList: [],
            gendersList: [
                {
                    label: 'Homme',
                    id: 1
                },
                {
                    label: 'Femme',
                    id: 2
                }
            ],
            showIsActiveSwitch: hasRole('admin'),
        }
    },

    computed: mapGetters({
        roles: 'roles/all',
    }),
    created() {
        this.$store.dispatch('settings/updatePageLoading', true)
        this.$store.dispatch('roles/fetchAll').then(() => {
            this.rolesList = this.roles.all
            this.$store.dispatch('settings/updatePageLoading', false)
        })
    },
    methods: {
        create() {
            this.form.post('users').then(response => {
                if (response.data.status) {
                    this.$swal.toast_success(response.data.message)
                    this.form.reset()
                } else {
                    this.$swal.alert_error(response.data.message)
                }
            }).catch(error => {
                console.log(error)
            })
        },
    }
}
</script>
