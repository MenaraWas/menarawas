<template>
    <article v-if="post" class="max-w-4xl mx-auto py-8">
        <router-link to="/news" class="text-indigo-400 hover:text-indigo-300 font-medium inline-flex items-center gap-1 mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to News
        </router-link>

        <header class="mb-10 text-center">
            <div v-if="post.categories && post.categories.length > 0" class="flex justify-center gap-2 mb-4">
                <span v-for="cat in post.categories" :key="cat.id" class="px-3 py-1 bg-indigo-600/20 text-indigo-400 text-sm font-medium rounded-full">
                    {{ cat.name }}
                </span>
            </div>
            
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">{{ post.title }}</h1>
            
            <div class="flex items-center justify-center gap-4 text-gray-400 text-sm">
                <div v-if="post.user" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-white font-bold">
                        {{ post.user.name.charAt(0) }}
                    </div>
                    <span class="font-medium text-gray-300">{{ post.user.name }}</span>
                </div>
                <span v-if="post.user && post.published_at" class="w-1.5 h-1.5 rounded-full bg-gray-700"></span>
                <span v-if="post.published_at">{{ new Date(post.published_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
            </div>
        </header>

        <div v-if="post.featured_image" class="aspect-video w-full rounded-2xl overflow-hidden mb-12 border border-gray-800">
            <img :src="post.featured_image" :alt="post.title" class="w-full h-full object-cover">
        </div>

        <div class="prose prose-invert prose-lg max-w-none prose-indigo prose-headings:font-bold prose-a:text-indigo-400 hover:prose-a:text-indigo-300" v-html="post.content">
        </div>

        <div v-if="post.tags && post.tags.length > 0" class="mt-12 pt-8 border-t border-gray-800 flex flex-wrap gap-2">
            <span class="text-gray-400 mr-2 flex items-center">Tags:</span>
            <span v-for="tag in post.tags" :key="tag.id" class="px-3 py-1 bg-gray-900 border border-gray-800 text-gray-300 text-sm rounded-lg">
                #{{ tag.name }}
            </span>
        </div>
    </article>
    <div v-else-if="isLoading" class="py-20">
        <LoadingSpinner />
    </div>
    <div v-else class="text-center py-20">
        <h2 class="text-2xl font-bold text-white mb-2">Article not found</h2>
        <p class="text-gray-400 mb-6">The article you are looking for does not exist or has been removed.</p>
        <router-link to="/news" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
            Return to News
        </router-link>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '../services/api';
import LoadingSpinner from '../components/LoadingSpinner.vue';

const route = useRoute();
const post = ref(null);
const isLoading = ref(true);

onMounted(async () => {
    try {
        const response = await api.get(`/posts/${route.params.slug}`);
        post.value = response.data;
    } catch (e) {
        console.error('Failed to load post', e);
    } finally {
        isLoading.value = false;
    }
});
</script>
