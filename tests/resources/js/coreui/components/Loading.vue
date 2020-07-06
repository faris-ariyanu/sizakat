<template>
  <div v-show="$store.state.spinner" class="spinner-container full-width full-height">
    <component
      :is="cVariant"
      :size="cSize"
    />
    <div class="spinner-body">
      <slot />
    </div>
  </div>
</template>

<style lang="scss" scoped>
  .spinner-container {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, .5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 99999;
  }
</style>
<script>
import * as Spinner from 'vue-loading-spinner'

export const COMPONENTS = _.mapKeys(Spinner, (value, key) => `Spinner${key}`)

export default {
  name      : 'LoadingSpinner',
  components: { ...COMPONENTS },
  props     : {
    variant: {
      type     : String,
      default  : 'rotate-square',
      validator: function (value) {
        return _.keys(Spinner).includes(_.pascalCase(value))
      },
    },
    size: {
      type   : Number,
      default: 40,
    },
    fullWidth : Boolean,
    fullHeight: Boolean,
    cover     : Boolean,
  },
  computed: {
    cVariant () {
      return `Spinner${_.pascalCase(this.variant)}`
    },
    cSize () {
      return `${this.size}px`
    },
  },
}
</script>
