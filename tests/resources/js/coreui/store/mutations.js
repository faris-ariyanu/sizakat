// mutations are operations that actually mutates the state.
// each mutation handler gets the entire state tree as the
// first argument, followed by additional payload arguments.
// mutations must be synchronous and can be recorded by plugins
// for debugging purposes.
import state from './state'
import { defaultMutations } from 'vuex-easy-access'

export default {
    ...defaultMutations(state),
    authUser(state, userData) {
        state.user = userData
        localStorage.setItem('token', userData.token)
        localStorage.setItem('id', userData.id)
    },
    logoutUser(state) {
        state.user = {}
        localStorage.removeItem('token')
        localStorage.removeItem('id')
    },
    set_permission(state, data) {
        state.permission = data
    },
    set_loading(state, boolean) {
        state.loading = boolean
    }
}