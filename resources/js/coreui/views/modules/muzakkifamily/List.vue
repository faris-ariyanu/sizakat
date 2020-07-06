<template>
	<div class="animated fadeIn">
        <b-row>
            <b-col sm="12">
                <b-card v-if="params.page == 'list'">
                    <b-row>
                        <div class="col-sm-12 mb-10">
                            <div class="col-sm-12 pull-right no-padding">
                                <div class="input-group">
                                    <input type="text" placeholder="Pencarian..." v-model="config.search.key" 
                                        v-on:keyup.enter="get()" class="form-control" />
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" v-on:click="get()">
                                            <i class="fa fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <b-col sm="12">
                            <table class="table b-table">
                                <thead v-if="config.total">
                                    <tr>
                                        <th v-for="h in config.header" :width="h.width">
                                            <a v-if="h.value" 
                                                class="sort dark-font"
                                                v-on:click="sort(h.value)"
                                                href="javascript:void(0)">
                                                {{h.title}}
                                                <i :id="h.value" class="fa fa-sort"></i>
                                            </a>
                                            <a v-if="!h.value"
                                                class="sort dark-font" :id="h.value"
                                                href="javascript:void(0)">
                                                {{h.title}}
                                            </a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="config.total" v-for="data in config.datas">
                                        <td>
                                            <img :src="data.photo" width="80"/>
                                        </td>
                                        <td>{{data.name}} ( {{data.no_ktp}} ) <br>
                                             Status : {{data.relation}}<br>
											 {{data.address}}<br>
                                             {{data.phone}}
                                        </td>
                                        <td align="center">
                                            <button v-on:click="select(data)" title="Pilih" class="btn btn-light">
                                                Pilih
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="!config.total">
                                        <td :colspan="config.header.length" align="center"> No data available. </td>
                                    </tr>
                                </tbody>
                                <tfoot v-if="config.total">
                                    <tr>
                                        <td :colspan="config.header.length">
                                            <div class="pull-left">
                                                <p>Total {{config.total}} data</p>
                                            </div>
                                            <div class="pull-right pagination">
                                                <b-pagination @change="gotoPage" :total-rows="config.total" v-model="config.page" :per-page="config.limit">
                                                </b-pagination>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </b-col>
                    </b-row>
                    <div slot="footer">
                        <button v-on:click="params.page = 'store'" class="btn btn-primary">
				        	<i class="fa fa-plus"></i> Tambah Keluarga Muzakki
				        </button>
                    </div>
                </b-card>
                <form class="form-horizontal" @submit.prevent="save" >
                    <b-card v-if="params.page == 'store'">
                        <div slot="header">
                            <strong>Tambah Keluarga Muzakki</strong>
                        </div>
                        <b-row>
                            <div class="col-sm-12 mb-10" v-if="response.message">
                                <b-alert show :variant="response.variant">
                                    {{response.message}}
                                </b-alert>
                            </div>
                            <b-col sm="12">
                            <b-form-group v-bind:class="{ 'is-invalid': errors.has('Name')}">
                                <label for="name">* Nama</label>
                                <b-form-input type="text" v-model="model.name" v-validate="'required'" name="Name"></b-form-input>
                                <div v-if="errors.has('Name')">
                                <p class="text-danger">{{ errors.first('Name') }}</p>
                                </div>
                            </b-form-group>
                            </b-col>
                            <b-col sm="12">
                                <b-form-group v-bind:class="{ 'is-invalid': errors.has('Status Di Keluarga')}">
                                    <label for="Status Di Keluarga">* Status Di Keluarga</label>
                                    <select class="form-control" v-validate="'required'" name="Status Di Keluarga" v-model="model.relation">
                                        <option value="">Pilih</option>
                                        <option v-for="row in params.relations" v-bind:value="row">{{ row }}</option>
                                    </select>
                                    <div v-if="errors.has('Status Di Keluarga')">
                                    <p class="text-danger">{{ errors.first('Status Di Keluarga') }}</p>
                                    </div>
                                </b-form-group>
                            </b-col>
                            <b-col sm="12">
                            <b-form-group v-bind:class="{ 'is-invalid': errors.has('No KTP')}">
                                <label for="no_ktp">* No KTP</label>
                                <b-form-input type="text" v-model="model.no_ktp" v-validate="'required'" name="No KTP"></b-form-input>
                                <div v-if="errors.has('No KTP')">
                                <p class="text-danger">{{ errors.first('No KTP') }}</p>
                                </div>
                            </b-form-group>
                            </b-col>
                            <b-col sm="12">
                            <b-form-group>
                                <label for="email">Email</label>
                                <b-form-input type="email" v-model="model.email" name="Email"></b-form-input>
                            </b-form-group>
                            </b-col>
                            <b-col sm="12">
                            <b-form-group>
                                <label for="address">Alamat</label>
                                <b-form-input type="text" v-model="model.address"></b-form-input>
                            </b-form-group>
                            </b-col>
                            <b-col sm="12">
                            <b-form-group>
                                <label for="Telepon">Telepon</label>
                                <b-form-input type="text" v-model="model.phone" name="Telepon"></b-form-input>
                            </b-form-group>
                            </b-col>
                            <b-col sm="12">
                            <b-form-group v-bind:class="{ 'is-invalid': errors.has('Username')}">
                                <label for="username">* Username</label>
                                <b-form-input type="text" v-model="model.username" v-validate="'required'" name="Username"></b-form-input>
                                <div v-if="errors.has('Username')">
                                <p class="text-danger">{{ errors.first('Username') }}</p>
                                </div>
                            </b-form-group>
                            </b-col>
                            <b-col sm="12">
                            <b-form-group v-bind:class="{ 'is-invalid': errors.has('Password')}">
                                <label for="password">* Password</label>
                                <b-form-input type="password" v-model="model.password" v-validate="'required'" name="Password"></b-form-input>
                                <div v-if="errors.has('Password')">
                                <p class="text-danger">{{ errors.first('Password') }}</p>
                                </div>
                            </b-form-group>
                            </b-col>
                            <b-col sm="12">
                            <b-form-group>
                                <label for="photo">Foto</label>
                                <input type="file" class="form-control" v-on:change="AddPhoto">
                                <p class="mt-2" v-if="model.photo_display">
                                    <img :src="model.photo_display" class="responsive"/>
                                </p>
                            </b-form-group>
                            </b-col>
                        </b-row>
                        <div slot="footer">
                            <b-button type="submit" variant="primary" class="px-4">
                                <i class="fa fa-save"></i> Simpan
                            </b-button>
                            <button v-on:click="params.page = 'list'" class="btn btn-primary">
                                <i class="fa fa-close"></i> Batal
                            </button>
                        </div>
                    </b-card>
                </form>
            </b-col>
        </b-row>
    </div>
