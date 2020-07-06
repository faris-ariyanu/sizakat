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
						<b-form-group v-bind:class="{ 'is-invalid': errors.has('Hubungan')}">
							<label for="Hubungan">Hubungan</label>
							<select class="form-control" v-validate="'required'" name="Hubungan" v-model="model.relation">
								<option value="">Pilih Hubungan</option>
								<option v-for="row in params.relations" v-bind:value="row">{{ row }}</option>
							</select>
							<div v-if="errors.has('Hubungan')">
							<p class="text-danger">{{ errors.first('Hubungan') }}</p>
							</div>
						</b-form-group>
					</b-col>
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
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('No KTP')}">
		                <label for="no_ktp">* No KTP</label>
		                <b-form-input type="text" v-model="model.no_ktp" v-validate="'required'" name="No KTP"></b-form-input>
		                <div v-if="errors.has('No KTP')">
	                      <p class="text-danger">{{ errors.first('No KTP') }}</p>
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
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Password')}">
		                <label for="password">{{(action == 'store') ? '*' : ''}} Password</label>
		                <b-form-input type="password" v-model="model.password" v-validate="action == 'store' ? 'required' : ''" name="Password"></b-form-input>
		                <div v-if="errors.has('Password')">
	                      <p class="text-danger">{{ errors.first('Password') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="12">
		              <b-form-group>
		                <label for="email">Email</label>
		                <b-form-input type="email" v-model="model.email" name="Email"></b-form-input>
		              </b-form-group>
		            </b-col>
					<b-col sm="12">
		              <b-form-group>
		                <label for="address">Alamat</label>
		                <b-form-input type="text" v-model="model.address"></b-form-input>
		              </b-form-group>
		            </b-col>
					<b-col sm="12">
		              <b-form-group>
		                <label for="Telepon">Telepon</label>
		                <b-form-input type="text" v-model="model.phone" name="Telepon"></b-form-input>
		              </b-form-group>
		            </b-col>
					<b-col sm="12">
		              <b-form-group>
		                <label for="photo">Foto</label>
		                <input type="file" class="form-control" v-on:change="AddPhoto">
		                <p class="mt-2" v-if="model.photo_display">
							<img :src="model.photo_display" class="responsive"/>
                        </p>
		              </b-form-group>
		            </b-col>
		            <b-col sm="12">
		            	<b-form-group>
		            		<label for="name">* Status</label>
		            		<div class="col-sm-12 no-padding">
		                        <label class="radio-inline">
		                           	<input type="radio" v-model="model.muzakki_status" value="activated">
		                           		<span class="badge badge-success">Activated</span>
		                        </label>
		                        <label class="radio-inline">
		                            <input type="radio" v-model="model.muzakki_status" value="not activated">
		                            	<span class="badge badge-danger">Not Activated</span>
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
					        <router-link :to="'/'+config.uri + '?parent=' + model.parent" class="btn btn-primary">
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
				uri : "muzakkifamily",
				api : 'muzakki',
			},
			action : this.$route.meta.permission.action,
			response : {
				variant : "",
				message : ""
			},
			model : {
				photo_display : "",
				muzakki_status : "activated",
				relation : "",
				parent : this.$route.query.parent,
			},
			params : {
				relations : []
			}
		}
	},
	created(){
		let _ = this
		_.getById()
		_.getRelations()
		if(!_.$route.query.parent){
			_.$router.push({name: "muzakki"})
		}
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
						_.model.photo_display	= _.model.photo
					}else{
						_.$router.push({name: _.config.uri, params: {
			              	'variant': 'danger',
			               	'message': 'Data cannot be found!'
			            }})
					}
				})
	    	}
		},
		getRelations(){
			let _ = this
			_.$axios.get('muzakki/relation').then(res => {
	        	_.params.relations = res.data.data
			})
		},
		AddPhoto (event) {
			this.model.photo 			= event.target.files[0]
            this.model.photo_display  	= URL.createObjectURL(this.model.photo)
        },
		save(){
			let _ = this
			if(_.action == "update"){
				_.model.id = _.$route.params.id
			}

			_.params.FormData = new FormData()
            for (let i in this.model){
				if(this.model[i]){
					_.params.FormData.append(i, this.model[i])
				}
			}
			_.$validator.validate().then(valid => {
	        	if (valid) {
					_.$axios.post(_.config.api+'/'+_.action, _.params.FormData)
		            .then(res => {
		            	_.$router.push({name: "muzakkifamily",query: { parent: _.model.parent }, params: {
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