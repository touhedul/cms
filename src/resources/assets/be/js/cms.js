
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Blog
Vue.component('posts-list', require('./components/blog/PostsListComponent.vue'));
Vue.component('posts-create', require('./components/blog/PostsCreateComponent.vue'));

//Pages
Vue.component('pages-list', require('./components/pages/PagesListComponent.vue'));
Vue.component('pages-create', require('./components/pages/PagesCreateComponent.vue'));

//Documents
Vue.component('documents-list', require('./components/documents/DocumentListComponent.vue'));
Vue.component('documents-create', require('./components/documents/DocumentCreateComponent.vue'));
Vue.component('documents-categories-list', require('./components/documents/DocumentCategoriesListComponent.vue'));



const cms = new Vue({
    el: '#cms-module'
});
