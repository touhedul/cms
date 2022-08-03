<template>
    <div>
        <div v-if="current_posts.length > 0" class="row">
            <div v-for="(post, index) in current_posts" class="col-lg-4 col-xl-3" :key="index">
                <div v-if="post.header_media_type == 'picture'" class="card" style="height:420px;">
                    <div class="card-content" style="text-align: center">
                        <a :href="(post.slug != null) ? '/blog/post/'+post.slug : '/blog/post/'+post.id" target="blank" class="tt-post-grid__image ttg-image-translate--left">
                            <img class="card-img-top img-fluid" :src="'/storage/' + post.header_media_path" style="height: 190px; width: 100%;object-fit: cover;">
                        </a>
                        <div class="card-body" style="text-align: left">
                            <h4 class="card-title">
                                <span style="display:inline-block; width:100%;">
                                    <p class="text-truncate" style="margin:0;" >
                                        {{(post.title)}}
                                    </p>
                                </span> 
                            </h4>
                            <p class="card-text">
                                {{post.summary}}
                            </p>
                            
                        </div>
                    </div>
                    <div class=" text-right action-buttom">
                        <p v-if="post.active == 1"><span style="font-weight:bold; text-transform: uppercase;">Status: </span><span style="font-weight:bold;">Active</span></p>
                        <p v-else-if="post.active == 0"><span style="font-weight:bold; text-transform: uppercase;">Status: </span><span style="font-weight:bold;">Inactive</span></p>
                        <a :href="(post.slug != null) ? '/blog/test/'+post.slug : '/blog/test/'+post.id" target="blanck" class="btn btn-outline-primary">View live</a>
                        <a :href="'/admin/edit-blog-post/' + post.id" class="btn btn-outline-amber" title="Edit"><i class="fa fa-pencil"></i></a>
                        <a @click="deletePost(post.id)" class="btn btn-outline-danger" style="cursor: pointer;" title="Remove"><i class="fa fa-trash-o"></i></a>
                    </div>
                </div>
                <div v-else-if="post.header_media_type == 'video'" class="card">style="height:420px;"
                    <div class="card-content">
                        <div class="embed-responsive embed-responsive-item embed-responsive-16by9">
                            <youtube class="ytp-cued-thumbnail-overlay" :video-id="post.header_media_path" style="max-height: 190px; width: auto;object-fit: cover;"></youtube>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">
                                <span style="display:inline-block; width:100%;">
                                    <p class="text-truncate" style="margin:0;" >
                                        {{(post.title)}}
                                    </p>
                                </span> 
                            </h4>
                            <p class="card-text">
                                {{post.summary}}
                            </p>
                        </div>
                    </div>
                     <div class=" text-right action-buttom">
                        <p v-if="post.active == 1"><span style="font-weight:bold; text-transform: uppercase;">Status: </span><span style="font-weight:bold;">Active</span></p>
                        <p v-else-if="post.active == 0"><span style="font-weight:bold; text-transform: uppercase;">Status: </span><span style="font-weight:bold;">Inactive</span></p>
                        <a :href="(post.slug != null) ? '/blog/test/'+post.slug : '/blog/test/'+post.id" target="blanck" class="btn btn-outline-primary">View live</a>
                        <a :href="'/admin/edit-blog-post/' + post.id" class="btn btn-outline-amber" title="Edit"><i class="fa fa-pencil"></i></a>
                        <a @click="deletePost(post.id)" class="btn btn-outline-danger" style="cursor: pointer;" title="Remove"><i class="fa fa-trash-o"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="row">
            <div class="col-md-12">
                <div style="text-align: center">
                    <h4>You have not post created or published</h4>
                    <a href="/admin/add-blog-post" class="create-link">Create your first post now</a>
                </div>
            </div>
        </div>
        <item-paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getPosts()" :offset="offset"></item-paginator>
    </div>
</template>

<script>
    import BlogServices from '../../services/BlogServices.js';
    import Paginator from '../../../../../../components/Paginator.vue'
    export default {
        mixins: [BlogServices],
        components: {
            'item-paginator': Paginator
        },
        props: ['posts'],
        data() {
            return {
                current_posts: [],
                paginator: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 8
            }
        },
        methods: {
            deletePost(id) {
                swal({
                    title: "Remove post",
                    text: "Are you sure you want remove this post definitely?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                }).then((confirm) => {
                    if (confirm) {
                        $.LoadingOverlay("show");
                        this.deletePostCall(id, this.deletePostCallback);
                    }
                });
            },
            deletePostCallback(code, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    if (this.current_posts.length > 0) {
                        for (var i in this.current_posts) {
                            if (this.current_posts[i].id == data) {
                                this.current_posts.splice(i, 1);
                            }
                        }
                    } else {
                        this.current_posts = [];
                    }
                }
            },
            getPosts() {
                $.LoadingOverlay("show");
                axios.get(`/api/admin/blogs?page=${this.paginator.current_page}`)
                    .then((response) => {
                        $.LoadingOverlay("hide");
                        this.paginator = response.data;
                        this.current_posts = response.data.data;
                    })
                    .catch(() => {
                        console.log('handle server error from here');
                    });
            },
        },
        created() {
            if (this.posts) {
                this.paginator = this.posts;
                this.current_posts = this.posts.data;
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