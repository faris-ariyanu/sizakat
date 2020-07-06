// root state object.
// each Vuex instance is just a single state tree.
export default {
    version: __VERSION,
    state: {
        user: {},
        loading: false,
        permission: {
            create: 0,
            update: 0,
            read: 0,
            delete: 0,
        }
    }
}