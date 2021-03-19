/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
import VueTimeago from 'vue-timeago';
Vue.use(VueTimeago, {
    name: 'timeago', // component name, `timeago` by default
    locale: 'en-US',
    locales: {
        'en-US': require('vue-timeago/locales/en-US.json')
    }
})
import VueToast from 'vue-toast-notification';
Vue.use(VueToast);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);


Vue.component('chatbox', require('./components/Chatbox/Chatbox.vue').default);
Vue.component('contact-list', require('./components/Chatbox/ContactList.vue').default);
Vue.component('chat-conversation', require('./components/Chatbox/Conversation.vue').default);

Vue.component('articles', require('./components/admin/Articles.vue').default);
Vue.component('agencies', require('./components/admin/Agencies.vue').default);
Vue.component('staffs', require('./components/admin/Staff.vue').default);
Vue.component('dropdownlists', require('./components/admin/Dropdownlists.vue').default);
Vue.component('testservices', require('./components/admin/Testservices.vue').default);
Vue.component('packages', require('./components/admin/Packages.vue').default);
Vue.component('addons', require('./components/admin/Addon.vue').default);
Vue.component('sidebar', require('./components/admin/Sidebar.vue').default);
Vue.component('methodreference', require('./components/admin/Method.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
