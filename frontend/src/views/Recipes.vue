<template>
<Navbar />
  <div class="jumbotron text-center">
    <h1>Restaurant MealMaster</h1>
    <p>Best Food Best offers Just Scroll down...!!!</p>
  </div>
<!--  <div class="w-25 m-auto">
    <h3>Generate Recipes</h3>
    <form class="form-group" @submit.prevent="generateRecipes">
      <input class="form-control" v-model="query" placeholder="Veuillez saisir votre préférence">
      <button type="submit" class="btn btn-sm btn-outline-primary">Générer votre Recette</button>
    </form>
  </div>-->

  <div class="container" >
    <h4>Your favorite recipes
      <i class="fa fa-map-marker" aria-hidden="true"></i>
    </h4> <br>
    <div class="row" >
      <div class="col-md-4" v-for="recipe in recipesByPreferences" :key="recipe.id">

        <img :src="recipe.image" class="img-fluid m-2" width="400" :alt="recipe.titre"/>
        <h4>{{recipe.title}}</h4>

        <span class="fa fa-star checked"> </span>
        <span class="fa fa-star"></span>
        <span class="flr">(Delivery Reviews)</span>

        <p>{{recipe.title}}
          <br>
          ₹150 for one 31 min <i class="fa fa-clock-o" aria-hidden="true"></i>
        </p>
      </div>
    </div>
  </div>
<!--  <div class="container w-50 m-auto " v-else>
    <div class="alert alert-danger m-5">
      <p class="text-center p-5">Aucune recette n'a été trouvé</p>
    </div>
  </div>-->
</template>

<script>
import {onMounted, ref} from 'vue';
import axios from 'axios';
import Navbar from "@/components/Navbar.vue";

export default {
  name:"Recipes",
  components: {Navbar},
  setup() {
    const query = ref('');
    const intolerances = ref('')
    const diet = ref('')
    const recipes = ref([]);
    const recipesByPreferences = ref([]);
    //const recipes = ref([]);
    let token = localStorage.getItem('token')
    //console.log(token)
    /*onMounted(async () => {
      try {
        const response = await axios.get('http://localhost:8000/api/recipes', {
          params: { query: query.value },
          headers:{
            'Accept':'application/json',
            'Authorization':`Bearer ${token}`
          }
        });
        recipes.value = await response.data;
        console.log(JSON.parse(recipes.value.recipes[0].ingredients))
      } catch (error) {
        console.error(error);
      }
    })*/
    onMounted(async () => {
      try {
        const response = await axios.get('http://localhost:8000/api/recipesByPreferences', {
          params: {
            diet: diet.value,
            intolerances: intolerances.value
          },
          headers:{
            'Accept':'application/json',
            'Authorization':`Bearer ${token}`
          }
        });
        recipesByPreferences.value = await response.data;
        console.log(recipesByPreferences.value)
      }catch (error) {
        if (
            error.response
            //&& error.response.status === 422
        ) {
          //errors.value = error.response.data.errors;
          console.error("Error:", error.value);
        } else {
          console.error("Error: La requête a échoué", error);
        }
      }
    })
    /*const generateRecipes = async () => {
      try {
        const response = await axios.get('http://localhost:8000/api/generateRecipes', {
          //params: { query: query.value },
          headers:{
            'Accept':'application/json',
            'Authorization':`Bearer ${token}`
          }
        });
        recipes.value = await response.data;
        console.log(recipes.value)
      } catch (error) {
        console.error(error);
      }
    }*/


    return {
      query,
      recipes,
      //generateRecipes,
      recipesByPreferences
    };
  }
};
</script>
<style scoped>
.checked {color: orange;}
h1{color:#fff}
img{border-radius:30px;padding-bottom:10px;}
p{padding-top:0px;font-style:italic;}
.flr{float:right}
.jumbotron {
  padding: 2rem 1rem;
  margin-bottom: 2rem;
  background-image: linear-gradient(to right, rgb(205 231 38 / 12%), #f5b800);
  border-radius: .3rem;

}
</style>
