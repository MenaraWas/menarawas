<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">Projects Management</h1>
            <router-link to="/admin/projects/create" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors">
                + Add Project
            </router-link>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-950 border-b border-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3">Project Title</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="isLoading" class="border-b border-gray-800">
                            <td colspan="3" class="px-6 py-8 text-center"><LoadingSpinner /></td>
                        </tr>
                        <tr v-else-if="projects.length === 0" class="border-b border-gray-800">
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">No projects found.</td>
                        </tr>
                        <tr v-else v-for="project in projects" :key="project.id" class="border-b border-gray-800 hover:bg-gray-800/50">
                            <td class="px-6 py-4 font-medium text-white">
                                {{ project.title }}
                                <div class="text-xs text-gray-500 mt-1 truncate max-w-xs">{{ project.tech_stack }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="project.status === 'published' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20'" class="px-2 py-1 text-xs rounded-full border">
                                    {{ project.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <router-link :to="`/admin/projects/${project.slug}/edit`" class="text-indigo-400 hover:text-indigo-300">Edit</router-link>
                                <button @click="deleteProject(project.slug)" class="text-red-400 hover:text-red-300">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../services/api';
import LoadingSpinner from '../../components/LoadingSpinner.vue';

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

const deleteProject = async (slug) => {
    if (!confirm('Are you sure you want to delete this project?')) return;
    
    try {
        await api.delete(`/projects/${slug}`);
        fetchProjects();
    } catch (e) {
        alert('Failed to delete project: ' + (e.response?.data?.message || e.message));
    }
};

onMounted(() => fetchProjects());
</script>
