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
								<td>{{data.year}}</td>
			                    <td>{{data.description}}</td>
			                    <td>
			                    	<span :class="'badge badge-'+data.status.class">
			                    		{{data.status.name}}
			                    	</span>
			                    </td>
			                    <td align="center">
			                    	<router-link :to="'/'+config.uri+'/edit/'+data.id" 
			                    		v-if="$store.state.permission.update" 
			                    		title="Edit" class="btn btn-light">
			            				<i class="fa fa-pencil"></i>
			            			</router-link>
			                        <button v-on:click="status(data.id,1)" 
			                        	v-if="$store.state.permission.update && data.status.code == 0" 
			                        	title="Aktifkan" class="btn btn-light">
			                        	<i class="fa fa-check"></i>
			                        </button>
									<button v-on:click="status(data.id,0)" 
			                        	v-if="$store.state.permission.update && data.status.code == 1" 
			                        	title="NonAktifkan" class="btn btn-light">
			                        	<i class="fa fa-close"></i>
			                        </button>
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
				            <i class="fa fa-plus"></i> Tambah
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
				api : "periode",
				datas: [],
	            total: 0,
	            limit: 20,
	            offset: 0,
	            orderby: "id",
	            sort: "desc",
	            page: 1,
	            search : {
	            	key : ""
	            },
	            header: [{
	                title: "Tahun",
	                value: "year",
	                width: "15%",
	            },{
	                title: "Keterangan",
	                value: "description",
	                width: "30%",
	            },{
	                title: "Status",
	                value: "status",
	                width: "12%",
	            },{
	                title: "",
	                value: false,
	                width: "15%",
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
        status(id,sts){
			let _ = this
			_.$swal.fire({
				title: 'Yakin akan ' + (sts == 1 ? 'mengaktifkan' : 'Menonaktifkan') +' ?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then((result) => {
				if (result.value) {
					_.$axios.get(_.config.api + '/status', {
						params: { 'id': id, 'status' : sts }
					}).then(res => {
						_.response = {
							variant: (res.data.status) ? "success" : "danger",
							message: res.data.message
						}
						_.get()
					})
				}
			})
        }
	}
}
</script>