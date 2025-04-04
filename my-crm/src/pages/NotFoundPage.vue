<template>
    <div
        class="h-screen bg-cover bg-center flex flex-col items-center justify-center text-white dark:bg-gray-900"
    >
        <div class="text-center">
            <h1 class="text-6xl font-bold text-red-500 dark:text-red-400">404</h1>
            <p class="text-xl mt-4 font-bold text-gray-800 dark:text-gray-300">
                {{ $t("notfoundpage") }}
            </p>
            <router-link
                to="/"
                class="mt-6 inline-block bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700
                text-white dark:text-gray-200 px-6 py-3 rounded-lg font-semibold shadow-lg transition"
            >
                {{ $t("homepage") }}
            </router-link>
        </div>
    </div>
</template>


<script setup>
import {useI18n} from "vue-i18n";

const i18n = useI18n();
</script>

<style scoped>
</style>
