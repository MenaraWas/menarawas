<template>
    <div class="min-h-screen flex flex-col bg-gray-950 text-gray-200 font-sans">
        <!-- Navbar -->
        <header class="border-b border-gray-800 bg-gray-950/80 backdrop-blur sticky top-0 z-50">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <router-link to="/" class="text-xl font-bold text-white tracking-tight">
                    Menara<span class="text-indigo-500">Was</span>
                </router-link>
                <nav class="space-x-6 text-sm font-medium">
                    <router-link to="/" class="hover:text-indigo-400 transition-colors" active-class="text-indigo-400">Home</router-link>
                    <router-link to="/news" class="hover:text-indigo-400 transition-colors" active-class="text-indigo-400">News</router-link>
                    <router-link to="/portfolio" class="hover:text-indigo-400 transition-colors" active-class="text-indigo-400">Portfolio</router-link>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-4 py-8">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-800 py-6 mt-12 text-center text-sm text-gray-500">
            &copy; {{ new Date().getFullYear() }} MenaraWas. All rights reserved.
        </footer>
    </div>
</template>

<script setup>
</script>
