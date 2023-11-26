export const state = {
    title: null,
    validationRules: null,
    pageIsLoading: true,
}

// getters
export const getters = {
    title: state => state.title,
    validationRules: state => state.validationRules,
    pageIsLoading: state => state.pageIsLoading,
}

export const mutations = {
    SET_PAGE_LOADING(state, pageIsLoading) {
        state.pageIsLoading = pageIsLoading
    },
    SET_TITLE(state, title) {
        state.title = title
    },
    SET_VALIDATION_RULES(state, validationRules) {
        state.validationRules = validationRules
    }
}

export const actions = {
    setTitle({ commit }, title) {
        commit('SET_TITLE', title)
    },

    updatePageLoading({ commit }, pageIsLoading) {
        commit('SET_PAGE_LOADING', pageIsLoading);
    },

    async fetchValidationRules({ commit }) {
        // const { data } = await api.get('settings/laravel/rules')
        const data = [
            { id: 'nullable', label: 'Facultatif' },
            { id: 'required', label: 'Obligatoire' },
            { id: 'distinct', label: 'Distinct' },
            { id: 'email', label: 'Adresse e-mail' },
            { id: 'integer', label: 'Nombre entier' },
            { id: 'float', label: 'Nombre flottant' },
            { id: 'boolean', label: 'Booléen' }
        ]
        commit('SET_VALIDATION_RULES', { validationRules: data })
    }
}
