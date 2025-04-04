<script setup>
import { ref, computed, onMounted, watch} from "vue";
import api from "@/plugins/vuex/axios.js";
import { useRouter } from "vue-router";
import DataTable from "@/components/DataTable.vue";
import SideBar from "@/components/layouts/Sidebar.vue";
import AddModal from "@/components/modals/AddModal.vue";
import SearchFilter from "@/components/filter/SearchFilter.vue";
import ToastMessage from "@/components/layouts/ToastMessage.vue";
import Pagination from "@/components/layouts/Pagination.vue";
import { useI18n } from "vue-i18n"
const { t } = useI18n();
const router = useRouter();
const isOpen = ref(true);
const isOpenModal = ref(false);
const activeMenu = ref("users");
const currentPage = ref(1);
const itemsPerPage = ref(10);
const totalItems = ref(0);
const totalPages = ref(1);
const companies = ref([])
const customers = ref([]);


onMounted(async () => {
    try {
        const token = localStorage.getItem('token');
        if (!token) {
            throw new Error("Token topilmadi. Iltimos, tizimga kiring.");
        }

        let currentPage = 1;
        let lastPage = 1;

        customers.value = []; // Avvalgi ma’lumotlarni tozalaymiz

        while (currentPage <= lastPage) {
            const response = await fetch(`http://localhost:8505/api/users/`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`Server xatosi: ${response.status}`);
            }

            const result = await response.json();

            if (!result.data || !Array.isArray(result.data)) {
                throw new Error("Kutilmagan format: data massiv emas.");
            }

            customers.value = [...customers.value, ...result.data]; // Yangi mijozlarni qo‘shamiz

            if (result.meta) {
                lastPage = result.meta.last_page;
                currentPage++;
            } else {
                break; // Agar pagination bo‘lmasa, tsiklni to‘xtatamiz
            }
        }
    } catch (error) {
        console.error("Mijozlarni yuklashda xatolik:", error);
    }
});

// Kompaniyalarning mijozlari sonini hisoblash
const customerCounts = computed(() => {
    if (!Array.isArray(customers.value)) {
        return {}; // Agar customers noto‘g‘ri formatda bo‘lsa, bo‘sh obyekt qaytaramiz
    }

    return customers.value.reduce((acc, customer) => {
        if (customer.workplace?.id) { // Kompaniya ID bo‘lsa
            const companyId = customer.workplace.id;
            acc[companyId] = (acc[companyId] || 0) + 1;
        }
        return acc;
    }, {});
});

const token = localStorage.getItem("token");
if (!token) {
    router.push("/login");
} else {
    api.defaults.headers.common["Authorization"] = `Bearer ${token}`;
}

const menuItems = [
    {name: "users", label: t("users"), icon: "user"},
    {name: "company", label: t("companies"), icon: "company"},
    {name: "customer", label: t("customers"), icon: "customer"}
];

const columnsMap = {
    users: [
        {key: "fullname", label: "Foydalanuvchi", icon: "search", placeholder: "Foydalanuvchi"},
        {key: "email", label: "Email", icon: "search", placeholder: "Email"},
        {key: "phone", label: "Telefon raqami", icon: "search", placeholder: "Telefon raqami"},
        {key: "lastActiveAt", label: "So‘nggi faollik", icon: "search", placeholder: "So‘nggi faollik"}
    ],
    company: [
        {key: "companyName", label: "Kompaniya Nomi", icon: "search", placeholder: "Kompaniya Nomi"},
        {key: "email", label: "Email", icon: "search", placeholder: "Email"},
        {key: "address", label: "Manzil", icon: "search", placeholder: "Manzil"},
        {key: "employees", label: "Tizimdagi hodimlar", icon: "search", placeholder: "Tizimdagi hodimlar"}
    ],
    customer: [
        {key: "fullname", label: "Mijozlar", icon: "search", placeholder: "Ismi sharifi"},
        {key: "email", label: "Email", icon: "search", placeholder: "Email"},
        {key: "workplace", label: "Ishlash joyi", icon: "search", placeholder: "Ishlash joyi"},
        {key: "lastActiveAt", label: "So‘nggi faollik", icon: "search", placeholder: "So‘nggi faollik"}
    ]
};
watch([currentPage, totalPages], () => {
    console.log("Joriy sahifa:", currentPage.value);
    console.log("Jami sahifalar:", totalPages.value);
});
watch(currentPage, () => {
    console.log("Sahifa o'zgardi:", currentPage.value);
    fetchData();
});
watch(activeMenu, () => {
    currentPage.value = 1;
});
const dataMap = ref({ users: [], company: [], customer: [] });

