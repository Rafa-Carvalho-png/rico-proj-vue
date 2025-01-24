<template>
  <div class="login-container">
    <h2 class="text-2xl font-bold mb-4">Login</h2>
    <form @submit.prevent="login" class="space-y-8">
      <div class="flex flex-col">
        <div class="flex-1">
          <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
          <input type="email" v-model="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm" />
        </div>
        <div class="flex-1 mt-2">
          <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
          <input type="password" v-model="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm" />
        </div>
      </div>
      <button type="submit" class="w-full my-2 flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium bg-black hover:bg-gray-500 text-white">Login</button>
    </form>
    <div class="mt-4 text-right">
      <a href="/forgot-password" class="text-sm text-gray-600 hover:text-gray-900">Forgot Password?</a>
    </div>
    <p v-if="error" class="mt-4 text-red-600">{{ error }}</p>
  </div>
</template>

<script>
import { post } from "../http/auth-api";

export default {
  data() {
    return {
      email: "",
      password: "",
      error: null,
    };
  },
  methods: {
    async login() {
      try {
        const response = await post('/auth/login', {
          email: this.email,
          password: this.password,
        });
        const token = response.data.token;
        const myId = response.data.id
        localStorage.setItem("auth_token", token);
        localStorage.setItem("my_id", myId);
        window.location.href = "/";
      } catch (err) {
        this.error = err.response?.data?.errors?.message || "Invalid credentials. Try again later.";
      }
    },
  },
};
</script>

<style scoped>
.login-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #f9f9f9;
}
</style>