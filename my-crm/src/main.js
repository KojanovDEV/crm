import { createApp } from 'vue';
import './style.css';
import App from './App.vue';
import router from "@/plugins/router.js";
import { createPinia } from "pinia";
import api from "@/plugins/vuex/axios.js";
import i18n from "@/plugins/i118n.js";

const pinia = createPinia();

const app = createApp(App);
app.use(i18n)
app.use(pinia);
app.use(router);
app.mount('#app');
