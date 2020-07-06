<template>
	<form class="form-horizontal" @submit.prevent="save">
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
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Kode')}">
		                <label for="name">* Kode</label>
		                <b-form-input type="text" v-model="model.code" v-validate="'required'" name="Kode"></b-form-input>
		                <div v-if="errors.has('Kode')">
	                      <p class="text-danger">{{ errors.first('Kode') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="12">
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Nama')}">
		                <label for="name">* Nama</label>
		                <b-form-input type="text" v-model="model.name" v-validate="'required'" name="Nama"></b-form-input>
		                <div v-if="errors.has('Nama')">
	                      <p class="text-danger">{{ errors.first('Nama') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
		            <b-col sm="12">
	                    <b-form-group>
	                        <table class="table b-table">
	                            <thead>
	                                <tr>
	                                    <th>Menu</th>
	                                    <th class="text-center">Create</th>
	                                    <th class="text-center">Read</th>
	                                    <th class="text-center">Update</th>
	                                    <th class="text-center">Delete</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                <template v-for="menu in params.menus">
	                                    <tr>
	                                        <td>{{menu.name}}</td>
	                                        <template v-if="!menu.childs.length">
	                                            <td align="center">
	                                                <input type="checkbox" 
	                                                    v-model="menu.permission.store"
	                                                    v-bind:true-value="1" 
	                                                    v-bind:false-value="0"
														v-show="menu.action.includes('store')">
	                                            </td>
	                                            <td align="center">
	                                                <input type="checkbox" 
	                                                    v-model="menu.permission.read"
	                                                    v-bind:true-value="1" 
	                                                    v-bind:false-value="0"
														v-show="menu.action.includes('read')">
	                                            </td>
	                                            <td align="center">
	                                                <input type="checkbox" 
	                                                    v-model="menu.permission.update"
	                                                    v-bind:true-value="1" 
	                                                    v-bind:false-value="0"
														v-show="menu.action.includes('update')">
	                                            </td>
	                                            <td align="center">
	                                                <input type="checkbox" 
	                                                    v-model="menu.permission.delete"
	                                                    v-bind:true-value="1" 
	                                                    v-bind:false-value="0"
														v-show="menu.action.includes('delete')">
	                                            </td>
	                                        </template>
	                                        <template v-else>
	                                            <td colspan="6"></td>
	                                        </template>
	                                    </tr>
	                                    <template v-if="menu.childs.length">
	                                        <tr v-for="child in menu.childs">
	                                            <td class="pdl-40"><span style="padding-left:20px;">{{child.name}}</span></td>
	                                            <td align="center">
	                                                <input type="checkbox" 
	                                                    v-model="child.permission.store"
	                                                    v-bind:true-value="1" 
	                                                    v-bind:false-value="0"
														v-show="menu.action.includes('store')">
	                                            </td>
	                                            <td align="center">
	                                                <input type="checkbox" 
	                                                    v-model="child.permission.read"
	                                                    v-bind:true-value="1" 
	                                                    v-bind:false-value="0"
														v-show="menu.action.includes('read')">
	                                            </td>
	                                            <td align="center">
	                                                <input type="checkbox" 
	                                                    v-model="child.permission.update"
	                                                    v-bind:true-value="1" 
	                                                    v-bind:false-value="0"
														v-show="menu.action.includes('update')">
	                                            </td>
	                                            <td align="center">
	                                                <input type="checkbox" 
	                                                    v-model="child.permission.delete"
	                                                    v-bind:true-value="1" 
	                                                    v-bind:false-value="0"
														v-show="menu.action.includes('delete')">
	                                            </td>
	                                        </tr>
	                                    </template>
	                                </template>
	                            </tbody>
	                        </table>
	                    </b-form-group>
	                </b-col>
		            <b-col sm="12">
		            	<b-form-group>
		            		<label for="name">* Status</label>
		            		<div class="col-sm-12 no-padding">
		                        <label class="radio-inline">
		                           	<input type="radio" v-model="model.status" value="1">
		                           		<span class="badge badge-success">Aktif</span>
		                        </label>
		                        <label class="radio-inline">
		                            <input type="radio" v-model="model.status" value="0">
		                            	<span class="badge badge-danger">Tidak Aktif</span>
		                            </label>
		                    </div>
				        </b-form-group>
		            </b-col>
	        	</b-row>
			</b-card>
			<div class="card-button">
				<div class="card-button-body">
					<b-row>
						<b-col sm="12">
					        <router-link :to="'/'+config.uri" class="btn btn-primary">
					        	<i class="fa fa-arrow-left"></i> Kembali
					        </router-link>
					        <b-button v-if="$store.state.permission.store || $store.state.permission.update" 
					        	type="submit" variant="primary" class="px-4">
					        	<i class="fa fa-save"></i> Simpan
					        </b-button>
			            </b-col>
					</b-row>
				</div>
			</div>
	    </div>
	</form>
</template>
<script>
export default {
	data() {
		return {
			config : {
				uri : this.$route.meta.permission.link,
				api : 'role',
			},
			action : this.$route.meta.permission.action,
			response : {
				variant : "danger",
				message : ""
			},
			model : {
				status : 1,
			},
			params : {
				menus : []
			}
		}
	},
	created(){
		let _ = this
        _.getById()
	},
	methods : {
		getById(){
			let _ = this
			if(_.$route.params.id){
				_.$axios.get(_.config.api,{
		            params:{ 
		               	'id': _.$route.params.id,
		            }
		        }).then(res => {
		        	if(res.data.data.total){
				    	_.model 		= res.data.data.rows
				    	_.model.status 	= parseInt(_.model.status.code)
				    	_.getMenu()
					}else{
						_.$router.push({name: _.config.uri, params: {
			              	'variant': 'danger',
			               	'message': 'Data tidak ditemukan'
			            }})
					}
				})
	    	}else{
	    		_.getMenu()
	    	}
		},
		getMenu(){
			let _ = this
			_.$axios.get('menu',{
	            params:{ 
	               	'parent': 0,
	               	'orderby' : 'sequence',
	               	'sort' : 'asc'
	            }
	        }).then(res => {
	        	_.params.menus = res.data.data.rows
                for(let i in _.params.menus){
                    _.params.menus[i].permission = _.setPermission(_.params.menus[i].id)
                    if(_.params.menus[i].childs.length > 0){
                        for(let c in _.params.menus[i].childs){
                            _.params.menus[i].childs[c].permission = _.setPermission(_.params.menus[i].childs[c].id)
                        }
                    }
                }
			})
		},
		setPermission(menuid){
            let _ = this,
            	prms = {
                        store : 0,
                        read : 0,
                        update : 0,
                        delete : 0,
                    }

            if ( _.$route.params.id ) {
                let data = _.model.menus
                for(let x in data){
                    if(menuid == data[x].menu){
                        prms = data[x].permission
                    }
                }
            }
            return prms;
        },
		save(){
			let _ = this
			_.model.permission = _.params.menus
			if(_.action == "update"){
				_.model.id = _.$route.params.id
			}
			_.$validator.validate().then(valid => {
	        	if (valid) {
					_.$axios.post(_.config.api+'/'+_.action, _.model)
		            .then(res => {
		            	_.$router.push({name: _.config.uri, params: {
			              	'variant': (res.data.status) ? 'success':'danger',
			               	'message': res.data.message
			            }})
		            }).catch(e => {
						_.response = {
							variant : 'danger',
							message : e.response.data.message
						}              
		            })
		        }
	        });
		}
	}
}
</script>