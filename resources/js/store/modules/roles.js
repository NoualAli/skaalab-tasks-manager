import api from '../../plugins/api'

export const state = {
  paginated: null,
  all: null,
  current: null
}

export const mutations = {
  FETCH_PAGINATED (state, roles) {
    state.paginated = roles
  },
  FETCH_ALL (state, roles) {
    state.all = roles
  },
  FETCH (state, role) {
    state.current = role
  }
}

export const actions = {
  async fetchPaginated ({ commit }) {
    const { data } = await api.get('roles')
    commit('FETCH_PAGINATED', { paginated: data })
  },

  async fetchAll ({ commit }) {
    const { data } = await api.get('roles?fetchAll')
    commit('FETCH_ALL', { all: data })
  },
  async fetch ({ commit }, id) {
    const { data } = await api.get('roles/' + id)
    commit('FETCH', { current: data })
  }
}

export const getters = {
  all: state => state.all,
  paginated: state => state.paginated,
  current: state => state.current
}
