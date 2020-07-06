<template>
	<form class="form-horizontal" @submit.prevent="save">
		<div class="animated fadeIn">
	    	<b-card>
			    <div slot="header">
			    	<strong>{{$route.meta.label}}</strong>
			    </div>
			    <b-row>
			    	<b-col sm="6">
							<b-form-group>
								<label for="periode"><b>Periode</b></label><br>
								<label>{{model.periode}}</label>							
							</b-form-group>
							<b-form-group>
								<label for="date"><b>Tanggal</b></label><br>
								<label>{{model.created_at}}</label>							
							</b-form-group>
						</b-col>
						<b-col sm="6">
							<b-form-group>
								<label for="Pembayar"><b>Telah Terima Dari</b></label><br>
								<label>
									<p class="no-margin">{{model.name}}</p>
									<p class="no-margin">{{model.address}}</p>
									<p class="no-margin">{{ model.phone ? 'Telp : ' + model.phone : '' }} </p>
								</label>							
							</b-form-group>
							<b-form-group>
								<label for="date"><b>Hewan Qurban</b></label><br>
								<label>{{model.hewan_qurban.hewan_qurban.name +' - '+ model.hewan_qurban.no_urut}}</label>							
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
									</div>
									<div>
										<b-card>
											<b-row style="position:relative;">
												<b-col sm="4">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('name')}">
														<label for="parent"><strong>Nama</strong></label><br>
														<label>{{i.name}}</label>
													</b-form-group>
												</b-col>

												<b-col sm="4">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('sepertiga_ambil')}">
														<label for="parent"><strong>1/3 Bagian Diambil</strong></label><br>
														<label>{{i.status_cacahan? "Diambil" : "Tidak Diambil"}}</label>
													</b-form-group>
												</b-col>

												<b-col sm="4">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('biaya_potong')}">
														<label for="parent"><strong>Biaya Potong</strong></label><br>
														<label>{{model.income_value / model.items.length | currency}}</label>
													</b-form-group>
												</b-col>
											</b-row>
										</b-card>
									</div>
								</b-card>
							</template>
						</b-col>
						<b-col sm="12">
							<table class="table b-table" style="border-radius: 0;">
								<tbody>
									<tr>
										<td align="right" colspan="6" width="80%">Total biaya potong (Rp)</td>
										<td align="right" width="20%"><b>{{model.income_value | currency}}</b></td>
									</tr>
								</tbody>
							</table>
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
							<a target="_blank" :href="model.print" class="btn btn-primary">
								<i class="fa fa-print"></i> Cetak Kwitansi
							</a>
			            </b-col>
					</b-row>
				</div>
			</div>
	    </div>
	</form>
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
				hewan_qurban : {
					hewan_qurban: {
						name: ''
					},
					no_urut: 0,
				},
				income_value: 0,
				created_at: '',
				items : [
				],
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