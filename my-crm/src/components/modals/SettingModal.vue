<template>
    <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $t("settings") }}</h2>

            <!-- Light/Dark Mode Toggle -->
            <div class="mt-4">
                <label class="flex items-center justify-between cursor-pointer">
                    <span class="text-gray-700 dark:text-gray-300">{{ $t("selectmode") }}</span>
                    <button @click="toggleTheme" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded">
                        {{ theme === 'dark' ? $t("dark") : $t("light") }}
                    </button>
                </label>
            </div>

            <!-- Language Selection -->
            <div class="mt-4">
                <label class="block text-gray-700 dark:text-gray-300">{{ $t("selectlanguage") }}:</label>
                <select v-model="locale" @change="saveLanguage"
                        class="mt-1 block w-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white p-2 rounded">
                    <option v-for="(lang, code) in languages" :key="code" :value="code">{{ lang }}</option>
                </select>
            </div>

            <!-- Close Button -->
            <button @click="closeModal" class="mt-6 px-4 py-2 bg-blue dark:bg-blue-700 text-white rounded w-full">
                {{ $t("close") }}
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, onMounted, watch } from 'vue';
import { useI18n } from 'vue-i18n';

const props = defineProps({ isOpen: Boolean });
const emit = defineEmits(['close']);

const theme = ref(localStorage.getItem('theme') || 'light');

const languages = {
    uz: 'O‘zbekcha',
    en: 'English',
    ru: 'Русский',
    kz: 'Қазақша',
    kk: 'Qaraqalpaqsha'
};

const toggleTheme = () => {
    theme.value = theme.value === 'dark' ? 'light' : 'dark';
    localStorage.setItem('theme', theme.value);
    document.documentElement.classList.toggle('dark', theme.value === 'dark');
};

const { locale } = useI18n();

const saveLanguage = () => {
    localStorage.setItem('lang', locale.value);
};

const closeModal = () => {
    emit('close');
    setTimeout(() => {
        location.reload(); // Sahifani yangilash
    }, 100);
};
watch(locale, (newLang) => {
    localStorage.setItem('lang', newLang);
});

onMounted(() => {
    document.documentElement.classList.toggle('dark', theme.value === 'dark');

    const savedLang = localStorage.getItem('lang');
    if (savedLang && Object.keys(languages).includes(savedLang)) {
        locale.value = savedLang;
    }
});
</script>
