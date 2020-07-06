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
								<label>{{model.date}}</label>							
							</b-form-group>
							<b-form-group>
								<label for="date"><b>Tanggal Jatuh Tempo</b></label><br>
								<label>{{model.expire_time}}</label>							
							</b-form-group>
							<b-form-group>
								<label for="status"><b>Status</b></label><br>
								<label :class="'badge badge-' + model.status.class">
									{{model.status.name}}
								</label>							
							</b-form-group>
						</b-col>
						<b-col sm="6">
							<b-form-group>
								<label for="payment_method_name"><b>Mode Pembayaran</b></label><br>
								<label>{{model.payment_method_name}}</label>							
							</b-form-group>
							<b-form-group v-if="model.payment_method_id == '2'">
								<img :src="model.qrcode" width="300"/>
							</b-form-group>
							<b-form-group v-if="model.payment_method_id == '1'">
								<img :src="model.bank" width="400"/>
							</b-form-group>
							<b-form-group>
								<label for="Pembayar"><b>Pembayar</b></label><br>
								<label>
									<p class="no-margin">{{model.name}} ({{model.no_ktp}})</p>
									<p class="no-margin">{{model.address}}</p>
									<p class="no-margin">{{ model.phone ? 'Telp : ' + model.phone : '' }} </p>
									<p class="no-margin">{{ model.email ? 'Email : ' + model.email : '' }} </p>
									<p class="no-margin">{{ model.no_ktp ? 'KTP : ' + model.no_ktp : '' }}</p>
								</label>							
							</b-form-group>
						</b-col>
			    </b-row>
	    	</b-card>
			<b-card>
			    <div slot="header">
			    	<strong>Rincian Zakat</strong>
			    </div>
			    <b-row>
						<b-col sm="12" v-if="model.items.length">
							<template v-for="(m,idx) in model.items">
								<b-card>
									<div slot="header">
										<strong>{{m.muzakki_name}} ({{m.muzakki_relation}}) - {{m.muzakki_ktp}}</strong>
									</div>
									<div>
										<b-card v-for="(i,idx_zakat) in m.zakat" :key="idx_zakat"  >
											<b-row>
												<b-col sm="3">
													<b-form-group>
														<label for="zakat_type_id">Tipe Zakat</label><br>
														<label>{{i.zakat_type}}</label>
													</b-form-group>
												</b-col>

												<!-- Fitrah --->
												<b-col sm="3" v-if="i.zakat_type_id == 'FTR'">
													<b-form-group v-bind:class="{ 'is-invalid': errors.has('Jenis Fitrah')}">
														<label for="parent">Jenis Fitrah</label><br>
														<label>Zakat Fitrah {{i.jenis_fitrah}}</label>
													</b-form-group>
												</b-col>
												<b-col sm="3" v-if="i.zakat_type_id == 'FTR' && i.jenis_fitrah == 'Khusus'">
													<b-form-group>
														<label for="parent">Jenis Dibayarkan</label><br>
														<label>{{i.transaction_type}}</label>
													</b-form-group>
												</b-col>
												<b-col sm="3" v-if="i.zakat_type_id == 'FTR' && i.jenis_fitrah == 'Khusus'">
													<b-form-group>
														<label for="parent">Jumlah Fitrah Khusus (Rp/Kg)</label><br>
														<label>{{i.income | currency}}</label>
													</b-form-group>
												</b-col>
												<b-col sm="3" v-if="i.zakat_type_id == 'FTR' && i.jenis_fitrah == 'Uang'">
													<b-form-group>
														<label for="zakat_quality_id">Kualitas Zakat</label><br>
														<label>{{i.zakat_quality_id.name}}</label>
													</b-form-group>
												</b-col>

												<b-col sm="3" v-if="i.zakat_type_id == 'FTR' && i.jenis_fitrah == 'Beras'">
													<b-form-group>
														<label for="income">Keterangan</label><br>
														<label>Sebesar 2,5 Kg / 3,5 liter per orang</label>
													</b-form-group>
												</b-col>

												<!-- Fidyah --->
												<b-col sm="3" v-if="i.zakat_type_id == 'FDY'">
													<b-form-group >
														<label for="parent">Jenis Dibayarkan</label><br>
														<label>{{i.transaction_type}}</label>
													</b-form-group>
												</b-col>
												<b-col sm="3" v-if="i.zakat_type_id == 'FDY'">
													<b-form-group>
														<label for="parent">Jumlah (Rp/Kg)</label><br>
														<label>{{i.income | currency}}</label>
													</b-form-group>
												</b-col>

												<!-- Sedekah --->
												<b-col sm="3" v-if="i.zakat_type_id == 'SDQ'">
													<b-form-group>
														<label for="parent">Jenis Dibayarkan</label><br>
														<label>{{i.transaction_type}}</label>
													</b-form-group>
												</b-col>
												<b-col sm="3" v-if="i.zakat_type_id == 'SDQ'">
													<b-form-group>
														<label for="parent">Jumlah (Rp)</label><br>
														<label>{{i.income | currency}}</label>
													</b-form-group>
												</b-col>

												<!-- Mal --->
												<b-col sm="3" v-if="i.zakat_type_id == 'MAL'">
													<b-form-group>
														<label for="parent">Jenis Dibayarkan</label><br>
														<label>{{i.transaction_type}}</label>
													</b-form-group>
												</b-col>
												<b-col sm="3" v-if="i.zakat_type_id == 'MAL'">
													<b-form-group>
														<label for="parent">Jumlah (Rp/Gram)</label><br>
														<label>{{i.income | currency}}</label>
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
										<td align="right" colspan="6" width="80%">Total Zakat Uang + Cek (Rp)</td>
										<td align="right" width="20%"><b>{{model.total_money | currency}}</b></td>
									</tr>
									<tr>
										<td align="right" colspan="6" width="80%">Total Zakat Beras (Kg)</td>
										<td align="right" width="20%"><b>{{model.total_goods | currency}}</b></td>
									</tr>
									<template v-if="model.show_uniquecode">
										<tr>
											<td align="right" colspan="6" width="80%">Unique Code</td>
											<td align="right" width="20%"><b>{{model.unique_code}}</b></td>
										</tr>
										<tr>
											<td align="right" colspan="6" width="80%"><h5>Grandtotal (Rp)</h5></td>
											<td align="right" width="20%"><h5><b>{{(parseFloat(model.total_money) + parseFloat(model.unique_code)) | currency}}</b></h5></td>
										</tr>
									</template>
								</tbody>
							</table>
						</b-col>
	        	</b-row>
			</b-card>
			<b-card v-if="model.status.id != 0 && model.createdby.role == 3">
			    <div slot="header">
			    	<strong>Konfirmasi Pembayaran</strong>
			    </div>
	        	<b-row>
					<b-col sm="6">
						<b-form-group>
							<label for="sender_bank"><b>Bank Pembayar</b></label><br>
							<label>{{model.sender_bank}}</label>							
						</b-form-group>
						<b-form-group>
							<label for="sender_name"><b>Nama Pembayar</b></label><br>
							<label>{{model.sender_name}}</label>							
						</b-form-group>
					</b-col>
					<b-col sm="6">
						<b-form-group v-if="model.receipt">
							<label for="receipt"><b>Bukti Pembayaran</b></label><br>
							<label>
								<a href="#" data-toggle="modal" data-target="#mdlReceipt">
									<img :src="model.receipt" width="100"/>
								</a>
							</label>							
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
							<b-button v-if="model.status.id != 2 && model.role != 3" 
								type="button" variant="success" v-on:click="update(2)" class="px-4">
								<i class="fa fa-check"></i> Terima Pembayaran
							</b-button>
							<b-button v-if="model.status.id == 0 && model.role == 3" 
									type="button" variant="success" class="px-4" 
									data-toggle="modal" data-target="#confirmModal">
									<i class="fa fa-check"></i> Konfirmasi Pembayaran
							</b-button>
							<a target="_blank" v-if="model.status.id == 2" :href="model.print" class="btn btn-primary">
								<i class="fa fa-print"></i> Cetak Kwitansi
							</a>
			            </b-col>
					</b-row>
				</div>
			</div>
	    </div>

			<div class="modal" tabindex="-1" ref="confirmModal" id="confirmModal" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form class="form-horizontal" @submit.prevent="confirm">
							<div class="modal-header">
								<h5 class="modal-title">Konfirmasi Pembayaran</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<b-row>
									<b-col sm="12">
										<b-form-group>
											<label for="sender_bank">* Bank Pembayar</label>
											<b-form-input type="text" v-model="model.sender_bank" required></b-form-input>
										</b-form-group>
									</b-col>
									<b-col sm="12">
										<b-form-group>
											<label for="sender_name">* Nama Pembayar</label>
											<b-form-input type="text" v-model="model.sender_name" required></b-form-input>
										</b-form-group>
									</b-col>
									<b-col sm="12">
										<b-form-group>
											<b-form-group>
												<label for="receipt">* Bukti Pembayaran</label>
												<input type="file" required class="form-control" v-on:change="AddReceipt">
											</b-form-group>
										</b-form-group>
									</b-col>
								</b-row>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
								<button type="submit" class="btn btn-primary">Simpan</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="modal" tabindex="-1" ref="mdlReceipt" id="mdlReceipt" role="dialog">
				<div class="modal-dialog modal-block" role="document">
					<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Bukti Pembayaran</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<b-row>
									<b-col sm="12" style="text-align:center;">
										<img :src="model.receipt" class="img-responsive" style="max-width: 100%;height: auto;"/>
									</b-col>
								</b-row>
							</div>
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
				api : 'zakat',
			},
			action : this.$route.meta.permission.action,
			response : {
				variant : "",
				message : ""
			},
			model : {
				role : "",
				avatar_display : "",
				status : 1,
				sender_bank : "",
				sender_name : "",
				receipt : "",
			},
			params : {
				roles : []
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
						_.model.avatar_display	= _.model.avatar
					}else{
						_.$router.push({name: _.config.uri, params: {
			              	'variant': 'danger',
			               	'message': 'Data cannot be found!'
			            }})
					}
				})
	    	}
		},
		update(status) {
                let _ = this
                _.$swal.fire({
                    title: status == 2 ? 'Yakin akan menerima pembayaran ?' : 'Yakin akan mengkonfirmasi pembayaran ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.value) {
                        _.$axios.post(_.config.api + "/updatestatus", {
                            'id': _.model.id , 'status' : status
                        }).then(res => {
                            _.$router.push({name: _.config.uri, params: {
                                'variant': (res.data.status) ? 'success':'danger',
                                'message': res.data.message
							}})
                        }).catch(err => {
                            _.response = {
                                variant: "danger",
                                message: err.response.data.message
                            }
                        })
                    }
                })
            },
		AddReceipt (event) {
			this.model.receipt 			= event.target.files[0]
		},
		confirm(){
			let _ = this
			_.params.FormData = new FormData()
            for (let i in this.model){
				_.params.FormData.append(i, this.model[i])
			}
			_.$validator.validate().then(valid => {
	        	if (valid) {
					_.$axios.post(_.config.api+'/payment', _.params.FormData)
		            .then(res => {
		            	_.$router.push({name: _.config.uri, params: {
			              	'variant': (res.data.status) ? 'success':'danger',
			               	'message': res.data.message
						}})
						location.reload()
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