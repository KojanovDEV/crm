<template>
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div :class="[isDarkMode ? 'bg-gray-800 text-white' : 'bg-white', 'rounded-2xl shadow-lg p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto']">
            <div class="flex justify-between items-center border-b pb-3" :class="isDarkMode ? 'border-gray-600' : 'border-gray-300'">
                <h2 class="text-xl font-semibold">{{ $t ("edit_profile") }}</h2>
                <button @click="closeModal" :class="isDarkMode ? 'text-gray-400 hover:text-red-400' : 'text-gray-600 hover:text-red-500'">✖</button>
            </div>

            <!-- Avatar upload -->
            <div class="flex flex-col items-center my-4">
                <div class="relative w-24 h-24 rounded-full overflow-hidden border" :class="isDarkMode ? 'border-gray-600' : 'border-gray-300'">
                    <img :src="previewImage" class="w-full h-full object-cover" alt="Profile">
                    <input type="file" class="absolute inset-0 opacity-0 cursor-pointer" @change="uploadImage">
                </div>
                <p class="text-xs mt-2" :class="isDarkMode ? 'text-gray-400' :
                'text-gray-500'">{{ $t ("add_picture") }}</p>
            </div>

            <!-- Form Fields -->
            <form @submit.prevent="saveChanges">
                <div class="mb-4">
                    <label class="block text-sm font-medium" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Ism va Familiya</label>
                    <input type="text" v-model="form.fullname" class="w-full border rounded-lg p-2 mt-1 focus:ring focus:ring-blue-300" :class="isDarkMode ? 'bg-gray-700 text-white border-gray-600' : 'bg-white text-black border-gray-300'">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Username</label>
                    <input type="text" v-model="form.username" class="w-full border rounded-lg p-2 mt-1 focus:ring focus:ring-blue-300" :class="isDarkMode ? 'bg-gray-700 text-white border-gray-600' : 'bg-white text-black border-gray-300'">
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" @click="closeModal" class="px-4 py-2 rounded-lg" :class="isDarkMode ? 'text-gray-400 hover:bg-gray-700' : 'text-gray-600 hover:bg-gray-300'">Bekor qilish</button>
                    <button type="submit" class="px-4 py-2 bg-blue text-white font-bold rounded-lg">Saqlash</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import {ref, defineProps, defineEmits, watch} from "vue";
import axios from "axios";
import {useI18n} from "vue-i18n";

const { t } = useI18n();

const props = defineProps({
    isOpen: Boolean,
    userData: Object,
    isDarkMode: Boolean
});

const emit = defineEmits(["close", "save"]);

const form = ref({
    fullname: "",
    username: "",
    profilePhoto: "",
});

const defaultAvatar = "https://avatar.iran.liara.run/public";
const previewImage = ref(defaultAvatar);

watch(
    () => props.userData,
    (newUserData) => {
        if (newUserData) {
            form.value.fullname = newUserData.fullname || "";
            form.value.username = newUserData.username || "";
            previewImage.value = newUserData.profilePhoto?.contentUrl || defaultAvatar;
            form.value.profilePhoto = newUserData.profilePhoto?.["@id"] || "";
        }
    },
    {immediate: true}
);

const uploadImage = async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append("file", file);

    try {
        const response = await axios.post(
            "http://localhost:8505/api/media_objects",
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            }
        );

        form.value.profilePhoto = response.data["@id"];
        previewImage.value = `http://localhost:8505${response.data.contentUrl}`;
    } catch (error) {
        console.error("Rasm yuklashda xatolik:", error);
    }
};

const saveChanges = async () => {
    try {
        const requestData = {
            fullname: form.value.fullname,
            username: form.value.username,
        };

        if (form.value.profilePhoto) {
            requestData.profilePhoto = form.value.profilePhoto;
        }

        console.log("📤 Yuborilayotgan ma’lumot:", requestData);

        const response = await axios.patch(
            `http://localhost:8505/api/users/update`,
            requestData,
            {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            }
        );

        console.log("✅ Foydalanuvchi yangilandi:", response.data);

        emit("save", response.data);
        closeModal();
    } catch (error) {
        console.error("❌ Xatolik yuz berdi:", error.response?.data || error);
    }
};

const closeModal = () => {
    emit("close");
};

</script>


