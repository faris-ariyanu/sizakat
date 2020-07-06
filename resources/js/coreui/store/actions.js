// actions are functions that cause side effects and can involve
// asynchronous operations.
import axios from 'axios'
import router from '@/router'

export default {
    login({ commit }, formData) {
        commit('authUser', formData)
        axios.defaults.headers.common['Authorization'] = "Bearer " + formData.token
        router.push('/')
    },
    logout({ commit }) {
        delete axios.defaults.headers.common['Authorization'];
        commit('logoutUser')
    },
}