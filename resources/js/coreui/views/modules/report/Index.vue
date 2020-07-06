<template>
	<div class="animated fadeIn">
        <b-card>
            <form class="form-horizontal" @submit.prevent="get">
                <div slot="header">
                    <strong>Buat Laporan</strong>
                </div>
                <b-row>
                    <div class="col-sm-12 mb-10" v-if="response.message">
                        <b-alert show :variant="response.variant">
                            {{response.message}}
                        </b-alert>
                    </div>
                    <div class="col-sm-12 mb-10">
                        <b-row>
                            <div class="col-sm-3">
                                <b-form-group>
                                    <label for="periode">Periode</label>
                                    <select class="form-control" v-model="config.search.periode" required name="Periode">
                                        <option value="">Pilih Periode</option>
                                        <option v-for="row in params.periode" v-bind:value="row.year">{{ row.year }}</option>
                                    </select>
                                </b-form-group>
                            </div>
                            <div class="col-sm-3">
                                <b-form-group>
                                    <label for="report_type">Jenis Laporan</label>
                                    <select v-model="config.search.report_type" required class="form-control">
                                        <option value="today">Laporan Hari Ini</option>
                                        <option value="date">Berdasarkan Tanggal</option>
                                    </select>
                                </b-form-group>
                            </div>
                            <div class="col-sm-3" v-if="config.search.report_type == 'date'">
                                <b-form-group>
                                    <label for="date_start">Tanggal Awal</label>
                                    <input type="date" required class="form-control" v-model="config.search.date_start"/>
                                </b-form-group>
                            </div>
                            <div class="col-sm-3" v-if="config.search.report_type == 'date'">
                                <b-form-group>
                                    <label for="date_end">Tanggal Akhir</label>
                                    <input type="date" required class="form-control" v-model="config.search.date_end"/>
                                </b-form-group>
                            </div>

                            <b-col sm="3">
                                <b-form-group>
                                    <label for="name">Filter Tipe Zakat</label>
                                    <div class="col-sm-12 no-padding">
                                        <label class="checkbox-inline" v-for="i in params.type" style="margin-right: 12px;">
                                            <input type="checkbox" v-model="config.search.filter[i.code]" :value="i.code">
                                                {{i.name}}
                                        </label>
                                    </div>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </div>
                </b-row>
                <div slot="footer">
                    <button type="submit" class="btn btn-primary">
                        Buat Laporan
                    </button>
                </div>
            </form>
        </b-card>
    	<b-card>
		    <div slot="header">
		    	<strong>Laporan Periode {{config.search.periode}}</strong>
		    </div>
		    <b-row>
	            <b-col sm="12">
	            	<table class="table b-table">
	            		<thead v-if="config.total">
			                <tr>
			                    <th v-for="h in config.header" :width="h.width">
			                        <a v-if="h.value" 
			                        	class="sort dark-font"
			                        	v-on:click="sort(h.value)"
			                        	href="javascript:void(0)">
			                            {{h.title}}
			                            <i :id="h.value" class="fa fa-sort"></i>
			                        </a>
			                        <a v-if="!h.value"
			                        	class="sort dark-font" :id="h.value"
			                        	href="javascript:void(0)">
			                            {{h.title}}
			                        </a>
			                    </th>
			                </tr>
			            </thead>
			            <tbody>
			                <tr v-if="config.total" v-for="data in config.datas">
								<td>
									<router-link :to="'/zakat/view/'+data.transaction_id">
										<b>{{data.transaction_number}}</b>
									</router-link>
								</td>
			                    <td>{{data.created_at}}</td>
			                    <td>{{data.zakat_type}}</td>
			                    <td>{{data.muzakki_name}} <br> {{data.muzakki_address}}<br> {{data.muzakki_phone}}</td>
			                    <td>{{data.transaction_type}}</td>
                                <td>{{data.income_goods | currency}}</td>
                                <td>{{data.income_value | currency}}</td>
			                </tr>
			                <tr v-if="!config.total">
			                    <td :colspan="config.header.length" align="center"> No data available. </td>
			                </tr>
			            </tbody>
			            <tfoot v-if="config.total">
			                <tr>
			                    <td :colspan="config.header.length">
			                        <div class="pull-left">
			                            <p>Total {{config.total}} data</p>
			                        </div>
			                        <div class="pull-right pagination">
			                            <b-pagination @change="gotoPage" :total-rows="config.total" v-model="config.page" :per-page="config.limit">
	                                	</b-pagination>
			                        </div>
			                    </td>
			                </tr>
			            </tfoot>
	            	</table>
	            </b-col>
        	</b-row>
		</b-card>
        <b-card>
                <div slot="header">
                    <strong>Rekap Laporan Periode {{config.search.periode}}</strong>
                </div>
                <b-row>
                    <div class="col-sm-12 mb-10">
                        <b-row>
                            <div class="col-sm-2">
                                <label>Fitrah Uang</label>
                            </div>
                            <div class="col-sm-3">
                                <label><b>{{config.rekap.ftr_uang.total | currency}} orang</b></label>
                            </div>
                            <div class="col-sm-3">
                                <label><b>Rp. {{config.rekap.ftr_uang.amount | currency}}</b></label>
                            </div>
                        </b-row>
                        <b-row>
                            <div class="col-sm-2">
                                <label>Fitrah Beras</label>
                            </div>
                            <div class="col-sm-3">
                                <label><b>{{config.rekap.ftr_beras.total | currency}} orang</b></label>
                            </div>
                            <div class="col-sm-3">
                                <label><b>{{config.rekap.ftr_beras.amount | currency}} Kg</b></label>
                            </div>
                        </b-row>
                        <b-row>
                            <div class="col-sm-2">
                                <label>Fidyah Uang</label>
                            </div>
                            <div class="col-sm-3">
                                <label><b>{{config.rekap.fdy_uang.total | currency}} orang</b></label>
                            </div>
                            <div class="col-sm-3">
                                <label><b>Rp. {{config.rekap.fdy_uang.amount | currency}}</b></label>
                            </div>
                        </b-row>
                        <b-row>
                            <div class="col-sm-2">
                                <label>Fidyah Beras</label>
                            </div>
                            <div class="col-sm-3">
                                <label><b>{{config.rekap.fdy_beras.total | currency}} orang</b></label>
                            </div>
                            <div class="col-sm-3">
                                <label><b>{{config.rekap.fdy_beras.amount | currency}} Kg</b></label>
                            </div>
                        </b-row>
                        <b-row>
                            <div class="col-sm-2">
                                <label>Mal</label>
                            </div>
                            <div class="col-sm-3">
                                <label><b>{{config.rekap.mal.total | currency}} orang</b></label>
                            </div>
                            <div class="col-sm-3">
                                <label><b>Rp. {{config.rekap.mal.amount | currency}}</b></label>
                            </div>
                        </b-row>
                        <b-row>
                            <div class="col-sm-2">
                                <label>Infak Shadaqoh Uang</label>
                            </div>
                            <div class="col-sm-3">
                                <label><b>{{config.rekap.sdq_uang.total | currency}} orang</b></label>
                            </div>
                            <div class="col-sm-3">
                                <label><b>Rp. {{config.rekap.sdq_uang.amount | currency}}</b></label>
                            </div>
                        </b-row>
                        <b-row>
                            <div class="col-sm-2">
                                <label>Infak Shadaqoh Beras</label>
                            </div>
                            <div class="col-sm-3">
                                <label><b>{{config.rekap.sdq_beras.total | currency}} orang</b></label>
                            </div>
                            <div class="col-sm-3">
                                <label><b>{{config.rekap.sdq_beras.amount | currency}} Kg</b></label>
                            </div>
                        </b-row>
                        <b-row>
                            <div class="col-sm-2">
                                <label>Jumlah Total Beras</label>
                            </div>
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3">
                                <label><b>{{config.rekap.total_beras | currency}} Kg</b></label>
                            </div>
                        </b-row>
                        <b-row>
                            <div class="col-sm-2">
                                <label>Jumlah Total Uang</label>
                            </div>
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3">
                                <label><b>Rp. {{config.rekap.total_uang | currency}}</b></label>
                            </div>
                        </b-row>
                    </div>
                </b-row>
        </b-card>
        <div class="card-button">
			<div class="card-button-body">
				<b-row>
					<b-col sm="12">	
						<button v-on:click="doExport" class="btn btn-primary">
				        	<i class="fa fa-print"></i> Export
				        </button>
		            </b-col>
				</b-row>
			</div>
		</div>
    </div>
