<template>
    <div class="row">
        <div class="col-md-12 text-center">
            <h3>{{post.title}}</h3>
        </div>
        <div class="col-md-12">
            <div v-if="post.header_media_type == 'picture'">
                <img :src="'/storage/' + post.header_media_path">
            </div>
            <div v-else-if="post.header_media_type == 'video'" class="embed-responsive embed-responsive-item embed-responsive-16by9">
                <youtube class="ytp-cued-thumbnail-overlay" :video-id="post.header_media_path"></youtube>
            </div>
        </div>
        <div class="col-md-12" style="font-size: 18px">
            <span>Admin - </span> {{moment(post.created_at).format('LLLL')}}
        </div>
        <div class="col-md-12">
            <div class="row" v-html="post.content"></div>
        </div>
        <div class="col-md-12">
            <hr>
            <div v-if="current_post.comments != null && current_post.comments.length > 0" >
                <div >{{current_post.comments.length}}
                    <span v-if="current_post.comments.length == 1">Comment</span>
                    <span v-if="current_post.comments.length > 1">Comments</span>
                </div>
                <!--comment-->
                <div v-for="(comment, index) in current_post.comments"  :key="index">
                    <template>
                        <div style="width: 100%">
                            <div>
                                <span>{{comment.user.name}}</span> on {{moment(post.created_at).format("dddd, MMMM Do YYYY")}}
                            </div>
                            <p style="font-size: 14px">{{comment.comment}}</p>
                            <div v-if="current_user != null && comment.user.id == current_user.id">
                                <a @click="removeComment(comment.id)" style="float: right; color: red; cursor: pointer;">remove</a>
                            </div>
                        </div>

                    </template>
                </div>
                <!--comment-->
            </div>
        </div>
        <div class="col-md-12">
            <div>Leave a Comment</div>
            <label>
                <div class="row">
                    <div class="col-md-2">
                        <span>Comment:</span>
                    </div>
                    <div class="col-md-10">
                        <textarea v-model="comment.content" :placeholder="placeholder" class="colorize-theme6-bg" v-bind:class="{'input-error-select' : error.content}">Wtite your comments here</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span v-if="error.content" class="message-error">{{error_messages.content}}</span>
                    </div>
                </div>
            </label>
            <div class="row" style="margin-top: 0px !important">
                <div class="col-md-10 offset-md-2" style="text-align: right">
                    <button @click="addComment()" class="btn btn--xs-flw">
                        <span v-if="current_user!=null">Submit</span>
                        <span v-else>Login</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BlogServices from '../../services/BlogServices';
    export default {
        mixins: [BlogServices],
        props: ['post', 'current_user'],
        data() {
            return {
                moment:moment,
                current_post: '',
                comment: {
                    post_id: '',
                    content: ''
                },
                error: {
                    content: false
                },
                error_messages: {
                    name: '',
                    content: ''
                },
                placeholder:'Leave a comment'
            }
        },
        methods: {
            addComment() {
                if(this.current_user == null){
                    window.location = '/login';
                }else{
                    if (this.comment.content != '') {
                    // $.LoadingOverlay("show");
                    this.addCommentCall(this.comment, this.addCommentCallback);
                    } else {
                        this.error.content = true;
                        this.error_messages.content = 'Comment is required';
                    }
                }
                
            },
            addCommentCallback(code, data) {
                // $.LoadingOverlay("hide");
                if (code == '200') {
                    window.location.reload();
                }
            },
            removeComment(id) {
                // $.LoadingOverlay("show");
                this.removeCommentCall(id, this.removeCommentCallback);
            },
            removeCommentCallback(code, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    if (this.current_post.comments.length > 0) {
                        for (var i in this.current_post.comments) {
                            if (this.current_post.comments[i].id == data) {
                                this.current_post.comments.splice(i, 1);
                            }
                        }
                    }
                }
            }
        },
        watch: {
            'comment.content'(val) {
                if (val != '') {
                    this.error.content = false;
                    this.error_messages.content = '';
                }
            }
        },
        created() {
            if(this.current_user == null){
                this.placeholder = 'You have to be logged';
            }
            if (this.post) {
                this.current_post = this.post;
                this.comment.post_id = this.post.id;
            }
        },
        mounted() {
            var _this = this;
            this.$nextTick(function(){
                if(_this.account){
                    $("#cms-module").removeClass('content');
                }
            })
        }
    }
</script>
<style>
    .tt-post-head__title {
        font-size: 36px
    }

    .input-error-select {
        color: #d61212 !important;
        border: 1px solid #b60707 !important;
        border-radius: 5px !important;
    }

    .message-error {
        color: #d61212 !important;
        float: right !important;
        padding-top: 10px !important;
        font-size: 12px !important;
    }
</style>