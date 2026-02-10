<!-- resources/js/Components/Ui/ConfirmDialog.vue -->
<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, default: '¿Estás seguro?' },
    message: { type: String, default: 'Esta acción no se puede deshacer.' },
    confirmText: { type: String, default: 'Sí, eliminar' },
    cancelText: { type: String, default: 'Cancelar' },
    type: { type: String, default: 'danger' } // danger, warning, info
});

const emit = defineEmits(['confirm', 'cancel', 'close']);

const isVisible = ref(props.show);

watch(() => props.show, (val) => {
    isVisible.value = val;
});

const confirm = () => {
    emit('confirm');
    emit('close');
};

const cancel = () => {
    emit('cancel');
    emit('close');
};
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="isVisible" class="confirm-overlay" @click.self="cancel">
                <div class="confirm-dialog" :class="`confirm-dialog--${type}`">
                    <!-- Icon -->
                    <div class="confirm-dialog__icon">
                        <span v-if="type === 'danger'">🗑️</span>
                        <span v-else-if="type === 'warning'">⚠️</span>
                        <span v-else>ℹ️</span>
                    </div>
                    
                    <!-- Content -->
                    <h3 class="confirm-dialog__title">{{ title }}</h3>
                    <p class="confirm-dialog__message">{{ message }}</p>
                    
                    <!-- Actions -->
                    <div class="confirm-dialog__actions">
                        <button class="btn btn--cancel" @click="cancel">{{ cancelText }}</button>
                        <button class="btn btn--confirm" :class="`btn--${type}`" @click="confirm">{{ confirmText }}</button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.confirm-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 1rem;
}

.confirm-dialog {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    max-width: 400px;
    width: 100%;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.confirm-dialog__icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.confirm-dialog__title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.confirm-dialog__message {
    font-size: 0.9375rem;
    color: #6B7280;
    margin: 0 0 1.5rem 0;
}

.confirm-dialog__actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
}

.btn {
    padding: 0.625rem 1.25rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.btn--cancel {
    background: #F3F4F6;
    color: #4B5563;
}

.btn--cancel:hover {
    background: #E5E7EB;
}

.btn--confirm.btn--danger {
    background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
    color: white;
}

.btn--confirm.btn--warning {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    color: white;
}

.btn--confirm.btn--info {
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: white;
}

.btn--confirm:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Transitions */
.modal-enter-active, .modal-leave-active {
    transition: opacity 0.2s ease;
}
.modal-enter-active .confirm-dialog,
.modal-leave-active .confirm-dialog {
    transition: transform 0.2s ease;
}
.modal-enter-from, .modal-leave-to {
    opacity: 0;
}
.modal-enter-from .confirm-dialog {
    transform: scale(0.95);
}

/* ===== RESPONSIVE — CIERRE ENTERPRISE ===== */

/* E-3: Confirm dialog ergonomics */
@media (max-width: 640px) {
    .confirm-dialog {
        padding: 1.5rem;
    }
}

@media (max-width: 375px) {
    .confirm-dialog {
        padding: 1rem;
    }

    .confirm-dialog__actions {
        flex-direction: column;
    }
}
</style>
