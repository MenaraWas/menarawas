<template>
    <component :is="layout">
        <router-view />
    </component>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import PublicLayout from './components/PublicLayout.vue';
import AdminLayout from './components/AdminLayout.vue';

const route = useRoute();

const layout = computed(() => {
    if (route.path.startsWith('/admin') && route.name !== 'admin.login') {
        return AdminLayout;
    } else if (route.name === 'admin.login') {
        // Just empty wrapper for login
        return 'div';
    }
    return PublicLayout;
});
</script>
