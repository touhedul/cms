<template>
    <div class="row">
        <div class="col-md-12">
            <div v-if="current_documents.length > 0" class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row" style="margin-bottom:15px;">
                            <div class="col-md-6 col-xl-4 input-group" style="padding-left:21px; padding-right:21px;">
                                <input type="text" v-model="query" class="form-control" id="query" placeholder="Search...">
                            </div>
                        </div>
                        <div>
                            <ul class="list-group">
                                <li id="first" class="list-group-item d-none d-lg-block shrink">
                                    <div class="row">
                                        <div class="col-md-1 d-none d-md-block">

                                        </div>
                                        <div class="col-md-2 d-none d-md-block">
                                            <label>
                                                <strong>Label</strong>
                                            </label>
                                        </div>
                                        <div class="col-md-4 d-none d-md-block">
                                            <label>
                                                <strong>Filename</strong>
                                            </label>
                                        </div>
                                        <div class="col-md-1 d-none d-md-block">
                                            <label>
                                                <strong>Size</strong>
                                            </label>
                                        </div>
                                        <div class="col-md-2 d-none d-md-block" style="text-align:center;">
                                            <label>
                                                <strong>Created at</strong>
                                            </label>
                                        </div>
                                        <div class="col-md-1 d-none d-md-block" style="text-align:center;">
                                            <label>
                                                <strong>Amendment</strong>
                                            </label>
                                        </div>
                                        <div class="col-md-1 d-none d-md-block">

                                        </div>
                                    </div>
                                </li>
                                <li v-for="(document, index) in current_documents" :key="index" class="list-group-item shrink">
                                    <div class="row">
                                        <div class="col-sm-4 col-md-1" style="align-self:center;text-align:center">
                                            <a :href="'/admin/documents/edit/' + document.id">
                                                <img v-if="document.extension == 'jpg' || document.extension == 'jpeg' || document.extension == 'png' || document.extension == 'gif'" :src="'/storage/' + document.path"
                                                    class="adj-logo list-inner-item">
                                                <img v-else-if="document.extension == 'pdf'" src="/images/icons/pdf.png"
                                                    class="" style="width:30px; height:30px;">
                                                <img v-else-if="document.extension == 'ppt' || document.extension == 'pptx'"
                                                    src="/images/icons/ppt.png" class="" style="width:30px; height:30px;">
                                                <img v-else-if="document.extension == 'doc' || document.extension == 'docx'"
                                                    src="/images/icons/word.png" class="" style="width:30px; height:30px;">
                                                <img v-else-if="document.extension == 'xls' || document.extension == 'xlsx'"
                                                    src="/images/icons/excel.jpg" class="" style="width:30px; height:30px;">
                                                <img v-else src="/images/icons/txt.png" class="" style="width:30px; height:30px;">
                                            </a>
                                        </div>
                                        <div class="col-sm-4 col-md-2 list-item">
                                            <span class="list-inner-item"><b>{{document.label | capitalize}}</b></span>
                                        </div>
                                        <div class="col-sm-4 col-md-4 list-item">
                                            <span class="list-inner-item"><b>{{document.filename | capitalize}}</b></span>
                                        </div>
                                        <div class="col-sm-4 col-md-1  d-none d-md-block list-item">
                                            <span class="list-inner-item" style="padding-top:20px;">{{document.size |
                                                bytesToSize}}</span>
                                        </div>
                                        <div class="col-sm-4 col-md-2 list-item">
                                            <span class="list-inner-item d-none d-md-block" style="font-size:12px;text-align:center;padding-top:20px;">{{moment(document.created_at).format('LL')}}</span>
                                            <span class="list-inner-item d-sm-block d-md-none" style="font-size:12px;">{{moment(document.created_at).format('LL')}}</span>
                                        </div>
                                        <div class="col-sm-4 col-md-1 list-item">
                                            <span v-if="document.amendment" class="list-inner-item d-none d-md-block" style="font-size:14px;color:#20c997;text-align:center;padding-top:20px;"><i
                                                    class="fa fa-check"></i></span>
                                            <span v-if="document.amendment" class="list-inner-item d-sm-block d-md-none"
                                                style="font-size:14px;color:#20c997;">Amendment</span>
                                        </div>
                                        <div class="col-sm-12 col-md-1 list-item">
                                            <span class="list-inner-item">
                                                <a :href="'/admin/documents/edit/' + document.id" class="btn btn-secondary btn-md-width"
                                                    style="float: right"><i class="fa fa-pencil"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <document-paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getDocuments" :offset="offset"></document-paginator>
                </div>
            </div>
            <div v-else>
                <div class="text-center">
                    <h3>No documents registered at this moment</h3>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import DocumentServices from "../../services/DocumentServices.js";
    import FilePaginator from "../../../../../../components/Paginator";
    export default {
        props: ['owner_id', 'owner_type'],
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
                current_owner_id: '',
                current_owner_type: '',
                offset: 4,
                query: ''
            }
        },
        watch: {
            query: _.debounce(function () {
                this.getDocuments();
            }, 300)
        },
        methods: {
            getDocuments() {
                $.LoadingOverlay("show");
                var params = {
                    page: this.paginator.current_page,
                    limit: 10,
                    fields: ['id', 'label', 'filename', 'owner_id', 'owner_type', 'amendment', 'created_at', 'extension', 'path', 'size'],
                };
                if (this.current_owner_id > 0 && this.current_owner_type != '') {
                    params['where'] = [['owner_id', [this.current_owner_id]], ['owner_type', [this.current_owner_type]]];
                }
                if (this.query != '') {
                    params['query'] = '*' + this.query + '*';
                }
                this.getDocumentsCall(params, this.getDocumentsCallback);
            },
            getDocumentsCallback(response) {
                var self = this;
                $.LoadingOverlay("hide");
                if (response.status == 1) {
                    if (response.data.length > 0) {
                        this.paginator = response.pagination;
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
            }
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            },
            bytesToSize(bytes) {
                var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                if (bytes == 0) return '0 Byte';
                var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
                return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
            }
        },
        created() {
            if (this.owner_id > 0) {
                this.current_owner_id = this.owner_id;
            }
            if (this.owner_type != '') {
                this.current_owner_type = this.owner_type;
            }
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
        width: 20%;
        height: auto;
    }

    @media (min-width: 576px) {
        .adj-logo {
            width: 40%;
            height: auto;
        }
    }

    @media (min-width: 768px) {
        .adj-logo {
            width: 30%;
            height: auto;
        }
    }

    @media (min-width: 992px) {
        .adj-logo {
            width: 8%;
            height: auto;
        }

        .list-item {
            display: table;
            height: 70px;
            width: 100%;
        }
    }

    @media (min-width: 1200px) {
        .adj-logo {
            width: 70%;
        }

        .list-item {
            display: table;
            height: 70px;
            width: 100%;
        }
    }

    #first {
        background-color: #e0e2e5;
        font-size: 12px;
    }

    .shrink {
        padding-top: 5px;
        padding-bottom: 5px;
    }
</style>