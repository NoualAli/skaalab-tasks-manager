<template>
    <div class="pagination my-6 grid">
        <div class="col-12 col-lg-2 d-flex align-center justify-center">
            <div class="grid gap-2 w-100">
                <div class="col-12 col-lg-4">
                    <NLSelect name="per_page" :options="perPageChoices" v-model="perPage" class="disable-clear-value" />
                </div>
                <div class="col-12 col-lg-8 d-flex align-center justify-center gap-2">
                    <span v-if="total">
                        {{ total }}
                    </span>
                    <span>
                        de {{ from }} à {{ to }}
                    </span>
                </div>
            </div>
        </div>
        <!-- .col-8.col-lg-7 -->
        <div class="col-12 col-lg-1 d-flex justify-center align-center">
            <button @click="handlePageChange(previousLink)" class="btn mx-2" v-if="previousLink">
                Précédent
            </button>
        </div>
        <div class="col-12 col-lg-8 d-flex align-center justify-center">
            <button @click="handlePageChange(link?.url)" class="btn is-radius mx-2" :class="{ 'is-active': link.active }"
                v-for="(link, index) in numericLinks" :key="'numeric-link-' + index" :id="'numeric-link-' + index"
                :disabled="link.active || link.label == '...'" v-if="numericLinks?.length > 1">
                {{ link.label }}
            </button>
        </div>
        <div class="col-12 col-lg-1 d-flex justify-center align-center">
            <button @click="handlePageChange(nextLink)" class="btn mx-2" v-if="nextLink">
                Suivant
            </button>
        </div>
    </div>
</template>
<script>
export default {
    name: 'TablePagination',
    empits: [ 'pageChange', 'perPageChange' ],
    props: {
        data: { type: [ Object, null ], required: true },
        perPageChoices: { type: Array, required: false, default: () => [ { id: 10, label: '10' }, { id: 25, label: '25' }, { id: 50, label: '50' }, { id: 100, label: '100' }, { id: 250, label: '250' } ] },
    },
    watch: {
        perPage(newValue, oldValue) {
            if (newValue !== oldValue) {
                this.$emit('perPageChange', newValue)
            }
        },
    },
    data() {
        return {
            perPage: 10,
        }
    },
    methods: {
        /**
         * Handle page change
         *
         * @param {String}
         */
        handlePageChange(url) {
            let page = url?.split('?')[ 1 ].split('=')[ 1 ]
            this.$emit('pageChange', page)
        },
    },
    computed: {
        /**
         * Get url from data
         */
        url() {
            return this.data?.meta?.path
        },

        /**
         * Get amount of total data
         */
        total() {
            return this.data?.meta?.total ?? 0
        },

        /**
         * Get length of showed data
         */
        count() {
            return this.data?.data?.length
        },

        /**
         * Show viewed data (from part)
         */
        from() {
            return this.data?.meta?.from
        },

        /**
         * Show viewed data (to part)
         */
        to() {
            return this.data?.meta?.to
        },

        /**
         * Get links list
         */
        links() {
            return this.data?.meta?.links
        },

        /**
         * Show numeric links
         */
        numericLinks() {
            return this.data?.meta?.links.filter(link => link.label !== '&laquo; Précédent' && link.label !== 'Suivant &raquo;')
        },

        /**
         * Show previous link
         */
        previousLink() {
            return this.data?.links?.prev
        },

        /**
         * Show next link
         */
        nextLink() {
            return this.data?.links?.next
        },
        /**
         * Check if should show pagination or not
         */
        showPagination() {
            return this.total > this.data?.meta?.per_page
        },
    },
}
</script>
