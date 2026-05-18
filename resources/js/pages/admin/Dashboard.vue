<template>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Stat Cards -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <div class="text-gray-400 text-sm font-medium mb-1">Total Posts</div>
                <div class="text-3xl font-bold text-white">{{ stats.posts }}</div>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <div class="text-gray-400 text-sm font-medium mb-1">Total Projects</div>
                <div class="text-3xl font-bold text-white">{{ stats.projects }}</div>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <div class="text-gray-400 text-sm font-medium mb-1">Categories</div>
                <div class="text-3xl font-bold text-white">{{ stats.categories }}</div>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <div class="text-gray-400 text-sm font-medium mb-1">Tags</div>
                <div class="text-3xl font-bold text-white">{{ stats.tags }}</div>
            </div>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Welcome back, {{ authStore.user?.name }}!</h3>
            <p class="text-gray-400">Manage your content using the sidebar menu.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../../stores/auth';
import api from '../../services/api';

const authStore = useAuthStore();
const stats = ref({
    posts: 0,
    projects: 0,
    categories: 0,
    tags: 0
});

// Since we don't have a dedicated stats endpoint yet, we'll fetch list sizes or just mock for now
onMounted(async () => {
    try {
        const [posts, projects, categories, tags] = await Promise.all([
            api.get('/posts'), // Note: this might only return published by default depending on controller
            api.get('/projects'),
            api.get('/categories'),
            api.get('/tags')
        ]);
        stats.value.posts = posts.data.total || posts.data.length || 0;
        stats.value.projects = projects.data.length || 0;
        stats.value.categories = categories.data.length || 0;
        stats.value.tags = tags.data.length || 0;
    } catch (e) {
        console.error('Failed to fetch stats', e);
    }
});
</script>
