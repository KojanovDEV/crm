<template>
    <div v-if="visible" class="toast-message">
        <p>{{ message }}</p>
    </div>
</template>

<script setup>
import {computed} from "vue";
import {useI18n} from "vue-i18n";

const { t } = useI18n();

const props = defineProps({
    visible: {
        type: Boolean,
        default: false,
    },
    activeMenu: {
        type: String,
        required: true,
    },
});

const message = computed(() => {
    switch (props.activeMenu) {
        case 'users':
            return t ("user_added");
        case 'company':
            return t ("company_added");
        case 'customer':
            return t ("customer_added");
        default:
            return '';
    }
});
</script>

<style scoped>
.toast-message {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: green;
    color: white;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}
</style>
