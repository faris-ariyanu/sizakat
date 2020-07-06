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
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Name')}">
		                <label for="name">* Nama</label>
		                <b-form-input type="text" v-model="model.name" v-validate="'required'" name="Name"></b-form-input>
		                <div v-if="errors.has('Name')">
	                      <p class="text-danger">{{ errors.first('Name') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="12">
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Username')}">
		                <label for="username">* Username</label>
		                <b-form-input type="text" v-model="model.username" v-validate="'required'" name="Username"></b-form-input>
		                <div v-if="errors.has('Username')">
	                      <p class="text-danger">{{ errors.first('Username') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="12">
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Email')}">
		                <label for="email">* Email</label>
		                <b-form-input type="email" v-model="model.email" v-validate="'required'" name="Email"></b-form-input>
		                <div v-if="errors.has('Email')">
	                      <p class="text-danger">{{ errors.first('Email') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="12">
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Password')}">
		                <label for="password">{{(action == 'store') ? '*' : ''}} Password</label>
		                <b-form-input type="password" v-model="model.password" v-validate="action == 'store' ? 'required' : ''" name="Password"></b-form-input>
		                <div v-if="errors.has('Password')">
	                      <p class="text-danger">{{ errors.first('Password') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="12">
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Role')}">
		                <label for="role">* Role</label>
		                <select v-model="model.role" v-validate="'required'" name="Role" class="form-control">
	                        <option value="">Select Role</option>
	                        <option v-for="role in params.roles" :value="role.id">
	                            {{role.name}}
	                        </option>
	                    </select>
	                    <div v-if="errors.has('Role')">
	                      <p class="text-danger">{{ errors.first('Role') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="12">
		              <b-form-group>
		                <label for="avatar">Foto</label>
		                <input type="file" class="form-control" v-on:change="AddAvatar">
		                <p class="mt-2" v-if="model.avatar_display">
							<img :src="model.avatar_display" class="responsive"/>
                        </p>
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
					        <b-button v-if="$store.state.permission.store" 
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
				api : 'user',
			},
			action : this.$route.meta.permission.action,
			response : {
				variant : "",
				message : ""
			},
			model : {
				role : "",
				avatar_display : "",
				status : 1,
			},
			params : {
				roles : []
			}
		}
	},
	created(){
		let _ = this
		_.getById()
		_.getRole()
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
				    	_.model 				= res.data.data.rows
						_.model.status 			= parseInt(_.model.status.code)
						_.model.role 			= parseInt(_.model.role.id)
						_.model.avatar_display	= _.model.avatar
					}else{
						_.$router.push({name: _.config.uri, params: {
			              	'variant': 'danger',
			               	'message': 'Data cannot be found!'
			            }})
					}
				})
	    	}
		},
		getRole(){
			let _ = this
			_.$axios.get('role',{
	            params:{ 
	            	status: 1
	            }
	        }).then(res => {
	        	_.params.roles = res.data.data.rows
			})
		},
		AddAvatar (event) {
			this.model.avatar 			= event.target.files[0]
            this.model.avatar_display  	= URL.createObjectURL(this.model.avatar)
        },
		save(){
			let _ = this
			if(_.action == "update"){
				_.model.id = _.$route.params.id
			}

			_.params.FormData = new FormData()
            for (let i in this.model){
				_.params.FormData.append(i, this.model[i])
			}
			_.$validator.validate().then(valid => {
	        	if (valid) {
					_.$axios.post(_.config.api+'/'+_.action, _.params.FormData)
		            .then(res => {
		            	_.$router.push({name: _.config.uri, params: {
			              	'variant': (res.data.status) ? 'success':'danger',
			               	'message': res.data.message
			            }})
		            }).catch(e => {
						_.response = {
							variant : "danger",
							message : e.response.data.message
						}              
		            })
		        }
	        });
		}
	}
}
</script>