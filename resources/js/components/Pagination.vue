<template>
    <div v-if="totalPages > 1" class="flex justify-center items-center gap-2 mt-12">
        <button 
            @click="$emit('change', currentPage - 1)" 
            :disabled="currentPage === 1"
            class="px-4 py-2 rounded-lg border border-gray-800 bg-gray-900 text-white disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-800 transition-colors"
        >
            Previous
        </button>
        
        <div class="flex gap-1">
            <button 
                v-for="page in totalPages" 
                :key="page"
                @click="$emit('change', page)"
                class="w-10 h-10 rounded-lg flex items-center justify-center border transition-colors"
                :class="page === currentPage ? 'bg-indigo-600 border-indigo-500 text-white' : 'border-gray-800 bg-gray-900 text-gray-400 hover:bg-gray-800'"
            >
                {{ page }}
            </button>
        </div>
        
        <button 
            @click="$emit('change', currentPage + 1)" 
            :disabled="currentPage === totalPages"
            class="px-4 py-2 rounded-lg border border-gray-800 bg-gray-900 text-white disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-800 transition-colors"
        >
            Next
        </button>
    </div>
</template>

<script setup>
defineProps({
    currentPage: { type: Number, required: true },
    totalPages: { type: Number, required: true }
});
defineEmits(['change']);
</script>
