<template>
    <div class="container">
        <div v-if="!account"  class="row">
            <div class="col-md-12">
                <div v-if="current_posts.length > 0">
                    <div v-for="(post, index) in current_posts" class="row" style="padding: 40px" :key="index">
                        <div class="col-md-4 offset-md-2">
                            <a v-if="post.header_media_type == 'picture'" :href="(post.slug != null) ? '/blog/post/'+post.slug : '/blog/post/'+post.id" style="width: 100%; height: auto">
                                <img :src="'/storage/' + post.header_media_path" alt="Image name">
                            </a>
                            <div v-else-if="post.header_media_type == 'video'" class="embed-responsive embed-responsive-item embed-responsive-16by9">
                                <youtube class="ytp-cued-thumbnail-overlay" :video-id="post.header_media_path"></youtube>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <a :href="(post.slug != null) ? '/blog/post/'+post.slug : '/blog/post/'+post.id" >{{post.title}}
                            </a>
                            <div>
                                <span>{{post.author.name}} - </span> {{moment(post.created_at).format('LLLL')}}</div>
                            <p>
                                {{post.summary}}
                            </p>
                            <a v-if="post.comments.length > 0" :href="(post.slug != null) ? '/blog/post/'+post.slug : '/blog/post/'+post.id" style="float: left">
                                <i class="icon-comment-empty"></i>
                                <span>{{post.comments.length}}</span>
                            </a>
                            <a :href="(post.slug != null) ? '/blog/post/'+post.slug : '/blog/post/'+post.id"  style="float: right">
                                <span>Read more</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div style="text-align: center">
                        <h4>There aren't posts published yet :(</h4>
                        <!-- <a href="/admin/add-blog-post">Be the first</a> -->
                    </div>
                </div>
            </div>
        </div>
        <div v-else  class="row">
            <div class="col-md-12">
                <div v-if="current_posts.length > 0">
                    <div v-for="(post, index) in current_posts" class="row" style="padding: 40px" :key="index">
                        <div class="col-md-4">
                            <a v-if="post.header_media_type == 'picture'" :href="(post.slug != null) ? '/blog/post/'+post.slug : '/blog/post/'+post.id" style="width: 100%; height: auto">
                                <img :src="'/storage/' + post.header_media_path" alt="Image name">
                            </a>
                            <div v-else-if="post.header_media_type == 'video'" class="embed-responsive embed-responsive-item embed-responsive-16by9">
                                <youtube class="ytp-cued-thumbnail-overlay" :video-id="post.header_media_path"></youtube>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <a :href="(post.slug != null) ? '/blog/post/'+post.slug : '/blog/post/'+post.id" >{{post.title}}
                            </a>
                            <div>
                                <span>{{post.author.name}} - </span> {{moment(post.created_at).format('LLLL')}}</div>
                            <p>
                                {{post.summary}}
                            </p>
                            <a v-if="post.comments.length > 0" :href="(post.slug != null) ? '/blog/post/'+post.slug : '/blog/post/'+post.id" style="float: left">
                                <i class="icon-comment-empty"></i>
                                <span>{{post.comments.length}}</span>
                            </a>
                            <a :href="(post.slug != null) ? '/blog/post/'+post.slug : '/blog/post/'+post.id"  style="float: right">
                                <span>Read more</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div style="text-align: center">
                        <h4>There aren't posts published yet :(</h4>
                        <!-- <a href="/admin/add-blog-post">Be the first</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mixins: [],
        props: ['posts', 'account'],
        data() {
            return {
                moment:moment,
                current_posts: []
            }
        },
        methods: {

        },
        created() {
            if (this.posts) {
                this.current_posts = this.posts;
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
    .tt-post-grid__title {
        font-size: 24px;
    }

    .embed-responsive {
        position: relative !important;
    }

    hr {
        display: block;
        height: 1px;
        border: 0;
        border-top: 1px solid #ccc;
        margin: 1em 0;
        padding: 0;
    }

    .preview {
        width: 100%;
    }

    p {
        font-size: 14px;
    }

    .single-article {
        padding-top: 40px;
    } 
    img{
        image-rendering: auto;
        width: 100%;
        height: auto;
    }
</style>