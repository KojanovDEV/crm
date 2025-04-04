import { defineStore } from "pinia";
import axios from "axios";

export const useAuthorization = defineStore("authorization", () => {
    async function userAuth(data) {
        try {
            const response = await axios.post("http://localhost:8505/api/users/auth", data);
            if (response.data?.token) {
                console.log("Token olindi:", response.data.token);
                localStorage.setItem("token", response.data.token);
                return response.data.token;
            } else {
                throw new Error("Server token qaytarmadi");
            }
        } catch (error) {
            console.error("Token olishda xatolik:", error?.response?.data || error.message || "Noma'lum xato");
            throw error?.response?.data || new Error("Noma'lum server xatosi");
        }
    }

    return { userAuth };
});
