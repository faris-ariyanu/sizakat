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
		                	<label for="name">* Name</label>
		                	<b-form-input type="text" v-model="model.name" v-validate="'required'" name="Name"></b-form-input>
		                	<div v-if="errors.has('Name')">
	                      		<p class="text-danger">{{ errors.first('Name') }}</p>
	                    	</div>
		              	</b-form-group>
		            	<b-form-group v-bind:class="{ 'is-invalid': errors.has('Link')}">
		                	<label for="link">* Link</label>
		                	<b-form-input type="text" v-model="model.link" v-validate="'required'" name="Link"></b-form-input>
		                	<div v-if="errors.has('Link')">
	                      		<p class="text-danger">{{ errors.first('Link') }}</p>
	                    	</div>
		              	</b-form-group>
		            	<b-form-group v-bind:class="{ 'is-invalid': errors.has('Icon')}">
		                	<label for="icon">* Icon</label>
		                	<b-form-input type="text" v-model="model.icon" v-validate="'required'" name="Icon"></b-form-input>
		                	<div v-if="errors.has('Icon')">
	                      		<p class="text-danger">{{ errors.first('Icon') }}</p>
	                    	</div>
		              	</b-form-group>
		            	<b-form-group v-bind:class="{ 'is-invalid': errors.has('Sequence')}">
		                	<label for="sequence">* Sequence</label>
		                	<b-form-input type="number" v-model="model.sequence" v-validate="'required'" name="Sequence"></b-form-input>
		                	<div v-if="errors.has('Sequence')">
	                      		<p class="text-danger">{{ errors.first('Sequence') }}</p>
	                    	</div>
		              	</b-form-group>
		            	<b-form-group>
		                	<label for="parent">Parent</label>
							<select class="form-control" v-model="model.parent" name="Parent">
								<option v-bind:value="0">No Parent</option>
								<option v-for="menu in params.menus" v-bind:value="menu.id">{{ menu.name }}</option>
							</select>
		              	</b-form-group>
		            </b-col>
		            <b-col sm="12">
		            	<b-form-group>
		            		<label for="name">* Status</label>
		            		<div class="col-sm-12 no-padding">
		                        <label class="radio-inline">
		                           	<input type="radio" v-model="model.status" value="1">
		                           		<span class="badge badge-success">Active</span>
		                        </label>
		                        <label class="radio-inline">
		                            <input type="radio" v-model="model.status" value="0">
		                            	<span class="badge badge-danger">Not Active</span>
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
					        	<i class="fa fa-arrow-left"></i> Back
					        </router-link>
					        <b-button v-if="$store.state.permission.store" 
					        	type="submit" variant="primary" class="px-4">
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
				api : 'menu',
			},
			action : this.$route.meta.permission.action,
			response : {
				variant : "",
				message : ""
			},
			model : {
				status : 1,
				parent : 0,
			},
			params : {
				menus : []
			},
		}
	},
	created(){
		let _ = this
        _.getById()
		_.getMenu()
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
						_.model.parent  = (_.model.parent == null) ? 0 : _.model.parent.id
					}else{
						_.$router.push({name: _.config.uri, params: {
			              	'variant': 'danger',
			               	'message': 'Data cannot be found!'
			            }})
					}
				})
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