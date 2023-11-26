import store from '~/store'
export function user() {
    return store.getters[ 'auth/user' ]
}

export const helpersMixin = {
    computed: {
        currentYear() {
            return new Date().getFullYear()
        },
    },
    methods: {
        addDays(startingDay, days) {
            // Set the starting day
            var startingDay = new Date(startingDay);

            // Add 15 days to the starting day
            var fifteenDaysLater = new Date(startingDay.getTime() + (days * 24 * 60 * 60 * 1000));

            // Format the date as desired (e.g., YYYY-MM-DD)
            return fifteenDaysLater.toISOString().split('T')[ 0 ];
        },
        /**
         * Ask user to confirm page leaving
         *
         * @param {Closure} next
         * @param {Object} config
         */
        askBeforeLeave(next, config = { icon: 'question', title: '', message: 'Vous n\'avez pas enregistré vos changements, êtes-vous sûr de vouloir quitter la page ?', showConfirmButton: true, showCancelButton: true }) {

            this.$swal.routeLeaveConfirm(config).then(action => {
                if (action.isConfirmed) {
                    return next();
                } else {
                    return next(false);
                }
            })
        }
    }
}
