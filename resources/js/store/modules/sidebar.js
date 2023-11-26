export const state = {
  showSidebar: true
}

// getters
export const getters = {
  showSidebar: state => state.showSidebar
}

export const mutations = {
  toggleSidebar (state) {
    state.showSidebar = !state.showSidebar
  }
}

export const actions = {
  toggleSidebar ({ commit }) {
    commit('toggleSidebar')
  }
}
