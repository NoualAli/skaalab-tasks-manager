<template>
    <slot name="filter"></slot>
    <NLFlex extraClass="my-6">
        <NLHeading type="2">{{ title }}</NLHeading>
        <NLFlex>
            <SearchBar @search="(e) => this.$emit('search', e)" v-if="isSearchable" />
            <button class="btn btn-filter" :class="{ 'btn-danger': filterIsOpen, 'btn-info': !filterIsOpen }"
                v-if="hasFilters" @click.stop="(e) => this.handleFilterState(e)">
                <i class="las la-filter icon" v-if="!filterIsOpen"></i>
                <i class="las la-times icon" v-else></i>
            </button>
        </NLFlex>
    </NLFlex>
    <!-- <NLContainer extraClass="my-6 p-none" isFluid>
    </NLContainer> -->
    <div class="table-container">
        <table>
            <TableHead>
                <slot name="head"></slot>
            </TableHead>
            <TableBody>
                <slot name="body"></slot>
            </TableBody>
            <TableFoot>
                <slot name="foot"></slot>
            </TableFoot>
        </table>
        <slot name="pagination"></slot>
        <div class="component-loader-container" v-if="isLoading">
            <div class="component-loader"></div>
            <div class="component-loader-text">
                Chargement en cours
            </div>
        </div>
    </div>
</template>

<script>
import NLButton from '../Inputs/NLButton'
import NLFlex from '../Grid/NLFlex'
import NLSelect from '../Inputs/NLSelect'
import SearchBar from './SearchBar'
import TableFoot from './TableFoot'
import TableBody from './TableBody'
import TableHead from './TableHead'

export default {
    components: {
        NLButton,
        NLFlex,
        NLSelect,
        SearchBar,
        TableFoot,
        TableBody,
        TableHead,
    },
    emits: [ 'search', 'toggleFilter' ],
    props: {
        isLoading: { type: Boolean, required: false, default: true },
        title: { type: [ String, null ], default: null },
        filters: { type: [ Object, Array ], required: false, default: () => { } },
        isSearchable: { type: Boolean, require: false, default: true },
    },
    data() {
        return {
            filterIsOpen: false
        }
    },
    computed: {
        hasFilters() {
            if (typeof this.filters == 'object') {
                return Object.values(this.filters).length
            } else if (typeof this.filters == 'array') {
                return this.filters.length
            }
        },
    },
    methods: {
        handleFilterState(e) {
            this.filterIsOpen = !this.filterIsOpen
            this.$emit('toggleFilter', e)
        }
    }
}
</script>
