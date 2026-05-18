<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-950 font-sans px-4">
        <div class="max-w-md w-full bg-gray-900 border border-gray-800 rounded-2xl p-8 shadow-2xl">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white tracking-tight mb-2">Admin Login</h1>
                <p class="text-gray-400">Sign in to manage MenaraWas CMS</p>
            </div>
            
            <form @submit.prevent="handleLogin" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                    <input 
                        v-model="email" 
                        type="email" 
                        required
                        class="w-full bg-gray-950 border border-gray-800 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                        placeholder="admin@example.com"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <input 
                        v-model="password" 
                        type="password" 
                        required
                        class="w-full bg-gray-950 border border-gray-800 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                        placeholder="••••••••"
                    >
                </div>
                
                <div v-if="error" class="text-red-400 text-sm bg-red-500/10 p-3 rounded-lg border border-red-500/20">
                    {{ error }}
                </div>
                
                <button 
                    type="submit" 
                    :disabled="isLoading"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span v-if="isLoading">Signing in...</span>
                    <span v-else>Sign In</span>
                </button>
            </form>
            <div class="mt-6 text-center">
                 <router-link to="/" class="text-sm text-gray-500 hover:text-gray-300 transition-colors">&larr; Back to Website</router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const email = ref('');
const password = ref('');
const error = ref('');
const isLoading = ref(false);

const router = useRouter();
const authStore = useAuthStore();

const handleLogin = async () => {
    isLoading.value = true;
    error.value = '';
    
    try {
        await authStore.login(email.value, password.value);
        router.push('/admin');
    } catch (e) {
        error.value = e.response?.data?.message || 'Invalid credentials or server error.';
    } finally {
        isLoading.value = false;
    }
};
</script>
