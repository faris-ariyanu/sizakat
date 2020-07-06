// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import Datepicker from 'vuejs-datepicker'
import { id } from 'vuejs-datepicker/dist/locale'
import Notifications from 'vue-notification'
import Sweetalert from 'vue-sweetalert2'
import VeeValidate from 'vee-validate'
import Loading from './components/Loading'
import Select2 from './components/Select'
import App from './App'
import router from './router'
import store from './store'

Vue.use(BootstrapVue)
Vue.use(Notifications)
Vue.use(Sweetalert)
Vue.use(VeeValidate)

Vue.filter('state', (value, dirtyOnly = true) => {
    if (dirtyOnly) {
        if (!value.$dirty)
            return null
    }

    return !value.$invalid ? 'valid' : 'invalid'
})

Vue.component('b-loading', Loading)
Vue.component('b-select-2', Select2)
Vue.component('b-datepicker', {
    extends: Datepicker,
    props: {
        bootstrapStyling: {
            type: Boolean,
            default: true,
        },
        language: {
            type: Object,
            default: () => id,
        },
    },
})

// axios setting
Vue.prototype.$axios = axios
axios.defaults.baseURL = document.head.querySelector('meta[name="base-url"]').content + '/api/v1'
axios.defaults.headers.get['Content-Type'] = 'application/json'

if (localStorage.getItem('token')) {
    axios.defaults.headers.common['Authorization'] = "Bearer " + localStorage.getItem('token');
}


axios.interceptors.request.use(function(config) {
    store.commit('set_loading', true)
    return config;
}, function(error) {
    return Promise.reject(error);
});

// 401, 422 ,500 handle
axios.interceptors.response.use(function(response) {
    store.commit('set_loading', false)
    return response
}, function(error) {
    store.commit('set_loading', false)
    if (error.response.status === 401) {
        store.dispatch('logout')
        router.push('/login')
    }

    if (error.response.status === 500) {
        alert("Kode 500,Terjadi kesalahan sistem, silahkan coba lagi");
    }

    return Promise.reject(error)
})


export default new Vue({
    el: '#app',
    router: router,
    store: store,
    components: { App },
    template: '<App/>',
})