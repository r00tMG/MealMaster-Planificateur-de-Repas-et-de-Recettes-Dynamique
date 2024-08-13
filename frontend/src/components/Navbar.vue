<script>
import Dropdown from '@/components/Dropdown.vue'

export default {
  name: 'Navbar',
  components: { Dropdown }
}
</script>

<template>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <router-link to="/recipes" class="navbar-brand" >MealMaster</router-link>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        </ul>
        <div class="d-flex" role="search">
          <div class="  me-auto" type="search" aria-label="Search"></div>
          <Dropdown />
        </div>
      </div>
    </div>
  </nav>
</template>

<style scoped>

</style>