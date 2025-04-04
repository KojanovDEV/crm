import {createRouter, createWebHistory} from "vue-router";
import {defineAsyncComponent} from "vue";

const ifAuthorized = (to, from, next) => {
    if (localStorage.getItem('token') !== null) {
        next()
    } else {
        next('/login')
    }
}

const ifNotAuthorized = (to, from, next) => {
    if (localStorage.getItem('token') === null) {
        next()
    } else {
        next('/')
    }
}

const routes = [
    {
        path: '/',
        component: () => import("../pages/HomePage.vue"),
        meta: {
            layout: defineAsyncComponent(() => import("../components/layouts/DefaultLayout.vue"))
        },
        beforeEnter: ifAuthorized
    },
    {
        path: '/login',
        component: () => import("../pages/auth/LoginPage.vue"),
        meta: {
            layout: defineAsyncComponent(() => import("../components/layouts/BlankLayout.vue"))
        },
        beforeEnter: ifNotAuthorized
    },
    // 404 sahifasi
    {
        path: '/:pathMatch(.*)*',
        component: () => import("../pages/NotFoundPage.vue"),
        meta: {
            layout: defineAsyncComponent(() => import("../components/layouts/BlankLayout.vue"))
        }
    }
]

export default createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: 'active'
})
