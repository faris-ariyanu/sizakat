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
							<b-form-group>
								<label for="date"><b>Tanggal</b></label><br>
								<label>{{model.date}}</label>							
							</b-form-group>
						</b-col>
						<b-col sm="4">
							<b-form-group v-bind:class="{ 'is-invalid': errors.has('Mode Pembayaran')}">
								<label for="payment_method_id">Mode Pembayaran</label>
								<select class="form-control" v-model="model.payment_method_id" v-validate="'required'" name="Mode Pembayaran">
									<option value="">Pilih Mode Pembayaran</option>
									<option v-for="row in params.paymentmethod" v-bind:value="row.id">{{ row.name }}</option>
								</select>
								<div v-if="errors.has('Mode Pembayaran')">
									<p class="text-danger">{{ errors.first('Mode Pembayaran') }}</p>
								</div>
							</b-form-group>
							<b-form-group v-if="model.payment_method_id == '1'">
								<img :src="params.bank" width="400"/>
							</b-form-group>
							<b-form-group v-if="model.payment_method_id == '2'">
								<img :src="params.qrcode" width="300"/>
							</b-form-group>
							<b-form-group v-bind:class="{ 'is-invalid': errors.has('Identitas Pembayar')}">
								<label for="muzakki_id"><b>Identitas Pembayar</b></label>
								
								<div class="input-group mb-2">
									<input type="text" readonly v-model="model.name" v-validate="'required'" name="Identitas Pembayar" class="form-control">
									<template v-if="!params.muzakki">
										<div class="input-group-append">
											<a class="input-group-text"
												href="javascript:void(0)" 
												v-on:click="openMuzzaki()">
												Pilih Muzakki
											</a>
										</div>
									</template>
								</div>	
								<div v-if="errors.has('Identitas Pembayar')">
									<p class="text-danger">{{ errors.first('Identitas Pembayar') }}</p>
								</div>	
								<label v-if="model.name">
									
									<p class="no-margin">{{model.address}}</p>
									<p class="no-margin">{{ model.phone ? 'Telp : ' + model.phone : '' }} </p>
									<p class="no-margin">{{ model.email ? 'Email : ' + model.email : '' }} </p>
									<p class="no-margin">{{ model.no_ktp ? 'KTP : ' + model.no_ktp : '' }}</p>
								</label>					
							</b-form-group>
						</b-col>
					</b-row>
				</b-card>
				<b-card  v-if="model.items.length">
					<div slot="header">
						<strong>Rincian Zakat</strong>
					</div>
					<b-row>
						<b-col sm="12" v-if="model.items.length">
							<template v-for="(i,idx) in model.items">
								<b-card>
									<div slot="header">
										<strong>{{i.muzakki_name}} ({{i.muzakki_relation}}) - {{i.muzakki_ktp}}</strong>

										<button v-if="model.items.length > 1" type="button" class="btn btn-danger btn-sm pull-right" v-on:click="rmItem(idx)">
											<i class="fa fa-trash"></i>
										</button>
									</div>
									<div>
										<b-card v-for="(z,idx_zakat) in i.zakat" :key="idx_zakat"  >
											<b-row style="position:relative;">
												<button v-if="i.zakat.length > 1" type="button" class="btn btn-danger btn-sm" style="position: absolute;top: 1px;right: 4px;" v-on:click="rmZakat(i,idx_zakat)">
													<i class="fa fa-trash"></i>
												</button>
												
												<b-col sm="3">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('Tipe Zakat')}">
														<label for="zakat_type_id">Tipe Zakat</label>
														<select class="form-control" v-model="z.zakat_type_id" v-validate="'required'" name="Tipe Zakat">
															<option value="">Pilih Tipe Zakat</option>
															<option v-for="type in params.type" v-bind:value="type.code">{{ type.name }}</option>
														</select>
														<div v-if="errors.has('Tipe Zakat')">
															<p class="text-danger">{{ errors.first('Tipe Zakat') }}</p>
														</div>
													</b-form-group>
												</b-col>

												<!-- Fitrah --->
												<b-col sm="3" v-if="z.zakat_type_id == 'FTR'">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('Jenis Fitrah')}">
														<label for="parent">Jenis Fitrah</label>
														<select class="form-control" v-model="z.jenis_fitrah" v-validate="'required'" name="Jenis Fitrah">
															<option value="">Pilih Fitrah</option>
															<option value="Uang">Zakat Fitrah Uang</option>
															<option value="Beras">Zakat Fitrah Beras</option>
															<option value="Khusus">Zakat Fitrah Khusus</option>
														</select>
														<div v-if="errors.has('Jenis Fitrah')">
															<p class="text-danger">{{ errors.first('Jenis Fitrah') }}</p>
														</div>
													</b-form-group>
												</b-col>
												<b-col sm="3" v-if="z.zakat_type_id == 'FTR' && z.jenis_fitrah == 'Khusus'">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('Jenis Dibayarkan')}">
														<label for="parent">Jenis Dibayarkan</label>
														<select class="form-control" v-model="z.transaction_type" v-validate="'required'" name="Jenis Dibayarkan">
															<option value="">Pilih Jenis Dibayarkan</option>
															<option value="Uang">Uang</option>
															<option value="Beras">Beras</option>
														</select>
														<div v-if="errors.has('Jenis Dibayarkan')">
															<p class="text-danger">{{ errors.first('Jenis Dibayarkan') }}</p>
														</div>
													</b-form-group>
												</b-col>
												<b-col sm="3" v-if="z.zakat_type_id == 'FTR' && z.jenis_fitrah == 'Khusus'">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('Jumlah')}">
														<label for="parent">Jumlah Fitrah Khusus (Rp/Kg)</label>
														<b-form-input type="number" v-model="z.income" v-validate="'required'" name="Jumlah"></b-form-input>
														<div v-if="errors.has('Jumlah')">
															<p class="text-danger">{{ errors.first('Jumlah') }}</p>
														</div>
													</b-form-group>
												</b-col>
												<b-col sm="3" v-if="z.zakat_type_id == 'FTR' && z.jenis_fitrah == 'Uang'">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('Kualitas Zakat')}">
														<label for="zakat_quality_id">Kualitas Zakat</label>
														<select class="form-control" v-model="z.zakat_quality_id" v-validate="'required'" name="Kualitas Zakat">
															<option value="">Pilih Kualitas Zakat</option>
															<option v-for="row in params.zakatquality" v-bind:value="row">{{ row.description }} ({{ row.value }})</option>
														</select>
														<div v-if="errors.has('Kualitas Zakat')">
															<p class="text-danger">{{ errors.first('Kualitas Zakat') }}</p>
														</div>
													</b-form-group>
												</b-col>
												<b-col sm="3" v-if="z.zakat_type_id == 'FTR' && z.jenis_fitrah == 'Beras'">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('Kualitas Zakat')}">
														<label for="income">Keterangan</label><br>
														<label>Sebesar 2,5 Kg / 3,5 liter per orang</label>
													</b-form-group>
												</b-col>

												<!-- Fidyah --->
												<b-col sm="3" v-if="z.zakat_type_id == 'FDY'">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('Jenis Dibayarkan')}">
														<label for="parent">Jenis Dibayarkan</label>
														<select class="form-control" v-model="z.transaction_type" v-validate="'required'" name="Jenis Dibayarkan">
															<option value="">Pilih Jenis Dibayarkan</option>
															<option value="Uang">Uang</option>
															<option value="Beras">Beras</option>
														</select>
														<div v-if="errors.has('Jenis Dibayarkan')">
															<p class="text-danger">{{ errors.first('Jenis Dibayarkan') }}</p>
														</div>
													</b-form-group>
												</b-col>
												<b-col sm="3" v-if="z.zakat_type_id == 'FDY'">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('Jumlah')}">
														<label for="parent">Jumlah (Rp/Kg)</label>
														<b-form-input type="number" v-model="z.income" v-validate="'required'" name="Jumlah"></b-form-input>
														<div v-if="errors.has('Jumlah')">
															<p class="text-danger">{{ errors.first('Jumlah') }}</p>
														</div>
													</b-form-group>
												</b-col>

												<!-- Sedekah --->
												<b-col sm="3" v-if="z.zakat_type_id == 'SDQ'">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('Jenis Dibayarkan')}">
														<label for="parent">Jenis Dibayarkan</label>
														<select class="form-control" v-model="z.transaction_type" v-validate="'required'" name="Jenis Dibayarkan">
															<option value="">Pilih Jenis Dibayarkan</option>
															<option value="Uang">Uang</option>
															<option value="Cek">Cek</option>
															<option value="Beras">Beras</option>
														</select>
														<div v-if="errors.has('Jenis Dibayarkan')">
															<p class="text-danger">{{ errors.first('Jenis Dibayarkan') }}</p>
														</div>
													</b-form-group>
												</b-col>
												<b-col sm="3" v-if="z.zakat_type_id == 'SDQ'">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('Jumlah')}">
														<label for="parent">Jumlah (Rp / Kg)</label>
														<b-form-input type="number" v-model="z.income" v-validate="'required'" name="Jumlah"></b-form-input>
														<div v-if="errors.has('Jumlah')">
															<p class="text-danger">{{ errors.first('Jumlah') }}</p>
														</div>
													</b-form-group>
												</b-col>

												<!-- Mal --->
												<b-col sm="3" v-if="z.zakat_type_id == 'MAL'">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('Jenis Dibayarkan')}">
														<label for="parent">Jenis Dibayarkan</label>
														<select class="form-control" v-model="z.transaction_type" v-validate="'required'" name="Jenis Dibayarkan">
															<option value="">Pilih Jenis Dibayarkan</option>
															<option value="Uang">Uang</option>
															<option value="Cek">Cek</option>
															<option value="Emas">Emas</option>
														</select>
														<div v-if="errors.has('Jenis Dibayarkan')">
															<p class="text-danger">{{ errors.first('Jenis Dibayarkan') }}</p>
														</div>
													</b-form-group>
												</b-col>
												<b-col sm="3" v-if="z.zakat_type_id == 'MAL'">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('Jumlah')}">
														<label for="parent">Jumlah (Rp/Gram)</label>
														<b-form-input type="number" v-model="z.income" v-validate="'required'" name="Jumlah"></b-form-input>
														<div v-if="errors.has('Jumlah')">
															<p class="text-danger">{{ errors.first('Jumlah') }}</p>
														</div>
													</b-form-group>
												</b-col>
											</b-row>
										</b-card>
									</div>
									<div slot="footer">
										<a href="javascript:void(0)" v-on:click="addZakat(i)" class="btn btn-success pull-right">Tambah Zakat</a>
									</div>
								</b-card>
							</template>
						</b-col>
					</b-row>
					<div slot="footer">
						<b-button  variant="primary" v-on:click="openMuzzakiFamily()" class="px-4 pull-right">
							Tambah Keluarga Muzakki
						</b-button>
					</div>
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
		<div class="modal fade" id="MdlMuzakki" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
			aria-hidden="true">
			<div class="modal-dialog  modal-half" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Pilih Muzakki</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<ListMuzzaki v-on:setMuzakki="setMuzakki"/>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="MdlMuzakkiFamily" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
			aria-hidden="true">
			<div class="modal-dialog  modal-half" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Tambah Keluarga Muzakki</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<ListMuzzakiFamily v-if="model.muzakki_id != 0" v-on:setMuzakkiFamily="setMuzakkiFamily" :parent="model.muzakki_id" />
					</div>
				</div>
			</div>
		</div>
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
import ListMuzzaki from '../muzakki/List'
import ListMuzzakiFamily from '../muzakkifamily/List'
export default {
	components : {
		ListMuzzaki,
		ListMuzzakiFamily
	},
	data() {
		return {
			config : {
				uri : this.$route.meta.permission.link,
				api : 'zakat',
			},
			action : this.$route.meta.permission.action,
			response : {
				variant : "",
				message : ""
			},
			model : {
				payment_method_id : "",
				muzakki_id : 0,
				name : "",
				address : "",
				phone : "",
				email : "",
				no_ktp : "",
				relation : "",
				periode : "",
				items : [],
			},
			params : {
				type : [],
				zakatquality : [],
				paymentmethod :[], 
				qrcode : "",
				bank : "",
				muzakki : "",
			},
		}
	},
	created(){
		let _ = this
        _.getById()
		_.getComponent()
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
		getComponent(){
			let _ = this
			_.$axios.get(_.config.api + '/component').then(res => {
				let data 		= res.data.data
				_.model.periode = data.periode ? data.periode.year : ""
				_.model.date 	= data.date 
				_.params.type	= data.type
				_.params.zakatquality	= data.zakatquality
				_.params.paymentmethod	= data.paymentmethod
				_.params.qrcode 		= data.qrcode
				_.params.bank			= data.bank
				_.params.muzakki		= data.muzakki
				if(_.params.muzakki){
					_.setMuzakki(_.params.muzakki)
				}
			})
		},
		rmItem(idx){
			this.model.items.splice(idx,1)
		},
		openMuzzaki(){
			$("#MdlMuzakki").modal('show')
		},
		openMuzzakiFamily(){
			$("#MdlMuzakkiFamily").modal('show')
		},
		setMuzakki(muzakki){
    		let _ = this
			_.model.items 		= []
			_.model.muzakki_id 	= muzakki.id
			_.model.name 		= muzakki.name
			_.model.address 	= muzakki.address
			_.model.phone 		= muzakki.phone
			_.model.email 		= muzakki.email
			_.model.no_ktp 		= muzakki.no_ktp
			_.model.relation 	= muzakki.relation
			let relations 		= muzakki.relations.concat(muzakki)
			for(let i in relations){
				_.model.items.push({
					zakat : [
						{
							zakat_type_id : "",
							transaction_type : "",
							jenis_fitrah : "",
							zakat_quality_id : "",
							income : "",
						}
					],
					muzakki_id : relations[i].id,
					muzakki_name : relations[i].name,
					muzakki_phone : relations[i].phone,
					muzakki_address : relations[i].address,
					muzakki_email : relations[i].email,
					muzakki_ktp : relations[i].no_ktp,
					muzakki_relation : relations[i].relation,
				})
			}
	        $("#MdlMuzakki").modal('hide')
		},
		setMuzakkiFamily(muzakki){
			let _ = this
			let duplicate = false
			for(let i in _.model.items){
				if(_.model.items[i].muzakki_id == muzakki.id){
					duplicate = true
					break;
				}
			}

			if(!duplicate){
				_.model.items.push({
					zakat : [
						{
							zakat_type_id : "",
							transaction_type : "",
							jenis_fitrah : "",
							zakat_quality_id : "",
							income : "",
						}
					],
					muzakki_id : muzakki.id,
					muzakki_name : muzakki.name,
					muzakki_phone : muzakki.phone,
					muzakki_address : muzakki.address,
					muzakki_email : muzakki.email,
					muzakki_ktp : muzakki.no_ktp,
					muzakki_relation : muzakki.relation,
				})
			}
	        $("#MdlMuzakkiFamily").modal('hide')
		},
		addZakat(data){
			data.zakat.push({
				zakat_type_id : "",
				transaction_type : "",
				jenis_fitrah : "",
				zakat_quality_id : "",
				income : "",
			})
		},
		rmZakat(data,idx){
			data.zakat.splice(idx,1)
		},
		validation(){
			let _ = this,
			error = ""
			let items = _.model.items
			if(!items.length){
				error = "Data transaksi tidak boleh kosong"
			}

			for(let i in items){
				if(!items[i].zakat.length){
					error = "Data zakat tidak boleh kosong"
					break
				}
			}

			return error
		},
		save(){
			let _ = this
			let validation = _.validation()

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