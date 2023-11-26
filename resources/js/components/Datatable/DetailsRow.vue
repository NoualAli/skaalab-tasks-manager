<template>
    <tr class="details-row">
        <td :colspan="span">
            <NLContainer isFluid>
                <div class="details-row-loader-container" v-if="isLoading">
                    <div class="details-row-loader"></div>
                    <div class="details-row-loader-text">
                        Chargement en cours
                    </div>
                </div>
                <NLGrid v-else>
                    <NLColumn :lg="column.cols.lg" :md="column.cols.md" :sm="column.cols.sm" v-for="column in columns"
                        class="py-2">
                        <span class="text-bold">
                            {{ label(column) }} :
                        </span>
                        &nbsp;
                        <span v-if="!isHtml(column) && !hasMany(column)">
                            {{ showField(column) }}
                        </span>
                        <span v-html="showField(column)" v-else></span>
                    </NLColumn>
                </NLGrid>
            </NLContainer>
        </td>
    </tr>
</template>

<script>
import NLContainer from '../NLContainer'
export default {
    components: { NLContainer },
    name: 'DetailsRow',
    props: {
        columns: {
            type: [ Object, null ],
            required: false,
        },
        item: { type: [ Object, null ], required: false, default: null },
        rowId: { type: String, default: 'id', required: false },
        span: { type: Number, required: true },
        customUrl: { type: String, default: null, required: false },
        urlPrefix: { type: [ String, null ], required: false, default: null },
        parentUrlPrefix: { type: String, required: true },
    },
    computed: {
        label() {
            return (column) => column?.label || ''
        },
        isHtml() {
            return (column) => column?.isHtml || false
        },
        hasMany() {
            return (column) => column?.hasMany || false
        }
    },
    data() {
        return {
            url: null,
            isLoading: false,
            data: null,
            cols: {
                lg: 6,
                md: 12,
                sm: 12,
            },
        }
    },
    created() {
        this.initColumns()
        this.loadData()
    },
    methods: {
        initColumns() {
            this.columns.forEach(column => {
                if (column.cols) {
                    for (const col in this.cols) {
                        if (Object.hasOwnProperty.call(this.cols, col)) {
                            if (!Object.hasOwnProperty.call(column.cols, col)) {
                                column.cols[ col ] = this.cols[ col ]
                            }

                        }
                    }
                } else {
                    column.cols = this.cols
                }
            });
        },
        loadData() {
            this.isLoading = true
            this.getUrl()
            api.get(this.url).then(response => {
                this.isLoading = false
                this.data = response?.data?.data || response?.data
            }).catch(error => {
                this.isLoading = false
            })
        },
        getUrl() {
            let urlPrefix = this.urlPrefix || this.parentUrlPrefix
            this.url = this.customUrl ? this.customUrl + urlPrefix : window.Laravel.baseUrl + '/api/' + urlPrefix
            this.url = this.url + '/' + this.item[ this.rowId ]
        },
        showField(column) {
            let field = ''
            if (column.field.includes('.')) {
                let splited = column.field.split('.')
                let relationship = splited[ 0 ]
                let value = splited[ 1 ]
                field = this.hasMany(column) ? this.loadHasMany(this.data[ relationship ], value) : this.data[ relationship ][ value ]
            } else {
                field = this.data[ column.field ] || '-'
            }
            return field
        },
        // showField(column) {
        //     if (column !== undefined && this.item !== undefined) {
        //         if (Object.hasOwnProperty.call(column, 'methods')) {
        //             if (Object.hasOwnProperty.call(column.methods, 'showField')) {
        //                 return column.methods[ 'showField' ](this.item);
        //             }
        //         }
        //         return this.getField(column)
        //     } else if (column.field.includes('.')) {
        //         let splited = column.field.split('.')
        //         let relationship = splited[ 0 ]
        //         let value = splited[ 1 ]
        //         console.log(this.loadHasMany(this.data[ relationship ], value));
        //         return this.hasMany(column) ? this.loadHasMany(this.data[ relationship ], value) : this.data[ relationship ][ value ]
        //     }
        //     return '-'
        // },
        /**
        * Get field value
        *
        * @param {Object} data
        * @param {String} field
        */
        getField(column) {
            let field = ''
            if (column.field) {
                if (column.field !== null && column.field !== undefined && column.field.includes('.')) {
                    let fields = column.field.split('.')
                    const relationshipName = fields[ 0 ]
                    const relationshipValue = fields[ 1 ]
                    if (relationshipName !== undefined && relationshipValue !== undefined && relationshipName !== null && relationshipValue !== null) {
                        field = this.item[ relationshipName ] !== null ? this.item[ relationshipName ][ relationshipValue ] : '-'
                    }
                } else {
                    field = this.item[ column.field ] !== undefined ? this.item[ column.field ] : '-'
                }
            }
            return field
        },
        /**
         * Truncate string for max length
         *
         * @param {String} string
         */
        // truncate(string) {
        //     return this.length && string.length > this.length ? this.getField().slice(0, this.length) + '...' : string
        // },
        loadHasMany(data, field) {
            let containerStart = '<div class="content mt-6"><ul class="text-normal">'
            let fields = ''
            let containerEnd = '</ul></div>'
            for (const key in data) {
                if (Object.hasOwnProperty.call(data, key)) {
                    const element = data[ key ] || null;
                    if (element) {
                        fields += `<li class="my-2">${element[ field ]}</li>`
                    }
                }
            }
            return containerStart + fields + containerEnd
        }
    }
}
</script>
