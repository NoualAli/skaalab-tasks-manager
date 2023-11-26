<template>
    <input type="search" @keyup="clearSearch" v-model.trim.lazy="appliedSearch" class="input m-0 search-bar"
        placeholder="Faite votre recherche...">
</template>

<script>
export default {
    name: 'SearchBar',
    emits: [ 'search' ],
    props: {
    },
    data() {
        return {
            appliedSearch: '',
        }
    },
    watch: {
        appliedSearch(newValue, oldValue) {
            if (newValue !== oldValue) {
                this.$emit('search', newValue)
            }
        }
    },
    methods: {
        clearSearch(e) {
            if (e.key == 'Backspace' && e.target.value == '') {
                this.appliedSearch = ''
            } else if (e.key == 'Enter' && e.target.value) {
                this.appliedSearch = e.target.value
            }
            return
        }
    }
}
</script>
