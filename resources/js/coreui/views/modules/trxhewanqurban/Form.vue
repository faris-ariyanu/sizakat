<template>
  <form
    class="form-horizontal"
    @submit.prevent="save">
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
            <b-form-group v-bind:class="{ 'is-invalid': errors.has('Jenis Hewan')}">
              <label for="description">* Jenis Hewan</label>
              <select class="form-control" v-validate="'required'" name="jenis_hewan" v-model="model.hewan_qurban_id">
								<option value="">Pilih Jenis Hewan</option>
								<option v-for="hewan in params.hewanqurban" v-bind:value="hewan.id">{{ hewan.name }}</option>
    	        </select>
              <div v-if="errors.has('Jenis Hewan')">
                <p class="text-danger">{{ errors.first('Jenis Hewan') }}</p>
              </div>
            </b-form-group>
            <b-form-group v-bind:class="{ 'is-invalid': errors.has('periode')}">
              <label for="value">* Periode</label>
              <b-form-input type="number" v-model="model.periode" v-validate="'required'" name="periode" aria-disabled=""></b-form-input>
              <div v-if="errors.has('periode')">
                <p class="text-danger">{{ errors.first('periode') }}</p>
              </div>
            </b-form-group>
            <b-form-group v-bind:class="{ 'is-invalid': errors.has('No Urut')}">
              <label for="value">* No Urut</label>
              <b-form-input type="number" v-model="model.no_urut" v-validate="'required'" name="No Urut"></b-form-input>
              <div v-if="errors.has('No Urut')">
                <p class="text-danger">{{ errors.first('No Urut') }}</p>
              </div>
            </b-form-group>
            <b-form-group v-bind:class="{ 'is-invalid': errors.has('picture')}">
              <label for="value">* Gambar</label>
              <input type="file" class="form-control" v-on:change="addLampiran">
              <div v-if="errors.has('picture')">
                <p class="text-danger">{{ errors.first('picture') }}</p>
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
  data () {
    return {
      config: {
        uri: this.$route.meta.permission.link,
        api: 'trxhewanqurban',
      },
      action  : this.$route.meta.permission.action,
      response: {
        variant: '',
        message: '',
      },
      model: {
        hewan_qurban_id: 0,
        periode        : '',
        no_urut        : 0,
        picture        : '',
        status         : 0,
      },
      params: {
        hewanqurban: [],
      },
    }
  },
  created () {
    let _ = this
		_.getById()
		_.getHewanQurban()
		_.getComponent()
  },
  methods: {
    getById () {
      let _ = this
      if (_.$route.params.id) {
        _.$axios.get(_.config.api, {
		            params:{ 
		               	'id': _.$route.params.id,
		            }
		        }).then(res => {
		        	if (res.data.data.total){
				    	_.model 		= res.data.data.rows
          }else {
            _.$router.push({name  : _.config.uri, params: {
			              	'variant': 'danger',
			               	'message': 'Data cannot be found!'
			            }})
          }
        })
	    	}
		},
		addLampiran (event) {
            this.model.picture = event.target.files[0]
		},
    save (){
      let _ = this
      if (_.action == 'update'){
        _.model.id = _.$route.params.id
			}

			_.params.FormData = new FormData()
			for (let i in _.model){
				_.params.FormData.append(i, _.model[i] ? _.model[i] : "")
			}
			
      _.$validator.validate().then(valid => {
	        	if (valid) {
          		_.$axios.post(_.config.api+'/'+_.action, _.params.FormData)
		            .then(res => {
		            	_.$router.push({name  : _.config.uri, params: {
			              	'variant': (res.data.status) ? 'success':'danger',
			               	'message': res.data.message
			            }})
		            }).catch(e => {
              _.response = {
                variant: 'danger',
                message: err.response.data.message
              }
		            })
		        }
	        });
		},
		getHewanQurban () {
			let _ = this
			_.$axios.get('hewanqurban').then(res => {
	        	_.params.hewanqurban = res.data.data.rows
			})
		},
		getComponent(){
			let _ = this
			_.$axios.get(_.config.api + '/component').then(res => {
				let data 		= res.data.data
				_.model.periode = data.periode ? data.periode.year : ""
			})
		},
  }
}
</script>