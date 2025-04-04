<template>
    <svg
        v-if="icon"
        :class="[
            'w-6 h-6 transition-colors duration-300',
            isActive ? activeColor : inactiveColor,
            customClass
        ]"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        aria-hidden="true"
    >
        <component :is="icon" />
    </svg>
</template>

<script setup>
import { defineProps, computed } from 'vue';
import HomeIcon from "@/assets/icons/HomeIcon.vue";
import SearchIcon from "@/assets/icons/SearchIcon.vue";
import UserIcon from "@/assets/icons/UserIcon.vue";
import CompanyIcon from "@/assets/icons/CompanyIcon.vue";
import CustomerIcon from "@/assets/icons/CustomerIcon.vue";
import SettingsIcon from "@/assets/icons/SettingsIcon.vue";
import MenuCloseIcon from "@/assets/icons/MenuCloseIcon.vue";
import DeleteIcon from "@/assets/icons/DeleteIcon.vue";
import LogoutIcon from "@/assets/icons/LogoutIcon.vue";
import DeleteCompanyIcon from "@/assets/icons/DeleteCompanyIcon.vue";

const props = defineProps({
    name: String,
    customClass: {
        type: String,
        default: ''
    },
    isActive: Boolean,
    activeColor: {
        type: String,
        default: 'stroke-blue-500'
    },
    inactiveColor: {
        type: String,
        default: 'stroke-gray-200'
    }
});

const icons = {
    home: HomeIcon,
    user: UserIcon,
    search: SearchIcon,
    customer: CustomerIcon,
    company: CompanyIcon,
    settings: SettingsIcon,
    menuClose: MenuCloseIcon,
    logout: LogoutIcon,
    delete: DeleteIcon,
    deleteCompany: DeleteCompanyIcon
};

const icon = computed(() => icons[props.name] || null);
</script>
