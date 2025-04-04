<template>
    <div class="grid grid-cols-4 lg:grid-cols-4 gap-4">
        <div
            v-for="(filter, index) in activeFilters"
            :key="index"
            class="flex items-center gap-3 px-4 py-2.5 bg-white dark:bg-gray-800 shadow-md rounded-lg"
        >
            <BaseIcon :name="filter.icon" class="w-8 h-8 text-gray-900 dark:text-white" />
            <input
                class="w-full outline-none bg-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                type="text"
                :placeholder="filter.placeholder"
                v-model="filterValues[index]"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, watch, computed } from 'vue';
import BaseIcon from "@/components/BaseIcon.vue";

const props = defineProps({
    filtersData: {
        type: Object,
        required: true
    },
    activeMenu: {
        type: String,
        required: true
    }
});
const emit = defineEmits(['update']);

const activeFilters = computed(() => props.filtersData[props.activeMenu] || []);

const filterValues = ref([]);

watch(activeFilters, (newFilters) => {
    filterValues.value = newFilters.map(() => '');
}, { immediate: true });

watch(filterValues, (newValues) => {
    emit('update', newValues);
}, { deep: true });
</script>
