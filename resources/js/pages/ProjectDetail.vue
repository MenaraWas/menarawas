<template>
    <div v-if="project" class="max-w-5xl mx-auto py-8">
        <router-link to="/portfolio" class="text-indigo-400 hover:text-indigo-300 font-medium inline-flex items-center gap-1 mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Portfolio
        </router-link>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2">
                <div class="aspect-video bg-gray-900 rounded-2xl overflow-hidden border border-gray-800 mb-8 relative">
                    <img v-if="project.image_url" :src="project.image_url" :alt="project.title" class="w-full h-full object-cover">
                    <div v-else class="w-full h-full flex items-center justify-center text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <div class="prose prose-invert prose-lg max-w-none">
                    <h2 class="text-2xl font-bold text-white mb-4">About this project</h2>
                    <div class="text-gray-300 whitespace-pre-wrap leading-relaxed">{{ project.description }}</div>
                </div>
            </div>

            <div class="space-y-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-white mb-4">{{ project.title }}</h1>
                    
                    <a v-if="project.link" :href="project.link" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors w-full justify-center">
                        Visit Live Project
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Technologies Used</h3>
                    <div class="flex flex-wrap gap-2">
                        <span v-for="(tech, idx) in techStackList" :key="idx" class="px-3 py-1.5 bg-gray-950 border border-gray-800 text-gray-300 text-sm rounded-lg font-medium">
                            {{ tech }}
                        </span>
                    </div>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Project Info</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="text-white capitalize">{{ project.status }}</span>
                        </li>
                        <li class="flex justify-between" v-if="project.created_at">
                            <span class="text-gray-500">Added on</span>
                            <span class="text-white">{{ new Date(project.created_at).toLocaleDateString() }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div v-else-if="isLoading" class="py-20">
        <LoadingSpinner />
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';
import api from '../services/api';
import LoadingSpinner from '../components/LoadingSpinner.vue';

const route = useRoute();
const project = ref(null);
const isLoading = ref(true);

const techStackList = computed(() => {
    if (!project.value || !project.value.tech_stack) return [];
    return project.value.tech_stack.split(',').map(t => t.trim());
});

onMounted(async () => {
    try {
        const response = await api.get(`/projects/${route.params.slug}`);
        project.value = response.data;
    } catch (e) {
        console.error('Failed to load project', e);
    } finally {
        isLoading.value = false;
    }
});
</script>
