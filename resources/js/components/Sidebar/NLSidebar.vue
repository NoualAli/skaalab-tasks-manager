<template>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="brand"></div>
            <div class="active-brand"></div>
        </div>
        <div class="sidebar-body">
            <div class="sidebar-title" />
            <NLSidebarItem label="Tableau de bord" route="home" iconName="la-tachometer-alt" />
            <NLSidebarItem label="Utilisateurs" route="users-index" iconName="la-users" v-if="can('view_users')" />
            <NLSidebarItem label="Rôles" route="roles-index" iconName="la-user-shield" v-if="can('view_roles')" />
            <NLSidebarItem label="Tâches" route="tasks-index" iconName="la-tasks" v-if="can('view_tasks')" />
        </div>

        <div class="sidebar-footer">
            <a href="logout" @click.prevent="logout" class="sidebar-item logout-btn" :class="{ 'is-loading': isLogout }"
                v-if="!isLogout">
                <i class="las la-sign-out-alt sidebar-icon" v-if="!isLogout"></i>
                <i class="las la-spinner la-spin sidebar-icon" v-else></i>
                <span class="sidebar-icon_text" v-if="!isLogout">
                    Déconnexion
                </span>
                <span class="sidebar-icon_text" v-else>Déconnexion en cours</span>
            </a>
            <div class="sidebar-item logout-btn" :class="{ 'is-loading': isLogout }" v-else>
                <i class="las la-spinner la-spin sidebar-icon"></i>
                <span class="sidebar-icon_text">Déconnexion en cours</span>
            </div>
        </div>
    </div>
</template>

<script>
// import { Store } from 'vuex'
import NLSidebarItem from './NLSidebarItem.vue'
import NLSidebarSubmenu from './NLSidebarSubmenu.vue'
export default {
    name: "NLSidebar",
    components: { NLSidebarItem, NLSidebarSubmenu },
    data() {
        return {
            isLogout: false
        }
    },
    methods: {
        async logout() {
            this.isLogout = true
            // Log out the user.
            await this.$store.dispatch('auth/logout')

            localStorage.clear()
            // Redirect to login.
            this.$router.push({ name: 'login' })
        }
    }
}
</script>
