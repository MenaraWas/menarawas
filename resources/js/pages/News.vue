<template>
    <div class="max-w-6xl mx-auto">
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-white mb-4">News & Articles</h1>
            <p class="text-xl text-gray-400">Stay updated with the latest news, tutorials, and insights.</p>
        </div>
        
        <!-- Search and Filter -->
        <div class="flex flex-col sm:flex-row justify-between gap-4 mb-8">
            <div class="relative w-full sm:w-96">
                <input 
                    v-model="searchQuery"
                    @keyup.enter="fetchNews"
                    type="text" 
                    placeholder="Search articles..."
                    class="w-full bg-gray-900 border border-gray-800 text-white rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:border-indigo-500 transition-colors"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <!-- Optional: Category Filter -->
        </div>

        <LoadingSpinner v-if="isLoading" />

        <div v-else-if="posts.length > 0">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <NewsCard v-for="post in posts" :key="post.id" :post="post" />
            </div>
            
            <Pagination 
                v-if="totalPages > 1"
                :current-page="currentPage" 
                :total-pages="totalPages" 
                @change="handlePageChange" 
            />
        </div>
        
        <div v-else class="text-center py-20 bg-gray-900 border border-gray-800 rounded-2xl">
            <h3 class="text-xl font-medium text-gray-300 mb-2">No articles found</h3>
            <p class="text-gray-500">Check back later or try adjusting your search.</p>
            <button v-if="searchQuery" @click="clearSearch" class="mt-4 text-indigo-400 hover:text-indigo-300">Clear Search</button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';
import NewsCard from '../components/NewsCard.vue';
import Pagination from '../components/Pagination.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';

const posts = ref([]);
const isLoading = ref(true);
const searchQuery = ref('');
const currentPage = ref(1);
const totalPages = ref(1);

const fetchNews = async (page = 1) => {
    isLoading.value = true;
    try {
        const response = await api.get('/posts', {
            params: {
                type: 'news',
                search: searchQuery.value || undefined,
                page
            }
        });
        
        posts.value = response.data.data || [];
        currentPage.value = response.data.current_page || 1;
        totalPages.value = response.data.last_page || 1;
    } catch (e) {
        console.error('Failed to fetch news', e);
    } finally {
        isLoading.value = false;
    }
};

const handlePageChange = (page) => {
    fetchNews(page);
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const clearSearch = () => {
    searchQuery.value = '';
    fetchNews();
};

onMounted(() => fetchNews());
</script>
