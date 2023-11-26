/* eslint-disable camelcase */
import { Form } from 'vform'
import axios from 'axios'
import Swal from 'sweetalert2'
import store from '~/store'
import * as swal from '../plugins/swal'
import { encryptData } from './crypto'


const instance = axios.create({
    headers: {

        'Content-Type': 'application/json',
        Accept: 'application/json'

    },
    baseURL: '/api/',
})

instance.interceptors.response.use(response => response, error => {
    const status = error?.response?.status
    let message = error?.response?.data?.message
    const title = status + ' ' + error?.response?.statusText
    if (status === 401) {
        swal.alert_error(message, "Erreur 401")
            .then(() => {
                store.commit('auth/LOGOUT')
            })
    }
    if (status === 422) {
        let totalErrors = Object.entries(error?.response?.data?.errors).length
        let word = totalErrors > 1 ? 'problèmes' : 'problème'
        message = `Il y'a ${totalErrors} ${word} avec vos entrées`
        swal.toast_error(message)
    }

    if (status === 404) {
        window.location.href = '/404'
    }
    if (status === 403 && store.getters[ 'auth/check' ]) {
        swal.alert_error(message, title)
            .then(() => {
                store.commit('auth/LOGOUT')
                location.reload()
            })
    }

    if (status >= 500) {
        serverError(error.response)
    }
    return Promise.reject(error)
})

let serverErrorModalShown = false
async function serverError(response) {
    if (serverErrorModalShown) {
        return
    }

    if ((response.headers[ 'content-type' ] || '').includes('text/html')) {
        const iframe = document.createElement('iframe')

        if (response.data instanceof Blob) {
            iframe.srcdoc = await response.data.text()
        } else {
            iframe.srcdoc = response.data
        }

        Swal.fire({
            html: iframe.outerHTML,
            showConfirmButton: false,
            customClass: { container: 'server-error-modal' },
            didDestroy: () => { serverErrorModalShown = false },
            grow: 'fullscreen',
            padding: 0
        })

        serverErrorModalShown = true
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Quelque chose s\'est mal passé ! Veuillez réessayer.',
            reverseButtons: true,
            confirmButtonText: 'ok',
            cancelButtonText: 'Annuler'
        })
    }
}

instance.interceptors.request.use(request => {
    const token = store.getters[ 'auth/token' ]
    if (token) {
        // eslint-disable-next-line dot-notation
        request.headers[ 'Authorization' ] = `Bearer ${token}`
    }

    const locale = store.getters[ 'lang/locale' ]
    if (locale) {
        request.headers[ 'Accept-Language' ] = locale
    }

    // request.headers['X-Socket-Id'] = Echo.socketId()
    // Encrypt the request data
    // if (request.data) {
    //     request.data = encryptData(JSON.stringify(request.data));
    //     console.log(request.data);
    // }
    return request
})


Form.axios = instance

export default Form
