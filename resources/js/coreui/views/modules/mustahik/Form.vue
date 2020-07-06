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
						<b-form-group v-bind:class="{ 'is-invalid': errors.has('Kategori')}">
							<label for="Kategori">Kategori</label>
							<select class="form-control" v-validate="'required'" name="Kategori" v-model="model.category_id">
								<option value="">Pilih Kategori</option>
								<option v-for="cat in params.categories" v-bind:value="cat.id">{{ cat.name }}</option>
							</select>
							<div v-if="errors.has('Kategori')">
							<p class="text-danger">{{ errors.first('Kategori') }}</p>
							</div>
						</b-form-group>
					</b-col>
		            <b-col sm="6">
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Name')}">
		                <label for="name">* Nama</label>
		                <b-form-input type="text" v-model="model.name" v-validate="'required'" name="Name"></b-form-input>
		                <div v-if="errors.has('Name')">
	                      <p class="text-danger">{{ errors.first('Name') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('No KTP')}">
		                <label for="id_card">* No KTP</label>
		                <b-form-input type="text" v-model="model.no_ktp" v-validate="'required'" name="No KTP"></b-form-input>
		                <div v-if="errors.has('No KTP')">
	                      <p class="text-danger">{{ errors.first('No KTP') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Alamat')}">
		                <label for="id_card">* Alamat</label>
						<textarea class="form-control" v-model="model.address" v-validate="'required'" name="Alamat"></textarea>
		                <div v-if="errors.has('Alamat')">
	                      <p class="text-danger">{{ errors.first('Alamat') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Provinsi')}">
		                <label for="id_card">* Provinsi</label>
		                <b-form-input type="text" v-model="model.province" v-validate="'required'" name="Provinsi"></b-form-input>
		                <div v-if="errors.has('Provinsi')">
	                      <p class="text-danger">{{ errors.first('Provinsi') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Kota')}">
		                <label for="Kota">* Kota / Kabupaten</label>
		                <b-form-input type="text" v-model="model.regency" v-validate="'required'" name="Kota"></b-form-input>
		                <div v-if="errors.has('Kota')">
	                      <p class="text-danger">{{ errors.first('Kota') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('RT')}">
		                <label for="id_card">* RT</label>
		                <b-form-input type="number" v-model="model.rt" v-validate="'required'" name="RT"></b-form-input>
		                <div v-if="errors.has('RT')">
	                      <p class="text-danger">{{ errors.first('RT') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('RW')}">
		                <label for="RW">* RW</label>
		                <b-form-input type="number" v-model="model.rw" v-validate="'required'" name="RW"></b-form-input>
		                <div v-if="errors.has('RW')">
	                      <p class="text-danger">{{ errors.first('RW') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Tanggal Lahir')}">
		                <label for="Tanggal Lahir">* Tanggal Lahir</label>
		                <b-form-input type="date" v-model="model.birthdate" v-validate="'required'" name="Tanggal Lahir"></b-form-input>
		                <div v-if="errors.has('Tanggal Lahir')">
	                      <p class="text-danger">{{ errors.first('Tanggal Lahir') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Status Mustahik')}">
		                <label for="role">* Status Mustahik</label>
		                <select v-model="model.mustahik_status" v-validate="'required'" name="Status Mustahik" class="form-control">
	                        <option value="">Pilih Status Mustahik</option>
							<option value="Janda">Janda</option>
							<option value="Yatim">Yatim</option>
							<option value="Miskin">Miskin</option>
	                    </select>
	                    <div v-if="errors.has('Status Mustahik')">
	                      <p class="text-danger">{{ errors.first('Status Mustahik') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group v-bind:class="{ 'is-invalid': errors.has('Jumlah Keluarga')}">
		                <label for="Jumlah Keluarga">* Jumlah Keluarga</label>
		                <b-form-input type="number" v-model="model.family_size" v-validate="'required'" name="Jumlah Keluarga"></b-form-input>
		                <div v-if="errors.has('Jumlah Keluarga')">
	                      <p class="text-danger">{{ errors.first('Jumlah Keluarga') }}</p>
	                    </div>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group>
		                <label for="avatar">Foto</label>
		                <input type="file" class="form-control" v-on:change="AddPhoto">
		                <p class="mt-2" v-if="model.photo_display">
							<img :src="model.photo_display" class="responsive" width="200"/>
                        </p>
		              </b-form-group>
		            </b-col>
					<b-col sm="6">
		              <b-form-group>
		                <label for="Telepon">Telepon</label>
		                <b-form-input type="text" v-model="model.phone" name="Telepon"></b-form-input>
		              </b-form-group>
		            </b-col>
					<b-col sm="12">
		              <b-form-group>
		                <label for="Telepon">Keterangan</label>
		                <textarea class="form-control" v-model="model.description"></textarea>
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
				api : 'mustahik',
			},
			action : this.$route.meta.permission.action,
			response : {
				variant : "",
				message : ""
			},
			model : {
				category_id : "",
				photo_display : "",
				mustahik_status : "",
			},
			params : {
				categories : []
			}
		}
	},
	created(){
		let _ = this
		_.getById()
		_.getCategory()
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
		getCategory(){
			let _ = this
			_.$axios.get('categorymustahik').then(res => {
	        	_.params.categories = res.data.data.rows
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