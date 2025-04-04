<template>
    <div
        :class="[
            'h-full bg-white dark:bg-gray-800 shadow flex-none flex flex-col transition-all duration-300 ease-in-out overflow-hidden',
            isOpen ? 'w-64' : 'w-[68px]'
        ]"
    >
        <div
            class="font-semibold text-lg text-blue py-4 border-b border-[#EBEFF2] dark:border-gray-700 px-2.5 text-nowrap"
            :class="{ 'text-center': !isOpen }"
        >
            {{ isOpen ? 'Kadirov ' : '' }}Inc
        </div>

        <div class="px-2.5 py-6 border-b border-[#EBEFF2] dark:border-gray-700">
            <div class="flex items-center gap-4">
                <!-- Profil rasmi qismi -->
                <div class="relative rounded-full size-[48px] overflow-hidden flex-none" :class="{ 'mx-auto': !isOpen }">
                    <img v-if="localUserData?.profilePhoto?.contentUrl"
                         :src="'http://localhost:8505' + localUserData.profilePhoto.contentUrl + '?t=' + new Date().getTime()"
                         class="object-cover h-full w-full" alt="avatar">

                    <!-- Edit tugmasi -->
                    <button v-if="isOpen" @click="editUserProfile"
                            class="absolute bottom-0 right-0 size-[20px] bg-white dark:bg-gray-800 shadow rounded-full flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-700 transition"
                            aria-label="Profilni tahrirlash">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4 fill-current text-gray-700 dark:text-gray-300">
                            <path d="M2,21H8a1,1,0,0,0,0-2H3.071A7.011,7.011,0,0,1,10,13a5.044,5.044,0,1,0-3.377-1.337A9.01,9.01,0,0,0,1,20,1,1,0,0,0,2,21ZM10,5A3,3,0,1,1,7,8,3,3,0,0,1,10,5ZM20.207,9.293a1,1,0,0,0-1.414,0l-6.25,6.25a1.011,1.011,0,0,0-.241.391l-1.25,3.75A1,1,0,0,0,12,21a1.014,1.014,0,0,0,.316-.051l3.75-1.25a1,1,0,0,0,.391-.242l6.25-6.25a1,1,0,0,0,0-1.414Zm-5,8.583-1.629.543.543-1.629L19.5,11.414,20.586,12.5Z"/>
                        </svg>
                    </button>
                </div>

                <div v-if="isOpen" class="flex-1 min-w-0">
                    <p class="font-semibold truncate text-gray-900 dark:text-gray-200">{{ userData?.fullname || "Foydalanuvchi" }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ userData?.emailOwner.email || "Email mavjud emas" }}</p>
                </div>

                <button v-if="isOpen" @click="isLogoutModalOpen = true" class="flex-none focus:ring-2 focus:ring-blue-500 rounded">
                    <BaseIcon name="logout" class="w-6 h-6 text-gray-700 dark:text-gray-300"/>
                </button>
            </div>

            <ul class="mt-10 flex flex-col gap-4">
                <li
                    v-for="item in menuItems"
                    :key="item.name"
                    :class="{'text-blue-500': activeMenu === item.name, 'text-[#334D6E] dark:text-gray-400': activeMenu !== item.name }"
                    class="text-sm"
                >
                    <button
                        @click="setActive(item.name)"
                        class="w-full flex items-center text-start hover:text-blue transition focus:ring-2 focus:ring-blue-500 rounded"
                        :class="isOpen ? 'gap-[14px]' : 'justify-center'"
                    >
                        <div class="flex justify-center items-center w-6 h-6">
                            <BaseIcon :name="item.icon" :isActive="activeMenu === item.name" />
                        </div>
                        <p v-if="isOpen" class="text-nowrap">{{ item.label }}</p>
                    </button>
                </li>
            </ul>
            <LogoutModal :isOpen="isLogoutModalOpen" @close="isLogoutModalOpen = false" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"/>
        </div>

        <div class="px-2.5">
            <button
                @click="isSettingsOpen = true"
                class="gap-[14px] flex text-[#334D6E] dark:text-gray-400 text-sm mt-4 hover:text-blue transition focus:ring-2 focus:ring-blue-500 rounded"
                :class="{ 'mx-auto': !isOpen }"
            >
                <BaseIcon name="settings" :isActive="true" class="w-6 h-6" />
                <p v-if="isOpen" class="text-nowrap">{{ $t("settings") }}</p>
            </button>
        </div>
        <button
            @click="toggleSidebar"
            class="px-2.5 pb-4 gap-[14px] flex text-[#334D6E] dark:text-gray-400 text-sm mt-auto hover:text-blue transition focus:ring-2 focus:ring-blue-500 rounded"
            :class="{ 'mx-auto': !isOpen }"
        >
            <BaseIcon name="menuClose" :isActive="true" class="w-6 h-6" />
            <p v-if="isOpen" class="text-nowrap">{{ $t("close_menu") }}</p>
        </button>

        <ProfileEditModal
            :isOpen="isModalOpen"
            :userData="userData"
            @close="isModalOpen = false"
            @save="updateProfile"
        />
        <SettingModal :isOpen="isSettingsOpen" @close="isSettingsOpen = false" class="z-50" />
    </div>
</template>

<script setup>
import { defineProps, defineEmits, ref, watch, watchEffect } from "vue";
import BaseIcon from "@/components/BaseIcon.vue";
import ProfileEditModal from "./ProfileEditModal.vue";
import LogoutModal from "@/components/modals/LogoutModal.vue";
import SettingModal from "@/components/modals/SettingModal.vue";
import { useI18n } from "vue-i18n"

const { t } = useI18n();
const isModalOpen = ref(false);
const isLogoutModalOpen = ref(false);
const isSettingsOpen = ref(false);

const props = defineProps({
    isOpen: Boolean,
    activeMenu: String,
    menuItems: Array,
    userData: Object,
});

const emit = defineEmits(["toggle", "update:activeMenu", "update:userData"]); // ❗ Emitni oldin e’lon qilish

const setActive = (menuName) => {
    emit("update:activeMenu", menuName);
};

const localUserData = ref({ ...props.userData });

watch(
    () => props.userData,
    (newValue) => {
        if (newValue) {
            console.log("🔄 `userData` yangilandi:", newValue);
            localUserData.value = JSON.parse(JSON.stringify(newValue)); // Deep copy orqali yangilash
        }
    },
    { deep: true, immediate: true }
);

const updateProfile = (updatedUser) => {
    if (!updatedUser) {
        console.error("❌ updateProfile ga noto‘g‘ri ma'lumot keldi!");
        return;
    }

    console.log("🆕 Profil yangilandi:", updatedUser);
    emit("update:userData", JSON.parse(JSON.stringify(updatedUser))); // Emit orqali ota komponentni yangilash
    localUserData.value = JSON.parse(JSON.stringify(updatedUser)); // Lokal nusxani ham yangilash
    isModalOpen.value = false;
};

watchEffect(() => {
    if (props.userData?.profilePhoto?.contentUrl) {
        localUserData.value = JSON.parse(JSON.stringify(props.userData));
    }
});

const editUserProfile = () => {
    isModalOpen.value = true;
};

const toggleSidebar = () => {
    emit("toggle");
};
</script>

