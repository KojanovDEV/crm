<template>
    <div v-if="isOpen" class="fixed inset-0 bg-black/40 flex justify-center items-center rounded z-50">
        <div class="bg-white shadow-md rounded">
            <div class="border-b border-[#C4C4C4] py-4 text-center text-gray-900 bg-white dark:text-gray-200 dark:bg-gray-800">
                {{ title }}
            </div>
            <div class="px-5 py-4 flex flex-col gap-4 bg-white dark:bg-gray-800">
                <div v-for="(field, index) in localFields" :key="index" class="flex flex-col gap-2.5">
                    <label :for="field.id" class="text-sm text-black/85 dark:text-gray-300">
                        {{ field.label }} <span v-if="field.required" class="text-blue-500">*</span>
                    </label>
                    <input
                        v-if="['text', 'email', 'password'].includes(field.type)"
                        :id="field.id"
                        v-model="formData[field.model]"
                        class="rounded-md px-4 py-2.5 border text-gray-900 dark:text-gray-200 dark:bg-gray-700"
                        :type="field.type"
                        :placeholder="field.placeholder"
                    />
                    <select
                        v-if="field.type === 'select' && field.options"
                        :id="field.id"
                        v-model="formData[field.model]"
                        class="rounded-md px-4 py-2.5 border bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200"
                    >
                        <option value="" disabled>{{ $t("choose") }}</option>
                        <option v-for="option in field.options" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>
                </div>
                <div class="flex justify-center gap-4">
                    <button @click="$emit('close')" class="bg-gray-400 hover:bg-gray-500 px-4 py-1.5 rounded-md text-white text-sm">
                        {{ $t("cancel") }}
                    </button>
                    <button @click="$emit('submit', formData)" class="bg-blue hover:bg-blue-600 px-4 py-1.5 rounded-md text-white text-sm">
                        {{ buttonText }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, defineProps, defineEmits, watch, onMounted} from 'vue';
import { useI18n } from "vue-i18n"

const { t } = useI18n();
const props = defineProps({
    isOpen: Boolean,
    title: String,
    fields: Array,
    buttonText: String
});

const emit = defineEmits(['close', 'submit']);
const formData = ref({});
const localFields = ref([]);

const fetchCompanies = async () => {
    try {
        const token = localStorage.getItem('token');
        if (!token) {
            console.error("Token topilmadi!");
            return;
        }

        const response = await fetch('http://localhost:8505/api/companies?pagination=false', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`Server xatosi: ${response.status}`);
        }

        const data = await response.json();
        console.log("Kompaniya ma'lumotlari:", data);

        const companyOptions = data.map(company => ({
            value: `/api/companies/${company.id}`,
            label: company.companyName
        }));

        localFields.value = localFields.value.map(field =>
            field.id === 'workplace' ? {...field, options: companyOptions} : field
        );

    } catch (error) {
        console.error("Xatolik yuz berdi:", error);
    }
};

onMounted(fetchCompanies);

watch(() => props.fields, async (newFields) => {
    if (!newFields) return;
    localFields.value = JSON.parse(JSON.stringify(newFields));

    formData.value = newFields.reduce((acc, field) => {
        acc[field.model] = field.type === 'select' ? (field.id === 'workplace' ? null : '') : field.default || '';
        return acc;
    }, {});

    if (newFields.some(field => field.id === 'workplace')) {
        await fetchCompanies();
    }
}, {immediate: true});
</script>
