<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">Posts Management</h1>
            <router-link to="/admin/posts/create" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors">
                + Create New Post
            </router-link>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="p-4 border-b border-gray-800 flex justify-between items-center gap-4">
                <input 
                    v-model="search"
                    @keyup.enter="fetchPosts"
                    type="text" 
                    placeholder="Search posts..." 
                    class="bg-gray-950 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 w-64 focus:outline-none focus:border-indigo-500"
                >
                <button @click="fetchPosts" class="text-gray-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-950 border-b border-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3">Title</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Date</th>
                            <th scope="col" class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="isLoading" class="border-b border-gray-800">
                            <td colspan="4" class="px-6 py-8 text-center"><LoadingSpinner /></td>
                        </tr>
                        <tr v-else-if="posts.length === 0" class="border-b border-gray-800">
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">No posts found.</td>
                        </tr>
                        <tr v-else v-for="post in posts" :key="post.id" class="border-b border-gray-800 hover:bg-gray-800/50">
                            <td class="px-6 py-4 font-medium text-white">
                                {{ post.title }}
                                <div class="text-xs text-gray-500 mt-1 truncate max-w-xs">{{ post.slug }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="post.status === 'published' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20'" class="px-2 py-1 text-xs rounded-full border">
                                    {{ post.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ post.published_at ? new Date(post.published_at).toLocaleDateString() : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <router-link :to="`/admin/posts/${post.slug}/edit`" class="text-indigo-400 hover:text-indigo-300">Edit</router-link>
                                <button @click="deletePost(post.slug)" class="text-red-400 hover:text-red-300">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-gray-800" v-if="totalPages > 1">
                <Pagination :current-page="currentPage" :total-pages="totalPages" @change="handlePageChange" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../services/api';
import Pagination from '../../components/Pagination.vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';

const posts = ref([]);
const isLoading = ref(true);
const search = ref('');
const currentPage = ref(1);
const totalPages = ref(1);

const fetchPosts = async (page = 1) => {
    isLoading.value = true;
    try {
        const response = await api.get('/posts', {
            params: { search: search.value || undefined, page }
        });
        posts.value = response.data.data || [];
        currentPage.value = response.data.current_page || 1;
        totalPages.value = response.data.last_page || 1;
    } catch (e) {
        console.error('Failed to fetch posts', e);
    } finally {
        isLoading.value = false;
    }
};

const handlePageChange = (page) => {
    fetchPosts(page);
};

const deletePost = async (slug) => {
    if (!confirm('Are you sure you want to delete this post?')) return;
    
    try {
        await api.delete(`/posts/${slug}`);
        fetchPosts(currentPage.value);
    } catch (e) {
        alert('Failed to delete post: ' + (e.response?.data?.message || e.message));
    }
};

onMounted(() => fetchPosts());
</script>
