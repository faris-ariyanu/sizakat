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
		                <label for="category"><b>Kategori : </b></label><br>
                        <label>{{model.category.name}}</label>
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
		                <label for="address"><b>Alamat</b></label><br>
                        <label>{{model.address}}</label>
		              </b-form-group>
		            </b-col>
                    <b-col sm="6">
		              <b-form-group>
		                <label for="province"><b>Provinsi</b></label><br>
                        <label>{{model.province}}</label>
		              </b-form-group>
		            </b-col>
                    <b-col sm="6">
		              <b-form-group>
		                <label for="city"><b>Kota</b></label><br>
                        <label>{{model.regency}}</label>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group>
		                <label for="rt"><b>RT</b></label><br>
                        <label>{{model.rt}}</label>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group>
		                <label for="city"><b>RW</b></label><br>
                        <label>{{model.rw}}</label>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group>
		                <label for="city"><b>Tanggal Lahir</b></label><br>
                        <label>{{model.tgl_lahir}}</label>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group>
		                <label for="city"><b>Status Mustahik</b></label><br>
                        <label>{{model.mustahik_status}}</label>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group>
		                <label for="city"><b>Jumlah Keluarga</b></label><br>
                        <label>{{model.family_size}}</label>
		              </b-form-group>
		            </b-col>
                    <b-col sm="6">
		              <b-form-group>
		                <label for="avatar"><b>Foto</b></label><br>
                        <p class="mt-2" v-if="model.photo_display">
							<img width="80" :src="model.photo_display" class="responsive"/>
                        </p>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group>
		                <label for="city"><b>Telepon</b></label><br>
                        <label>{{model.phone}}</label>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group>
		                <label for="city"><b>Keterangan</b></label><br>
                        <label>{{model.description}}</label>
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
				api : 'mustahik',
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

			}
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