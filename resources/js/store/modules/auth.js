import api from '../../plugins/api'
import Cookies from 'js-cookie'
import { ls_set } from '../../plugins/crypto'

// state
export const state = {
    user: null,
    logins: null,
    token: Cookies.get('token')
}

// getters
export const getters = {
    user: state => state.user,
    token: state => state.token,
    check: state => state.user !== null,
    logins: state => state.logins
}

// mutations
export const mutations = {
    SAVE_TOKEN(state, { token, remember }) {
        state.token = token
        Cookies.set('token', token, { expires: remember ? 365 : null })
    },

    FETCH_USER_SUCCESS(state, { user }) {
        state.user = user
    },

    FETCH_USER_FAILURE(state) {
        state.token = null
        Cookies.remove('token')
    },

    LOGOUT(state) {
        state.user = null
        state.token = null

        Cookies.remove('token')
    },

    UPDATE_USER(state, { user }) {
        state.user = user
    },

    FETCH_LOGINS(state, { logins }) {
        state.logins = logins
    }
}

// actions
export const actions = {
    saveToken({ commit, dispatch }, payload) {
        commit('SAVE_TOKEN', payload)
    },

    async fetchUser({ commit }) {
        try {
            const { data } = await api.get('user')
            const user = { ...data }
            delete user.permissions_arr
            delete user.authorizations
            ls_set('user', JSON.stringify(user))
            ls_set('role', user?.role?.code)
            ls_set('permissions', JSON.stringify(data.role.permissions.map(permission => permission.code)))
            delete user.role

            commit('FETCH_USER_SUCCESS', { user: data })
        } catch (e) {
            console.error(e)
            commit('FETCH_USER_FAILURE')
        }
    },

    async fetchCurrentUserLogins({ commit }) {
        try {
            const { data } = await api.get('users/logins/history')
            commit('FETCH_LOGINS', { logins: data })
        } catch (e) {
            console.error(e)

            this.$swal.alert_error()
        }
    },

    updateUser({ commit }, payload) {
        commit('UPDATE_USER', payload)
    },

    async logout({ commit }) {
        try {
            await api.post('logout').then(() => {
                localStorage.removeItem('user')
                localStorage.removeItem('role')
                localStorage.removeItem('permissions')
            })
        } catch (e) { }

        commit('LOGOUT')
    }
}
