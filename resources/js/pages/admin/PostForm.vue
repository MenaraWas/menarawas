<template>
    <div class="max-w-4xl space-y-6">
        <div class="flex items-center gap-4">
            <router-link to="/admin/posts" class="text-gray-400 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </router-link>
            <h1 class="text-2xl font-bold text-white">{{ isEdit ? 'Edit Post' : 'Create New Post' }}</h1>
        </div>

        <form @submit.prevent="savePost" class="space-y-6">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 space-y-6">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
                    <input 
                        v-model="form.title" 
                        type="text" 
                        required
                        class="w-full bg-gray-950 border border-gray-800 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500"
                        placeholder="Enter post title"
                    >
                </div>

                <!-- Featured Image URL -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Featured Image URL</label>
                    <input 
                        v-model="form.featured_image" 
                        type="url" 
                        class="w-full bg-gray-950 border border-gray-800 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500"
                        placeholder="https://example.com/image.jpg"
                    >
                    <p class="text-xs text-gray-500 mt-1">Direct link to the image (e.g., from Unsplash or your storage)</p>
                </div>

                <!-- Excerpt -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Excerpt (Optional)</label>
                    <textarea 
                        v-model="form.excerpt" 
                        rows="2"
                        class="w-full bg-gray-950 border border-gray-800 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500"
                        placeholder="Short summary for the post card"
                    ></textarea>
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Content (Markdown / HTML)</label>
                    <textarea 
                        v-model="form.content" 
                        rows="15"
                        required
                        class="w-full bg-gray-950 border border-gray-800 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-indigo-500 font-mono text-sm"
                        placeholder="Write your content here..."
                    ></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-6">
                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Type</label>
                        <select 
                            v-model="form.type"
                            class="w-full bg-gray-950 border border-gray-800 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500"
                        >
                            <option value="news">News</option>
                            <option value="blog">Blog</option>
                        </select>
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
            </div>

            <!-- Error message -->
            <div v-if="error" class="text-red-400 text-sm bg-red-500/10 p-3 rounded-lg border border-red-500/20">
                {{ error }}
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4">
                <router-link to="/admin/posts" class="px-6 py-2 border border-gray-700 text-gray-300 rounded-lg hover:bg-gray-800 transition-colors">
                    Cancel
                </router-link>
                <button 
                    type="submit" 
                    :disabled="isSaving"
                    class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors disabled:opacity-50"
                >
                    {{ isSaving ? 'Saving...' : 'Save Post' }}
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
    featured_image: '',
    excerpt: '',
    content: '',
    type: 'news',
    status: 'draft',
});

onMounted(async () => {
    if (isEdit.value) {
        try {
            const res = await api.get(`/posts/${route.params.id}`);
            const data = res.data;
            form.value = {
                title: data.title,
                featured_image: data.featured_image || '',
                excerpt: data.excerpt || '',
                content: data.content,
                type: data.type,
                status: data.status,
            };
        } catch (e) {
            error.value = 'Failed to load post data.';
        }
    }
});

const savePost = async () => {
    isSaving.value = true;
    error.value = '';
    
    try {
        if (isEdit.value) {
            await api.put(`/posts/${route.params.id}`, form.value);
        } else {
            await api.post('/posts', form.value);
        }
        router.push('/admin/posts');
    } catch (e) {
        error.value = e.response?.data?.message || 'Failed to save post.';
    } finally {
        isSaving.value = false;
    }
};
</script>
