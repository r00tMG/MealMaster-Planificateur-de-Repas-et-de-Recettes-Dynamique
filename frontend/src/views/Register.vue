<script>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

export default {
  name: 'Register',
  setup(){
    const name = ref('')
    const email = ref('')
    const password = ref('')
    const password_confirmation = ref('')
    const errors = ref({})

    const router = useRouter()

    const onRegister = async () => {
      try{
        const response = await axios.post('http://localhost:8000/api/users', {
          name:name.value,
          email: email.value,
          password: password.value,
          password_confirmation:password_confirmation.value,

        },{
          headers: { 'Content-Type':'application/json' },

        })

        const data = response.data
        console.log(data)
        if (data)
        {
          alert(data.message)
          await router.push('/')
        }else{
          alert('L\'enregistrement a échoué, veuillez rééssayer')
        }

      }catch (error) {
        if (error.response && error.response.status === 400) {
          errors.value = error.response.data.errors;
          console.error("Validation errors:", errors.value);
        } else {
          console.error("Error: La requête a échoué", error);
        }
      }

    }
    return{
      name,
      email,
      password,
      password_confirmation,
      onRegister,
      errors
    }
  }
}
</script>

<template>

  <div class="kotak_login">
    <p class="tulisan_login">Register</p>

    <img src="https://freedesignfile.com/upload/2017/07/Hand-drawn-coffee-logos-design-vector-set-07.jpg" alt="coffee">

    <form @submit.prevent="onRegister">
      <div class="form-group mb-3">
        <label>name</label>
        <input type="text" v-model="name" name="name" class="form_login" placeholder="Name..">
        <p v-if="errors.name" class="text-danger">{{ errors.name[0] }}</p>
      </div>

      <div class="form-group mb-3">
        <label>Email</label>
        <input type="text" v-model="email" name="email" class="form_login" placeholder="Email..">
        <p v-if="errors.email" class="text-danger">{{ errors.email[0] }}</p>

      </div>

      <div class="form-group mb-3">
      <label>Password</label>
      <input type="password" v-model="password"	name="password" class="form_login" placeholder="Password ..">
        <p v-if="errors.password" class="text-danger">{{ errors.password[0] }}</p>
      </div>
      <div class="form-group mb-3">

      <label>Password Confirmation</label>
      <input type="password" v-model="password_confirmation"	name="password_confirmation" class="form_login" placeholder="Password ..">
      </div>

      <input type="submit" class="tombol_login" value="Register">
      <p>Si vous êtes inscrite <router-link to="/" > Cliquez Ici</router-link></p>
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
select {
  -webkit-appearance:none;
  -moz-appearance:none;
  -ms-appearance:none;
  appearance:none;
  outline:0;
  box-shadow:none;
  border:0!important;
  background: #5c6664;
  background-image: none;
  flex: 1;
  padding: 0 .5em;
  color:#fff;
  cursor:pointer;
  font-size: 1em;
  font-family: 'Open Sans', sans-serif;
}
select::-ms-expand {
  display: none;
}
.select {
  position: relative;
  display: flex;
  width: 20em;
  height: 3em;
  line-height: 3;
  background: #5c6664;
  overflow: hidden;
  border-radius: .25em;
}
.select::after {
  content: '\25BC';
  position: absolute;
  top: 0;
  right: 0;
  padding: 0 1em;
  background: #2b2e2e;
  cursor:pointer;
  pointer-events:none;
  transition:.25s all ease;
}
.select:hover::after {
  color: #23b499;
}
</style>