<template>
  <div class="app">
    <app-header :fixed="fixedHeader || fixedSidebar" />
    <div class="app-body">
      <sidebar
        :nav-items="nav"
        :fixed="fixedSidebar"
      />
      <notifications
        :style="{ 'margin-top': offset }"
        class="custom-notifications"
      />
      <main class="main">
        <breadcrumb :list="list" />
        <div class="container-fluid">
          <router-view />
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import { Header as AppHeader, Sidebar, Breadcrumb } from '../components'

export default {
  name      : 'Full',
  components: {
    AppHeader,
    Sidebar, 
    Breadcrumb,
  },
  data () {
    return {
      nav         : [],
      offset      : true,
      fixedHeader : true,
      fixedSidebar: true,
    }
  },
  computed: {
    name () {
      return this.$route.name
    },
    list () {
      return this.$route.matched
    },
  },
  mounted () {
    $('body').addClass('app sidebar-lg-show pace-done')
    $(window).on('scroll', this.setPosNotify)
    this.getMenu()
  },
  beforeDestroy () {
    $('body').removeClass('app sidebar-lg-show pace-done')
    $(window).off('scroll', this.setPosNotify)
  },
  methods: {
    setPosNotify () {
      const top    = $(document).scrollTop()
      const height = $('.app-header').height()
      const offset = top < height ? height - top : 0
      this.offset = `${offset}px`
    },
    getMenu(){
      let _ = this
      _.$axios.get('me/menu')
      .then(resp => {
        _.nav = resp.data.data
      }).catch(e=>{
        console.log(e)
      })
    },
  },
}
</script>
