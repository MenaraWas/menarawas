<template>
    <div class="min-h-screen flex bg-gray-900 text-gray-200 font-sans">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-950 border-r border-gray-800 flex flex-col">
            <div class="p-6 border-b border-gray-800">
                <h1 class="text-xl font-bold text-white tracking-tight">Admin<span class="text-indigo-500">Panel</span></h1>
            </div>
            <nav class="flex-grow p-4 space-y-1">
                <router-link to="/admin" class="block px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors" active-class="bg-indigo-600/10 text-indigo-400">Dashboard</router-link>
                <router-link to="/admin/posts" class="block px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors" active-class="bg-indigo-600/10 text-indigo-400">Posts</router-link>
                <router-link to="/admin/projects" class="block px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors" active-class="bg-indigo-600/10 text-indigo-400">Projects</router-link>
                <router-link to="/admin/categories" class="block px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors" active-class="bg-indigo-600/10 text-indigo-400">Categories</router-link>
                <router-link to="/admin/tags" class="block px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors" active-class="bg-indigo-600/10 text-indigo-400">Tags</router-link>
            </nav>
            <div class="p-4 border-t border-gray-800">
                <button @click="handleLogout" class="w-full text-left px-4 py-2 rounded-lg hover:bg-red-500/10 hover:text-red-400 transition-colors">
                    Logout
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-grow flex flex-col min-w-0">
            <header class="bg-gray-950/50 backdrop-blur border-b border-gray-800 px-8 py-4 flex justify-between items-center sticky top-0 z-10">
                <h2 class="text-lg font-semibold text-white capitalize">{{ currentRouteName }}</h2>
                <div class="text-sm text-gray-400">
                    Logged in as <span class="text-white font-medium">{{ authStore.user?.name }}</span>
                </div>
            </header>
            <div class="p-8 flex-grow overflow-y-auto">
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

const currentRouteName = computed(() => {
    return route.name ? String(route.name).replace('admin.', '') : 'Admin';
});

const handleLogout = async () => {
    await authStore.logout();
    router.push('/admin/login');
};
</script>
