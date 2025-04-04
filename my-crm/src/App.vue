<script setup>
import { useRoute } from "vue-router";
import { computed, defineAsyncComponent, ref, watchEffect } from "vue";

const route = useRoute();
const isDarkMode = ref(localStorage.getItem("theme") === "dark");

watchEffect(() => {
    if (isDarkMode.value) {
        document.documentElement.classList.add("dark");
        localStorage.setItem("theme", "dark");
    } else {
        document.documentElement.classList.remove("dark");
        localStorage.setItem("theme", "light");
    }
});

const toggleTheme = () => {
    isDarkMode.value = !isDarkMode.value;
};

const layouts = {
    DefaultLayout: defineAsyncComponent(() => import("./components/layouts/DefaultLayout.vue")),
    BlankLayout: defineAsyncComponent(() => import("./components/layouts/BlankLayout.vue"))
};

const LayoutComponent = computed(() => layouts[route.meta.layout] || layouts.DefaultLayout);
</script>

<template>
    <component :is="LayoutComponent">
        <router-view />
    </component>
</template>