const fetchData = async () => {
    try {
        let endpoint = "";
        if (activeMenu.value === "users") {
            endpoint = "/user/generals";
        } else if (activeMenu.value === "company") {
            endpoint = "/companies";
        } else if (activeMenu.value === "customer") {
            endpoint = "/users/clients";
        }

        const response = await api.get(endpoint, {
            params: {
                page: currentPage.value,
                itemsPerPage: itemsPerPage.value
            }
        });
        console.log("API dan kelgan javob:", response.data);

        // API javobini tekshirish
        const responseData = Array.isArray(response.data)
            ? response.data
            : response.data?.data || response.data?.member || response.data?.company || [];

        // Pagination metadata
                const paginationMeta = response.data?.meta || response.data?.pagination || {};

        // `totalItems` ni to‘g‘ri olish
                totalItems.value = paginationMeta.total
                    || response.data?.totalItems  // Company uchun
                    || response.data?.total
                    || 0;

        // `itemsPerPage` ni faqat 1-sahifada aniqlash, boshqa sahifalarda o‘zgartirmaslik
                if (currentPage.value === 1) {
                    itemsPerPage.value = paginationMeta.per_page
                        || response.data?.itemsPerPage
                        || responseData.length  // Faqat 1-sahifada hisoblash
                        || 10; // Default 10
                }

        // Sahifalar sonini hisoblash
                totalPages.value = totalItems.value > 0
                    ? Math.ceil(totalItems.value / itemsPerPage.value)
                    : 1;

        console.log(`Jami elementlar: ${totalItems.value}, Sahifalar soni: ${totalPages.value}`);

        dataMap.value[activeMenu.value] = await Promise.all(
            responseData.map(async (item) => {
                if (activeMenu.value === "users") {
                    return {
                        id: item.id,
                        profilePhoto: item.profilePhoto?.contentUrl || "default-avatar.jpg",
                        fullname: item.fullname || "Noma'lum",
                        email: item.emailOwner?.email || item.email || "Noma'lum",
                        phone: item.phoneOwner?.phoneNumber || item.mobile || "Noma'lum",
                        lastActiveAt: item.lastActiveAt || "Noma'lum"
                    };
                }

                if (activeMenu.value === "company") {
                    return {
                        id: item.id,
                        companyName: item.companyName || "Noma'lum",
                        email: item.email?.email || "Noma'lum",
                        phone: item.phoneOwner?.phoneNumber || item.mobile || "Noma'lum",
                        address: item.address || "Noma'lum",
                        employees: customerCounts.value[item.id] || 0 // Kompaniyaga tegishli mijozlar soni
                    };
                }

                if (activeMenu.value === "customer") {
                    return {
                        id: item.id,
                        profilePhoto: item.profilePhoto?.contentUrl || "default-avatar.jpg",
                        fullname: item.fullname || "Noma'lum",
                        email: item.emailOwner?.email || item.email || "Noma'lum",
                        workplace: item.workplace.companyName || "Noma'lum",
                        lastActiveAt: item.lastActiveAt || "Noma'lum"
                    };
                }
            })
        );
    } catch (error) {
        console.error("Xatolik:", error);
        if (error.response?.status === 401) {
            localStorage.removeItem("token");
            router.push("/login");
        }
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
        console.log("Oldingi sahifaga o'tildi:", currentPage.value);
        fetchData();
    }
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
        console.log("Keyingi sahifaga o'tildi:", currentPage.value);
        fetchData();
    }
};

const userData = ref(null);

const fetchUserData = async () => {
    try {
        const response = await api.get("/user/me");
        console.log("Foydalanuvchi ma'lumotlari:", response.data);
        userData.value = response.data;
    } catch (error) {
        console.error("Foydalanuvchi ma'lumotlarini olishda xatolik:", error);
    }
};

onMounted(fetchUserData);
onMounted(fetchData);

watch(activeMenu, () => {
    fetchData();
});

const tableColumns = computed(() => columnsMap[activeMenu.value]);
const tableData = computed(() => dataMap.value[activeMenu.value]);
watch(tableData, (newValue) => {
    console.log("Jadvalga yuklangan ma'lumotlar:", newValue);
});
const modalTitle = computed(() => ({
    users: "Foydalanuvchi qo‘shish",
    company: "Kompaniya qo‘shish",
    customer: "Mijoz qo‘shish"
})[activeMenu.value]);

