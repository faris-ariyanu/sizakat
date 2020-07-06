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
		            <b-col sm="6">
		              <b-form-group>
		                <label for="name"><b>Nama : </b></label><br>
                        <label>{{model.name}}</label>
		              </b-form-group>
		            </b-col>

					<b-col sm="6">
		              <b-form-group>
		                <label for="username"><b>Username : </b></label><br>
                        <label>{{model.username}}</label>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group>
		                <label for="relation"><b>Hubungan : </b></label><br>
                        <label>{{model.relation}}</label>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group>
		                <label for="no_ktp"><b>No KTP</b></label><br>
                        <label>{{model.no_ktp}}</label>
		              </b-form-group>
		            </b-col>
                    <b-col sm="6">
		              <b-form-group>
		                <label for="email"><b>Email</b></label><br>
                        <label>{{model.email}}</label>
		              </b-form-group>
		            </b-col>
                    <b-col sm="6">
		              <b-form-group>
		                <label for="address"><b>Alamat</b></label><br>
                        <label>{{model.address}}</label>
		              </b-form-group>
		            </b-col>
                    <b-col sm="6">
		              <b-form-group>
		                <label for="phone"><b>Telepon</b></label><br>
                        <label>{{model.phone}}</label>
		              </b-form-group>
		            </b-col>
                    <b-col sm="6">
		              <b-form-group>
		                <label for="status"><b>Status</b></label><br>
                        <label>
                            {{model.muzakki_status}}
                        </label>
		              </b-form-group>
		            </b-col>
                    <b-col sm="6">
		              <b-form-group>
		                <label for="photo_display"><b>Foto</b></label><br>
                        <p class="mt-2" v-if="model.photo_display">
							<img width="80" :src="model.photo_display" class="responsive"/>
                        </p>
		              </b-form-group>
		            </b-col>
	        	</b-row>
			</b-card>
			<div class="card-button">
				<div class="card-button-body">
					<b-row>
						<b-col sm="12">
					        <router-link :to="'/'+config.uri+'?parent=' + model.parent" class="btn btn-primary">
					        	<i class="fa fa-arrow-left"></i> Kembali
					        </router-link>
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
			},
			params : {
				roles : []
			}
		}
	},
	created(){
		let _ = this
		_.getById()
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
	}
}
</script>