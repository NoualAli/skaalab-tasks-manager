<template>
    <Transition name="modal">
        <div class="modal" v-if="show" :class="{ 'active': isOpen, 'reduced': isReduced, 'expanded': isExpanded }">
            <div class="modal-overlay" @click.stop="close"></div>
            <div class="modal-card">
                <header class="modal-header">
                    <NLGrid extraClass="w-100">
                        <NLColumn lg="11" sm="11" md="11">
                            <h3>
                                <slot name="title" v-if="!isLoading"></slot>
                            </h3>
                        </NLColumn>
                        <NLColumn lg="1" sm="1" md="1" extraClass="d-flex align-center justify-end">
                            <NLFlex alignItems="center" lgJustifyContent="end" justifyContent="end" gap="3">
                                <i class="las la-expand icon modal-action-icon modal-expand-button"
                                    @click.prevent="handleExpansion" v-if="!isExpanded" title="Agrandir (alt + e)"></i>
                                <i class="las la-compress icon modal-action-icon modal-reduce-button"
                                    @click.prevent="handleExpansion" v-else title="Minimiser (alt + e)"></i>
                                <i class="las la-times icon modal-action-icon modal-close-button" @click.stop="close"
                                    title="Fermer (échape)"></i>
                            </NLFlex>
                        </NLColumn>
                    </NLGrid>
                </header>
                <main class="modal-body">
                    <slot v-if="!isLoading"></slot>
                    <div class="component-loader-container" v-else>
                        <div class="component-loader"></div>
                        <div class="component-loader-text">
                            Chargement en cours
                        </div>
                    </div>
                </main>
                <footer class="modal-footer" v-if="showFooter">
                    <NLColumn extraClass="d-flex justify-end align-center gap-2" v-if="!isLoading">
                        <slot name="footer"></slot>
                    </NLColumn>
                </footer>
            </div>
        </div>
    </Transition>
</template>

<script>
export default {
    name: 'NLModal',
    props: {
        show: { type: [ Boolean, Array, Object, null ], required: true },
        defaultMode: { type: Boolean, default: false },
        isLoading: { type: Boolean, default: false },
    },
    emits: [ 'isExpanded', 'isReduced', 'close' ],
    data() {
        return {
            isExpanded: false,
            isReduced: false,
            showFooter: false
        }
    },
    computed: {
        isOpen() {
            return this.show
        }
    },
    watch: {
        show() {
            if (!this.show) {
                window.removeEventListener('keydown', this)
            } else {
                window.addEventListener('keydown', (e) => {
                    this.handleKeyboard(e)
                })
            }
        }
    },
    created() {
        this.setShowSlots()
        this.isExpanded = this.defaultMode
    },
    beforeUpdate() {
        this.setShowSlots()
    },
    methods: {
        handleKeyboard(e) {
            if (e.key === 'e' && e.altKey) {
                e.preventDefault()
                this.handleExpansion()
            }
            if (e.key === 'Escape') {
                e.preventDefault()
                this.close()
            }
        },
        close() {
            this.$emit('close')
        },
        setShowSlots() {
            this.showFooter = this.$slots.footer
            // console.log(this.$slots.footer()[ 0 ].children.default());
        },
        handleExpansion() {
            this.isExpanded = !this.isExpanded
            this.$emit('isExpanded', this.isExpanded)
        },
        handleReduce() {
            this.isReduced = !this.isReduced
            this.$emit('isReduced', this.isReduced)
        }
    }
}
</script>
<style>
.modal-enter-active,
.modal-leave-active {
    transition: .4s ease-in-out !important;
}

.modal-enter-active .modal-overlay,
.modal-leave-active .modal-overlay {
    transition: .6s ease-in-out !important;
}

.modal-enter-from .modal-card,
.modal-leave-to .modal-card {
    transform: scale(0) !important;
}

.modal-enter-from .modal-overlay,
.modal-leave-to .modal-overlay {
    opacity: 0 !important;
}
</style>
