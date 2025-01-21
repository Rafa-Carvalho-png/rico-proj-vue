<template>
  <div class="reset-password-container">
    <h2 class="text-2xl font-bold mb-4">Reset Password</h2>
    <form @submit.prevent="resetPassword" class="space-y-4">
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">New Password:</label>
        <input type="password" v-model="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
      </div>
      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password:</label>
        <input type="password" v-model="password_confirmation" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
      </div>
      <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Reset Password</button>
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
      password: "",
      password_confirmation: "",
      message: null,
      error: null,
    };
  },
  methods: {
    async resetPassword() {
      try {
        const token = this.$route.query.token;
        const response = await post("/auth/reset-password", {
          token: token,
          password: this.password,
          password_confirmation: this.password_confirmation,
        });
        this.message = "Password reset successfully.";
        this.error = null;

        setTimeout(() => {
          window.location.href = "/login";
        }, 2000);
      } catch (err) {
        this.error = err.response?.data?.message || "Failed to reset password. Please try again.";
        this.message = null;
      }
    },
  },
};
</script>

<style scoped>
.reset-password-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #f9f9f9;
}
</style>