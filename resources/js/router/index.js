
import store from '~/store'
import routes from './routes'
import { createRouter, createWebHistory } from 'vue-router'
import { sync } from 'vuex-router-sync'
import Cookies from 'js-cookie'
import { nextTick } from 'vue'

// The middleware for every page of the application.
// const globalMiddleware = []
const globalMiddleware = ['check-auth']

// Load middleware modules dynamically.
const routeMiddleware = resolveMiddleware(
  require.context('~/middleware', false, /.*\.js$/)
)

const router = createRouterWrapper()

sync(store, router)

/**
 * Create a new router instance.
 *
 * @return {Router}
 */
function createRouterWrapper () {
  const router = createRouter({
    history: createWebHistory(),
    // scrollBehavior,
    routes
  })

  // router.beforeEach(beforeEach)
  router.beforeEach(beforeEach)
  router.beforeResolve(beforeResolve)
  router.afterEach(afterEach)
  // router.scrollBehavior
  return router
}
function beforeResolve (to, from, next) {
  // console.log(to)
  const layout = to?.matched[0]?.components?.default?.layout
  to.meta.layout = layout || ''
  next()
}
/**
 * Global router guard.
 *
 * @param {Route} to
 * @param {Route} from
 * @param {Function} next
 */
async function beforeEach (to, from, next) {
  let components = []
  try {
    // Get the matched components and resolve them.
    const { matched } = await router.resolve(to)
    components = await resolveComponents(matched.map(record => record.components.default))
  } catch (error) {
    if (/^Loading( CSS)? chunk (\d)+ failed\./.test(error.message)) {
      window.location.reload(true)
      return
    }
  }

  if (components.length === 0) {
    return next()
  }
  //   console.log(components[components.length - 1].loading)
  // Start the loading bar.
  if (components[components.length - 1]?.loading !== false) {
    await nextTick()
    // await useRouter()?.appContext?.app?.$nextTick()
  }

  // Get the middleware for all the matched components.
  const middleware = getMiddleware(components)

  // Load async data for all the matched components.
  await asyncData(components)
  // Call each middleware.
  await callMiddleware(middleware, to, from, (...args) => {
    return next(...args)
  })
}

/**
 * @param  {Array} components
 * @return {Promise<void>}
 */
async function asyncData (components) {
  for (let i = 0; i < components.length; i++) {
    const component = components[i]

    if (!component.asyncData) {
      continue
    }

    const dataFn = component.data

    try {
      const asyncData = await component.asyncData()

      component.data = function () {
        return {
          ...(dataFn ? dataFn.apply(this) : {}),
          ...asyncData
        }
      }
    } catch (e) {
      component.layout = 'error'

      console.error('Failed to load asyncData', e)
    }
  }
}

/**
 * Global after hook.
 *
 * @param {Route} to
 * @param {Route} from
 * @param {Function} next
 */
async function afterEach (to, from, next) {
  // await useRouter()?.appContext?.app?.$nextTick()
  // await nextTick()
  Cookies.set('previous_url', from)
  // router.isReady()
}

/**
 * Call each middleware.
 *
 * @param {Array} middleware
 * @param {Route} to
 * @param {Route} from
 * @param {Function} next
 */
async function callMiddleware (middleware, to, from, next) {
  const stack = middleware.reverse()
  const _next = async (...args) => {
    // Stop if "_next" was called with an argument or the stack is empty.
    if (args.length > 0 || stack.length === 0) {
      if (args.length > 0) {
        // router.app.$loading.finish() stop loading mechanique we are invokking a stop methode
        // router.isReady()
        // router?.app?.config?.globalProperties?.$loading?.finish();    // router.isReady()
      }
      return next(...args)
    }

    const { middleware, params } = parseMiddleware(stack.pop())
    // console.log(middleware)
    if (typeof middleware === 'function') {
      await middleware(to, from, _next, params)
    } else if (routeMiddleware[middleware]) {
      // console.log(routeMiddleware)
      await routeMiddleware[middleware](to, from, _next, params)
    } else {
      throw Error(`Undefined middleware [${middleware}]`)
    }
  }
  await _next()
}

/**
 * @param  {String|Function} middleware
 * @return {Object}
 */
function parseMiddleware (middleware) {
  if (typeof middleware === 'function') {
    return { middleware }
  }

  const [name, params] = middleware.split(':')

  return { middleware: name, params }
}

/**
 * Resolve async components.
 *
 * @param  {Array} components
 * @return {Array}
 */
function resolveComponents (components) {
  return Promise.all(components.map(component => {
    return typeof component === 'function' ? component() : component
  }))
}

/**
 * Merge the the global middleware with the components middleware.
 *
 * @param  {Array} components
 * @return {Array}
 */
function getMiddleware (components) {
  const middleware = [...globalMiddleware]
  components.filter(c => c.middleware).forEach(component => {
    if (Array.isArray(component.middleware)) {
      middleware.push(...component.middleware)
    } else {
      middleware.push(component.middleware)
    }
  })
  return middleware
}

/**
 * Scroll Behavior
 *
 * @link https://router.vuejs.org/en/advanced/scroll-behavior.html
 *
 * @param  {Route} to
 * @param  {Route} from
 * @param  {Object|undefined} savedPosition
 * @return {Object}
 */

/**
 * @param  {Object} requireContext
 * @return {Object}
 */
function resolveMiddleware (requireContext) {
  return requireContext.keys()
    .map(file =>
      [file.replace(/(^.\/)|(\.js$)/g, ''), requireContext(file)]
    )
    .reduce((guards, [name, guard]) => (
      { ...guards, [name]: guard.default }
    ), {})
}

export default router
