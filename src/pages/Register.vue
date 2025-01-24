<template>
  <div class="register-container">
    <h2 class="text-2xl font-bold mb-4">Register</h2>
    <form @submit.prevent="register" class="space-y-4">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
        <input type="text" v-model="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
      </div>
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
        <input type="email" v-model="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
      </div>
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
        <input type="password" v-model="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
      </div>
      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password:</label>
        <input type="password" v-model="password_confirmation" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
      </div>
      <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Register</button>
    </form>
    <p v-if="error" class="mt-4 text-red-600">{{ error }}</p>
  </div>
</template>

<script>
import { post } from "../http/auth-api";

export default {
  data() {
    return {
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      error: null,
    };
  },
  methods: {
    async register() {
      try {
        const response = await post('/auth/register',{
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
        });

        const expiresIn = response.data.token_expires;
        const expirationTime = new Date().getTime() + expiresIn * 1000;
        const token = response.data.token;
        const myId = response.data.id
        localStorage.setItem("token_expires_at", expirationTime);
        localStorage.setItem("auth_token", token);
        localStorage.setItem("my_id", myId);
        window.location.href = "/";
      } catch (err) {
        this.error = err.response?.data?.errors?.message || "Registration failed. Please try again.";
      }
    },
  },
};
</script>

<style scoped>
.register-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #f9f9f9;
}
</style>