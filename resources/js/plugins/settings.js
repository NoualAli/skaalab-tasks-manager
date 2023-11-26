import Cookies from 'js-cookie'
// import store from '~/store'
import router from '../router'
export function setTitle(title, parent = null) {
  // store.dispatch('settings/setTitle', title)
  // console.log(getPreviousUrl());
  router.currentRoute.meta.breadcrumb.label = title
  // router.currentRoute.meta.info = title
  if (parent) {
    router.currentRoute.meta.breadcrumb.parent = parent
  }
  // router.replace({ query: { temp: Date.now() } })
}

export function getPreviousUrl() {
  try {
    return JSON.parse(Cookies.get('previous_url'))
  } catch (error) {

  }
}
