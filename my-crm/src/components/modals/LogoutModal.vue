<template>
    <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-80">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-200 text-center">
                Tizimdan chiqishni amalga oshirmoqchimisiz?
            </h2>
            <div class="mt-4 flex justify-center space-x-4">
                <button @click="logout" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-400 dark:hover:bg-green-600">
                    Ha
                </button>
                <button @click="closeModal" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-400 dark:hover:bg-red-600">
                    Yo'q
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        isOpen: Boolean
    },
    methods: {
        closeModal() {
            this.$emit('close');
        },
        logout() {
            localStorage.removeItem('token');
            this.$router.push('/login');
            this.$emit('close');
        }
    }
};
</script>

<style scoped>
</style>
