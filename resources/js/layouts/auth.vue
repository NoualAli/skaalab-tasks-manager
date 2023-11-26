<template>
    <div class="global-auth-container">
        <div id="particles-js"></div>
        <div class="auth-container">
            <main class="main-container">
                <child />
            </main>
            <div class="footer-container">
                <p>
                    VERSION {{ app_version }} &copy; {{ currentYear }} - Tous droits réservés - NLDEV
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import 'particles.js/particles.js'
import * as APP from '~/store/global/version'
/* particlesJS.load(@dom-id, @path-json, @callback (optional)); */
export default {
    name: 'AuthLayout',
    created() {
        particlesJS.load('particles-js', 'particles.json', function () {
            // console.log('callback - particles-js config loaded');
        });
    },
    computed: {
        app_version() {
            return APP.CURRENT_VERSION
        }
    }
}
</script>
