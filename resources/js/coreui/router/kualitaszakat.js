import List from '@/views/modules/kualitaszakat/Index'
import Form from '@/views/modules/kualitaszakat/Form'
const module = {
    name: "Kualitas Zakat",
    link: "kualitaszakat"
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
        }
    ]
}