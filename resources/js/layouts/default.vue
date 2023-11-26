<template>
  <div class="main-layout grid gap-0">
    <div class="col-12 header-container">

    </div>
    <div class="col-2 d-none d-lg-block sidebar-container">

    </div>
    <div class="col-12 col-lg-10 container content-container">
      <Child />
    </div>
  </div>
</template>

<script>
import Child from '../components/Child'
// import Navbar from '~/components/Navbar'

export default {
  name: 'MainLayout',

  components: {
    Child,
    // Navbar
  }
}
</script>
