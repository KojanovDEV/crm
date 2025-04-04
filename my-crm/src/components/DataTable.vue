<script setup>
import { defineProps } from "vue";
import BaseIcon from "@/components/BaseIcon.vue";

const props = defineProps({
    columns: Array,
    data: Array,
});
</script>

<template>
    <div class="w-full bg-white dark:bg-gray-800 shadow h-full overflow-hidden">
        <div v-if="!props.data || props.data.length === 0" class="text-center p-5 text-gray-900 dark:text-gray-300">
            Ma'lumot yuklanmoqda...
        </div>

        <table v-else class="w-full">
            <thead class="sticky top-0 bg-white dark:bg-gray-700 shadow-md z-10">
            <tr class="h-12 border-b border-gray-300 dark:border-gray-600">
                <th v-for="column in props.columns" :key="column.key" class="text-start text-gray-900 dark:text-gray-200 px-4">
                    {{ column.label }}
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in props.data" :key="item.id" class="h-12 border-b border-gray-300 dark:border-gray-600">
                <td v-for="column in props.columns" :key="column.key" class="px-4 text-gray-900 dark:text-gray-300">
                    <div v-if="column.key === 'fullname'" class="flex items-center gap-3">
                        <div class="rounded-full size-[40px] overflow-hidden flex-none">
                            <img v-if="item?.profilePhoto"
                                 v-bind:src="'http://localhost:8505' + (typeof item.profilePhoto === 'string' ? item.profilePhoto : item.profilePhoto.contentUrl)"
                                 class="object-cover h-full" alt="avatar">
                        </div>
                        <span>{{ item[column.key] }}</span>
                    </div>
                    <template v-else>
                        {{ item[column.key] }}
                    </template>
                </td>
                <td class="px-4 text-center">
                    <button @click="handleDelete(item.id)" class="text-red-500 hover:text-red-400 dark:text-red-400 dark:hover:text-red-300">
                        <BaseIcon name="delete" class="w-6 h-6" />
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

