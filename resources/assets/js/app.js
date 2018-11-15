
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import App from './App.vue';
import router from './router';
import FormMixin from './mixins/FormMixin'
import axios from 'axios'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
const axiosConfig = {
  baseURL: 'http://jdev.webbillingsystem',
  timeout: 30000
}

Vue.component('App', require('./App.vue'));
Vue.use(BootstrapVue);
Vue.prototype.$http = axios.create(axiosConfig)
Vue.mixin(FormMixin)
Vue.use(BootstrapVue);
/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: {
    App
  }
})
