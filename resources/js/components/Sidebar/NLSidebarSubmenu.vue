<template>
    <div class="sidebar-submenu" :class="{ 'expanded': expanded }">
        <a class="sidebar-item" :class="{ 'is-active': expanded }" @click="handle">
            <i :class="icon" class="sidebar-icon"></i>
            <span class="sidebar-icon_text">
                {{ label }}
            </span>
        </a>
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: 'NLSidebarSubmenu',
    props: {
        iconPrefix: { type: String, default: "las" },
        iconName: { type: String, required: false, default: "" },
        label: { type: String, required: true }
    },
    data() {
        return {
            expanded: false
        }
    },
    computed: {
        icon() {
            return this.iconName !== "" && this.iconName !== null ? `${this.iconPrefix} ${this.iconName}` : 'las la-question-circle'
        }
    },
    created() {
        let self = this;
        window.addEventListener('click', function (e) {
            if (!self.$el.contains(e.target)) {
                self.expanded = false
            }
        })
    },
    methods: {
        handle() {
            this.expanded = !this.expanded
        }
    }
}
</script>
