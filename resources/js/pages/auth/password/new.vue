<template>
    <NLGrid class="box auth-box" gap="6">
        <NLColumn class="auth-box__header">
            <img src="/app/images/brand.svg" class="auth-brand">
            <span class="auth-box__title">
                1<sup>ère</sup> Connexion <br>
                Nouveau mot de passe
            </span>
        </NLColumn>
        <NLColumn class="form-container container">
            <form method="POST" @submit.prevent="renew" @keydown="form.onKeydown($event)" v-if="showForm">
                <NLGrid gap="2" class="my-2">
                    <NLColumn>
                        <NLInput v-model="form.current_password" name="current_password" class="is-for-auth"
                            placeholder="Mot de passe actuel" :form="form" type="password" />
                    </NLColumn>
                    <NLColumn>
                        <NLInput v-model="form.password" name="password" class="is-for-auth" placeholder="Mot de passe"
                            :form="form" type="password" />
                    </NLColumn>
                    <NLColumn>
                        <NLInput v-model="form.password_confirmation" name="password_confirmation" class="is-for-auth"
                            placeholder="Confirmation mot de passe" :form="form" type="password" />
                    </NLColumn>
                </NLGrid>
                <NLFlex lgJustifyContent="center">
                    <NLButton :loading="form.busy" label="Continuer" class="d-block w-100" />
                </NLFlex>
            </form>
        </NLColumn>
        <NLColumn class="text-center d-block d-lg-none">
            &copy; {{ currentYear }} - Tous droits réservés - NLDEV
        </NLColumn>
    </NLGrid>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'
import { ls_get } from '../../../plugins/crypto'
export default {
    layout: 'auth',
    // middleware: 'auth',
    metaInfo() {
        return { title: 'Réinitialisation du mot de passe' }
    },
    computed: {
        ...mapGetters({
            user: 'auth/user'
        })
    },
    data: () => ({
        form: new Form({
            current_password: null,
            password: null,
            password_confirmation: null,
            mustChangePassword: true
        }),
        showForm: true,
    }),

    methods: {
        renew() {
            const user = JSON.parse(ls_get('user'))
            this.form.patch('settings/password/' + user?.id).then(response => {
                if (response.data.status) {
                    this.showForm = false
                    this.$swal.toast_success(response.data.message)
                    this.form.reset()
                    this.$store.dispatch('auth/fetchUser').then(() => this.$router.push({ name: 'home' }))
                } else {
                    this.showForm = true
                    this.$swal.alert_error(response.data.message)
                }
            }).catch(error => {
                this.$swal.alert_error(error?.data?.message)
                console.log(error)
                this.showForm = false
            })
            // this.status = data.status
        }
    }
}
</script>
