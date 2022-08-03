<template>
    <div class="row">
        <div class="col-md-12 card">
            <div class="text-right">
                <a href="#" class="btn btn-secondary btn-md-width" style="float: right" @click="newCategory()">New Category</a>
            </div>
            <div v-if="current_categories.length > 0" class="">
                <div class="card-content">
                    <div class="card-body">
                        <div>
                            <ul class="list-group">
                                <li id="first" class="list-group-item d-none d-lg-block shrink">
                                    <div class="row">
                                        <div class="col-md-7 d-none d-md-block">
                                            <label>
                                                <strong>Name</strong>
                                            </label>
                                        </div>
                                        <div class="col-md-3 d-none d-md-block">
                                            <label>
                                                <strong>Created at</strong>
                                            </label>
                                        </div>
                                        <div class="col-md-2 d-none d-md-block">

                                        </div>
                                    </div>
                                </li>
                                <li v-for="(category, index) in current_categories" :key="index" class="list-group-item shrink">
                                    <div class="row">
                                        <div class="col-sm-4 col-md-7 list-item">
                                            <span class="list-inner-item"><b>{{category.name | capitalize}}</b></span>
                                        </div>
                                        <div class="col-sm-4 col-md-3 list-item">
                                            <span class="list-inner-item" style="font-size:12px;">{{moment(category.created_at).format('YYYY-MM-DD')}}</span>
                                        </div>
                                        <div class="col-sm-12 col-md-2 list-item">
                                            <span class="list-inner-item">
                                                <div class="text-right" style="width: 100%">
                                                    <div style="padding: 5px; display:inline;">
                                                        <a href="#" @click="editCategory(category.id, category.name)" class="btn btn-secondary"
                                                            style="color: #fff; margin-top:2; margin-bottom:2px;" title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    </div>
                                                    <div style="padding: 5px; display:inline;">
                                                        <a @click="removeCategory(category.id)" href="#" class="btn btn-danger" style="color: #fff;margin-top:2px; margin-bottom:2px;" title="Remove">
                                                            <i class="fa fa-trash-o"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getDocumentCategories"
                        :offset="offset"></paginator>
                </div>
            </div>
            <div v-else>
                <div class="text-center">
                    <h3>No files registered at this moment</h3>
                </div>
            </div>
        </div>
        <div class="modal fade text-left" id="create-category-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-xs" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="border-bottom: 1px solid #98a4b845;">
                        <h4 class="modal-title" id="myModalLabel1">New Category</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-1 col-12">
                            <label for="category">Category Name</label>
                            <br>
                            <input v-model="category.name" type="text" class="form-control" id="category">
                            <span v-if="error.category" class="message-error">{{error_message.category}}</span>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:none;">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline-primary" @click="saveCategory()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DocumentServices from "../../services/DocumentServices.js";
import Paginator from "../../../../../../components/Paginator";
export default {
    mixins: [DocumentServices],
    components: {
        paginator: Paginator
    },
    data() {
        return {
            moment: moment,
            current_categories: [],
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
            category:{
                id: 0,
                name:''
            },
            error:{
                category: false
            },
            error_message:{
                category: ''
            }
        };
    },
    watch: {},
    methods: {
        getDocumentCategories() {
            $.LoadingOverlay("show");
            var params = {
                page: this.paginator.current_page,
                limit: 10,
                fields: ["id", "name", "created_at"]
            };
            this.getDocumentCategoriesCall(params, this.getDocumentCategoriesCallback);
        },
        getDocumentCategoriesCallback(response) {
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
                this.current_categories = response.data;
            } else {
                toastr.error(response.errors[0], "An error has occurred", {
                positionClass: "toast-top-center",
                containerId: "toast-top-center"
                });
            }
        },
        editCategory(id,name){
            this.category.id = id;
            this.category.name = name;
            setTimeout(function(){
                $("#create-category-modal").modal('show');
            },50)
        },
        newCategory(){
            this.category.id = 0;
            this.category.name = '';
            setTimeout(function(){
                $("#create-category-modal").modal('show');
            },50)
        },
        saveCategory(){
            if(this.category.name != ''){
                 $.LoadingOverlay("show");
                if(this.category.id > 0){
                    this.updateCategoryCall(this.category, this.saveCategoryCallback);
                }else{
                    this.createCategoryCall(this.category, this.saveCategoryCallback);
                }
            }else{
                this.error.category = true;
                this.error_message.category = 'Category name is required';
            }
        },
        saveCategoryCallback(response){
             $.LoadingOverlay("hide");
            if(response.status > 0){
                toastr.success(response.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                this.getDocumentCategories();
                $("#create-category-modal").modal('hide');
            }else{
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
        removeCategory(id) {
                swal({
                    title: "Are you sure?",
                    text: "Please confirm you want to remove this category.",
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
                        this.removeCategoryCall(id, this.removeCategoryCallback);
                    }
                });
            },
            removeCategoryCallback(response) {
                if (response.status == 1) {
                    toastr.success(response.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    this.getDocumentCategories();
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
                $.LoadingOverlay("hide");
            },
    },
    filters: {
        capitalize: function(value) {
        if (!value) return "";
        value = value.toString();
        return value.charAt(0).toUpperCase() + value.slice(1);
        }
    },
    created() {
        this.getDocumentCategories();
    },
    mounted() {}
};
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
  border-radius: 5px;
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
  background-color: #404e67 !important;
  border-color: #404e67 !important;
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
  width: 100%;
  height: auto;
}

@media (min-width: 576px) {
  .adj-logo {
    width: 80%;
    height: auto;
  }
}

@media (min-width: 768px) {
  .adj-logo {
    width: 60%;
    height: auto;
  }
}

@media (min-width: 992px) {
  .adj-logo {
    width: 100%;
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
    width: 100%;
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