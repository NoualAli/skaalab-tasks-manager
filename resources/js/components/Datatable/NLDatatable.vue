<template>
    <Table :key="key" :filters="filters" :isLoading="isLoading" @search="(e) => this.loadData({ search: e })" :title="title"
        @toggleFilter="toggleFilterState" :isSearchable="isSearchable">
        <template #filter>
            <!-- Table filters -->
            <NLFilter :filters="filters" :isOpen="filterIsOpen" :customUrl="filtersCustomUrl" :urlPrefix="filtersUrlPrefix"
                :parentUrlPrefix="urlPrefix" @filtered="(e) => this.loadData({ filters: e })"
                @unloaded="() => this.loadData({ page: 1 })" />
        </template>
        <template #head>
            <!-- Table head -->
            <TableRow>
                <!-- Table header details label -->
                <TableHeader key="detailsRowHead" class="details_row-th" v-if="hasDetails" />

                <!-- Table headers labels -->
                <TableHeader :column="column" v-for="(column, index) in columns" :key="'th-' + index"
                    @sorted="(e) => this.loadData({ sorting: e })" />

                <!-- Table header actions label -->
                <TableHeader :key="'th-' + columns.length" :column="actionsColumn" v-if="hasActions" />
            </TableRow>
        </template>

        <template #body>
            <!-- Table body -->
            <TableRow v-if="!hasData">
                <!-- Showed if there are no data -->
                <TableData :colspan="noDataColspan" :align="'center'" v-if="!isLoading">
                    {{ noDataText }}
                </TableData>
            </TableRow>

            <template v-for="(value, row) in values" :key="'tr-' + row">
                <TableRow>
                    <!-- Table data toggle details -->
                    <TableData :key="'tr-' + row + '-td-details_row'" class="details_row-td"
                        @click="toggleDetailsRow(value)" v-if="hasDetails">
                        <i class="las la-angle-right" v-if="!detailIsActive(value)"></i>
                        <i class="las la-angle-down" v-else></i>
                    </TableData>

                    <!-- Table data -->
                    <TableData :item="value" :column="column" v-for="(column, index) in columns"
                        :key="'tr-' + row + '-td-' + index" />

                    <!-- Table data actions -->
                    <TableData :key="'tr-' + row + '-td-' + columns.length" :column="actionsColumn" :item="value">
                        <slot name="actions-before" :item="value" :callback="handleCustomAction"></slot>
                        <TableAction v-for="(action, name) in actions" :name="name" :action="action" :column="actionsColumn"
                            :item="value" @action="handleAction" />
                        <slot name="actions-after" :item="value" :callback="handleCustomAction"></slot>
                    </TableData>
                </TableRow>

                <!-- Detail row -->
                <Transition>
                    <DetailsRow :item="value" :columns="details" :span="detailsRowSpan" :rowId="rowId"
                        :customUrl="detailsCustomUrl" :urlPrefix="detailsUrlPrefix" :parentUrlPrefix="urlPrefix"
                        v-if="detailIsActive(value)" />
                </Transition>
            </template>
        </template>

        <template #pagination>
            <!-- Table pagination -->
            <TablePagination :data="data" @perPageChange="(e) => loadData({ perPage: e })"
                @pageChange="(e) => loadData({ page: e })" v-if="values?.length" />
        </template>
    </Table>
</template>

<script>
import TableData from './TableData'
import TableHeader from './TableHeader'
import TableRow from './TableRow'
import TablePagination from './TablePagination'
import TableAction from './TableAction'
import DetailsRow from './DetailsRow'
import Table from './Table'
import NLFilter from '../Filters/NLFilter'
import api from '../../plugins/api'

