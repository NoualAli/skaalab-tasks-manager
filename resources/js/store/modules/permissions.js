import api from "../../plugins/api"

export const state = {
  paginated: null,
  all: null,
  current: null
}

export const mutations = {
  FETCH_PAGINATED(state, permissions) {
    state.paginated = permissions
  },
  FETCH_ALL(state, permissions) {
    state.all = permissions
  },
  FETCH(state, permission) {
    state.current = permission
  },
}

export const actions = {
  async fetchPaginated({ commit }) {
    const { data } = await api.get('permissions')
    commit('FETCH_PAGINATED', { paginated: data })
  },

  async fetchAll({ commit }) {
    const { data } = await api.get('permissions?fetchAll')
    commit('FETCH_ALL', { all: data })
  },

  async fetch({ commit }, id) {
    const { data } = await api.get('permissions/' + id)
    commit('FETCH', { current: data })
  },
}

export const getters = {
  paginated: state => state.paginated,
  all: state => state.all,
  current: state => state.current
}
