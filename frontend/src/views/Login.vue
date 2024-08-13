<script>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

export default {
  name: 'Login',
  setup(){
    const email = ref('')
    const password = ref('')
    const errors = ref({})
    const router = useRouter()
    const onLogin = async () => {
      try{
        const response = await axios.post('http://localhost:8000/api/login', {
            email: email.value,
            password: password.value
        },{
          headers: { 'Content-Type':'application/json' },

        })

        const data = response.data
        console.log(data)

       if(data.token)
        {
          localStorage.setItem('user',JSON.stringify(data.user))
          localStorage.setItem('token',data.token)
          await checkUserPreferences(data.token)
        }else {
          alert('Authentification échouée')
        }
      }catch (error) {
        if (error.response && error.response.status === 422) {
          errors.value = error.response.data.errors;
          console.error("Validation errors:", errors.value);
        } else {
          console.error("Error: La requête a échoué", error);
        }
      }

    }

    //const profile = ref([])
    const checkUserPreferences = async (tok) =>{
      const r = await axios.get('http://localhost:8000/api/getPreferences',{
        headers:{
          'Accept':'application/json',
          'Authorization':`Bearer ${tok}`
        }
      })
      const preferences = await r.data
      console.log(preferences)
      if (preferences.length === 1)
      {
        await router.push('/recipes')
      }else {
        await router.push('/preferences')
      }
    }
    return{
      email,
      password,
      onLogin,
      errors
    }
  }
}
</script>

<template>

    <div class="kotak_login">
      <p class="tulisan_login">Login</p>

      <img src="https://freedesignfile.com/upload/2017/07/Hand-drawn-coffee-logos-design-vector-set-07.jpg" alt="coffee">

        <form @submit.prevent="onLogin">
          <label>Username</label>
          <input type="text" v-model="email" name="email" class="form_login" placeholder="Username..">
          <p v-if="errors.email" class="text-danger" v-text="errors.email[0]"></p>

          <label>Password</label>
          <input type="password" v-model="password"	name="password" class="form_login" placeholder="Password ..">
          <p v-if="errors.password" class="text-danger" v-text="errors.password[0]"></p>

          <input type="submit" class="tombol_login" value="LOGIN">
          <p>Si vous n'êtes pas inscrite<router-link to="/register"> Cliquez Ici</router-link></p>
        </form>

    </div>


</template>

<style scoped>
body {
  font-family: sans-serif;
  background: brown;
}

h1 {
  text-align: center;
  font-weight: 300;
}

.tulisan_login {
  text-align: center;

  text-transform: uppercase;
}

.kotak_login {
  width: 350px;
  background: #f2f2f2;
  /*tengah*/
  margin: 80px auto;
  padding: 30px 20px;
  box-shadow: 0 0 21px;
}

label {
  font-size: 11pt;
}

.form_login {
  box-sizing: border-box;
  width: 100%;
  padding: 10px;
  font-size: 11pt;
  margin-bottom: 20px;
}

.tombol_login {
  background: green;
  color: white;
  font-size: 11pt;
  width: 100%;
  border: none;
  border-radius: 3px;
  padding: 10px 20px;
  cursor:pointer;
}

.link {
  color: #232323;
  text-decoration: none;
  font-size: 10pt;
}

img {

  border-radius: 50%;
  width: 150px;
  margin: 0 auto;
  display: block;
}
input[type="submit"]:hover {
  background-color: #45a049;

}

</style>