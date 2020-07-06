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
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Tahun')}">
		                <label for="Tahun">* Tahun</label>
						<select v-model="model.year" v-validate="'required'" name="Tahun" class="form-control">
	                        <option value="">Pilih Tahun</option>
							<option v-for="year in params.years" :value="year">{{year}}</option>
	                    </select>
		                <div v-if="errors.has('Tahun')">
	                      <p class="text-danger">{{ errors.first('Tahun') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="12">
		              <b-form-group>
		                <label for="description">Keterangan</label>
		                <b-form-input type="text" v-model="model.description"></b-form-input>
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
				api : 'periode',
			},
			action : this.$route.meta.permission.action,
			response : {
				variant : "",
				message : ""
			},
			model : {
				status : 0,
				year : "",
			},
			params : {
				years : []
			}
		}
	},
	created(){
		let _ = this
		_.getById()
		let start_year = new Date().getFullYear()
		for (let i = start_year; i <= (start_year + 10); i++) {
			_.params.years.push(i)
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
						_.model.status 			= parseInt(_.model.status.code)
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