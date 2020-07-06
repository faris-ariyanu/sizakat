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
		            	<b-form-group v-bind:class="{ 'is-invalid': errors.has('old_password')}">
		                	<label for="old_password">* Old Password</label>
		                	<b-form-input type="password" v-model="model.old_password" v-validate="'required'" name="old_password"></b-form-input>
		                	<div v-if="errors.has('old_password')">
	                      		<p class="text-danger">{{ errors.first('old_password') }}</p>
	                    	</div>
		              	</b-form-group>
		            	<b-form-group v-bind:class="{ 'is-invalid': errors.has('password')}">
		                	<label for="password">* Password</label>
		                	<b-form-input type="password" v-model="model.password" v-validate="'required'" name="password"></b-form-input>
		                	<div v-if="errors.has('password')">
	                      		<p class="text-danger">{{ errors.first('password') }}</p>
	                    	</div>
		              	</b-form-group>
		            	<b-form-group v-bind:class="{ 'is-invalid': errors.has('password_confirmation')}">
		                	<label for="password_confirmation">* Password Confirmation</label>
		                	<b-form-input type="password" v-model="model.password_confirmation" v-validate="'required'" name="password_confirmation"></b-form-input>
		                	<div v-if="errors.has('password_confirmation')">
	                      		<p class="text-danger">{{ errors.first('password_confirmation') }}</p>
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
					        	<i class="fa fa-arrow-left"></i> Back
					        </router-link>
							<b-button type="submit" variant="primary" class="px-4">
								<i class="fa fa-save"></i> Save
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
				api : 'me',
			},
			action : this.$route.meta.permission.action,
			response : {
				variant : "",
				message : ""
			},
			model : {},
			params : {},
		}
	},
	created(){
		let _ = this
	},
	methods : {
		save(){
			let _ = this
			_.model.id = _.$route.params.id
			_.$validator.validate().then(valid => {
	        	if (valid) {
					_.$axios.post(_.config.api+'/'+_.action, _.model)
		            .then(res => {
						_.model 	= {}
						_.response 	= {
							variant : 'success',
							message : res.data.message
						}
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