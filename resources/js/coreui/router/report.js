import List from '@/views/modules/report/Index'
const module = {
    name: "Laporan",
    link: "report"
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
    }]
}