export default {
    name: "NLDatatable",
    components: { TableData, TableHeader, TableRow, TablePagination, Table, TableAction, DetailsRow, NLFilter },
    emits: [ 'action', 'detailsChanged', 'show', 'delete', 'edit', 'dataLoaded' ],
    props: {
        columns: { type: [ Object, Array ], required: true },
        actions: { type: [ Object, Array ], required: false, default: () => { } },
        details: { type: [ Object, Array ], required: false, default: () => { } },
        filters: { type: [ Object, Array ], required: false, default: () => { } },
        customUrl: { type: String, default: null, required: false },
        urlPrefix: { type: String, required: true },
        noDataText: { type: String, default: "Aucune entrée" },
        title: { type: [ String, null ], default: null },
        rowId: { type: String, default: 'id', required: false },
        detailsCustomUrl: { type: String, default: null, required: false },
        detailsUrlPrefix: { type: String, required: false, default: null },
        filtersCustomUrl: { type: String, default: null, required: false },
        filtersUrlPrefix: { type: String, required: false, default: null },
        isSearchable: { type: Boolean, required: false, default: true },
        refresh: { type: Number, required: false, default: 0 },
    },
    data() {
        return {
            perPageChoices: [ 10, 25, 50, 100, 250 ],
            page: 1,
            perPage: 10,
            search: '',
            sorting: {},
            isLoading: false,
            data: null,
            url: null,
            activeDetailsRowsId: [],
            activeDetailsRows: [],
            filterIsOpen: false,
            activeFilters: {},
            key: 1,
            forceFilterReload: 0
        }
    },
    watch: {
        refresh(newVal, oldVal) {
            if (newVal !== oldVal) {
                this.loadData({ page: this.page, perPage: this.perPage, search: this.search, sorting: this.sorting, filters: this.activeFilters })
            }
        }
    },
    computed: {
        values() {
            return this.data?.data
        },
        actionsColumn() {
            return {
                hide: !this.hasActions,
                label: 'Actions',
                type: 'actions',
                extraClass: {
                    td: 'cell-actions'
                },
                sortable: false,
                filterable: false,
            }
        },
        detailsRowSpan() {
            return this.columns.length + 1
        },
        hasActions() {
            const hasSlots = !!this.$slots[ 'actions-before' ] || !!this.$slots[ 'actions-after' ]
            let hasActions = false
            if (typeof this.actions == 'object') {
                hasActions = Object.values(this.actions).length
            } else if (typeof this.actions == 'array') {
                hasActions = this.actions.length
            }
            return hasActions || hasSlots
        },
        hasDetails() {
            if (typeof this.details == 'object') {
                return Object.values(this.details).length
            } else if (typeof this.details == 'array') {
                return this.details.length
            }
        },
        hasData() {
            return this.values?.length
        },
        hasFilters() {
            if (typeof this.filters == 'object') {
                return Object.values(this.filters).length
            } else if (typeof this.filters == 'array') {
                return this.filters.length
            }
        },
        detailIsActive() {
            return (value) => this.activeDetailsRowsId.includes(value[ this.rowId ])
        },
        noDataColspan() {
            let additionalCols = 1
            if (this.hasActions) {
                additionalCols += 1
            }
            if (this.hasDetails) {
                additionalCols += 1
            }
            return this.columns.length + additionalCols
        }
    },
    created() {
        this.loadData({ page: 1, perPage: 10 })
    },
    unmounted() {
        this.data = null
    },
    methods: {
        /**
         * Fetch data from server
         *
         * @param {Object} params
         * @param {Number} params.page // current active page
         * @param {Number} params.perPage // amount of data showed per page
         * @param {String} params.search // searched valued
         * @param {Object} params.sorting // key for column -> value for direction
         * @param {Object} param.filters // active filters
         */
        loadData({ page = 1, perPage = this.perPage, search = this.search, sorting = this.sorting, filters = this.activeFilters }) {
            this.isLoading = true
            this.page = page
            this.perPage = perPage
            this.search = search
            this.activeFilters = filters
            this.handleSorting(sorting)
            this.getUrl()
            api.get(this.url).then(response => {
                this.isLoading = false
                this.data = response.data
                this.$emit('dataLoaded', this.data)
            }).catch(error => {
                this.isLoading = false
            })

        },

        /**
         * Generate url after each call
         */
        getUrl() {
            this.url = this.customUrl ? this.customUrl + this.urlPrefix : window.Laravel.baseUrl + '/api/' + this.urlPrefix
            this.url += this.formatQueryString()
        },

        /**
         * Handle sorting object values
         *
         * @param {*} item
         */
        handleSorting(item) {
            const sortBy = item?.sortingColumn !== undefined && item?.sortingColumn !== null ? item?.sortingColumn : item?.column?.field
            if (item?.direction) {
                this.sorting[ sortBy ] = item.direction
            } else {
                delete this.sorting[ sortBy ]
            }
        },

        /**
         * Handle custom actions passed as slot
         *
         * @param {Closure} callback
         * @param {Object} item
         */
        handleCustomAction(callback, item) {
            callback(item).then(response => {
                if (response && Object.hasOwnProperty.call(response, 'data')) {
                    if (response.data.status) {
                        this.loadData({ page: this.page })
                        this.key += 1
                        this.$swal.toast_success(response.data.message)
                    } else {
                        this.$swal.toast_error(response.data.message)
                    }
                }
            }).catch(error => {
                this.$swal.alert_error(error)
            });
        },

        /**
         * Handle action
         *
         * @param {CustomEvent} e
         */
        handleAction(e) {
            if (e.apply !== undefined) {
                if (e.apply(e)) {
                    e.apply(e).then((response) => {
                        if (response && Object.hasOwnProperty.call(response, 'data')) {
                            if (response.data.status) {
                                this.loadData({ page: this.page })
                                this.key += 1
                                this.$swal.toast_success(response.data.message)
                            } else {
                                this.$swal.toast_error(response.data.message)
                            }
                        }
                    }).catch(error => {
                        this.$swal.alert_error(error)
                    });
                } else {
                    e.apply(e)
                }
            } else {
                this.$emit(e.type, e.item)
            }
        },

        /**
         * Toggle detail rows
         *
         * @param {Object} value
         */
        toggleDetailsRow(value) {
            if (this.activeDetailsRowsId.includes(value[ this.rowId ])) {
                const index = this.activeDetailsRowsId.indexOf(value[ this.rowId ]);
                if (index !== -1) {
                    this.activeDetailsRowsId.splice(index, 1);
                }
            } else {
                this.activeDetailsRowsId.push(value[ this.rowId ])
                this.activeDetailsRows.push(value)
            }
            this.$emit('detailsChanged', { activeDetailsRowsId: this.activeDetailsRowsId, activeDetailsRows: this.activeDetailsRows })
        },

        /**
         * Toggle filter state
         */
        toggleFilterState() {
            this.filterIsOpen = !this.filterIsOpen
        },

        /**
         * Format query string before generate url
         */
        formatQueryString() {
            let queryString = `?page=${this.page}&perPage=${this.perPage}`
            if (this.search) {
                queryString += `&search=${this.search}`
            }
            for (const key in this.sorting) {
                if (Object.hasOwnProperty.call(this.sorting, key)) {
                    const element = this.sorting[ key ];
                    queryString += `&sort[${key}]=${element}`
                }
            }
            for (const key in this.filters) {
                if (Object.hasOwnProperty.call(this.filters, key)) {
                    const element = this.filters[ key ];
                    let value = null

                    if ((typeof element.value == 'object' || typeof element.value == 'object') && element.value?.length) {
                        value = Object.values(element.value).join(',')
                    } else if (typeof Boolean(element.value) == 'boolean') {
                        value = element.value
                    }

                    if (value?.length && typeof value !== 'boolean') {
                        queryString += `&filter[${key}]=${value}`
                    } else if (typeof value == 'boolean') {
                        queryString += `&filter[${key}]=${value}`
                    }
                }
            }
            return queryString
        },
    }
}
</script>
