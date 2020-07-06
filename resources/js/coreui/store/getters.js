// getters are functions like computed
export default {
    user(state) {
        return state.user
    },
    isAuthenticated(state) {
        return state.user.token !== null
    }
}