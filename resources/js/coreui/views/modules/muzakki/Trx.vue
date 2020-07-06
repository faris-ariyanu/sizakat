<template>
	<div class="animated fadeIn">
    	<b-card>
		    <div slot="header">
		    	<strong>Transaksi Muzakki</strong>
		    </div>
		    <b-row>
                <div class="col-sm-12 mb-10">
                    <b-row>
                        <div class="col-sm-3">
                            <b-form-group>
                                <label for="date_start">Tanggal Awal</label>
                                <input type="date" class="form-control" v-model="config.search.date_start"/>
                            </b-form-group>
                        </div>
						<div class="col-sm-3">
                            <b-form-group>
                                <label for="date_end">Tanggal Akhir</label>
                                <input type="date" class="form-control" v-model="config.search.date_end"/>
                            </b-form-group>
                        </div>
						<div class="col-sm-3">
                            <b-form-group>
                                <label for="transaction_number">No Transaksi</label>
                                <input type="text" class="form-control" v-model="config.search.transaction_number"/>
                            </b-form-group>
                        </div>
                        <div class="col-sm-3">
                            <b-form-group>
                                <label for="muzakki_name">Nama Muzakki</label>
                                <input type="text" class="form-control" v-model="config.search.muzakki_name"/>
                            </b-form-group>
                        </div>
						<div class="col-sm-12">
                            <button v-on:click="get" class="btn btn-primary pull-right">
				        		<i class="fa fa-search"></i> Cari Transaksi
				        	</button>
                        </div>
						<br>
                    </b-row>
	            </div>
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
                                <td>{{data.income_goods}}</td>
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
    </div>
</template>
<script>
import table from '@/shared/table.js'
export default {
	data() {
		return {
			config : {
				uri : this.$route.meta.permission.link,
				api : "zakat/muzakki",
				datas: [],
	            total: 0,
	            limit: 50,
	            offset: 0,
	            orderby: "id",
	            sort: "desc",
	            page: 1,
	            search : {
                    key : "",
					transaction_number : "",
					date_start : "",
                    date_end : "",
                    muzakki_name : "",
	            },
	            header: [{
	                title: "No Transaksi",
	                value: "trx_zakat.transaction_number",
	                width: "15%",
	            },{
	                title: "Tanggal",
	                value: "trx_zakat_items.created_at",
	                width: "12%",
	            },{
	                title: "Tipe Zakat",
	                value: "trx_zakat_items.zakat_type_id",
	                width: "13%",
	            },{
	                title: "Nama Muzakki",
	                value: "trx_zakat_items.muzakki_name",
	                width: "20%",
	            },{
	                title: "Tipe Transaksi",
	                value: "trx_zakat_items.transaction_type",
	                width: "13%",
	            },{
	                title: "Jml Barang",
	                value: "trx_zakat_items.income_goods",
	                width: "13%",
	            },{
	                title: "Jml Nominal",
	                value: "trx_zakat_items.income_value",
	                width: "15%",
                }]
			},
			response : {
				variant : this.$route.params.variant,
				message : this.$route.params.message
			}
		}
	},
	created(){
		let _ = this
        _.get()
	},
	methods : {
		get(){ 
			table.get(this)
		},
		sort(orderby) {
			table.sort(this,orderby)
        },
        gotoPage(page) {
        	table.gotoPage(this,page)
        },
	}
}
</script>