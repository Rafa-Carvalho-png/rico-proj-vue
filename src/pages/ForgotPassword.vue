<template>
  <div class="forgot-password-container">
    <h2 class="text-2xl font-bold mb-4">Forgot Password</h2>
    <form @submit.prevent="sendResetEmail" class="space-y-4">
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
        <input type="email" v-model="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
      </div>
      <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Send Reset Email</button>
    </form>
    <p v-if="message" class="mt-4 text-green-600">{{ message }}</p>
    <p v-if="error" class="mt-4 text-red-600">{{ error }}</p>
  </div>
</template>

<script>
import { post } from "../http/auth-api";

export default {
  data() {
    return {
      email: "",
      message: null,
      error: null,
    };
  },
  methods: {
    async sendResetEmail() {
      try {
        await post('/auth/forgot-password', { email: this.email });
        this.message = "Reset email sent successfully.";
        this.error = null;
      } catch (err) {
        this.error = err.response?.data?.errors?.message || "Failed to send reset email. Please try again.";
        this.message = null;
      }
    },
  },
};
</script>

<style scoped>
.forgot-password-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #f9f9f9;
}
</style>