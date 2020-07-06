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
	            	<div class="col-sm-3 pull-right no-padding">
		                <div class="input-group">
		                    <input type="text" placeholder="Pencarian..." v-model="config.search.key" 
		                    	v-on:keyup.enter="get()" class="form-control" />
		                    <div class="input-group-prepend">
		                        <div class="input-group-text" v-on:click="get()">
		                        	<i class="fa fa-search"></i>
		                        </div>
		                    </div>
		                </div>
		            </div>
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
								<td>{{data.no_ktp}}</td>
			                    <td>{{data.name}}</td>
			                    <td>{{data.username}}</td>
								<td>{{data.email ? data.email : '-'}}</td>
								<td>{{data.phone ? data.phone : '-'}}</td>
			                    <td>
			                    	{{data.muzakki_status}}
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
												:to="'/muzakkifamily?parent='+data.id" 
												v-if="$store.state.permission.read" 
												title="Struktur Keluarga"> Struktur Keluarga
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
				            <i class="fa fa-plus"></i> Tambah
				        </router-link>	
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
				api : "muzakki",
				datas: [],
	            total: 0,
	            limit: 20,
	            offset: 0,
	            orderby: "id",
	            sort: "desc",
	            page: 1,
	            search : {
					key : "",
					parent : 0,
	            },
	            header: [{
	                title: "No KTP",
	                value: "no_ktp",
	                width: "12%",
	            },{
	                title: "Nama",
	                value: "name",
	                width: "15%",
	            },{
	                title: "Username",
	                value: "username",
	                width: "15%",
	            },{
	                title: "Email",
	                value: "email",
	                width: "15%",
	            },{
	                title: "Telepon",
	                value: "phone",
	                width: "15%",
	            },{
	                title: "Status",
	                value: "muzakki_status",
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
        destroy(id){
        	table.destroy(this,id)
        },
		doExport(){
            let _ = this
            _.$axios.get(_.config.api + '/export?getlink=1',{
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