</template>
<script>
import table from '@/shared/table.js'
export default {
	props     : {
    	parent: {
			type   : Number,
			default : 0,
		},
	},
	data() {
		return {
			config : {
				uri : this.$route.meta.permission.link,
				api : "muzakki",
				datas: [],
	            total: 0,
	            limit: 20,
	            offset: 0,
	            orderby: "id",
	            sort: "desc",
	            page: 1,
	            search : {
                    key : "",
                    status : 1,
                    parent : this.parent,
	            },
	            header: [{
	                title: "Keluarga Muzakki",
	                value: false,
	                width: "5%",
	            },{
	                title: "",
	                value: false,
	                width: "80%",
	            },{
	                title: "",
	                value: false,
	                width: "15%",
	            }],
			},
			response : {
				variant : this.$route.params.variant,
				message : this.$route.params.message
			},
			model : {
                muzakki_status : "activated",
                relation : "",
                address : "",
                email : "",
                phone : "",
				photo_display : "",
				parent : this.parent
            },
            params : {
                page : "list",
                relations : [],
                FormData : new FormData()
            }
		}
	},
	created(){
		let _ = this
        _.get()
		_.getRelations()
	},
	methods : {
		get(){ 
			table.get(this)
		},
		sort(orderby) {
			table.sort(this,orderby)
        },
        gotoPage(page) {
        	table.gotoPage(this,page)
        },
        select(data) {
            this.$emit('setMuzakkiFamily', data)
        },
        getRelations(){
			let _ = this
			_.$axios.get('muzakki/relation').then(res => {
	        	_.params.relations = res.data.data
			})
        },
        AddPhoto (event) {
			this.model.photo 			= event.target.files[0]
            this.model.photo_display  	= URL.createObjectURL(this.model.photo)
        },
        save(){
            let _ = this
            _.params.FormData = new FormData()
            for (let i in this.model){
				if(this.model[i]){
					_.params.FormData.append(i, this.model[i])
				}
			}
			_.$validator.validate().then(valid => {
	        	if (valid) {
					_.$axios.post('muzakki/store', _.params.FormData)
		            .then(res => {
                        _.params.page = "list"
                        _.response = {
							variant : (res.data.status) ? 'success':'danger',
							message : res.data.message
                        } 
                        _.get()
		            }).catch(e => {
						_.response = {
							variant : "danger",
							message : e.response.data.message
						}              
		            })
		        }
	        });
		}
	},
	watch: { 
      	parent: function(newVal, oldVal) {
			let _ = this
			_.config.search.parent = newVal
			_.model.parent = newVal
          	_.get()
        }
    }
}
</script>