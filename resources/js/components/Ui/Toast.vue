<!-- resources/js/Components/Ui/Toast.vue -->
<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    message: { type: String, required: true },
    type: { type: String, default: 'success' }, // success, error, warning, info
    duration: { type: Number, default: 3000 }
});

const emit = defineEmits(['close']);

const isVisible = ref(true);

const icons = {
    success: '✓',
    error: '✕',
    warning: '!',
    info: 'ℹ'
};

onMounted(() => {
    setTimeout(() => {
        isVisible.value = false;
        setTimeout(() => emit('close'), 300);
    }, props.duration);
});
</script>

<template>
    <Transition name="toast">
        <div v-if="isVisible" class="toast" :class="`toast--${type}`">
            <span class="toast__icon">{{ icons[type] }}</span>
            <span class="toast__message">{{ message }}</span>
            <button class="toast__close" @click="isVisible = false; $emit('close')">×</button>
        </div>
    </Transition>
</template>

<style scoped>
.toast {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    border-radius: 12px;
    font-size: 0.9375rem;
    font-weight: 500;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    min-width: 280px;
    max-width: 400px;
}

.toast--success {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
}

.toast--error {
    background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
    color: white;
}

.toast--warning {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    color: white;
}

.toast--info {
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    color: white;
}

.toast__icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    font-size: 0.75rem;
    font-weight: 700;
    flex-shrink: 0;
}

.toast__message {
    flex: 1;
}

.toast__close {
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0;
    line-height: 1;
}

.toast__close:hover {
    color: white;
}

/* Animation */
.toast-enter-active, .toast-leave-active {
    transition: all 0.3s ease;
}
.toast-enter-from {
    opacity: 0;
    transform: translateX(100%);
}
.toast-leave-to {
    opacity: 0;
    transform: translateX(100%);
}

/* ===== RESPONSIVE — FASE 1 ===== */
@media (max-width: 640px) {
    .toast {
        min-width: auto;
        width: 100%;
        max-width: none;
    }
}
</style>
