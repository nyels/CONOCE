<!-- resources/js/Components/Ui/ToastContainer.vue -->
<script setup>
import { ref, provide } from 'vue';
import Toast from './Toast.vue';

const toasts = ref([]);
let toastId = 0;

const addToast = (message, type = 'success', duration = 3000) => {
    const id = ++toastId;
    toasts.value.push({ id, message, type, duration });
};

const removeToast = (id) => {
    toasts.value = toasts.value.filter(t => t.id !== id);
};

// Provide toast function to all children
provide('toast', addToast);

// Expose for direct use
defineExpose({ addToast });
</script>

<template>
    <Teleport to="body">
        <div class="toast-container">
            <Toast
                v-for="toast in toasts"
                :key="toast.id"
                :message="toast.message"
                :type="toast.type"
                :duration="toast.duration"
                @close="removeToast(toast.id)"
            />
        </div>
    </Teleport>
    <slot />
</template>

<style scoped>
.toast-container {
    position: fixed;
    top: 1.5rem;
    right: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    z-index: 10000;
}

@media (max-width: 640px) {
    .toast-container {
        top: 1rem;
        right: 1rem;
        left: 1rem;
    }
}
</style>
