<template>
    <NLGrid>
        <!-- <NLColumn> -->
        <!-- <NLForm :action="updateProfile" :form="infoForm">
                <NLColumn>
                    <h2>Mettez à jour vos informations</h2>
                </NLColumn> -->

        <!-- Username -->
        <!-- <NLColumn lg="4" md="6">
                    <NLInput v-model="infoForm.username" :form="infoForm" name="username" label="Nom d'utilisateur"
                        readonly />
                </NLColumn> -->

        <!-- Firstname -->
        <!-- <NLColumn lg="4" md="6">
                    <NLInput v-model="infoForm.first_name" :form="infoForm" name="firstname" label="Prénom" />
                </NLColumn> -->

        <!-- Lastname -->
        <!-- <NLColumn lg="4" md="6">
                    <NLInput v-model="infoForm.last_name" :form="infoForm" name="last_name" label="Nom de famille" />
                </NLColumn> -->

        <!-- Phone -->
        <!-- <NLColumn lg="6" md="6">
                    <NLInput v-model="infoForm.phone" :form="infoForm" name="phone" label="N° de téléphone" type="phone" />
                </NLColumn> -->

        <!-- Email -->
        <!-- <NLColumn lg="6" md="6">
                    <NLInput v-model="infoForm.email" :form="infoForm" name="email" label="Adresse e-mail" label-required
                        type="email" />
                </NLColumn>
                <NLColumn>
                    <NLFlex lgJustifyContent="end">
                        <NLButton :loading="infoForm.busy" label="Mettre à jour" @click.prevent="updateProfile" />
                    </NLFlex>
                </NLColumn>
            </NLForm> -->
        <!-- </NLColumn> -->

        <NLColumn>
            <NLForm :action="updatePassword" :form="passwordForm">
                <NLColumn>
                    <h2>Changez votre mot de passe</h2>
                </NLColumn>
                <!-- Current password -->
                <NLColumn lg="4" md="4">
                    <NLInput v-model="passwordForm.current_password" :form="passwordForm" label="Mot de passe actuel"
                        name="current_password" type="password" label-required />
                </NLColumn>

                <!-- Password -->
                <NLColumn lg="4" md="4">
                    <NLInput v-model="passwordForm.password" :form="passwordForm" label="Mot de passe" name="password"
                        type="password" label-required />
                </NLColumn>
                <!-- Password Confirmation -->
                <NLColumn lg="4" md="4">
                    <NLInput v-model="passwordForm.password_confirmation" :form="passwordForm"
                        label="Confirmation mot de passe" name="password_confirmation" type="password" label-required />
                </NLColumn>
                <NLColumn>
                    <NLFlex lgJustifyContent="end">
                        <NLButton :loading="infoForm.busy" label="Mettre à jour" @click.prevent="updatePassword" />
                    </NLFlex>
                </NLColumn>
            </NLForm>
        </NLColumn>

        <NLColumn>
            <NLDatatable :columns="columns" title="Historique de connexion" urlPrefix="users/logins/history"
                @dataLoaded="() => this.$store.dispatch('settings/updatePageLoading', false)" />
        </NLColumn>
    </NLGrid>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'
import { user } from '../../plugins/user'

export default {
    layout: 'MainLayout',

    metaInfo() {
        return { title: 'Profil' }
    },

    data() {
        return {
            infoForm: new Form({
                username: '',
                email: '',
                first_name: '',
                last_name: ''
            }),
            passwordForm: new Form({
                password: '',
                current_password: '',
                password_confirmation: ''
            }),

            columns: [
                {
                    label: 'Appareil',
                    field: 'device'
                },
                {
                    label: 'Navigateur',
                    field: 'browser'
                },
                {
                    label: 'Version',
                    field: 'browser_version'
                },
                {
                    label: "Système d'exploitation",
                    field: 'platform'
                },
                {
                    label: 'Version',
                    field: 'platform_version'
                },
                {
                    label: 'Adresse IP',
                    field: 'ip_address'
                },
                {
                    label: 'Dernière connexion',
                    field: 'last_activity'
                }
            ]
        }
    },

    computed: mapGetters({
        user: 'auth/user',
    }),

    created() {
        this.$store.dispatch('settings/updatePageLoading', true)
        this.infoForm.keys().forEach(key => {
            this.infoForm[ key ] = this.user[ key ]
        })
    },

    methods: {
        updateProfile() {
            this.infoForm.patch('settings/profile/' + user().id).then(response => {
                if (response.data.status) {
                    this.$swal.toast_success(response.data.message)
                } else {
                    this.$swal.alert_error(response.data.message)
                }
            }).catch(error => {
                console.log(error)
            })
        },
        updatePassword() {
            this.passwordForm.patch('settings/password/' + user().id).then(response => {
                if (response.data.status) {
                    this.$swal.toast_success(response.data.message)
                    this.passwordForm.reset()
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
