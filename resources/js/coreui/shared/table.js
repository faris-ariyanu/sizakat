export default {
    get($self) {
        let _ = $self,
            params = {
                'limit': _.config.limit,
                'offset': _.config.offset,
                'orderby': _.config.orderby,
                'sort': _.config.sort,
            },
            search = _.config.search
        params = Object.assign(params, search)
        _.$axios.get(_.config.api, {
            params: params
        }).then(res => {
            if (res.data.status) {
                _.config.datas = res.data.data.rows
                _.config.total = res.data.data.total
            }
        })
    },
    sort: ($self, orderby) => {
        let _ = $self,
            elm = document.getElementById(orderby)
        elm.classList.remove('fa-sort', "fa-sort-" + _.config.sort)
        _.config.orderby = orderby
        _.config.sort = (_.config.sort == "asc") ? "desc" : "asc"
        elm.classList.add("fa-sort-" + _.config.sort)
        _.get()
    },
    gotoPage($self, page) {
        let _ = $self
        _.config.offset = (parseInt(page) - 1) * parseInt(_.config.limit)
        _.get();
    },
    destroy($self, id) {
        let _ = $self
        _.$swal.fire({
            title: 'Yakin akan menghapus data yang dipilih?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                _.$axios.delete(_.config.api, {
                    params: { 'id': id }
                }).then(res => {
                    _.response = {
                        variant: (res.data.status) ? "success" : "danger",
                        message: res.data.message
                    }
                    _.get()
                })
            }
        })
    },
    doPrint($self, id) {
        let _ = $self
        _.$axios.get(_.config.api + '/getprint', {
            params: { "id": id }
        }).then(res => {
            if (res.data.status) {
                window.open(res.data.data.url)
            }
        })
    },
    doExport($self) {
        let _ = $self
        _.$axios.get(_.config.api + '/export', {
            params: _.config.search
        }).then(res => {
            if (res.data.status) {
                window.open(res.data.data.url)
            }
        })
    }
}