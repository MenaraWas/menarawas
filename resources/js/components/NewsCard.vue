<template>
    <article class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden hover:border-indigo-500/50 transition-colors flex flex-col h-full group">
        <div class="aspect-video w-full bg-gray-800 relative overflow-hidden">
            <img v-if="post.featured_image" :src="post.featured_image" :alt="post.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <div v-else class="w-full h-full flex items-center justify-center text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <!-- Category Badge -->
            <div v-if="post.categories && post.categories.length > 0" class="absolute top-4 left-4 flex gap-2">
                <span v-for="cat in post.categories" :key="cat.id" class="px-3 py-1 bg-gray-950/80 backdrop-blur text-xs font-medium text-white rounded-full">
                    {{ cat.name }}
                </span>
            </div>
        </div>
        
        <div class="p-6 flex flex-col flex-grow">
            <div class="text-sm text-gray-400 mb-3 flex items-center gap-2">
                <span v-if="post.published_at">{{ new Date(post.published_at).toLocaleDateString() }}</span>
                <span v-if="post.user" class="w-1 h-1 bg-gray-600 rounded-full"></span>
                <span v-if="post.user">{{ post.user.name }}</span>
            </div>
            
            <h3 class="text-xl font-bold text-white mb-3 line-clamp-2">
                <router-link :to="`/news/${post.slug}`" class="hover:text-indigo-400 transition-colors">
                    {{ post.title }}
                </router-link>
            </h3>
            
            <p class="text-gray-400 text-sm line-clamp-3 mb-4 flex-grow">
                {{ post.excerpt || stripHtml(post.content).substring(0, 150) + '...' }}
            </p>
            
            <router-link :to="`/news/${post.slug}`" class="text-indigo-400 font-medium text-sm hover:text-indigo-300 inline-flex items-center gap-1 mt-auto">
                Read article
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </router-link>
        </div>
    </article>
</template>

<script setup>
const props = defineProps({
    post: Object
});

const stripHtml = (html) => {
    let tmp = document.createElement('DIV');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
};
</script>
