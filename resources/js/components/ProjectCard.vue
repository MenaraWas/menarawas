<template>
    <div class="group relative bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden hover:border-indigo-500/50 transition-colors">
        <div class="aspect-video w-full bg-gray-800 relative">
            <img v-if="project.image_url" :src="project.image_url" :alt="project.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <div v-else class="w-full h-full flex items-center justify-center text-gray-600">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/40 to-transparent opacity-80"></div>
            
            <!-- Content overlay -->
            <div class="absolute bottom-0 left-0 w-full p-6 translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                <div class="flex gap-2 mb-3 flex-wrap">
                    <span v-for="(tech, index) in techStackList" :key="index" class="px-2 py-1 bg-gray-800 text-xs text-gray-300 rounded border border-gray-700">
                        {{ tech }}
                    </span>
                </div>
                
                <h3 class="text-xl font-bold text-white mb-2">{{ project.title }}</h3>
                <p class="text-sm text-gray-300 line-clamp-2 mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
                    {{ project.description }}
                </p>
                
                <router-link :to="`/portfolio/${project.slug}`" class="inline-flex items-center gap-2 text-sm font-medium text-white hover:text-indigo-400 transition-colors">
                    View Details
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    project: Object
});

const techStackList = computed(() => {
    if (!props.project.tech_stack) return [];
    return props.project.tech_stack.split(',').map(t => t.trim());
});
</script>
