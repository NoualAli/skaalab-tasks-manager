import store from '~/store'

export default (to, from, next) => {
  const role = store.getters['auth/user'].role_name
  if (role !== 'user') {
    alert('alert user  redirect to home ')

    next({ name: 'home' })
  } else {
    alert('alert user  redirect to next ')

    next()
  }
}
