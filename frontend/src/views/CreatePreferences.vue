<script>
import {onMounted, ref} from "vue";
import axios from "axios";
import Navbar from "@/components/Navbar.vue";
import {useRouter} from "vue-router";

export default {
  name: "CreatePreferences",
  components: {Navbar},
  setup(){
    const diet = ref(['Gluten Free', 'Ketogenic', 'Vegetarian', 'Lacto-Vegetarian', 'Ovo-Vegetarian', 'Vegan', 'Pescetarian', 'Paleo', 'Primal', 'Low FODMAP', 'Whole30'])
    const intolerances = ref(['Dairy', 'Egg', 'Gluten', 'Grain', 'Peanut', 'Seafood', 'Sesame', 'Shellfish', 'Soy', 'Sulfite', 'Tree Nut', 'Wheat'])
    const allergies = ref('')
    const data = ref([])
    let token = localStorage.getItem('token')
    const router = useRouter()

    //console.log(diet.value,intolerances.value,allergies.value)
    const onSubmit = async ()=>{
      const r = await axios.post('http://localhost:8000/api/setPreferences',{
        diet:diet.value,
        intolerances:intolerances.value,
        allergies:allergies.value

      },{
        headers: {
          'Content-Type':'application/json',
          'Authorization':`Bearer ${token}`
        }
      })
       data.value = await r.data
      //console.log(data.value)
      if (data.value)
      {
        await router.push('/recipes')
      }
    }
    return{
      diet,
      intolerances,
      allergies,
      onSubmit

    }
  }
}
</script>

<template>
  <Navbar />
  <div class="w-50 m-auto mt-5 p-5 border-0.125rem shadow arounded">
    <h3 class="text-center ">Veuillez insérer vos preferences</h3>
    <form @submit.prevent="onSubmit">
      <div class="form-group mb-3">
<!--        <input class="form-control" v-model="diet" name="diet" placeholder="Diet...">-->
        <label>Choisir Votre Diet: </label>
        <select class="form-select" name="diet" v-model="diet" id="diet" >
          <option v-for="die in diet" :key="die.id" :value="die">{{die}}</option>
        </select>
      </div>
      <div class="form-group mb-3">
<!--        <input class="form-control" v-model="intolerances" name="intolerances" placeholder="Intolerances...">-->
        <label>Choisir votre intolérance: </label>
        <select class="form-select" name="intolerances" v-model="intolerances" id="intolerances" >
          <option v-for="intolerance in intolerances" :key="intolerance.id" :value="intolerance">{{intolerance}}</option>
        </select>
      </div>
      <div class="form-group mb-3">
        <input class="form-control" v-model="allergies" name="allergies" placeholder="Allergies...">

      </div>
      <div class="form-group mb-3">
        <input class="btn btn-primary" type="submit" value="Save">
      </div>
    </form>
  </div>
</template>

<style scoped>

</style>