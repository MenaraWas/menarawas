<template>
    <div class="space-y-24">
        <!-- Hero Section -->
        <section class="py-20 text-center max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tight mb-6">
                Welcome to <span class="text-indigo-500">MenaraWas</span>
            </h1>
            <p class="text-xl text-gray-400 mb-10 leading-relaxed">
                Exploring the intersection of technology, design, and code. Check out my latest projects and read my thoughts on web development.
            </p>
            <div class="flex gap-4 justify-center">
                <router-link to="/portfolio" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-full transition-colors">
                    View Portfolio
                </router-link>
                <router-link to="/news" class="px-8 py-4 bg-gray-900 border border-gray-700 hover:border-gray-500 text-white font-semibold rounded-full transition-colors">
                    Read News
                </router-link>
            </div>
        </section>

        <!-- Latest News -->
        <section v-if="!isLoading && latestNews.length > 0">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Latest News</h2>
                    <p class="text-gray-400">Recent articles and updates</p>
                </div>
                <router-link to="/news" class="text-indigo-400 hover:text-indigo-300 font-medium flex items-center gap-1">
                    View all
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </router-link>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <NewsCard v-for="post in latestNews" :key="post.id" :post="post" />
            </div>
        </section>

        <!-- Latest Projects -->
        <section v-if="!isLoading && latestProjects.length > 0">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Featured Projects</h2>
                    <p class="text-gray-400">Some of my recent work</p>
                </div>
                <router-link to="/portfolio" class="text-indigo-400 hover:text-indigo-300 font-medium flex items-center gap-1">
                    View all
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </router-link>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <ProjectCard v-for="project in latestProjects" :key="project.id" :project="project" />
            </div>
        </section>

        <LoadingSpinner v-if="isLoading" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';
import NewsCard from '../components/NewsCard.vue';
import ProjectCard from '../components/ProjectCard.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';

const latestNews = ref([]);
const latestProjects = ref([]);
const isLoading = ref(true);

onMounted(async () => {
    try {
        const [newsRes, projectsRes] = await Promise.all([
            api.get('/posts', { params: { type: 'news' } }),
            api.get('/projects')
        ]);
        
        // Assuming pagination for posts, so taking from data.data
        latestNews.value = (newsRes.data.data || newsRes.data).slice(0, 3);
        
        // Assuming projects might not be paginated initially in controller
        latestProjects.value = (projectsRes.data.data || projectsRes.data).slice(0, 3);
    } catch (e) {
        console.error('Error fetching home data', e);
    } finally {
        isLoading.value = false;
    }
});
</script>
