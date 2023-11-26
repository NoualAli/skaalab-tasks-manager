/* eslint-disable camelcase */
import Swal from 'sweetalert2'
import axios from 'axios'
import store from '~/store'
import * as swal from '../plugins/swal'
const api = axios.create({
    headers: {

        'Content-Type': 'application/json',
        Accept: 'application/json'

    },
    baseURL: '/api/',
    transformRequest: formData => formData
})
api.interceptors.response.use(response => response, error => {
    const status = error?.response?.status
    const message = error?.response?.data?.message
    const title = status + ' ' + error?.response?.statusText
    if (status === 401) {
        swal.alert_error('Vous avez dépassé le délai accordé à votre session, cette dernière a expiré, veuillez vous reconnecter de nouveau', '401 Session expirée')
            .then(() => {
                store.commit('auth/LOGOUT')
                window.location.href = '/login'
            })
    }

    if (status === 423) {
        swal.alert_error(message, title).then(() => window.location.href = '/')
    }

    if (status === 404) {
        window.location.href = '/404'
    }
    if (status === 403 && store.getters[ 'auth/check' ]) {
        swal.alert_error(message, title)
            .then(() => {
                store.commit('auth/LOGOUT')
                window.location.href = '/login'
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

api.interceptors.request.use(request => {
    const token = store.getters[ 'auth/token' ]
    if (token) {
        // eslint-disable-next-line dot-notation
        request.headers[ 'Authorization' ] = `Bearer ${token}`
    }

    const locale = store.getters[ 'lang/locale' ]
    if (locale) {
        request.headers[ 'Accept-Language' ] = locale
    }

    // Encrypt the request data
    // if (request.data) {
    //     request.data = encryptData(JSON.stringify(request.data));
    //     console.log(request.data);
    // }
    // request.headers['X-Socket-Id'] = Echo.socketId()

    return request
})

export default api
