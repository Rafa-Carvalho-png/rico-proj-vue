import { createRouter, createWebHistory } from 'vue-router';
import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';

const routes = [
  { path: '/login', component: Login, meta: { title: 'Login - Ricochet360' } },
  { path: '/register', component: Register, meta: { title: 'Register - Ricochet360' } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  document.title = to.meta.title || 'Ricochet360';
  next();
});

export default router;