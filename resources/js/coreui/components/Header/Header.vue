<template>
  <header class="app-header navbar">
    <button
      class="navbar-toggler mobile-sidebar-toggler d-lg-none"
      type="button"
      @click="mobileSidebarToggle"
    >
      <span class="navbar-toggler-icon" />
    </button>
    <b-link
      class="navbar-brand"
      to="#"
    />
    <button
      class="navbar-toggler sidebar-toggler d-md-down-none"
      type="button"
      @click="sidebarToggle"
    >
      <span class="navbar-toggler-icon" />
    </button>
    <b-navbar-nav class="ml-auto">
      <header-dropdown />
    </b-navbar-nav>
  </header>
</template>
<script>
import HeaderDropdown from './HeaderDropdown.vue'

export default {
  name      : 'CHeader',
  components: { HeaderDropdown },
  props     : {
    fixed: {
      type   : Boolean,
      default: true,
    },
  },
  mounted () {
    if (this.fixed) $('body').addClass('header-fixed')
  },
  beforeDestroy () {
    $('body').removeClass('header-fixed')
  },
  methods: {
    sidebarToggle (event) {
      event.preventDefault()
      document.body.classList.toggle('sidebar-hidden')
    },
    sidebarMinimize (event) {
      event.preventDefault()
      document.body.classList.toggle('sidebar-minimized')
    },
    mobileSidebarToggle (event) {
      event.preventDefault()
      document.body.classList.toggle('sidebar-mobile-show')
    },
  },
}

</script>
