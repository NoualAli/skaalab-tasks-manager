import Echo from 'laravel-echo';
import * as $swal from '~/plugins/swal'
import store from "~/store"
import { user } from '~/plugins/user'

window.user = user()

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.Laravel.app_host + ":" + window.Laravel.echo_port,
    Authorization: store.getters[ 'auth/token' ],
    auth: {
        headers: {
            Authorization: 'Bearer' + store.getters[ 'auth/token' ]
        }
    }
});


// Request permission to show desktop notifications
window.addEventListener('DOMContentLoaded', () => {
    Notification.requestPermission()
    if (!Notification) {
        $swal.alert_error('Your browser doesn\'t support notifications.')
    }

    /**
     * Public channels
     */
    window.Echo.channel('task-updated')
        .listen('.TaskUpdated', (e) => {
            console.log(e.user, window.user.id);
            if (e.user == window.user.id) {
                $swal.alert_success(e.title, 'Succès', true).then(action => {
                    if (action.isConfirmed) {
                        window.location.reload()
                    }
                })
            }
        });
    window.Echo.channel('task-created')
        .listen('.TaskCreated', (e) => {
            console.log(e.user, window.user.id);
            if (e.user == window.user.id) {
                $swal.alert_success(e.title, 'Succès', true).then(action => {
                    if (action.isConfirmed) {
                        window.location.reload()
                    }
                })
            }
        });

    window.Echo.channel('task-validated')
        .listen('.TaskValidated', (e) => {
            console.log(e.user, window.user.id);
            if (e.user == window.user.id) {
                $swal.alert_success(e.title, 'Succès', true).then(action => {
                    if (action.isConfirmed) {
                        window.location.reload()
                    }
                })
            }
        });

    window.Echo.channel('task-deleted')
        .listen('.TaskDeleted', (e) => {
            console.log(e.user, window.user.id);
            if (e.user == window.user.id) {
                $swal.alert_success(e.title, 'Succès', true).then(action => {
                    if (action.isConfirmed) {
                        window.location.reload()
                    }
                })
            }
        });

    /**
     * Private channels
     */
    window.Echo.private('task-assigned-' + window.user.id)
        .listen('.TaskAssigned', (e) => {
            console.log(e.user, window.user.id);
            if (e.user == window.user.id) {
                $swal.alert_success(e.title, 'Succès', true).then(action => {
                    if (action.isConfirmed) {
                        window.location.reload()
                    }
                })
            }
        });

    window.Echo.private('task-assignation-removed-' + window.user.id)
        .listen('.TaskAssignationRemoved', (e) => {
            console.log(e.user, window.user.id);
            if (e.user == window.user.id) {
                $swal.alert_success(e.title, 'Succès', true).then(action => {
                    if (action.isConfirmed) {
                        window.location.reload()
                    }
                })
            }
        });
    window.Echo.private('task-validated-' + window.user.id)
        .listen('.PrivateTaskValidated', (e) => {
            console.log(e.user, window.user.id);
            if (e.user == window.user.id) {
                $swal.alert_success(e.title, 'Succès', true).then(action => {
                    if (action.isConfirmed) {
                        window.location.reload()
                    }
                })
            }
        });
    window.Echo.private('task-deleted-' + window.user.id)
        .listen('.PrivateTaskDeleted', (e) => {
            console.log(e.user, window.user.id);
            if (e.user == window.user.id) {
                $swal.alert_success(e.title, 'Succès', true).then(action => {
                    if (action.isConfirmed) {
                        window.location.reload()
                    }
                })
            }
        });
    console.log(window.Echo,);
})
