import { createRouter, createWebHistory } from 'vue-router'
import Recipes from "@/views/Recipes.vue";
import Login from "@/views/Login.vue";
import Register from "@/views/Register.vue";
import CreatePreferences from "@/views/CreatePreferences.vue";
import Calendar from "@/views/Calendar.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: Login
    },
    {
      path: '/register',
      name: 'register',
      component: Register
    },
    {
      path: '/recipes',
      name: 'recipes',
      component: Recipes,
      meta:{requiresAuth:true}
    },
    {
      path: '/preferences',
      name:'create.preferences',
      component: CreatePreferences,
      meta: {requiresAuth: true}
    },
    {
      path: '/calendar',
      name:'full.calendar',
      component: Calendar,
      meta: {requiresAuth: true}
    }
  ]
})
router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const token = localStorage.getItem('token');

  if (requiresAuth && !token) {
    next('/');
  } else {
    next();
  }
});
export default router
