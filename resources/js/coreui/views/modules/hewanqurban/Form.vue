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
		            	<b-form-group v-bind:class="{ 'is-invalid': errors.has('Nama Hewan')}">
		                	<label for="description">* Nama Hewan</label>
		                	<b-form-input type="text" v-model="model.name" v-validate="'required'" name="Nama Hewan"></b-form-input>
		                	<div v-if="errors.has('Nama Hewan')">
	                      		<p class="text-danger">{{ errors.first('Nama Hewan') }}</p>
	                    	</div>
		              	</b-form-group>
		            	<b-form-group v-bind:class="{ 'is-invalid': errors.has('Kuota')}">
		                	<label for="value">* Kuota</label>
		                	<b-form-input type="number" v-model="model.kuota" v-validate="'required'" name="Kuota"></b-form-input>
		                	<div v-if="errors.has('Kuota')">
	                      		<p class="text-danger">{{ errors.first('Kuota') }}</p>
	                    	</div>
		              	</b-form-group>
						<b-form-group v-bind:class="{ 'is-invalid': errors.has('Cacahan')}">
		                	<label for="value">* Cacahan</label>
		                	<b-form-input type="number" v-model="model.cacahan" v-validate="'required'" name="Cacahan"></b-form-input>
		                	<div v-if="errors.has('Cacahan')">
	                      		<p class="text-danger">{{ errors.first('Cacahan') }}</p>
	                    	</div>
		              	</b-form-group>
						<b-form-group v-bind:class="{ 'is-invalid': errors.has('biaya_potong')}">
		                	<label for="value">* Biaya Potong</label>
		                	<b-form-input type="number" v-model="model.biaya_potong" v-validate="'required'" name="biaya_potong"></b-form-input>
		                	<div v-if="errors.has('biaya_potong')">
	                      		<p class="text-danger">{{ errors.first('biaya_potong') }}</p>
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
				api : 'hewanqurban',
			},
			action : this.$route.meta.permission.action,
			response : {
				variant : "",
				message : ""
			},
			model : {
				name : "",
				kuota : 0,
				cacahan : 0,
				biaya_potong : 0,
			},
			params : {
			},
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
					}else{
						_.$router.push({name: _.config.uri, params: {
			              	'variant': 'danger',
			               	'message': 'Data cannot be found!'
			            }})
					}
				})
	    	}
		},
		save(){
			let _ = this
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
							message : err.response.data.message
						}
		            })
		        }
	        });
		}
	}
}
</script>