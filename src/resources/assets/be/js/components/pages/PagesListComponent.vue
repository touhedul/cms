<template>
    <div>
        <div class="card-body">
            <ul class="list-group">
                <template v-if="current_pages.length > 0">
                    <li id="first" class="list-group-item">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Title</strong>
                            </div>
                            <div class="col-md-4">
                                <strong>URL</strong>
                            </div>
                            <div class="col-md-3">
                                <strong>Created At</strong>
                            </div>
                            <div class="col-md-2 text-center">

                            </div>
                        </div>
                    </li>
                    <li v-for="(page, key) in current_pages" class="list-group-item" :key="key">
                        <div class="row">
                            <div class="col-md-3" style="align-self:center;">
                                <b>{{page.p_title}}</b>
                            </div>
                            <div class="col-md-4" style="align-self:center;">
                                {{host}}/pages/{{page.m_url}}
                            </div>
                            <div class="col-md-3" style="align-self:center;">
                                {{moment(page.created_at, 'YYYY-MM-DD HH:mm:ss').format('MMM DD, YYYY')}}
                            </div>
                            <div class="col-md-2" style="align-self:center;">
                                <div class="text-right">
                                    <div tyle="padding: 5px" style="padding: 5px; display:inline;">
                                        <a :href="'/admin/edit-page/' + page.id" class="btn btn-primary" style="color: #fff" title="Edit">
                                            <i class="fa fa-pencil d-none d-sm-inline"></i>
                                        </a>
                                    </div>
                                    <!--  <div class="col-xs-4" style="padding: 5px">
                                        <a class="btn btn-warning" style="color: #fff">
                                            <i class="fa fa-eye d-none d-sm-inline"></i>
                                            Details
                                        </a>
                                    </div> -->
                                    <div style="padding: 5px; display:inline;">
                                        <a id="deletePage" @click="deletePage(page.id)" class="btn btn-danger" style="color: #fff" title="Remove">
                                            <i class="fa fa-trash-o d-none d-sm-inline"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </template>
                <template v-else>
                    <li class="list-group-item" style="text-align: center">
                        <h4>There are no pages</h4>
                        <a href="/admin/add-page" class="create-link">Create your first page</a>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</template>

<script>
    import PageServices from '../../services/PageServices.js';
    export default {
        mixins: [PageServices],
        props: ['pages', 'host'],
        data() {
            return {
                current_pages: [],
                moment:moment
            }
        },
        methods: {
            deletePage(id) {
                swal({
                    title: "Remove page",
                    text: "Are you sure you want remove this page definitely?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                }).then((confirm) => {
                    if (confirm) {
                        $.LoadingOverlay("show");
                        this.deletePageCall(id, this.deletePageCallback);
                    }
                });
            },
            deletePageCallback(code, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    if (this.current_pages.length > 0) {
                        for (var i in this.current_pages) {
                            if (this.current_pages[i].id == data) {
                                this.current_pages.splice(i, 1);
                            }
                        }
                    } else {
                        this.current_pages = [];
                    }
                }
            }
        },
        created() {
            if (this.pages) {
                this.current_pages = this.pages;
            }
        },
        mounted() {

        }
    }
</script>
<style>
    .create-link {
        font-size: 18px;
        color: #13919b !important;
    }
    .custom-card {
        height: 480px;
    }

    .action-buttom{
        position:absolute; 
        bottom:10px; 
        right:21px;
    }
    @media (max-width: 990px){
        .custom-card {
            height: 99%;
        }
        .action-buttom{
            position:unset; 
            bottom:unset; 
            right:unset;
            padding-right:21px;
        }
    }
    
</style>