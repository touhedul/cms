<template>
        <div id="doc-card" class="card">
            <div class="row mt-3">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="card-header">
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body div-dimensions">
                            <div v-if="!document.id" class="row">
                                <h4 class="card-title" id="repeat-form">New document</h4>
                            </div>
                            <form class="form row">
                                <div class="form-group mb-1 col-sm-6 col-md-6">
                                    <label for="document">Document</label>
                                    <br>
                                    <fieldset class="form-group">
                                        <label class="custom-file center-block block">
                                            <input type="file" id="document" ref="document" v-on:change="handleFileUpload"
                                                class="custom-file-input">
                                            <span class="custom-file-control"></span>
                                        </label>
                                    </fieldset>
                                </div>
                                <div class="form-group mb-1 col-sm-6 col-md-6">
                                    <label></label>
                                    <br>
                                    <div id="preview"><br>
    
                                    </div>
                                </div>
                                <div class="form-group mb-1 col-sm-6 col-md-6">
                                    <label for="label">Label</label>
                                    <br>
                                    <input v-model="document.label" type="text" class="form-control" id="label">
                                    <span v-if="error.label" class="message-error">{{error_message.label}}</span>
                                </div>
                                <div class="form-group mb-1 col-sm-6 col-md-6">
                                    <label for="category">Category</label>
                                    <br>
                                    <select v-model="document.category" id="select2-categories" class="select2-placeholder form-control input-bordered">
                                        <option :value="-1">Select category</option>
                                        <option v-for="(category, index) in categories" :key="index">{{category.name}}</option>
                                    </select>
                                    <span v-if="error.category" class="message-error">{{error_message.category}}</span>
                                </div>
    
                                <div class="form-group mb-1 col-sm-6 col-md-6">
                                    <div class="skin skin-square">
                                        <div class="form-group">
                                            <fieldset>
                                                <input type="checkbox" id="amendment">
                                                <label for="amendment">Amendment</label>
                                            </fieldset>
                                        </div>
                                        <div class="form-group">
                                            <fieldset>
                                                <input type="checkbox" id="visibility">
                                                <label for="visibility">Make public</label>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <!-- <span v-if="file.logo" style="color: red; float: right; font-size: 12px">Remove</span> -->
                                </div>
                                <div v-if="document.id > 0" class="form-group mb-1 col-sm-6 col-md-6">
                                    <a @click="removeDocument" class="danger" style="float: right">Remove document</a>
                                </div>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <a v-if="document.id > 0" :href="'/storage/'+document.path" class="btn btn-warning btn-min-width mr-1 mb-1"
                                    target="_blank">View</a>
                                <a href="#" @click="cancel" class="btn btn-light btn-min-width mr-1 mb-1">Cancel</a>
                                <button @click="save" type="button" class="btn btn-primary btn-min-width mr-1 mb-1">Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </template>
    
    <script>
        import DocumentServices from "../../services/DocumentServices.js";
    
        export default {
            mixins: [DocumentServices],
            props: ['roles', 'editable_document', 'owner_id', 'owner_type','account'],
            data() {
                return {
                    document: {
                        id: 0,
                        label: '',
                        filename: '',
                        category_id: '',
                        category_name: '',
                        logo: '',
                        file: '',
                        amendment: 0,
                        owner_type: 'admin',
                        owner_id: 0,
                        path: '',
                        visibility: 'private'
                    },
                    new_file: false,
                    error: {
                        label: false,
                        category: false,
                    },
                    error_message: {
                        label: '',
                        category: '',
                    },
                    categories: []
                }
            },
            watch: {
    
            },
            methods: {
                save() {
                    if (this.document.label != '' && this.document.category_id != '') {
                        let formData = new FormData();
                        formData.append('label', this.document.label);
                        formData.append('category_id', this.document.category_id);
                        formData.append('amendment', this.document.amendment);
                        formData.append('owner_id', this.document.owner_id);
                        formData.append('visibility', this.document.visibility);
                        formData.append('owner_type', this.document.owner_type);
                        if (this.document.file) {
                            formData.append('document', this.document.file);
                        }
                        $.LoadingOverlay("show");
                        if (this.document.id > 0) {
                            formData.append('id', this.document.id);
                            this.updateDocumentCall(formData, this.saveDocumentCallback);
                        } else {
                            this.saveDocumentCall(formData, this.saveDocumentCallback);
                        }
                    } else {
                        if (this.document.label == '') {
                            this.error.label = true;
                            this.error_message.label = 'Label is required';
                        }
                        if (this.document.category_id == '') {
                            this.error.category = true;
                            this.error_message.category = 'Category is required';
                        }
                    }
                },
                saveDocumentCallback(response) {
                    $.LoadingOverlay("hide");
                    if (response.status == 1) {
                        toastr.success(response.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                        if (this.document.id > 0) {
                            this.document = Object.assign(this.document, response.data);
                        } else {
                            if (this.owner_id && this.owner_id > 0) {
                                this.$emit('hideNewFile');
                            } else {
                                window.location.href = '/admin/documents';
                            }
                        }
                    } else {
                        if (Helpers.isAssoc(response.errors)) {
                            let errors = [];
                            for (var i in response.errors) {
                                var string
                                errors.push('<span>' + response.errors[i] + '</span></br>')
                            }
                            toastr.error(errors, 'Some error(s) has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                        } else {
                            toastr.error(response.errors[0], 'An error has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                        }
                    }
                },
                handleFileUpload() {
                    this.new_file = true;
                    this.document.file = this.$refs.document.files[0];
                    if (this.document.file.type.startsWith('image/')) {
                        var img = document.createElement("img");
                        img.file = this.document.file;
                        img.classList.add('preview-thumb');
                        $('#preview').empty();
                        $('#preview').append(img);
                        var reader = new FileReader();
                        reader.onload = (function (aImg) { return function (e) { aImg.src = e.target.result; }; })(img);
                        reader.readAsDataURL(this.document.file);
                        this.imageDeleted = false;
                    } else {
                        $('#preview').empty();
                        $('#preview').append(`<div>${this.document.file.name}</div>`);
                    }
                    if (!this.document.id) {
                        this.document.label = this.document.file.name.substr(0, this.document.file.name.lastIndexOf('.'));
                    }
                },
                removeDocument() {
                    swal({
                        title: "Are you sure?",
                        text: "Please confirm you want to remove this document.",
                        icon: "warning",
                        buttons: {
                            cancel: {
                                text: "Cancel",
                                value: null,
                                visible: true,
                                className: "btn-warning",
                                closeModal: true,
                            },
                            confirm: {
                                text: "Yes",
                                value: true,
                                visible: true,
                                className: "btn-primary",
                                closeModal: true
                            }
                        }
                    }).then(isConfirm => {
                        if (isConfirm) {
                            $.LoadingOverlay("show");
                            this.removeDocumentCall(this.document.id, this.removeDocumentCallback);
                        }
                    });
                },
                removeDocumentCallback(response) {
                    $.LoadingOverlay("hide");
                    if (response.data.status == 1) {
                        toastr.success(response.data.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                        if (this.owner_id && this.owner_id > 0) {
                            this.$emit('showNewFile');
                        } else {
                            window.location.href = '/admin/documents';
                        }
                    } else {
                        if (Helpers.isAssoc(response.data.errors)) {
                            let errors = [];
                            for (var i in response.data.errors) {
                                var string
                                errors.push('<span>' + response.data.errors[i] + '</span></br>')
                            }
                            toastr.error(errors, 'Some error(s) has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                        } else {
                            toastr.error(response.data.errors[0], 'An error has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                        }
                    }
                },
                cancel() {
                    if (this.owner_id && this.owner_id > 0) {
                        this.$emit('hideNewFile');
                    } else {
                        window.location.href = '/admin/documents';
                    }
                }
            },
            filters: {
                capitalize: function (value) {
                    if (!value) return ''
                    value = value.toString()
                    return value.charAt(0).toUpperCase() + value.slice(1)
                }
            },
            watch: {
                'document.label'(val) {
                    if (val) {
                        this.error.label = false;
                        this.error_message.label = '';
                    }
                },
                'document.category'(val) {
                    if (val) {
                        this.error.category = false;
                        this.error_message.category = '';
                    }
                },
            },
            created() {
                if (this.editable_document) {
                    this.document = Object.assign(this.document, this.editable_document);
                }
                if (this.owner_id > 0) {
                    this.document.owner_id = this.owner_id;
                }
                if (this.owner_type) {
                    this.document.owner_type = this.owner_type;
                }
            },
            mounted() {
                var self = this;
                this.$nextTick(function () {
                    if(self.account){
                        $("#doc-card").removeClass('card');
                    }
                    $("#select2-categories").select2({
                        ajax: {
                            url: '/api/admin/documents/categories/search',
                            dataType: 'json',
                            delay: 250,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: function (params) {
                                params.term = (typeof params.term == 'undefined') ? '' : params.term;
                                return {
                                    query: params.term + '*', // search term
                                    fields: ['id', 'name'],
                                    page: params.page,
                                    limit: 5
                                };
                            },
                            processResults: function (response, params) {
                                params.page = params.page || 1;
                                return {
                                    results: response.data,
                                    pagination: {
                                        more: (params.page) <= response.pagination.total_pages
                                    }
                                };
                            },
                            cache: true
                        },
                        escapeMarkup: function (markup) { return markup; },
                        templateResult: function (repo) {
                            if (repo.loading) return repo.text;
                            var markup = "<div class='select2-result-repository clearfix'>" + repo.name + "</div>";
                            return markup;
                        },
                        templateSelection: function (repo) {
                            if (repo.name) {
                                self.document.category_id = repo.id;
                                self.document.category_name = repo.name;
                                return repo.name
                            } else {
                                return repo.text;
                            }
                        }
                    });
    
                    if (self.document.category_id > 0) {
                        let data = {
                            name: self.editable_document.category.name,
                            id: self.document.category_id
                        };
                        var option = new Option(data.name, data.id, true, true);
                        $("#select2-categories").append(option).trigger('change');
                        $("#select2-categories").trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                    }
                    if (this.document.filename != '') {
                        $('#preview').empty();
                        if (this.document.extension == 'jpg' || this.document.extension == 'jpeg' || this.document.extension == 'png') {
                            $('#preview').append(`<img class="preview-thumb" src="/admin/documents/view/${this.document.id}">`);
                        } else {
                            $('#preview').append(`<div>${this.document.filename}</div>`);
                        }
    
                    }
                    this.document.amendment == 1 ? $('#amendment').iCheck('check') : $('#amendment').iCheck('chuncheckeck');
    
                    $('.skin-square input').iCheck({
                        checkboxClass: 'icheckbox_square-red',
                        radioClass: 'iradio_square-red',
                    });
    
                    $('#amendment').on('ifChecked', function (event) {
                        self.document.amendment = 1;
                    });
    
                    $('#amendment').on('ifUnchecked', function (event) {
                        self.document.amendment = 0;
                    });
    
                    $('#visibility').on('ifChecked', function (event) {
                        self.document.visibility = 'public';
                    });
    
                    $('#visibility').on('ifUnchecked', function (event) {
                        self.document.visibility = 'private';
                    });
    
                    if (this.document.id > 0) {
                        if (this.document.visibility == 'public') {
                            $('#visibility').iCheck('Check');
                        }
                    }
                });
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
    
        label {
            font-size: 12px
        }
    
        .preview-thumb {
            width: 100% !important;
            height: auto !important;
        }
    
        .select2-selection__rendered {
            font-size: 12px !important;
        }
    
        .swal-text {
            text-align: center !important;
        }
    </style>
    <style scoped>
        .active {
            background-color: #fff !important;
        }
    
        .tab-pane {
            background-color: #fff !important;
        }
    
        .nav.nav-tabs.nav-underline .nav-item a.nav-link:before {
            background: #727c8e !important;
        }
    
        .nav.nav-tabs.nav-underline .nav-item a.nav-link {
            color: #727c8e !important;
        }
    
        .nav.nav-tabs.nav-underline .nav-item a.nav-link:before {
            background: #727c8e !important;
        }
    
        input,
        textarea {
            font-size: 12px;
        }
    
        .icon-menu {
            font-size: 20px !important;
        }
    
        .nav.nav-tabs.nav-underline {
            border-bottom: 1px solid #404e67;
        }
    </style>