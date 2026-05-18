import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const routes = [
    // Public Routes
    {
        path: '/',
        name: 'home',
        component: () => import('@/pages/Home.vue'),
    },
    {
        path: '/news',
        name: 'news',
        component: () => import('@/pages/News.vue'),
        meta: { title: 'News' }
    },
    {
        path: '/news/:slug',
        name: 'news.detail',
        component: () => import('@/pages/NewsDetail.vue'),
    },
    {
        path: '/portfolio',
        name: 'portfolio',
        component: () => import('@/pages/Portfolio.vue'),
        meta: { title: 'Portfolio' }
    },
    {
        path: '/portfolio/:slug',
        name: 'portfolio.detail',
        component: () => import('@/pages/ProjectDetail.vue'),
    },

    // Admin Routes
    {
        path: '/admin/login',
        name: 'admin.login',
        component: () => import('@/pages/admin/Login.vue'),
        meta: { guest: true }
    },
    {
        path: '/admin',
        name: 'admin.dashboard',
        component: () => import('@/pages/admin/Dashboard.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/admin/posts',
        name: 'admin.posts',
        component: () => import('@/pages/admin/Posts.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/admin/posts/create',
        name: 'admin.posts.create',
        component: () => import('@/pages/admin/PostForm.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/admin/posts/:id/edit',
        name: 'admin.posts.edit',
        component: () => import('@/pages/admin/PostForm.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/admin/projects',
        name: 'admin.projects',
        component: () => import('@/pages/admin/Projects.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/admin/projects/create',
        name: 'admin.projects.create',
        component: () => import('@/pages/admin/ProjectForm.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/admin/projects/:id/edit',
        name: 'admin.projects.edit',
        component: () => import('@/pages/admin/ProjectForm.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/admin/categories',
        name: 'admin.categories',
        component: () => import('@/pages/admin/Categories.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/admin/tags',
        name: 'admin.tags',
        component: () => import('@/pages/admin/Tags.vue'),
        meta: { requiresAuth: true, title: 'Manage Tags' }
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: () => import('@/pages/NotFound.vue'),
        meta: { title: '404 Not Found' }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    
    // Fetch user if token exists but user data is empty
    if (authStore.token && !authStore.user) {
        await authStore.fetchUser();
    }

    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next({ name: 'admin.login' });
    } else if (to.meta.guest && authStore.isAuthenticated) {
        next({ name: 'admin.dashboard' });
    } else {
        next();
    }
});

router.afterEach((to) => {
    document.title = to.meta.title ? `${to.meta.title} - MenaraWas` : 'MenaraWas CMS';
});

export default router;