const modalFields = computed(() => {
    if (activeMenu.value === "users") {
        return [
            { id: "fullname", label: "F.I.Sh.", model: "fullname", type: "text", required: true },
            { id: "password", label: "Parol", model: "password", type: "password", required: true },
            { id: "email", label: "E-pochta", model: "email", type: "email", required: true },
            { id: "phoneNumber", label: "Telefon raqam", model: "phoneNumber", type: "text", required: true },
            { id: "username", label: "Tizimga kirish uchun login", model: "username", type: "text", required: true }
        ];
    } else if (activeMenu.value === "company") {
        return [
            { id: "companyName", label: "Kompaniya nomi", model: "companyName", type: "text", required: true },
            { id: "email", label: "Email", model: "email", type: "email", required: true },
            { id: "phoneNumber", label: "Telefon", model: "phoneNumber", type: "text", required: true },
            { id: "address", label: "Manzil", model: "address", type: "text", required: true }
        ];
    } else if (activeMenu.value === "customer") {
        return [
            { id: "fullname", label: "F.I.Sh.", model: "fullname", type: "text", required: true },
            { id: "password", label: "Parol", model: "password", type: "password", required: true },
            { id: "email", label: "E-pochta", model: "email", type: "email", required: true },
            { id: "phoneNumber", label: "Telefon raqam", model: "phoneNumber", type: "text", required: true },
            { id: "username", label: "Tizimga kirish uchun login", model: "username", type: "text", required: true },
            {
                id: "workplace",
                label: "Ishlash joyi",
                model: "workplace",
                type: "select",
                required: true,
                options: (companies.value || []).map(company => ({
                    value: company.id,
                    label: company.companyName
                }))
            }
        ];
    }
    return [];
});

const showToast = ref(false);

const handleSubmit = async (data) => {
    try {
        let endpoint = "";
        let payload = {};

        if (activeMenu.value === "users") {
            endpoint = "/users/create";
            payload = {
                fullname: data.fullname,
                username: data.username,
                password: data.password,
                emailOwner: { email: data.email },
                phoneOwner: { phoneNumber: data.phoneNumber }
            };
        } else if (activeMenu.value === "company") {
            endpoint = "/companies";
            payload = {
                companyName: data.companyName,
                email: { email: data.email },
                address: data.address,
                phoneNumber: { phoneNumber: data.phoneNumber }

            };
        } else if (activeMenu.value === "customer") {
            endpoint = "/users/create-client";
            payload = {
                fullname: data.fullname,
                username: data.username,
                password: data.password,
                emailOwner: { email: data.email },
                phoneOwner: { phoneNumber: data.phoneNumber },
                workplace: data.workplace
                    ? (typeof data.workplace === 'object' ? data.workplace.id : data.workplace)
                    : (companies.value.length > 0 ? companies.value[0].id : null)
            };
        }

        console.log("Yuborilayotgan ma'lumot:", payload);

        const response = await api.post(endpoint, payload);
        console.log("Yangi element qo‘shildi:", response.data);

        showToast.value = true;
        setTimeout(() => {
            showToast.value = false;
        }, 5000);

        isOpenModal.value = false;
        fetchData();
    } catch (error) {
        console.error("Ma'lumot qo'shishda xatolik:", error);
    }
};
</script>

<template>
    <div class="h-dvh flex gap-7 bg-gray-100 dark:bg-gray-900">
        <ToastMessage :visible="showToast" :activeMenu="activeMenu" />
        <SideBar
            :isOpen="isOpen"
            :activeMenu="activeMenu"
            :menuItems="menuItems"
            :userData="userData"
            @toggle="isOpen = !isOpen"
            @update:activeMenu="activeMenu = $event"
        />
        <div class="h-full bg-blue-700 dark:bg-blue-800 w-full flex flex-col gap-4 pr-4 overflow-hidden pb-4">
            <div class="flex justify-end px-3 py-2.5 bg-white dark:bg-gray-800 shadow">
                <button
                    @click="isOpenModal = true"
                    class="py-2.5 px-4 bg-blue dark:bg-blue-600 text-white text-sm font-semibold transition rounded"
                >
                    {{ modalTitle }}
                </button>
            </div>
            <AddModal
                v-if="isOpenModal"
                :isOpen="isOpenModal"
                :title="modalTitle"
                :fields="modalFields"
                buttonText="Qo‘shish"
                @close="isOpenModal = false"
                @submit="handleSubmit"
            />
            <SearchFilter :filtersData="columnsMap" :activeMenu="activeMenu"/>
            <DataTable :columns="tableColumns" :data="tableData"/>
            <Pagination
                :currentPage="currentPage"
                :totalPages="totalPages"
                @prev="prevPage"
                @next="nextPage"
                @changePage="(page) => { console.log('Yangi sahifa:', page); currentPage = page; fetchData(); }"
            />
        </div>
    </div>
</template>
<style>
</style>