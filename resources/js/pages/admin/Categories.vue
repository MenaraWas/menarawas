<template>
    <div class="space-y-6 max-w-4xl">
        <h1 class="text-2xl font-bold text-white">Categories Management</h1>

        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h2 class="text-lg font-medium text-white mb-4">Add New Category</h2>
            <form @submit.prevent="saveCategory" class="flex gap-4 items-start">
                <div class="flex-grow space-y-4">
                    <input 
                        v-model="form.name" 
                        type="text" 
                        required
                        class="w-full bg-gray-950 border border-gray-800 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500"
                        placeholder="Category Name"
                    >
                    <input 
                        v-model="form.slug" 
                        type="text" 
                        required
                        class="w-full bg-gray-950 border border-gray-800 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500"
                        placeholder="Category Slug (e.g. web-dev)"
                    >
                </div>
                <button type="submit" :disabled="isSaving" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors whitespace-nowrap">
                    {{ isEditing ? 'Update' : 'Add Category' }}
                </button>
                <button v-if="isEditing" type="button" @click="resetForm" class="px-4 py-2 border border-gray-700 text-gray-300 rounded-lg hover:bg-gray-800 transition-colors">
                    Cancel
                </button>
            </form>
            <div v-if="error" class="mt-4 text-red-400 text-sm">{{ error }}</div>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <table class="w-full text-left text-sm text-gray-400">
                <thead class="text-xs text-gray-500 uppercase bg-gray-950 border-b border-gray-800">
                    <tr>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Slug</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="isLoading" class="border-b border-gray-800">
                        <td colspan="3" class="px-6 py-8 text-center"><LoadingSpinner /></td>
                    </tr>
                    <tr v-else-if="categories.length === 0" class="border-b border-gray-800">
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">No categories found.</td>
                    </tr>
                    <tr v-else v-for="category in categories" :key="category.id" class="border-b border-gray-800 hover:bg-gray-800/50">
                        <td class="px-6 py-4 font-medium text-white">{{ category.name }}</td>
                        <td class="px-6 py-4">{{ category.slug }}</td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <button @click="editCategory(category)" class="text-indigo-400 hover:text-indigo-300">Edit</button>
                            <button @click="deleteCategory(category.id)" class="text-red-400 hover:text-red-300">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import api from '../../services/api';
import LoadingSpinner from '../../components/LoadingSpinner.vue';

const categories = ref([]);
const isLoading = ref(true);
const isSaving = ref(false);
const isEditing = ref(false);
const error = ref('');
const editId = ref(null);

const form = ref({
    name: '',
    slug: ''
});

// Auto generate slug
watch(() => form.value.name, (newVal) => {
    if (!isEditing.value && newVal) {
        form.value.slug = newVal.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
    }
});

const fetchCategories = async () => {
    isLoading.value = true;
    try {
        const response = await api.get('/categories');
        categories.value = response.data.data || response.data || [];
    } catch (e) {
        console.error('Failed to fetch categories', e);
    } finally {
        isLoading.value = false;
    }
};

const saveCategory = async () => {
    isSaving.value = true;
    error.value = '';
    
    try {
        if (isEditing.value) {
            await api.put(`/categories/${editId.value}`, form.value);
        } else {
            await api.post('/categories', form.value);
        }
        resetForm();
        fetchCategories();
    } catch (e) {
        error.value = e.response?.data?.message || 'Failed to save category.';
    } finally {
        isSaving.value = false;
    }
};

const editCategory = (category) => {
    isEditing.value = true;
    editId.value = category.id; // Controller uses implicit binding via id
    form.value = {
        name: category.name,
        slug: category.slug
    };
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const deleteCategory = async (id) => {
    if (!confirm('Are you sure you want to delete this category?')) return;
    
    try {
        await api.delete(`/categories/${id}`);
        fetchCategories();
    } catch (e) {
        alert('Failed to delete category: ' + (e.response?.data?.message || e.message));
    }
};

const resetForm = () => {
    isEditing.value = false;
    editId.value = null;
    form.value = { name: '', slug: '' };
    error.value = '';
};

onMounted(() => fetchCategories());
</script>
