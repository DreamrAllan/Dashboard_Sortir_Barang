import { createRouter, createWebHistory } from 'vue-router'

// Auth Views
import Login from '../views/auth/Login.vue'
import Register from '../views/auth/Register.vue'

// Main Views
import Dashboard from '../views/Dashboard.vue'

// Category Views
import CategoryIndex from '../views/categories/Index.vue'
import CategoryCreate from '../views/categories/Create.vue'
import CategoryEdit from '../views/categories/Edit.vue'

// Item Views
import ItemIndex from '../views/items/Index.vue'
import ItemCreate from '../views/items/Create.vue'
import ItemEdit from '../views/items/Edit.vue'

// Transaction Views
import TransactionIndex from '../views/transactions/Index.vue'
import TransactionCreate from '../views/transactions/Create.vue'

const routes = [
    // Auth routes (guest only)
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { guest: true }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: { guest: true }
    },

    // Protected routes
    {
        path: '/',
        redirect: '/dashboard'
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },

    // Categories
    {
        path: '/categories',
        name: 'categories.index',
        component: CategoryIndex,
        meta: { requiresAuth: true }
    },
    {
        path: '/categories/create',
        name: 'categories.create',
        component: CategoryCreate,
        meta: { requiresAuth: true }
    },
    {
        path: '/categories/:id/edit',
        name: 'categories.edit',
        component: CategoryEdit,
        meta: { requiresAuth: true }
    },

    // Items
    {
        path: '/items',
        name: 'items.index',
        component: ItemIndex,
        meta: { requiresAuth: true }
    },
    {
        path: '/items/create',
        name: 'items.create',
        component: ItemCreate,
        meta: { requiresAuth: true }
    },
    {
        path: '/items/:id/edit',
        name: 'items.edit',
        component: ItemEdit,
        meta: { requiresAuth: true }
    },

    // Transactions
    {
        path: '/transactions',
        name: 'transactions.index',
        component: TransactionIndex,
        meta: { requiresAuth: true }
    },
    {
        path: '/transactions/create',
        name: 'transactions.create',
        component: TransactionCreate,
        meta: { requiresAuth: true }
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// Navigation guards
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token')

    if (to.meta.requiresAuth && !token) {
        next('/login')
    } else if (to.meta.guest && token) {
        next('/dashboard')
    } else {
        next()
    }
})

export default router
