<template>
    <div class="card custom-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <fieldset class="form-group">
                        <label for="basicInput">
                            <b>Title</b>
                        </label>
                        <input type="text" v-model="title" class="form-control" v-bind:class="{'input-error-select' : error.title}">
                        <span v-if="error.title" class="message-error">{{error_message.title}}</span>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 offset-md-2">
                    <fieldset class="form-group">
                        <label for="basicInput">
                            <b>Header content type</b>
                        </label>
                        <fieldset>
                            <label class="custom-control custom-radio">
                                <input @change="mediaTypeChanged()" id="media_image" v-model="media_type" value="picture" name="media" :checked="media_type=='picture'"
                                    type="radio" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Picture</span>
                            </label>
                        </fieldset>
                        <fieldset>
                            <label class="custom-control custom-radio">
                                <input @change="mediaTypeChanged()" id="media_video" v-model="media_type" value="video" name="media" type="radio" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Youtube video</span>
                            </label>
                        </fieldset>

                    </fieldset>
                </div>
                <div class="col-md-5">
                    <template v-if="media_type=='picture'">
                        <label for="basicInput">
                            <b>Select picture</b>
                        </label>
                        <fieldset class="form-group" v-bind:class="{'input-error-select' : error.file}">
                            <label class="custom-file center-block block">
                                <input type="file" id="file" ref="file" v-on:change="handleFileUpload()" class="custom-file-input">
                                <span class="custom-file-control"></span>
                            </label>
                            <p style="font-size:13px;">Min dimensions:640x480</p>
                        </fieldset>
                        <span v-if="error.file" class="message-error">{{error_message.file}}</span>
                    </template>
                    <template v-else-if="media_type=='video'">
                        <label for="basicInput">
                            <b>Youtube video id</b>
                        </label>
                        <input type="text" v-model="file" class="form-control" v-bind:class="{'input-error-select' : error.file}">
                        <span v-if="error.file" class="message-error">{{error_message.file}}</span>
                    </template>
                </div>
            </div>
            <div v-if="media_type == 'picture'" class="row" style="text-align: right">
                <div class="col-md-6 offset-md-4">
                    <img v-if="editable_file_path" :src="'/storage/' + editable_file_path" class="preview-thumb" />
                    <div id="preview"></div>
                    <!-- <a  v-if="editable_file_path || !imageDeleted" @click="deleteImage()" style="text-align: right">Delete</a> -->
                </div>
            </div>
            <div v-else-if="media_type == 'video' && file" class="row" style="text-align: right">
                <div class="col-md-6 offset-md-4">
                    <youtube :video-id="file"></youtube>
                    <!-- <a  v-if="editable_file_path || !imageDeleted" @click="deleteImage()" style="text-align: right">Delete</a> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <fieldset class="form-group">
                        <label for="basicInput">
                             <b>Keywords</b>
                        </label>
                        <textarea type="text" class="form-control" v-model="keywords"></textarea>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <label for="basicInput">
                        <b>Content</b>
                    </label>
                    <div id="summernote" class="summer_n"></div>
                    <span v-if="error.content" class="message-error">{{error_message.content}}</span>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-7 offset-md-3">
                    <div class="skin skin-square">
                        <div class="form-group text-right">
                            <fieldset>
                                <input type="checkbox" id="active">
                                <label for="active">Active</label>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2" style="margin-top: 10px">
                    <button v-if="editable_post" @click="updatePost()" class="btn btn-primary" type="button" style="float: right; margin-left: 5px">
                        Update
                    </button>
                    <button v-else @click="savePost()" class="btn btn-primary" type="button" style="float: right; margin-left: 5px">
                        Save
                    </button>
                    <button @click="cancelPost()" class="btn btn-danger" type="button" style="float: right">
                        Cancel
                    </button>
                    <a v-if="editable_post != null" :href="(editable_post.slug != null) ? '/blog/test/'+editable_post.slug : '/blog/test/'+editable_post.id" target="blanck" class="btn btn-warning" style="float: right; margin-right:5px;">View live</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BlogServices from '../../services/BlogServices.js';
    import Helpers from '../../../../../../misc/helpers.js';
    export default {
        mixins: [BlogServices, Helpers],
        props: ['editable_post'],
        data() {
            return {
                id: '',
                title: '',
                content: '',
                media_type: 'picture',
                file: '',
                editable_file_path: '',
                active: 0,
                keywords:'',
                error: {
                    title: false,
                    content: false,
                    file: false
                },
                error_message: {
                    title: '',
                    content: '',
                    file: ''
                },
                imageDeleted: true
            }
        },
        methods: {
            savePost() {
                if (this.title != '' && this.file != '' && this.content != '') {
                    this.content = $('#summernote').summernote('code');
                    let formData = new FormData();
                    formData.append('active', this.active);
                    formData.append('content', this.content);
                    formData.append('title', this.title);
                    formData.append('file', this.file);
                    formData.append('owner_type', 'post');
                    formData.append('header_media_type', this.media_type);
                    formData.append('keywords', this.keywords);
                    $.LoadingOverlay("show");
                    this.savePostCall(formData, this.savePostCallback);
                } else {
                    if (this.title == '') {
                        this.error.title = true;
                        this.error_message.title = 'Post title is required';
                    }
                    if (this.file == '') {
                        this.error.file = true;
                        if (this.media_type == 'picture') {
                            this.error_message.file = 'Main post picture is required';
                        } else if (this.media_type == 'video') {
                            this.error_message.file = 'Youtube video id is required';
                        }
                    }
                    if (this.content == '') {
                        this.error.content = true;
                        $('.note-editor').css('border-color', '#d61212');
                        this.error_message.content = 'Post content is required';
                    }
                }

            },
            savePostCallback(response) {
                if(response.status > 0){
                    window.location.href = '/admin/blog';
                }else{
                    if(Helpers.isAssoc(response.errors) > 0){
                        let message = '';
                        for(let field in response.errors){
                            message += response.errors[field][0];
                        }
                        swal("Error!", message, "error");
                    }else{
                        swal('Error', response.errors[0], 'error');
                    }
                }
                $.LoadingOverlay("hide");
            },
            handleFileUpload() {
                this.editable_file_path = '';

                this.file = this.$refs.file.files[0];
                if (!this.file.type.startsWith('image/')) {
                    return;
                }
                var img = document.createElement("img");
                img.classList.add("obj");
                img.file = this.file;
                img.classList.add('preview-thumb');
                $('#preview').empty();
                $('#preview').append(img);
                var reader = new FileReader();
                reader.onload = (function (aImg) { return function (e) { aImg.src = e.target.result; }; })(img);
                reader.readAsDataURL(this.file);
                this.imageDeleted = false;
            },
            deleteImage(){
                 $('#preview').empty();
                 this.editable_file_path = '';
                 this.imageDeleted = true;
                 this.file = '';
            },
            mediaTypeChanged() {
                if (this.file)
                    this.file = '';
            },
            cancelPost() {
                this.file = '';
                window.location.href = "/admin/blog"
            },
            updatePost() {
                this.content = $('#summernote').summernote('code');
                let formData = new FormData();
                formData.append('active', this.active);
                formData.append('title', this.title);
                formData.append('file', this.file);
                formData.append('content', this.content);
                formData.append('owner_type', 'post');
                formData.append('header_media_type', this.media_type);
                formData.append('keywords', this.keywords);
                $.LoadingOverlay("show");

                this.updatePostCall(this.id, formData, this.updatePostCallback);
            },
            updatePostCallback(response) {
                if(response.status > 0){
                    window.location.href = '/admin/blog';
                }else{
                    if(Helpers.isAssoc(response.errors) > 0){
                        let message = '';
                        for(let field in response.errors){
                            message += response.errors[field][0];
                        }
                        swal("Error!", message, "error");
                    }else{
                        swal('Error', response.errors[0], 'error');
                    }
                }
                $.LoadingOverlay("hide");
            },
        },
        watch: {
            title(val) {
                if (val != '') {
                    this.error.title = false;
                    this.error_message.title = '';
                }
            },
            file(val) {
                if (val != '') {
                    this.error.file = false;
                    this.error_message.file = '';
                }
            }
        },
        created() {


        },
        mounted() {
            var self = this;
            this.$nextTick(function () {
                $('#summernote').summernote({
                    height: 500,
                    minHeight: null,
                    maxHeight: null,
                    focus: true,
                    toolbar: [
                        // [groupName, [list of button]]
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['misc', ['codeview']]
                    ],
                    popover: false,
                    callbacks: {
                        onKeyup: function (e) {
                            self.content = this.code;
                            if (this.code != '') {
                                self.error.content = false;
                                self.error_message.content = '';
                                $('.note-editor').css('border-color', '#a9a9a9');
                            }
                        }
                    }
                });

                if (this.editable_post) {
                    this.id = this.editable_post.id;
                    this.title = this.editable_post.title;
                    this.active = this.editable_post.active;
                    this.active == 1 ? $('#active').iCheck('check') : $('#active').iCheck('chuncheckeck');
                    this.media_type = this.editable_post.header_media_type;
                    this.keywords = this.editable_post.keywords;
                    if (this.editable_post.header_media_type == 'picture') {
                        this.editable_file_path = this.editable_post.header_media_path;
                    } else if (this.editable_post.header_media_type == 'video') {
                        this.file = this.editable_post.header_media_path;
                    }
                    $('#summernote').summernote('code', this.editable_post.content);
                }
            });

            $('.skin-square input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
            });

            $('#active').on('ifChecked', function (event) {
                self.active = 1;
            });

            $('#active').on('ifUnchecked', function (event) {
                self.active = 0;
            });

        }
    }
</script>
<style>
    .summer_n {
        text-align: center;
    }

    .preview-thumb {
        width: 70%;
        height: auto;
    }

    .input-error-select {
        color: #d61212;
        border: 1px solid #b60707;
        border-radius: 5px
    }

    .message-error {
        color: #d61212;
        float: right;
        padding-top: 10px;
        font-size: 12px;
    }
</style>