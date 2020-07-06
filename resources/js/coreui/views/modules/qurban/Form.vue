<template>
	<div>
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
								<label for="periode"><b>Periode</b></label><br>
								<label>{{model.periode}}</label>							
							</b-form-group>
						</b-col>
						<b-col sm="6">
							<b-form-group>
								<label for="date"><b>Tanggal</b></label><br>
								<label>{{model.date}}</label>							
							</b-form-group>
						</b-col>
						<b-col sm="6">
							<b-form-group>
								<label for="date"><b>Nama</b></label><br>
								<b-form-input type="text" v-model="model.name" v-validate="'required'" name="name"></b-form-input>						
							</b-form-group>
						</b-col>
						<b-col sm="6">
							<b-form-group>
								<label for="date"><b>Alamat</b></label><br>
								<b-form-input type="text" v-model="model.address" name="address"></b-form-input>						
							</b-form-group>
						</b-col>
						<b-col sm="6">
							<b-form-group>
								<label for="date"><b>No. Telp</b></label><br>
								<b-form-input type="text" v-model="model.phone" name="phone"></b-form-input>						
							</b-form-group>
						</b-col>
						<b-col sm="6">
							<b-form-group>
								<label for="date"><b>Hewan Qurban</b></label><br>
								<select class="form-control" v-validate="'required'" name="jenis_hewan" v-model="model.trx_hewan_qurban_id" v-on:change="setBiayaPotong()">
									<option value="">Pilih Hewan</option>
									<option v-for="hewan in params.hewanqurban" v-bind:value="hewan.id">{{ hewan.hewan_qurban.name+ ' - ' + hewan.no_urut }}</option>
								</select>
							</b-form-group>
						</b-col>
					</b-row>
				</b-card>
				<b-card>
					<div slot="header">
						<strong>Rincian Qurban</strong>
					</div>
					<b-row>
						<b-col sm="12">
							<template v-for="(i,idx) in model.items">
								<b-card>
									<div slot="header">
										<button v-if="model.items.length > 1" type="button" class="btn btn-danger btn-sm pull-right" v-on:click="rmItem(idx)">
											<i class="fa fa-trash"></i>
										</button>
									</div>
									<div>
										<b-card>
											<b-row style="position:relative;">
												<b-col sm="4">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('name')}">
														<label for="parent">Nama</label>
														<b-form-input type="text" v-model="i.name" v-validate="'required'" name="name"></b-form-input>
														<div v-if="errors.has('name')">
															<p class="text-danger">{{ errors.first('name') }}</p>
														</div>
													</b-form-group>
												</b-col>

												<b-col sm="4">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('sepertiga_ambil')}">
														<label for="parent">1/3 Bagian Diambil</label>
														<b-form-checkbox v-model="i.status_cacahan" v-validate="'required'" name="sepertiga_ambil"></b-form-checkbox>
														<div v-if="errors.has('sepertiga_ambil')">
															<p class="text-danger">{{ errors.first('sepertiga_ambil') }}</p>
														</div>
													</b-form-group>
												</b-col>

												<b-col sm="4">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('biaya_potong')}">
														<label for="parent">Biaya Potong</label><br>
														<label><strong>{{params.biayaPotong | currency}}</strong></label>
													</b-form-group>
												</b-col>
											</b-row>
										</b-card>
									</div>
								</b-card>
							</template>
							<div slot="footer">
								<label>Total Biaya Potong</label><br>
								<label><strong>{{ params.biayaPotong * model.items.length | currency }}</strong></label>
								<a href="javascript:void(0)" v-on:click="addOrang(model.items)" class="btn btn-success pull-right">Tambah Orang</a>
							</div>
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
	</div>
</template>
<style>
.modal-block {
  width: 100%;
  max-width: 100%;
  height: 100%;
  margin:auto;
  margin-top:5%;
  padding: 0;
}

.modal-half {
  width: 70%;
  max-width: 70%;
  height: 100%;
  margin:auto;
  margin-top:5%;
  padding: 0;
}

.modal-content {
  height: auto;
  min-height: 100%;
  border-radius: 0;
}
.no-margin{
	margin-bottom:0px;
}
</style>
<script>

export default {
	components : {
	},
	data() {
		return {
			config : {
				uri : this.$route.meta.permission.link,
				api : 'qurban',
			},
			action : this.$route.meta.permission.action,
			response : {
				variant : "",
				message : ""
			},
			model : {
				name : "",
				address : "",
				phone : "",
				periode : "",
				trx_hewan_qurban_id : 0,
				income_value: 0,
				status: true,
				items : [
					{
						name: "",
						status_cacahan: false
					}
				],
			},
			params : {
				hewanqurban : [],
				biayaPotong : 0
			},
		}
	},
	created(){
		let _ = this
        _.getById()
		_.getComponent()
		_.getHewanQurban()
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
						_.params.biayaPotong = _.model.hewan_qurban.hewan_qurban.biaya_potong;
					}else{
						_.$router.push({name: _.config.uri, params: {
			              	'variant': 'danger',
			               	'message': 'Data cannot be found!'
			            }})
					}
				})
	    	}
		},
		getComponent(){
			let _ = this
			_.$axios.get('/zakat/component').then(res => {
				let data 		= res.data.data
				_.model.periode = data.periode ? data.periode.year : ""
				_.model.date 	= data.date
			})
		},
		getHewanQurban(){
			let _ = this
			_.$axios.get('trxhewanqurban').then(res => {
	        	_.params.hewanqurban = res.data.data.rows
			})
		},
		setBiayaPotong(){
			let _ = this

			let hewanqurban = _.params.hewanqurban.find( ({ id }) => id == _.model.trx_hewan_qurban_id );
			_.params.biayaPotong = hewanqurban.hewan_qurban.biaya_potong;
		},
		rmItem(idx){
			this.model.items.splice(idx,1)
		},
		addOrang(data){
			data.push({
				name : "",
				status_cacahan : "",
			})
		},
		rmOrang(data,idx){
			data.splice(idx,1)
		},
		validation(){
			let _ = this,
			error = ""
			let items = _.model.items
			if(!items.length){
				error = "Data qurban tidak boleh kosong"
			}

			return error
		},
		save(){
			let _ = this
			let validation = _.validation()

			_.model.income_value = _.params.biayaPotong * _.model.items.length

			_.$validator.validate().then(valid => {
					if (valid) {
						if(!validation){
							if(_.action == "update"){
								_.model.id = _.$route.params.id
							}
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
						}else{
							alert(validation)
						}
					}
			});			
		}
	}
}
</script>