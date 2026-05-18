<template>
    <div class="max-w-6xl mx-auto">
        <div class="mb-12 text-center max-w-3xl mx-auto">
            <h1 class="text-4xl font-bold text-white mb-4">Portfolio</h1>
            <p class="text-xl text-gray-400">A collection of projects I've worked on, showcasing my skills in development and design.</p>
        </div>
        
        <LoadingSpinner v-if="isLoading" />

        <div v-else-if="projects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <ProjectCard v-for="project in projects" :key="project.id" :project="project" />
        </div>
        
        <div v-else class="text-center py-20 bg-gray-900 border border-gray-800 rounded-2xl">
            <h3 class="text-xl font-medium text-gray-300 mb-2">No projects yet</h3>
            <p class="text-gray-500">I'm currently working on some exciting stuff. Check back soon!</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';
import ProjectCard from '../components/ProjectCard.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';

const projects = ref([]);
const isLoading = ref(true);

const fetchProjects = async () => {
    isLoading.value = true;
    try {
        const response = await api.get('/projects');
        projects.value = response.data.data || response.data || [];
    } catch (e) {
        console.error('Failed to fetch projects', e);
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => fetchProjects());
</script>
