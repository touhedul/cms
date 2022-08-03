<template>
    <div class="home-inner calendar-pg row">
        <!-- <div class="col-md-12">
            <h3 class="section-title">Documents</h3>
        </div> -->
        <div class="col-md-12">
            <div v-show="loaded">
                <div v-if="current_documents.length > 0" class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row" style="margin-bottom:15px;">
                                <div class="col-md-6 col-xl-4 input-group"
                                    style="padding-left:21px; padding-right:21px;">
                                    <input type="text" v-model="query" class="form-control" id="query" autocomplete="off"
                                        placeholder="Search...">
                                </div>
                            </div>
                            <div>
                                <ul class="list-group">
                                    <li id="first" class="list-group-item d-none d-lg-block shrink">
                                        <div class="row">
                                            <div class="col-md-2 d-none d-md-block">

                                            </div>
                                            <div class="col-md-2 d-none d-md-block">
                                                <label>
                                                    <strong>Name</strong>
                                                </label>
                                            </div>
                                            <div class="col-md-2 d-none d-md-block" style="text-align:center;">
                                                <label>
                                                    <strong>Amendment</strong>
                                                </label>
                                            </div>
                                            <div class="col-md-6 offset-md-6 d-none d-md-block"
                                                style="text-align:center;">

                                            </div>
                                        </div>
                                    </li>
                                    <li v-for="(document, index) in current_documents" :key="index"
                                        class="list-group-item shrink">
                                        <div class="row">
                                            <div class="col-md-2" style="align-self:center;text-align:center">
                                                <a v-if="document.extension == 'pdf' || document.extension == 'jpeg' || document.extension == 'txt' || document.extension == 'jpg' || document.extension == 'png'"
                                                    :href="'/documents/view/' + document.id" target="_blank">
                                                    <span class="list-inner-item">
                                                        <img v-if="document.extension == 'jpeg' || document.extension == 'jpg' || document.extension == 'png'"
                                                            :src="'/storage/' + document.path" class="adj-logo">
                                                        <img v-else-if="document.extension == 'pdf'"
                                                            src="/images/icons/pdf.png" class="adj-logo">
                                                        <img v-else src="/images/icons/txt-white.png" class="adj-logo">
                                                    </span>
                                                </a>
                                                <a v-else-if="document.extension == 'ppt' || document.extension == 'pptx' || document.extension == 'txt' || document.extension == 'doc' || document.extension == 'docx' || document.extension == 'xls' || document.extension == 'xlsx' || document.extension == 'csv'"
                                                    :href="'/documents/download/' + document.id">
                                                    <img v-if="document.extension == 'ppt' || document.extension == 'pptx'"
                                                        src="/images/icons/ppt.png" class="adj-logo">
                                                    <img v-else-if="document.extension == 'doc' || document.extension == 'docx'"
                                                        src="/images/icons/word.png" class="adj-logo">
                                                    <img v-else-if="document.extension == 'xls' || document.extension == 'xlsx'"
                                                        src="/images/icons/excel.jpg" class="adj-logo">
                                                    <img v-else src="/images/icons/txt-white.png" class="adj-logo">
                                                </a>
                                            </div>
                                            <div class="col-md-2" style="align-self:center;text-align:center">
                                                <span
                                                    class="list-inner-item"><b>{{document.label | capitalize}}</b></span>
                                            </div>
                                            <div class="col-md-2" style="align-self:center;text-align:center">
                                                <span v-if="document.amendment"
                                                    class="list-inner-item d-none d-md-block"
                                                    style="font-size:14px;color:#20c997;text-align:center;padding-top:20px;"><i
                                                        class="fa fa-check"></i></span>
                                                <span v-else class="list-inner-item d-none d-md-block"
                                                    style="font-size:14px;color:#111;"><i
                                                        class="fa fa-remove"></i></span>
                                            </div>
                                            <div class="col-md-2 offset-md-2"
                                                style="align-self:center;text-align:center">
                                                <span
                                                    v-if="document.extension == 'pdf' || document.extension == 'jpeg' || document.extension == 'jpg' || document.extension == 'png'"
                                                    class="list-inner-item">
                                                    <a :href="'/documents/view/' + document.id" style="color: #111"
                                                        target="_blank">View</a>
                                                </span>
                                            </div>
                                            <div class="col-md-2" style="align-self:center;text-align:center">
                                                <span class="list-inner-item">
                                                    <a :href="'/documents/download/' + document.id" style="color: #111"
                                                        download>Download</a>
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <document-paginator v-if="paginator.last_page > 1" :pagination="paginator"
                            @paginate="getDocuments" :offset="offset"></document-paginator>
                    </div>
                </div>
                <div v-else>
                    <div class="text-center">
                        <h3>No documents uploaded at this moment</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import DocumentServices from "../../services/DocumentServices.js";
    import FilePaginator from "../../../../../../components/Paginator";
    export default {
        props: ['category', 'units', 'owner_type', 'visibility'],
        mixins: [DocumentServices],
        components: {
            'document-paginator': FilePaginator
        },
        data() {
            return {
                moment: moment,
                current_documents: [],
                paginator: {
                    total: 0,
                    per_page: 3,
                    from: 1,
                    to: 0,
                    current_page: 1,
                    data_length: 0,
                    last_page: 0
                },
                offset: 4,
                query: '',

                loaded: false
            }
        },
        watch: {
            query: _.debounce(function () {
                this.getDocuments();
            }, 300)
        },
        methods: {
            getDocuments(page) {
                $.LoadingOverlay("show");
                var params = {
                    page: (typeof page == 'undefined') ? this.paginator.current_page : page,
                    limit: 10,
                    fields: ['id', 'label', 'amendment', 'extension', 'path']
                };

                params['where'] = [];
                params['orWhere'] = [];

                if (this.units.length > 0) {
                    for (var i in this.units) {
                        params['orWhere'].push(['owner_id', this.units[i]]);
                    }
                    params['where'].push(['owner_type', this.owner_type]);
                } else {
                    params['where'].push(['owner_id', 0]);
                    params['where'].push(['owner_type', 'admin']);
                }

                if (this.category != 'all') {
                    params['where'].push(['category_id', this.category]);
                }

                if (this.visibility) {
                    params['where'].push(['visibility', this.visibility]);
                }

                if (this.query != '') {
                    params['query'] = "+*" + this.query.replace(/\s+/g, "* +*") + "*";
                }

                params['orderby'] = {
                    'created_at': 'desc',
                    'label': 'asc'
                }

                this.getDocumentsCall(params, this.getDocumentsCallback);
            },
            getDocumentsCallback(response) {
                $.LoadingOverlay("hide");
                this.loaded = false;
                if (response.status == 1) {
                    if (response.data.length > 0) {
                        this.paginator.total = response.pagination.total;
                        this.paginator.per_page = response.pagination.per_page;
                        this.paginator.current_page = response.pagination.current_page;
                        this.paginator.last_page = response.pagination.last_page;
                    } else {
                        this.paginator = {
                            total: 0,
                            per_page: 2,
                            from: 1,
                            to: 0,
                            current_page: 1
                        };
                    }
                    this.current_documents = response.data;
                } else {
                    toastr.error(response.errors[0], 'An error has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                }

                this.loaded = true;
            }
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },
        created() {
            this.getDocuments();
        },
        mounted() {

        }
    }
</script>
<style>
    .icon-store {
        width: 30px;
        height: auto;
    }

    .icon-amazon {
        width: 50px;
        height: auto;
    }

    .input-error-select {
        color: #d61212 !important;
        border: 1px solid #b60707 !important;
        border-radius: 5px
    }

    .message-error {
        color: #d61212;
        float: right;
        padding-top: 10px;
        font-size: 12px;
    }

    .icons-custom {
        width: 20px;
        height: 20px;
    }

    .page-item.active .page-link {
        color: #fff !important;
        background-color: #404E67 !important;
        border-color: #404E67 !important;
    }

    .page-link {
        color: rgba(0, 0, 0, .7) !important;
    }
</style>
<style scoped>
    label {
        font-size: 12px;
    }

    .list-item {
        display: table;
        height: auto;
        width: 100%;
    }

    .list-inner-item {
        display: table-cell;
        vertical-align: middle;
    }

    .adj-logo {
        width: 50%;
        height: auto;
    }

    @media (min-width: 576px) {}

    @media (min-width: 768px) {}

    @media (min-width: 992px) {

        .list-item {
            display: table;
            height: 70px;
            width: 100%;
        }
    }

    @media (min-width: 1200px) {
        .list-item {
            display: table;
            height: 70px;
            width: 100%;
        }
    }

    #first,
    #query {
        background-color: transparent;
        font-size: 12px;
        color: #111
    }

    .shrink {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .card,
    li {
        background: transparent;
    }

    .list-inner-item {
        font-size: 12px
    }
</style>