function page(path) {
    return () => import(`~/pages/${path}`).then(m => m.default || m)
}

export default [
    /**
     * Home / Dashboard
     */
    {
        path: '/',
        name: 'home',
        component: page('home.vue'),
        meta: {
            breadcrumb: {
                label: 'Tableau de bord'
            }
        }
    },

    {
        path: '/admin',
        name: 'admin-dashboard',
        component: page('admin_dashboard.vue'),
        meta: {
            breadcrumb: false
        }
    },

    /**
     * Uploaded and generated files
     */
    {
        path: '/admin/files',
        name: 'admin-server-files',
        component: page('admin/files/index.vue'),
        meta: {
            breadcrumb: {
                label: 'Fichiers'
            }
        }
    },

    /**
     * Auth
     */
    { path: '/login', name: 'login', component: page('auth/login.vue') },
    { path: '/password/new', name: 'password.new', component: page('auth/password/new.vue') },

    /**
     * Profile settings
     */
    {
        path: '/profile',
        name: 'profile',
        component: page('settings/profile.vue'),
        meta: {
            breadcrumb: {
                label: 'Profil'
            }
        }
    },

    /**
     * Users
     */
    {
        path: '/admin/users',
        name: 'users-index',
        component: page('admin/users/index.vue'),
        meta: {
            breadcrumb: {
                parent: '',
                label: 'Utilisateurs'
            }
        }
    },
    {
        path: '/admin/users/create',
        name: 'users-create',
        component: page('admin/users/create.vue'),
        meta: {
            breadcrumb: {
                label: 'Nouvel utilisateur',
                parent: 'users-index'
            }
        }
    },
    {
        path: '/admin/users/edit/:user',
        name: 'users-edit',
        component: page('admin/users/edit.vue'),
        meta: {
            breadcrumb: {
                label: 'Edition utilisateur',
                parent: 'users-index'
            }
        }
    },

    /**
     * Roles
     */
    {
        path: '/admin/roles',
        name: 'roles-index',
        component: page('admin/roles/index.vue'),
        meta: {
            breadcrumb: {

                label: 'Rôles'
            }
        }
    },
    {
        path: '/admin/roles/create',
        name: 'roles-create',
        component: page('admin/roles/create.vue'),
        meta: {
            breadcrumb: {
                label: 'Nouveau rôle',
                parent: 'roles-index'
            }
        }
    },
    {
        path: '/admin/roles/edit/:role',
        name: 'roles-edit',
        component: page('admin/roles/edit.vue'),
        meta: {
            breadcrumb: {
                label: 'Edition rôle',
                parent: 'roles-index'
            }
        }
    },

    /**
     * Notifications center
     */
    {
        path: '/notifications',
        name: 'notifications',
        component: page('notifications/index'),
        meta: {
            breadcrumb: {
                label: 'Centre de notification'
            }
        }
    },

    /**
     * Tasks
     */
    {
        path: '/tasks',
        name: 'tasks-index',
        component: page('tasks/index.vue'),
        meta: {
            breadcrumb: {
                parent: '',
                label: 'Tâches'
            }
        }
    },
    {
        path: '/tasks/create',
        name: 'tasks-create',
        component: page('tasks/create.vue'),
        meta: {
            breadcrumb: {
                label: 'Nouvelle tâche',
                parent: 'tasks-index'
            }
        }
    },
    {
        path: '/tasks/edit/:task',
        name: 'tasks-edit',
        component: page('tasks/edit.vue'),
        meta: {
            breadcrumb: {
                label: 'Edition tâche',
                parent: 'tasks-index'
            }
        }
    },

    /**
     * Errors
     */
    { path: '/:pathMatch(.*)*', name: '404', component: page('errors/404.vue') }

]
