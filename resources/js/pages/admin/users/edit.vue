<template>
    <div v-if="can('edit_user')">
        <ContentBody>
            <NLGrid class="my-4">
                <!-- Update User informations -->
                <NLColumn>
                    <NLForm :action="updateInfos" :form="form">
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
                            <NLInput v-model="form.email" :form="form" name="email" label="Adresse e-mail" type="email"
                                label-required />
                        </NLColumn>

                        <!-- Phone -->
                        <NLColumn lg="6" md="6">
                            <NLInput v-model="form.phone" :form="form" name="phone" label="N° de téléphone" type="phone" />
                        </NLColumn>


                        <!-- Role -->
                        <NLColumn lg="6" md="6">
                            <NLSelect v-model="form.role" :form="form" name="role" label="Rôle"
                                placeholder="Choisissez un rôle" :options="rolesList" labelRequired />
                        </NLColumn>

                        <!-- Gender -->
                        <NLColumn lg="6" md="6">
                            <NLSelect v-model="form.gender" :form="form" name="gender" label="Genre"
                                placeholder="Choisissez un genre" :options="gendersList" labelRequired />
                        </NLColumn>

                        <!-- Active -->
                        <NLColumn v-if="showIsActiveSwitch">
                            <NLSwitch v-model="form.is_active" name="is_active" :form="form" label="Le compte est activé ?"
                                type="is-success" />
                        </NLColumn>

                        <NLColumn>
                            <NLFlex lgJustifyContent="end">
                                <NLButton :loading="form.busy" label="Mettre à jour" />
                            </NLFlex>
                        </NLColumn>

                    </NLForm>
                </NLColumn>

                <!-- Update Password -->
                <NLColumn>
                    <NLForm :action="updatePassword" :form="form">
                        <!-- Password -->
                        <NLColumn lg="4" md="4">
                            <NLInput v-model="passwordForm.password" :form="passwordForm" label="Mot de passe"
                                name="password" type="password" label-required />
                        </NLColumn>
                        <!-- Password Confirmation -->
                        <NLColumn lg="4" md="4">
                            <NLInput v-model="passwordForm.password_confirmation" :form="passwordForm"
                                label="Confirmation mot de passe" name="password_confirmation" type="password"
                                label-required />
                        </NLColumn>
                        <NLColumn>
                            <NLFlex lgJustifyContent="end">
                                <NLButton :loading="passwordForm.busy" label="Mettre à jour" />
                            </NLFlex>
                        </NLColumn>
                    </NLForm>
                </NLColumn>
            </NLGrid>
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
                phone: null,
                role: null,
                is_active: true,
                gender: true,
                is_active: true,
            }),
            passwordForm: new Form({
                password: null,
                password_confirmation: null
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
            showIsActiveSwitch: hasRole([ 'admin' ]),
        }
    },
    computed: mapGetters({
        user: 'users/current',
        roles: 'roles/all'
    }),
    created() {
        this.initData()
    },
    methods: {
        initData() {
            this.$store.dispatch('settings/updatePageLoading', true)
            this.$store.dispatch('roles/fetchAll').then(() => {
                this.rolesList = this.roles.all
                this.$store.dispatch('users/fetch', this.$route.params.user).then(() => {
                    this.form.fill(this.user.current)
                    this.form.role = this.user.current.role_id
                    this.$store.dispatch('settings/updatePageLoading', false)
                })
            })
        },
        updateInfos() {
            this.$swal.confirm_update().then((action) => {
                if (action.isConfirmed) {
                    this.form.put('users/info/' + this.user.current.id).then(response => {
                        if (response.data.status) {
                            this.$swal.toast_success(response.data.message)
                            this.$router.push({ name: 'users-index' })
                        } else {
                            this.$swal.alert_error(response.data.message)
                        }
                    }).catch(error => {
                        console.log(error)
                    })
                }
            })
        },
        updatePassword() {
            this.$swal.confirm_update().then((action) => {
                if (action.isConfirmed) {
                    this.passwordForm.put('users/password/' + this.user.current.id).then(response => {
                        if (response.data.status) {
                            this.$swal.toast_success(response.data.message)
                            this.passwordForm.reset()
                            this.$router.push({ name: 'users-index' })
                        } else {
                            this.$swal.alert_error(response.data.message)
                        }
                    }).catch(error => {
                        console.log(error)
                    })
                }
            })
        }
    }
}
</script>
