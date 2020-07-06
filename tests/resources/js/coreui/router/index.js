import Vue from 'vue'
import Router from 'vue-router'

// Containers
import Full from '@/containers/Full'

// Views
import Dashboard from '@/views/Dashboard'

// Views - Pages
import Page404 from '@/views/pages/Page404'
import Login from '@/views/pages/Login'

Vue.use(Router)

const router = new Router({
    mode: 'history',
    linkActiveClass: 'open active',
    scrollBehavior: () => ({ y: 0 }),
    routes: [{
            path: '/',
            redirect: '/dashboard',
            name: 'Home',
            component: Full,
            children: [{
                path: 'dashboard',
                name: 'Dashboard',
                component: Dashboard,
            }],
        },
        {
            path: '/login',
            name: 'Login',
            component: Login,
        },
        {
            path: '*',
            name: '404',
            component: Page404,
        },
    ],
})


router.beforeEach((to, from, next) => {
    /* auth middleware */
    if (to.matched.some(record => record.meta.auth)) {
        if (!localStorage.getItem('token')) {
            next('/login');
        } else {

            // check page and action permission
            // if (to.matched.some(record => record.meta.permission)) {
            //     let permission = to.meta.permission
            //     axios.post('role/check/permission', {
            //         "link": permission.link,
            //         "action": permission.action
            //     }).then(resp => {
            //         if (resp.data.status) {
            //             store.commit('set_permission', resp.data.data)

            //             // check parameter type
            //             if (to.matched.some(record => record.meta.id)) {
            //                 if (to.params.id) {
            //                     next();
            //                 } else {
            //                     next('/404')
            //                 }
            //             } else {
            //                 next();
            //             }
            //         } else {
            //             // access forbidden
            //             next('/500')
            //         }
            //     }).catch(e => {
            //         console.log(e)
            //     })
            // } else {
            //     next();
            // }
        }
    } else {
        next();
    }
})

export default router