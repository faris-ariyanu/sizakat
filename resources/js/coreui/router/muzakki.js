import List from '@/views/modules/muzakki/Index'
import Form from '@/views/modules/muzakki/Form'
import View from '@/views/modules/muzakki/View'
import Trx from '@/views/modules/muzakki/Trx'
const module = {
    name: "Muzakki",
    link: "muzakki"
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
                    link: module.link,
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
                    link: module.link,
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
                    link: module.link,
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
                    link: module.link,
                    action: "read",
                },
                id: 'number'
            }
        }, {
            path: 'transaksi',
            name: 'transaksi' + module.link,
            component: Trx,
            meta: {
                auth: true,
                label: 'Transaksi ' + module.name,
                permission: {
                    link: module.link,
                    action: "read",
                }
            }
        },
    ]
}