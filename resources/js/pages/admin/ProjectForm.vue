<template>
    <div class="max-w-4xl space-y-6">
        <div class="flex items-center gap-4">
            <router-link to="/admin/projects" class="text-gray-400 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </router-link>
            <h1 class="text-2xl font-bold text-white">{{ isEdit ? 'Edit Project' : 'Add New Project' }}</h1>
        </div>

        <form @submit.prevent="saveProject" class="space-y-6">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 space-y-6">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
                    <input 
                        v-model="form.title" 
                        type="text" 
                        required
                        class="w-full bg-gray-950 border border-gray-800 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500"
                        placeholder="Project name"
                    >
                </div>

                <!-- Image URL -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Image URL</label>
                    <input 
                        v-model="form.image_url" 
                        type="url" 
                        class="w-full bg-gray-950 border border-gray-800 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500"
                        placeholder="https://example.com/screenshot.jpg"
                    >
                </div>

                <!-- Link -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Live Demo / Repo Link</label>
                    <input 
                        v-model="form.link" 
                        type="url" 
                        class="w-full bg-gray-950 border border-gray-800 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500"
                        placeholder="https://github.com/..."
                    >
                </div>

                <!-- Tech Stack -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tech Stack (comma separated)</label>
                    <input 
                        v-model="form.tech_stack" 
                        type="text" 
                        class="w-full bg-gray-950 border border-gray-800 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500"
                        placeholder="Vue.js, Laravel, TailwindCSS"
                    >
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                    <textarea 
                        v-model="form.description" 
                        rows="6"
                        required
                        class="w-full bg-gray-950 border border-gray-800 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-indigo-500"
                        placeholder="Describe the project..."
                    ></textarea>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                    <select 
                        v-model="form.status"
                        class="w-full bg-gray-950 border border-gray-800 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500"
                    >
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
            </div>

            <!-- Error message -->
            <div v-if="error" class="text-red-400 text-sm bg-red-500/10 p-3 rounded-lg border border-red-500/20">
                {{ error }}
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4">
                <router-link to="/admin/projects" class="px-6 py-2 border border-gray-700 text-gray-300 rounded-lg hover:bg-gray-800 transition-colors">
                    Cancel
                </router-link>
                <button 
                    type="submit" 
                    :disabled="isSaving"
                    class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors disabled:opacity-50"
                >
                    {{ isSaving ? 'Saving...' : 'Save Project' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../services/api';

const route = useRoute();
const router = useRouter();

const isEdit = computed(() => !!route.params.id);
const isSaving = ref(false);
const error = ref('');

const form = ref({
    title: '',
    description: '',
    image_url: '',
    link: '',
    tech_stack: '',
    status: 'draft',
});

onMounted(async () => {
    if (isEdit.value) {
        try {
            const res = await api.get(`/projects/${route.params.id}`);
            const data = res.data;
            form.value = {
                title: data.title,
                description: data.description,
                image_url: data.image_url || '',
                link: data.link || '',
                tech_stack: data.tech_stack || '',
                status: data.status,
            };
        } catch (e) {
            error.value = 'Failed to load project data.';
        }
    }
});

const saveProject = async () => {
    isSaving.value = true;
    error.value = '';
    
    try {
        if (isEdit.value) {
            await api.put(`/projects/${route.params.id}`, form.value);
        } else {
            await api.post('/projects', form.value);
        }
        router.push('/admin/projects');
    } catch (e) {
        error.value = e.response?.data?.message || 'Failed to save project.';
    } finally {
        isSaving.value = false;
    }
};
</script>
