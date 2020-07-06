<template>
  <b-nav-item-dropdown
    right
    no-caret
  >
    <template slot="button-content" v-if="user">
      <strong>Hallo {{user.name}}</strong> <img :src="user.avatar" class="img-avatar" :alt="user.email"/>
    </template>
    <b-dropdown-header
      tag="div"
      class="text-center"
    >
      <strong>Settings</strong>
    </b-dropdown-header>
    <b-dropdown-item>
      <i class="fa fa-user" /> Edit Profile
    </b-dropdown-item>
    <b-dropdown-item>
      <i class="fa fa-key" /> Change Password
    </b-dropdown-item>
    <b-dropdown-item @click="logout"> 
      <i class="fa fa-lock" /> Logout
    </b-dropdown-item>
  </b-nav-item-dropdown>
</template>
<script>
export default {
  name: 'HeaderDropdown',
  data () {
    return { 
      user : this.$store.state.user
    }
  },
  methods : {
    logout(){
      let _ = this
      _.$store.dispatch('logout')
      _.$router.push('/login')
    },
    getMe(){
      let _ = this
      _.$axios.get('me')
      .then(resp => {
        _.user = resp.data.data
      }).catch(e=>{
        console.log(e)
      })
    },
  },
  mounted(){
    let _ = this
    if(!_.user){
      _.getMe()
    }
  },
}

</script>
