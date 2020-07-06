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
		            	<b-form-group v-bind:class="{ 'is-invalid': errors.has('Nama')}">
		                	<label for="Nama">* Nama</label>
		                	<b-form-input type="text" v-model="model.name" v-validate="'required'" name="Nama"></b-form-input>
		                	<div v-if="errors.has('Nama')">
	                      		<p class="text-danger">{{ errors.first('Nama') }}</p>
	                    	</div>
		              	</b-form-group>
						<b-form-group v-bind:class="{ 'is-invalid': errors.has('Deskripsi')}">
		                	<label for="description">* Deskripsi</label>
		                	<b-form-input type="text" v-model="model.description" v-validate="'required'" name="Deskripsi"></b-form-input>
		                	<div v-if="errors.has('Deskripsi')">
	                      		<p class="text-danger">{{ errors.first('Deskripsi') }}</p>
	                    	</div>
		              	</b-form-group>
		            	<b-form-group v-bind:class="{ 'is-invalid': errors.has('Nilai Zakat')}">
		                	<label for="value">* Nilai Zakat</label>
		                	<b-form-input type="number" v-model="model.value" v-validate="'required'" name="Nilai Zakat"></b-form-input>
		                	<div v-if="errors.has('Nilai Zakat')">
	                      		<p class="text-danger">{{ errors.first('Nilai Zakat') }}</p>
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
				api : 'categorymustahik',
			},
			action : this.$route.meta.permission.action,
			response : {
				variant : "",
				message : ""
			},
			model : {
				value : 0,
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