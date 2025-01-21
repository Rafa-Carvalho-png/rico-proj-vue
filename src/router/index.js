import { createRouter, createWebHistory } from 'vue-router';
import ResetPassword from '../pages/ResetPassword.vue';
import ForgotPassword from '../pages/ForgotPassword.vue';
import Register from '../pages/Register.vue';
import Login from '../pages/Login.vue';

const routes = [
  { path: '/forgot-password', component: ForgotPassword, meta: { title: 'Forgot Password - Ricochet360' } },
  { path: '/reset-password', component: ResetPassword, meta: { title: 'Reset Password - Ricochet360' } },
  { path: '/register', component: Register, meta: { title: 'Register - Ricochet360' } },
  { path: '/login', component: Login, meta: { title: 'Login - Ricochet360' } },
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