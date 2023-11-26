<template>
    <router-link class="sidebar-item" :to="{ name: route }">
        <i :class="icon" class="sidebar-icon"></i>
        <span class="sidebar-icon_text">
            {{ label }}
        </span>
    </router-link>
</template>

<script>
export default {
    name: 'NLSidebarItem',
    props: {
        route: { type: String, required: true },
        iconPrefix: { type: String, default: "las" },
        iconName: { type: String, required: false, default: "" },
        label: { type: String, required: true }
    },
    computed: {
        icon() {
            return this.iconName !== "" && this.iconName !== null ? `${this.iconPrefix} ${this.iconName}` : 'las la-question-circle'
        }
    }
}
</script>
