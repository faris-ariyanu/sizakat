<template>
  <div class="app flex-row align-items-center">
    <div class="container">
      <b-row class="justify-content-center">
        <b-col md="5">
          <b-card-group>
            <b-card no-body class="p-5">
              <b-card-body>
                <form @submit.prevent="login" class="needs-validation" novalidate>
                  <h1>Login</h1>
                  <p class="text-muted">Masuk ke aplikasi</p>
                  <b-alert show v-if="response.message" variant="danger">
                    {{response.message}}
                  </b-alert>
                  <div class="form-group" :class="{'has-error': errors.has('username') }">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="icon-user"></i></div>
                      </div>
                      <input v-model="model.username" v-validate="'required'" name="username" class="form-control"
                        type="text" placeholder="Username">
                    </div>
                    <div class="help-block" v-if="errors.has('username')">
                      <p class="text-danger">{{ errors.first('username') }}</p>
                    </div>
                  </div>
                  <div class="form-group" :class="{'has-error': errors.has('password') }">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="icon-lock"></i></div>
                      </div>
                      <input v-model="model.password" v-validate="'required'" name="password" class="form-control"
                        type="password" placeholder="Password">
                    </div>
                    <div class="help-block" v-if="errors.has('password')">
                      <p class="text-danger">{{ errors.first('password') }}</p>
                    </div>
                  </div>
                  <b-row>
                    <b-col cols="6">
                      <b-button type="submit" variant="primary" class="px-4">Login</b-button>
                    </b-col>
                  </b-row>
                  <hr>
                  <p class="text-muted">
                    * Untuk mendapatkan username & password dapat menghubungi via Whatsapp Sdr. Reza (08118114424) atau Sdr. Burhanudin (087784291533)
                  </p>
                </form>
              </b-card-body>
            </b-card>
          </b-card-group>
        </b-col>
      </b-row>
    </div>
    <loading/>
  </div>
</template>

<script>
import Loading from "@/components/Loading"
export default {
  name: 'Login',
  components: {
    Loading
  },
  data() {
    return {
      model : {},
      response : {
        message : ""
      }
    }
  },
  methods : {
    login(){
      let _ = this
      _.$validator.validate().then(valid => {
        if (valid) {
          _.response.loading = true
          _.$axios.post('/login',_.model).then(resp => {
              _.$store.dispatch('login',resp.data.data)
          }).catch(err => {
            _.response = {
              message : err.response.data.message
            }
          })
        }
      });
    }
  }
}
</script>