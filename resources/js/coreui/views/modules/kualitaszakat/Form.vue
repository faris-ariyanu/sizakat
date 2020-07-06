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
		            	<b-form-group>
		                	<label for="parent">Tipe Zakat</label>
							<select class="form-control" v-model="model.type_zakat" name="Parent">
								<option value="">Pilih Tipe Zakat</option>
								<option v-for="type in params.type_zakat" v-bind:value="type.code">{{ type.name }}</option>
							</select>
		              	</b-form-group>
		            	<b-form-group v-bind:class="{ 'is-invalid': errors.has('Deskripsi Kualitas Zakat')}">
		                	<label for="description">* Deskripsi Kualitas Zakat</label>
		                	<b-form-input type="text" v-model="model.description" v-validate="'required'" name="Deskripsi Kualitas Zakat"></b-form-input>
		                	<div v-if="errors.has('Deskripsi Kualitas Zakat')">
	                      		<p class="text-danger">{{ errors.first('Deskripsi Kualitas Zakat') }}</p>
	                    	</div>
		              	</b-form-group>
		            	<b-form-group v-bind:class="{ 'is-invalid': errors.has('Nilai Kualitas Zakat')}">
		                	<label for="value">* Nilai Kualitas Zakat</label>
		                	<b-form-input type="number" v-model="model.value" v-validate="'required'" name="Nilai Kualitas Zakat"></b-form-input>
		                	<div v-if="errors.has('Nilai Kualitas Zakat')">
	                      		<p class="text-danger">{{ errors.first('Nilai Kualitas Zakat') }}</p>
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
				api : 'kualitaszakat',
			},
			action : this.$route.meta.permission.action,
			response : {
				variant : "",
				message : ""
			},
			model : {
				type_zakat : "",
				value : 0,
			},
			params : {
				type_zakat : []
			},
		}
	},
	created(){
		let _ = this
        _.getById()
		_.getType()
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
		getType(){
			let _ = this
			_.$axios.get('kualitaszakat/type').then(res => {
	        	_.params.type_zakat = res.data.data
			})
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