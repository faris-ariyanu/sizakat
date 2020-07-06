import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'

// Containers
import Full from '@/containers/Full'

// Views - Pages
import Page404 from '@/views/pages/Page404'
import Page403 from '@/views/pages/Page403'
import Login from '@/views/pages/Login'
import ChangePassword from '@/views/modules/profile/ChangePassword'
import EditProfile from '@/views/modules/profile/EditProfile'

// import route
import dashboard from '@/router/dashboard.js'
import role from '@/router/role.js'
import menu from '@/router/menu.js'
import user from '@/router/user.js'
import muzakki from '@/router/muzakki.js'
import muzakkifamily from '@/router/muzakkifamily.js'
import mustahik from '@/router/mustahik.js'
import periode from '@/router/periode.js'
import kualitaszakat from '@/router/kualitaszakat.js'
import categorymustahik from '@/router/categorymustahik.js'
import zakat from '@/router/zakat.js'
import report from '@/router/report.js'
import hewanqurban from '@/router/hewanqurban.js'
import trxhewanqurban from '@/router/trxhewanqurban.js'
import qurban from '@/router/qurban.js'

Vue.use(Router)
const router = new Router({
    //mode: 'history',
    linkActiveClass: 'open active',
    scrollBehavior: () => ({ y: 0 }),
    routes: [{
            path: '/',
            redirect: '/dashboard',
            name: 'Home',
            component: Full,
            children: [{
                    path: '/change-password',
                    name: 'ChangePassword',
                    component: ChangePassword,
                    meta: {
                        label: "Ubah Password",
                        permission: {
                            link: "ChangePassword",
                            action: "change-password",
                        }
                    }
                },
                {
                    path: '/edit-profile',
                    name: 'EditProfile',
                    component: EditProfile,
                    meta: {
                        label: "Ubah Profil",
                        permission: {
                            link: "EditProfile",
                            action: "edit-profile",
                        }
                    }
                },
                dashboard,
                role,
                menu,
                user,
                muzakki,
                muzakkifamily,
                mustahik,
                periode,
                kualitaszakat,
                categorymustahik,
                zakat,
                report,
                hewanqurban,
                trxhewanqurban,
                qurban,
            ]
        },
        {
            path: '/login',
            name: 'Login',
            component: Login,
        },
        {
            path: '/403',
            name: '403',
            component: Page403,
        }, {
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

            //check page and action permission
            if (to.matched.some(record => record.meta.permission)) {
                let permission = to.meta.permission
                axios.post('me/check-permission', {
                    "link": permission.link,
                    "action": permission.action
                }).then(resp => {
                    store.commit('set_permission', resp.data.data)
                    next();
                })
            } else {
                next();
            }
        }
    } else {
        next();
    }
})

export default router