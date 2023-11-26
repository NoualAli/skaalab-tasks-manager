/* eslint-disable camelcase */
import Swal from 'sweetalert2'
/**
 * @param string icon
 * @param string title
 * @param string message
 *
 * @return {any}
 */
export function confirm({ icon = 'question', title = '', message = '', showConfirmButton = true, showCancelButton = true }) {
    return Swal.fire({
        icon,
        title,
        position: 'center',
        confirmButtonColor: '#00C851',
        cancelButtonColor: '#CC0000',
        confirmButtonText: 'Ok',
        cancelButtonText: 'Annuler',
        showConfirmButton,
        showCancelButton,
        html: message
    })
}

/**
 * @param string icon
 * @param string title
 * @param string message
 *
 * @return {any}
 */
export function routeLeaveConfirm({ icon = 'question', title = '', message = 'Vous n\'avez pas enregistré vos changements, êtes-vous sûr de vouloir quitter la page ?', showConfirmButton = true, showCancelButton = true }) {
    return Swal.fire({
        icon,
        title,
        position: 'center',
        confirmButtonColor: '#00C851',
        cancelButtonColor: '#CC0000',
        showConfirmButton,
        showCancelButton,
        cancelButtonText: 'Annuler',
        confirmButtonText: 'Oui',
        text: message
    })
}

/**
 * @param string icon
 * @param string title
 * @param string message
 *
 * @return {any}
 */
export function alert_success(message = '', title = 'Succès', showCancelButton = false) {
    return confirm({ icon: 'success', title, message, showCancelButton })
}

/**
 * @param string icon
 * @param string title
 * @param string message
 *
 * @return {any}
 */
export function alert_error(message = 'Une erreur est survenue', title = 'Erreur') {
    return confirm({ icon: 'error', title, message, showCancelButton: false })
}

/**
 * @param string icon
 * @param string title
 * @param string message
 *
 * @return {any}
 */
export function confirm_destroy(message = 'Voulez-vous vraiment supprimer cette ressource ?') {
    return confirm({ icon: 'question', title: 'Confirmation', message })
}

/**
 * @param string icon
 * @param string title
 * @param string message
 *
 * @return {any}
 */
export function confirm_update(message = 'Voulez-vous enregistrer vos modifications ?') {
    return confirm({ icon: 'question', title: 'Confirmation', message })
}

/**
 * @param string icon
 * @param string title
 * @param string message
 *
 * @return {any}
 */
export function toast({ icon = 'question', title = '', message = '', target = '' }) {
    Swal.fire({
        toast: true,
        icon,
        title,
        position: 'bottom-right',
        timer: 5000,
        showCloseButton: true,
        timerProgressBar: true,
        showConfirmButton: false,
        text: message,
        width: '27rem',
        didOpen: (toast) => {
            if (target) {
                toast.classList.add('is-clickable')
            }
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
            toast.addEventListener('click', () => {
                if (target !== '' && target !== null) {
                    window.location.href = target
                    toast.remove(); // Remove the toast after it's clicked
                }
            });
        }
    })
}

/**
 * @param string icon
 * @param string title
 * @param string message
 *
 * @return {any}
 */
export function toast_success(message = '', title = 'Succès', target = '') {
    return toast({ icon: 'success', title, message, target })
}

/**
 * @param string icon
 * @param string title
 * @param string message
 *
 * @return {any}
 */
export function toast_error(message = 'Une erreur est survenue', title = 'Erreur', target = '') {
    return toast({ icon: 'error', title, message, target })
}

export function alert_status(error) {
    const code = error.response.status
    const statusText = error.response.statusText
    const title = code + ' ' + statusText
    const message = code !== 422 ? error.response.data.message : 'Les données fournies sont invalides.'
    confirm({ icon: 'error', title, message, showCancelButton: false })
}

export function loading(progress, message = 'Chargement en cours...') {
    Swal.fire({
        // title: 'Auto close alert!',
        html: message + ' <b></b>',
        // timer: 2000,
        // timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
            const b = Swal.getHtmlContainer().querySelector('b')
            setInterval(() => {
                b.textContent = progress + ' %'
            }, 100)
        }
    }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log('I was closed by the timer')
        }
    })
}

export function catchError(error) {
    if (error.response) {
        // la requête a été faite et le code de réponse du serveur n’est pas dans
        // la plage 2xx
        alert_error(error?.response?.data.message)
        console.log(error.response.data);
        console.log(error.response.status);
        console.log(error.response.headers);
    } else if (error.request) {
        // la requête a été faite mais aucune réponse n’a été reçue
        // `error.request` est une instance de XMLHttpRequest dans le navigateur
        // et une instance de http.ClientRequest avec node.js
        console.log(error.request);
    } else {
        // quelque chose s’est passé lors de la construction de la requête et cela
        // a provoqué une erreur
        console.log('Error', error.message);
    }
    alert_error("Quelque chose s'est mal passé. Veuillez réessayer, si le problème persiste, veuillez contacter l'administrateur du serveur.")
    console.log(error.config);
}
