<template>
    <nav class="bg-white border-b border-gray-200">
        <div class="container mx-auto px-4 py-2 flex justify-between items-center">
            <a href="#" class="text-xl font-bold">
                <span>Ricochet360</span>
            </a>
            <button class="block lg:hidden text-gray-500 focus:outline-none" @click="toggleMenu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div :class="{'block': isMenuOpen, 'hidden': !isMenuOpen}" class="w-full lg:flex lg:items-center lg:w-auto">
                <ul class="lg:flex lg:space-x-4 ml-auto">
                    <li v-if="!isAuthenticated">
                        <a href="/login" class="block lg:inline-block text-gray-700 hover:text-gray-900 border border-gray-300 rounded px-4 py-2">Login</a>
                    </li>
                    <li v-if="!isAuthenticated">
                        <a href="/register" class="block lg:inline-block text-white bg-blue-500 hover:bg-blue-600 rounded px-4 py-2">Register</a>
                    </li>
                    <li v-if="isAuthenticated">
                        <a href="#" class="block lg:inline-block text-gray-700 hover:text-gray-900 border border-gray-300 rounded px-4 py-2" @click="logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
import { get } from "../http/auth-api";

export default {
    data() {
        return {
            isMenuOpen: false
        };
    },
    computed: {
        isAuthenticated() {
            return !!localStorage.getItem("auth_token");
        }
    },
    methods: {
        toggleMenu() {
            this.isMenuOpen = !this.isMenuOpen;
        },
        async logout() {
            await get('/auth/logout');
            localStorage.removeItem("auth_token");
            window.location.href = "/login";
        }
    }
}
</script>

<style scoped>
</style>