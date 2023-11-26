<template>
    <div>
        <ContentHeader>
            <template #actions>

            </template>
        </ContentHeader>
        <ContentBody>
            <NLDatatable :columns="columns" :details="details" :actions="actions" :filters="filters" title="Fichiers"
                urlPrefix="media" @detailsChanged="show"
                @dataLoaded="() => this.$store.dispatch('settings/updatePageLoading', false)" :refresh="refresh" />
        </ContentBody>
    </div>
</template>

<script>
import { hasRole } from '../../../plugins/user';

export default {
    layout: 'MainLayout',
    middleware: [ 'auth', 'admin' ],
    metaInfo() {
        return { title: 'Fichiers' }
    },
    data() {
        return {
            refresh: 0,
            columns: [
                {
                    label: 'Nom',
                    field: 'original_name',
                    isHtml: true,
                    sortable: true,
                    methods: {
                        showField(e) {
                            if ([ 'jpg', 'jpeg', 'png', 'svg', 'tif', 'pdf' ].includes(e.extension)) {
                                return `<a target="_blank" href="${e.storage_link}">
                                    <i class="${e.icon} icon"></i>
                                    ${e.original_name}
                                    </a>`
                            } else {
                                return `<a target="_blank" href="${e.storage_link}" download="${e.original_name}">
                                    <i class="${e.icon} icon"></i>
                                    ${e.original_name}
                                    </a>`
                            }
                        }
                    }
                },
                {
                    label: 'Taille',
                    field: 'size_str',
                    sortable: 'size',
                },
                {
                    label: 'Propriétaire',
                    field: 'full_name',
                },
                {
                    label: 'Type',
                    field: 'type',
                },
                {
                    label: 'Date téléversement',
                    field: 'created_at_formatted',
                    sortable: 'created_at',
                }
            ],
            actions: {
                delete: {
                    show: (item) => {
                        return hasRole('root')
                    },
                    apply: this.destroy
                }
            },
            filters: {
                users: {
                    label: 'Utilisateurs',
                    cols: 3,
                    multiple: true,
                    data: null,
                    value: null
                },
                types: {
                    label: 'Types',
                    cols: 3,
                    multiple: true,
                    data: null,
                    value: null
                },
            },
            details: [
                {
                    label: 'Nom',
                    field: 'original_name',
                    cols: {
                        lg: 3,
                    },
                    isHtml: true,
                    methods: {
                        showField(e) {
                            if ([ 'jpg', 'jpeg', 'png', 'svg', 'tif', 'pdf' ].includes(e.extension)) {
                                return `<a target="_blank" href="${e.storage_link}">
                                    <i class="${e.icon} icon"></i>
                                    ${e.original_name}
                                    </a>`
                            } else {
                                return `<a target="_blank" href="${e.storage_link}" download="${e.original_name}">
                                    <i class="${e.icon} icon"></i>
                                    ${e.original_name}
                                    </a>`
                            }
                        }
                    }
                },
                {
                    label: 'Taille',
                    field: 'size_str',
                    cols: {
                        lg: 3,
                    }
                },
                {
                    label: 'Propriétaire',
                    field: 'full_name',
                    cols: {
                        lg: 3,
                    }
                },
                {
                    label: 'Type',
                    field: 'type',
                    cols: {
                        lg: 3,
                    }
                },
                {
                    label: 'Mimetype',
                    field: 'mimetype',
                    cols: {
                        lg: 3,
                    }
                },
                {
                    label: 'Type',
                    field: 'type',
                    cols: {
                        lg: 3,
                    }
                },
                {
                    label: 'Date téléversement',
                    field: 'created_at',
                },
                {
                    label: 'Emplacement',
                    field: 'path',
                    cols: {
                        lg: 12,
                    }
                },
            ]
        }
    },
    created() {
        this.initData()
    },
    methods: {
        initData() {
            this.$store.dispatch('settings/updatePageLoading', true)
        },
        show(e) {
            // console.log(e.activeDetailsRows[ 0 ].original_name);
        },
        destroy(e) {
            return this.$swal.confirm_destroy().then((action) => {
                if (action.isConfirmed) {
                    return this.$api.delete('media/' + e.item.id).then((response) => {
                        if (response.data.status) {
                            this.refresh += 1
                            this.$swal.toast_success(response.data.message)
                        } else {
                            this.$swal.alert_error(response.data.message)
                        }
                    }).catch(function (error) {
                        console.log(error);
                    })
                }
            })
        }
    }
}
</script>
