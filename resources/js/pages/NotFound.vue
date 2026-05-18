<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-950 text-center px-4">
        <h1 class="text-9xl font-extrabold text-indigo-500 mb-4">404</h1>
        <h2 class="text-3xl font-bold text-white mb-6">Page Not Found</h2>
        <p class="text-gray-400 mb-8 max-w-md">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
        <router-link to="/" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-full transition-colors">
            Go to Homepage
        </router-link>
    </div>
</template>
