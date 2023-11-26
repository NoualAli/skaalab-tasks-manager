export const state = {
  layout: null
}

export const mutations = {
  SET_LAYOUT (state, data) {
    state.layout = data
  }
}

export const actions = {
  async setLayout ({ commit }, layout) {
    try {
      const requireContext = require.context('~/layouts', false, /.*\.vue$/)

      const layouts = requireContext.keys()
        .map(file =>
          [file.replace(/(^.\/)|(\.vue$)/g, ''), requireContext(file)]
        )
        .reduce((components, [name, component]) => {
          components[name] = component.default || component
          return components
        }, {})
      if (!layout || !layouts[layout]) {
        layout = 'default'
      }
      console.log(layouts[layout])
      commit('SET_LAYOUT', { layout: layouts[layout] })
    } catch (error) {
      console.error(error)
    }
  }
}

export const getters = {
  layout: state => state.layout
}
