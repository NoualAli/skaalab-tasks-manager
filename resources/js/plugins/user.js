import { ls_get } from './crypto'
/* eslint-disable eqeqeq */
export function user() {
    return JSON.parse(ls_get('user')) || []
}

export function getRoles() {
    return JSON.parse(ls_get('roles')) || []
}

export function getRole() {
    return ls_get('role') || null
}

export function getPermissions() {
    return JSON.parse(ls_get('permissions')) || []
}



export function isAbleTo($abilities = String | Array) {
    const can = []
    const userPermissions = getPermissions()
    if (typeof $abilities == 'string') {
        $abilities = ($abilities || '').split(/\s*,\s*/)
        $abilities.forEach(ability => {
            can.push(userPermissions.includes(ability.trim()))
        })
        return can.some(value => value === true)
    } else if (Array.isArray($abilities)) {
        $abilities.forEach($ability => {
            can.push(userPermissions.includes($ability.trim()))
        })
        return can.includes(true)
    } else if (typeof $abilities === 'object') {
        for (const key in $abilities) {
            if (Object.hasOwnProperty.call($abilities, key)) {
                const $ability = $abilities[ key ].trim()
                can.push(userPermissions.includes($ability))
            }
        }
        return can.includes(true)
    }
}

export function hasRole(roles = String | Array, status) {
    const userRole = status || getRole()
    if (typeof roles === 'string') {
        roles = (roles.toLowerCase() || '').split(/\s*,\s*/)
        return roles.includes(userRole)
    } else if (typeof roles === 'array') {
        return roles.includes(userRole)
    } else if (typeof roles === 'object') {
        return roles.includes(userRole)
    }
}
