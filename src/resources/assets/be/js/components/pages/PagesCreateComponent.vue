<template>
    <div class="card custom-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="basicInput">
                                    <b>Page details</b>
                                </label>
                                <hr>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="row">
                        <div class="col-md-8">
                            <fieldset class="form-group">
                                <label for="basicInput">
                                    <b>Title</b>
                                </label>
                                <input type="text" v-model="p_title" class="form-control" v-bind:class="{'input-error-select' : error.p_title}">
                                <span v-if="error.p_title" class="message-error">{{error_message.p_title}}</span>
                            </fieldset>
                        </div>
                        <div class="col-md-4" style="align-self: center;margin-top: 10px;">
                            <fieldset>
                                <div class="input-group">
                                    <div class="skin skin-square">
                                        <div class="form-group text-right">
                                            <input type="checkbox" id="visibility">
                                            <label for="visibility">Visibility</label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 offset-md-2">
                    <fieldset class="form-group">
                        <label for="basicInput">
                            <b>Select picture</b>
                        </label>
                        <fieldset class="form-group" v-bind:class="{'input-error-select' : error.file}" style="margin-bottom:0px;">
                            <label class="custom-file center-block block">
                                <input type="file" id="file" ref="file" v-on:change="handleFileUpload()" class="custom-file-input"  accept="image/png, image/jpeg">
                                <span class="custom-file-control"></span>
                            </label>
                        </fieldset>
                        <p v-if="error.picture" class="message-error" style="float:left;margin-bottom:5px;padding-top:3px;">{{error_message.picture}}</p>
                        <p style="font-size:11px;">Max size: 15MB. Dimensions: 1920x720</p>
                    </fieldset>
                </div>
                <!-- <div class="row" style="text-align: right"> -->
                    <div class="col-md-4">
                        <img v-if="editable_file_path" :src="'/storage/' + editable_file_path" class="preview-thumb" />
                        <div id="preview"></div>
                        <a v-if="editable_file_path || !imageDeleted" @click="deleteImage()">Delete</a>
                    </div>
                <!-- </div> -->
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <label for="basicInput">
                        <b>Content</b>
                    </label>
                    <div id="summernote" class="summer_n"></div>
                    <span v-if="error.p_content" class="message-error">{{error_message.p_content}}</span>
                </div>
            </div>
            <div class="row" style="margin-top:30px;">
                <div class="col-md-8 offset-md-2">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="basicInput">
                                    <b>Search engine listing preview</b>
                                </label>
                                <hr>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="basicInput">
                                    <b>Page title</b>
                                </label>
                                <span style="float:right;color:#bbbaba;font-size:13px;">{{t_characters}} of {{t_length_ch}} characteres used</span>
                                <input type="text" v-model="m_title" class="form-control" :maxlength="t_length_ch">
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="basicInput">
                                    <b>Page description</b>
                                </label>
                                <span style="float:right;color:#bbbaba;font-size:13px;">{{d_characters}} of {{d_length_ch}} characteres used</span>
                                <textarea rows="6" type="text" v-model="m_description" class="form-control" :maxlength="d_length_ch"></textarea>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="basicInput">
                                    <b>URL and Handle</b>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">{{host}}/pages/</span>
                                    </div>
                                    <input type="text" class="form-control" aria-describedby="basic-addon1" v-model="m_url" v-bind:class="{'input-error-select' : error.m_url}">
                                </div>
                                <span v-if="error.m_url" class="message-error">{{error_message.m_url}}</span>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="basicInput">
                                    <b>Keywords</b>
                                </label>
                                <!-- <span style="float:right;color:#bbbaba;font-size:13px;">{{d_characters}} of {{d_length_ch}} characteres used</span> -->
                                <textarea rows="4" type="text" v-model="m_keywords" class="form-control"></textarea>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2" style="margin-top: 10px">
                    <button v-if="editable_page" @click="updatePage()" class="btn btn-primary" type="button" style="float: right; margin-left: 5px">
                        Update
                    </button>
                    <button v-else @click="savePage()" class="btn btn-primary" type="button" style="float: right; margin-left: 5px">
                        Save
                    </button>
                    <button @click="cancelPage()" class="btn btn-danger" type="button" style="float: right">
                        Cancel
                    </button>
                    <a v-if="editable_page != null" :href="(editable_page.m_url != null) ? '/pages/'+editable_page.m_url : '#'" target="blanck" class="btn btn-warning" style="float: right; margin-right:5px;">View live</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PageServices from '../../services/PageServices.js';
    import Helpers from '../../../../../../misc/helpers';
    export default {
        mixins: [PageServices,Helpers],
        props: ['editable_page', 'host'],
        data() {
            return {
                id: '',
                p_title: '',
                p_content: '',
                m_title: '',
                m_description: '',
                m_keywords: '',
                m_url: '',
                media_type: 'picture',
                visibility: 0,
                file: '',
                editable_file_path: '',

                error: {
                    p_title: false,
                    p_name: false,
                    p_content: false,
                    m_url: false,
                    picture: false
                },
                error_message: {
                    p_title: '',
                    p_name: '',
                    p_content: '',
                    m_url: '',
                    picture: ''
                },
                t_characters:0,
                d_characters:0,
                d_length_ch:320,
                t_length_ch:70,
                imageDeleted: true
            }
        },
        methods: {
            savePage() {
                this.p_content = $('#summernote').summernote('code');
                if (this.p_title != '' && this.p_content != '' && this.m_url) {
                    let formData = new FormData();
                    formData.append('p_content', this.p_content);
                    formData.append('p_title', this.p_title);
                    formData.append('m_title', this.m_title);
                    formData.append('m_description', this.m_description);
                    formData.append('m_keywords', this.m_keywords);
                    formData.append('m_url', this.m_url);
                    formData.append('file', this.file);
                    formData.append('owner_type', 'page');
                    formData.append('header_media_type', this.media_type);
                    formData.append('visibility', this.visibility);

                    $.LoadingOverlay("show");
                    this.savePageCall(formData, this.savePageCallback);
                } else {
                    if (this.p_title == '') {
                        this.error.p_title = true;
                        this.error_message.p_title = 'Page title is required';
                    }
                    if (this.p_content == '<p><br></p>') {
                        this.error.p_content = true;
                        $('.note-editor').css('border-color', '#d61212');
                        this.error_message.p_content = 'Page content is required';
                    }
                    if (this.m_url == '') {
                        this.error.m_url = true;
                        this.error_message.m_url = 'Page URL is required';
                    }
                }

            },
            savePageCallback(response) {
                if(response.status > 0){
                    window.location.href = '/admin/pages';
                }else{
                    if(Helpers.isAssoc(response.errors) > 0){
                        let message = '';
                        for(let field in response.errors){
                            this.error_message[field] = response.errors[field][0];
                            this.error[field] = true;
                        }
                        swal("Error!", 'Check the input fields', "error");
                    }else{
                        swal('Error', response.errors[0], 'error');
                    }
                }
                $.LoadingOverlay("hide");
            },
            handleFileUpload() {
                this.editable_file_path = '';
                if(this.$refs.file.files[0].size < 15728640){
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
                    $('#preview img').css('width','100%');
                    var reader = new FileReader();
                    reader.onload = (function (aImg) { return function (e) { aImg.src = e.target.result; }; })(img);
                    reader.readAsDataURL(this.file);
                    this.imageDeleted = false;
                }else{
                    swal('Error!','Picture is too large', 'error');
                }
                
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
            cancelPage() {
                this.file = '';
                window.location.href = "/admin/pages"
            },
            updatePage() {
                this.p_content = $('#summernote').summernote('code');
                 if (this.p_title != '' && this.p_content != '' && this.m_url) {

                    let formData = new FormData();
                    formData.append('p_content', this.p_content);
                    formData.append('p_title', this.p_title);
                    formData.append('m_title', this.m_title);
                    formData.append('m_description', this.m_description);
                    formData.append('m_keywords', this.m_keywords);
                    formData.append('m_url', this.m_url);
                    formData.append('file', this.file);
                    formData.append('owner_type', 'page');
                    formData.append('header_media_type', this.media_type);
                    formData.append('visibility', this.visibility);
                    if(this.file == ''){
                        formData.append('header_media_path', this.editable_file_path);
                    }
                    $.LoadingOverlay("show");
                    this.updatePageCall(this.id, formData, this.updatePageCallback);
                }else{
                    if (this.p_title == '') {
                        this.error.p_title = true;
                        this.error_message.p_title = 'Page title is required';
                    }
                    if (this.p_content == '<p><br></p>') {
                        this.error.p_content = true;
                        $('.note-editor').css('border-color', '#d61212');
                        this.error_message.p_content = 'Page content is required';
                    }
                    if (this.m_url == '') {
                        this.error.m_url = true;
                        this.error_message.m_url = 'Page URL is required';
                    }
                }
            },
            updatePageCallback(response) {
                if(response.status > 0){
                    window.location.href = '/admin/pages';
                }else{
                    if(Helpers.isAssoc(response.errors) > 0){
                        for(let field in response.errors){
                            this.error_message[field] = response.errors[field][0];
                            this.error[field] = true;
                        }
                        swal("Error!", 'Check the input fields', "error");
                    }else{
                        swal('Error', response.errors[0], 'error');
                    }
                }
                $.LoadingOverlay("hide");
            },
        },
        watch: {
            p_title(val) {
                if (val != '') {
                    this.error.p_title = false;
                    this.error_message.p_title = '';
                }
            },
            m_url(val) {
                if (val != '') {
                    this.error.m_url = false;
                    this.error_message.m_url = '';
                }
            },
            file(val) {
                if (val != '') {
                    this.error.file = false;
                    this.error_message.file = '';
                }
            },
            m_description(val){
                this.d_characters = val.length;
            },
            m_title(val){
                this.t_characters = val.length;
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
                    // focus: true,
                    toolbar: [
                        // [groupName, [list of button]]
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['insert', ['link', 'picture']],
                        ['misc', ['codeview']]
                    ],
                    // popover: false,
                    callbacks: {
                        onKeyup: function (e) {
                            self.p_content = this.code;
                            if (this.code != '') {
                                self.error.p_content = false;
                                self.error_message.p_content = '';
                                $('.note-editor').css('border-color', '#a9a9a9');
                            }
                        }
                    }
                });

                if (this.editable_page) {
                    this.id = this.editable_page.id;
                    this.p_title = this.editable_page.p_title;
                    this.p_content = this.editable_page.p_content;
                    this.m_title = this.editable_page.m_title;
                    this.m_description = this.editable_page.m_description;
                    this.m_keywords = this.editable_page.m_keywords;
                    this.m_url = this.editable_page.m_url;
                    this.editable_file_path = this.editable_page.header_media_path;
                    $('#summernote').summernote('code', this.editable_page.p_content);
    
                    if(this.editable_page.visibility >0){
                        $('#visibility').iCheck('check');
                        this.visibility = 1;
                    }else{
                        $('#visibility').iCheck('uncheckeck');
                        this.visibility = 0;
                    }
                }else{
                    $('#visibility').iCheck('check');
                    this.visibility = 1;
                }
                $('.note-popover').css('display','none');

                $('.skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
                });

                $('#visibility').on('ifChecked', function (event) {
                    self.visibility = 1;
                });

                $('#visibility').on('ifUnchecked', function (event) {
                    self.visibility = 0;
                });
            });
        }
    }
</script>
<style scoped>
    .summer_n {
        text-align: center;
    }

    .preview-thumb {
        width: 100%;
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

    .modal-header{
        display: unset!important;
    }
    .input-group-text {
    display: -webkit-box;
    display: -webkit-flex;
    display: -moz-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -moz-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    padding: 0.75rem 1rem;
    margin-bottom: 0;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.25;
    color: #4E5154;
    text-align: center;
    white-space: nowrap;
    background-color: #F5F7FA;
    border: 1px solid #BABFC7;
    border-right: unset;
    border-bottom-left-radius: 0.25rem;
    border-top-left-radius: 0.25rem;
}
</style>