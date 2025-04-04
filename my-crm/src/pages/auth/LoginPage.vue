<script>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthorization } from "@/plugins/vuex/modules/authorization.js";

export default {
    setup() {
        const router = useRouter();
        const isPasswordVisible = ref(false);
        const authorization = reactive({
            username: "",
            password: ""
        });

        const authService = useAuthorization();

        const auth = async () => {
            try {
                const authService = useAuthorization();
                const token = await authService.userAuth(authorization);
                router.push("/");
            } catch (error) {
                console.error("Authentication failed:", error?.message || JSON.stringify(error) || "Noma'lum xato");
            }
        };

        return {
            isPasswordVisible,
            authorization,
            auth
        };
    }
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div
                class="absolute inset-0 bg-gradient-to-r from-cyan-400 to-sky-500 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl">
            </div>
            <div class="relative px-4 py-10 bg-white dark:bg-gray-800 shadow-lg sm:rounded-3xl sm:p-20">
                <form @submit.prevent="auth">
                    <div class="max-w-md mx-auto">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Tizimga kirish</h1>
                        </div>
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            <div class="py-8 text-base leading-6 space-y-4 text-gray-700 dark:text-gray-300 sm:text-lg sm:leading-7">
                                <div class="relative">
                                    <input autocomplete="off"
                                           v-model="authorization.username"
                                           id="username"
                                           name="username"
                                           type="text"
                                           class="form-control peer placeholder-transparent h-10 w-full border-b-2 border-gray-400 dark:border-gray-600 text-gray-900 dark:text-white bg-transparent focus:outline-none focus:border-rose-600" placeholder="Email address" />
                                    <label for="username"
                                           class="absolute left-0 -top-3.5 text-gray-600 dark:text-gray-400 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 dark:peer-focus:text-gray-400 peer-focus:text-sm">Username</label>
                                </div>
                                <div class="relative">
                                    <input
                                        autocomplete="off"
                                        :type="isPasswordVisible ? 'text' : 'password'"
                                        id="password"
                                        name="password"
                                        v-model="authorization.password"
                                        required
                                        class="form-control peer placeholder-transparent h-10 w-full border-b-2 border-gray-400 dark:border-gray-600 text-gray-900 dark:text-white bg-transparent focus:outline-none focus:border-rose-600" placeholder="Password" />
                                    <label for="password" class="absolute left-0 -top-3.5 text-gray-600 dark:text-gray-400 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 dark:peer-focus:text-gray-400 peer-focus:text-sm">Parol</label>
                                </div>
                                <div class="relative">
                                    <button class="bg-cyan-500 dark:bg-cyan-600 text-white rounded-md px-2 py-1 transition">
                                        Kirish
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style>
</style>
