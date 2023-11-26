import { hasRole } from '../plugins/user'

export default (to, from, next, status) => {
  if (hasRole(['root', 'admin'])) {
    next()
  } else {
    next({ name: 'home' })
  }
}
