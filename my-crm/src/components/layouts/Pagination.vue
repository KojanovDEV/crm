<template>
    <ol class="flex justify-center text-xs font-medium space-x-1" :class="{'text-white': darkMode}">
        <li>
            <button
                @click="$emit('prev')"
                :disabled="currentPage === 1"
                :class="['inline-flex items-center justify-center w-8 h-8 border rounded', darkMode ? 'border-gray-600 text-white' : 'border-gray-100']"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
        </li>

        <li v-for="page in displayedPages" :key="page">
            <button
                v-if="page !== '...'"
                @click="$emit('changePage', page)"
                :class="{
                    'block w-8 h-8 text-center border rounded leading-8': true,
                    'border-gray-100': !darkMode && currentPage !== page,
                    'border-gray-600 text-white': darkMode && currentPage !== page,
                    'text-white bg-blue-600 border-blue-600': currentPage === page
                }"
            >
                {{ page }}
            </button>
            <span v-else class="w-8 h-8 flex items-center justify-center">...</span>
        </li>

        <li>
            <button
                @click="$emit('next')"
                :disabled="currentPage === totalPages"
                :class="['inline-flex items-center justify-center w-8 h-8 border rounded', darkMode ? 'border-gray-600 text-white' : 'border-gray-100']"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
        </li>
    </ol>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    currentPage: Number,
    totalPages: Number,
    darkMode: Boolean
});

const displayedPages = computed(() => {
    const pages = [];
    if (props.totalPages <= 5) {
        for (let i = 1; i <= props.totalPages; i++) {
            pages.push(i);
        }
    } else {
        if (props.currentPage <= 3) {
            pages.push(1, 2, 3, "...", props.totalPages);
        } else if (props.currentPage >= props.totalPages - 2) {
            pages.push(1, "...", props.totalPages - 2, props.totalPages - 1, props.totalPages);
        } else {
            pages.push(1, "...", props.currentPage - 1, props.currentPage, props.currentPage + 1, "...", props.totalPages);
        }
    }
    return pages;
});
</script>
