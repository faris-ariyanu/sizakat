<template>
	<div class="animated fadeIn">
    	<b-card>
		    <div slot="header">
		    	<strong>{{$route.meta.label}}</strong>
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
                                <label for="status">Status</label>
                                <select v-model="config.search.status" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="0">Menunggu Pembayaran</option>
                                    <option value="1">Menunggu Approval</option>
                                    <option value="2">Pembayaran Diterima</option>
                                </select>
                            </b-form-group>
                        </div>
						<div class="col-sm-3">
                            <b-form-group>
                                <label for="transaction_number">No Transaksi</label>
                                <input type="text" class="form-control" v-model="config.search.transaction_number"/>
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
									<router-link :to="'/'+config.uri+'/view/'+data.id">
										<b>{{data.transaction_number}}</b>
									</router-link>
								</td>
			                    <td>{{data.date}}</td>
			                    <td>{{data.name}} - {{data.no_ktp}} <br> {{data.address}}<br> {{data.phone}}</td>
			                    <td>{{data.payment_method_name}}</td>
								<td>
									<span :class="'badge badge-'+data.status.class">
			                    		{{data.status.name}}
			                    	</span>
								</td>
								<td align="center">
									<div class="dropdown">
										<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Action
										</button>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<router-link class="dropdown-item" 
												:to="'/'+config.uri+'/view/'+data.id" 
												v-if="$store.state.permission.read" 
												title="Lihat"> Lihat
											</router-link>
											<router-link class="dropdown-item" 
												:to="'/'+config.uri+'/edit/'+data.id" 
												v-if="$store.state.permission.update" 
												title="Edit"> Edit
											</router-link>
											<a href="#" v-on:click="destroy(data.id)" 
												v-if="$store.state.permission.delete" 
												title="Hapus" class="dropdown-item">
												Hapus
											</a>
										</div>
									</div>
			                    </td>
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
		<div class="card-button">
			<div class="card-button-body">
				<b-row>
					<b-col sm="12">
				        <button v-on:click="get" class="btn btn-primary">
				        	<i class="fa fa-refresh"></i> Refresh
				        </button>
		            	<router-link :to="'/'+config.uri+'/add'" 
		            		v-if="$store.state.permission.store" 
				            title="Add New" class="btn btn-primary">
				            <i class="fa fa-plus"></i> Tambah Zakat
				        </router-link>	
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
				api : "zakat",
				datas: [],
	            total: 0,
	            limit: 20,
	            offset: 0,
	            orderby: "id",
	            sort: "desc",
	            page: 1,
	            search : {
					key : "",
					transaction_number : "",
					status : "",
					date_start : "",
					date_end : "",
	            },
	            header: [{
	                title: "No Transaksi",
	                value: "transaction_number",
	                width: "15%",
	            },{
	                title: "Tanggal",
	                value: "created_at",
	                width: "12%",
	            },{
	                title: "Pembayar",
	                value: "name",
	                width: "25%",
	            },{
	                title: "Mode Pembayaran",
	                value: "payment_method_name",
	                width: "15%",
	            },{
	                title: "Status",
	                value: "status",
	                width: "12%",
	            },{
	                title: "",
	                value: false,
	                width: "10%",
	            }],
			},
			response : {
				variant : this.$route.params.variant,
				message : this.$route.params.message
			},
			params : {
				
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
        destroy(id){
        	table.destroy(this,id)
        }
	}
}
</script>