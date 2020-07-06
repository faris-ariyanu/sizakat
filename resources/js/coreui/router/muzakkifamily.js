import List from '@/views/modules/muzakkifamily/Index'
import Form from '@/views/modules/muzakkifamily/Form'
import View from '@/views/modules/muzakkifamily/View'
const module = {
    name: "Keluarga Muzakki",
    link: "muzakkifamily"
}

export default {
    path: module.link,
    redirect: '/' + module.link,
    component: {
        render(c) { return c('router-view') }
    },
    meta: {
        label: module.name
    },
    children: [{
            path: '/',
            name: module.link,
            component: List,
            meta: {
                auth: true,
                label: module.name,
                permission: {
                    link: "muzakki",
                    action: "",
                }
            }
        },
        {
            path: 'add',
            name: 'Tambah' + module.link,
            component: Form,
            meta: {
                auth: true,
                label: 'Tambah ' + module.name,
                permission: {
                    link: "muzakki",
                    action: "store",
                }
            }
        },
        {
            path: 'edit/:id',
            name: 'edit' + module.link,
            component: Form,
            meta: {
                auth: true,
                label: 'Edit ' + module.name,
                permission: {
                    link: "muzakki",
                    action: "update",
                },
                id: 'number'
            }
        }, {
            path: 'view/:id',
            name: 'view' + module.link,
            component: View,
            meta: {
                auth: true,
                label: 'Lihat ' + module.name,
                permission: {
                    link: "muzakki",
                    action: "read",
                },
                id: 'number'
            }
        },
    ]
}