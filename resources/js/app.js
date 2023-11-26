import { createApp } from 'vue'
import store from '~/store'
import router from '~/router'
import { helpersMixin, user } from './plugins/helpers'
import api from './plugins/api'
import * as swal from './plugins/swal'
import '~/plugins'
// import { LoadingPlugin } from 'vue-loading-overlay'
import { createMetaManager as createVueMetaManager, defaultConfig, plugin as pluginVueMeta } from 'vue-meta'
import Swal from 'sweetalert2'
import Vue3Breadcrumbs from 'vue-3-breadcrumbs'
import App from '~/components/App'
import '~/components'
import './plugins/charts'
import { useComponents } from './components'
import { aclMixin, defineDirectives } from './plugins/acl.js'
import '~/store/global'

window.Swal = Swal
window.api = api
window.user = user
const app = createApp(App)
app.use(store)
app.use(router)
app.use(createVueMetaManager(false, { ...defaultConfig, meta: { tag: 'meta', nameless: true } })) // gotta update meta and use it differently
app.use(pluginVueMeta) // gotta update meta and use it differently
// app.use(LoadingPlugin, {
//     'is-full-page': true
// }, {})

app.use(Vue3Breadcrumbs, { includeComponent: true })

app.config.globalProperties.$api = api
app.config.globalProperties.$swal = swal
// console.log(api)
app.mixin(aclMixin)
app.mixin(helpersMixin)

useComponents(app)
defineDirectives(app)
app.config.performance = true
app.config.errorHandler = (err, vm, info) => {
    // handle error
    // console.error(err)
    // console.log(vm)
    // console.log(info)
}
app.mount('#app')

require('./bootstrap')
require('./echo-realtime')
