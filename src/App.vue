<script setup>
import { computed } from 'vue';
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
import Sidebar from './components/Sidebar.vue';
import { isAuthenticated } from './utils/auth';

const isAuthenticatedApp = computed(() => {
    return isAuthenticated();
});
</script>
<template>
    <Sidebar v-if="isAuthenticatedApp" />
    <Navbar />
    <router-view />
    <Footer />
</template>
<style scoped>
</style>