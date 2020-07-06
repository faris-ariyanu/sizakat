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
		              <b-form-group>
		                <label for="avatar">Avatar</label>
		                <input type="file" class="form-control" v-on:change="AddAvatar">
		                <p class="mt-2" v-if="model.avatar_display">
							<img :src="model.avatar_display" class="responsive"/>
                        </p>
		              </b-form-group>
		            </b-col>
	        	</b-row>
			</b-card>
			<div class="card-button">
				<div class="card-button-body">
					<b-row>
						<b-col sm="12">
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
			model : {
                avatar          : "",
				avatar_display : "",
			},
			params : {

			}
		}
	},
	created(){
		let _ = this
		_.getMe()
	},
	methods : {
		getMe(){
			let _ = this
			_.$axios.get(_.config.api)
            .then(res => {
		        _.model 				= res.data.data
                _.model.avatar_display	= _.model.avatar
			})
		},
		AddAvatar (event) {
			this.model.avatar 			= event.target.files[0]
            this.model.avatar_display  	= URL.createObjectURL(this.model.avatar)
        },
		save(){
			let _ = this
			_.params.FormData = new FormData()
            for (let i in this.model){
				_.params.FormData.append(i, this.model[i])
			}
			_.$validator.validate().then(valid => {
	        	if (valid) {
					_.$axios.post(_.config.api+'/'+_.action, _.params.FormData)
		            .then(res => {
		            	_.response = {
							variant : "success",
							message : res.data.message
						}  
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