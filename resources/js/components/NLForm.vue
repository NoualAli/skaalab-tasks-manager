<template>
    <form enctype="multipart/form-data" class="box grid gap-6" @submit.prevent="action" @keydown="form.onKeydown($event)">
        <NLColumn>
            <Alert v-if="form.errors.any()" type="is-danger" extraClass="mb-6">
                <template #title>
                    <p v-if="!Object.hasOwnProperty.call(form.errors.errors, 'error')">
                        Il y a {{ formErrorsCount }}
                        {{ formErrorsCount > 1 ? 'problèmes avec vos entrées' : 'problème avec une entrée' }}.
                    </p>
                    <p v-else>Erreur serveur</p>
                </template>
                <ul v-for="error in form?.errors?.errors" v-if="!Object.hasOwnProperty.call(form.errors.errors, 'error')">
                    <li v-for="item in error" v-if="Array.isArray(error)">{{ item }}</li>
                </ul>
                <p v-else>{{ serverError }}</p>
            </Alert>
        </NLColumn>
        <slot></slot>
    </form>
</template>

<script>
export default {
    name: 'NLForm',
    props: {
        action: { type: Function, required: true },
        form: { type: [ Object, null ], required: true }
    },
    computed: {
        formErrorsCount() {
            return Object.entries(this.form.errors.errors).length
        },
        serverError() {
            let error = this.form.errors.errors.error
            // return 'test'
            if (error == 'Something went wrong. Please try again.') {
                return "Quelque chose s'est mal passé. Veuillez réessayer, si le problème persiste, veuillez contacter l'administrateur du serveur."
            }
            return error
        },
    },
    methods: {
    }
}
</script>
