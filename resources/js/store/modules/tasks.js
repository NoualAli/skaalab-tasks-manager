import api from '../../plugins/api'

export const state = {
    all: null,
    paginated: null,
    current: null,
}

export const mutations = {
    FETCH_ALL(state, data) {
        state.all = data
    },
    FETCH_PAGINATED(state, data) {
        state.paginated = data
    },
    FETCH(state, data) {
        state.current = data
    }
}

export const actions = {
    async fetchAll({ commit }, filters = null) {
        let url = 'tasks?fetchAll'
        if (filters) {
            for (const key in filters) {
                if (Object.hasOwnProperty.call(filters, key)) {
                    url += '&filter[' + key + ']=' + filters[ key ]
                }
            }
        }
        const { data } = await api.get(url)
        commit('FETCH_ALL', { all: data })
    },
    async fetchPaginated({ commit }) {
        const { data } = await api.get('tasks')
        commit('FETCH_PAGINATED', { paginated: data })
    },
    async fetch({ commit }, id) {
        try {
            const { data } = await api.get('tasks/' + id)
            commit('FETCH', { current: data })
        } catch (error) {

        }
    }
}

export const getters = {
    all: state => state.all,
    paginated: state => state.paginated,
    current: state => state.current
}