</template>
<script>
import table from '@/shared/table.js'
export default {
	data() {
		return {
			config : {
				uri : this.$route.meta.permission.link,
				api : "zakat/report",
				datas: [],
	            total: 0,
	            limit: 50,
	            offset: 0,
	            orderby: "id",
	            sort: "desc",
	            page: 1,
	            search : {
                    periode : "",
                    report_type : "today",
					date_start : "",
                    date_end : "",
                    filter : {},
	            },
	            header: [{
	                title: "No Transaksi",
	                value: false,
	                width: "15%",
	            },{
	                title: "Tanggal",
	                value: false,
	                width: "12%",
	            },{
	                title: "Tipe Zakat",
	                value: false,
	                width: "13%",
	            },{
	                title: "Nama Muzakki",
	                value: false,
	                width: "20%",
	            },{
	                title: "Tipe Transaksi",
	                value: false,
	                width: "13%",
	            },{
	                title: "Jml Barang",
	                value: false,
	                width: "13%",
	            },{
	                title: "Jml Nominal",
	                value: false,
	                width: "15%",
                }],
                rekap : {
                    ftr_beras : {},
                    ftr_uang : {},
                    fdy_beras : {},
                    fdy_uang : {},
                    mal : {},
                    sdq_beras : {},
                    sdq_uang : {},
                    total_beras : 0,
                    total_uang : 0,
                }
			},
			response : {
				variant : this.$route.params.variant,
				message : this.$route.params.message
			},
			params : {
                periode : [],
                type : [],
            }
		}
	},
	created(){
		let _ = this
        _.getPeriode()
        _.getComponent()
	},
	methods : {
		get(){ 
			let _ = this,
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
                _.config.rekap = res.data.data.rekap
            }
        })
		},
		sort(orderby) {
			table.sort(this,orderby)
        },
        gotoPage(page) {
        	table.gotoPage(this,page)
        },
        getPeriode(){
			let _ = this
			_.$axios.get('periode').then(res => {
				_.params.periode = res.data.data.rows
			})
        },
        getComponent(){
			let _ = this
			_.$axios.get('zakat/component').then(res => {
				let data 		        = res.data.data
				_.config.search.periode = data.periode ? data.periode.year : ""
				_.params.type	        = data.type
			})
        },
        doExport(){
            let _ = this
            _.$axios.get('zakat/export?getlink=1',{
	            params : _.config.search
	        }).then(res => {
                window.open(res.data.data.url)
			}).catch(err => {
				_.response = {
					variant : 'danger',
					message : err.response.data.message
				}
	        })
        }
	}
}
</script>