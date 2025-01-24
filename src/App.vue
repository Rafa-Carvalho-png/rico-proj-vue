<script setup>
import { computed } from 'vue';
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
import Sidebar from './components/Sidebar.vue';


const isAuthenticated = computed(() => {
    return !!localStorage.getItem("auth_token");
});
</script>
<template>
    <Sidebar v-if="isAuthenticated" />
    <Navbar />
    <router-view />
    <Footer />
</template>
<style scoped>
</style>