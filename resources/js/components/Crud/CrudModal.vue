<!-- resources/js/Components/Crud/CrudModal.vue -->
<script setup>
import { ref, watch, computed } from 'vue';

const props = defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, default: 'Nuevo Registro' },
    size: { type: String, default: 'md' }, // sm, md, lg, xl
    loading: { type: Boolean, default: false }
});

const emit = defineEmits(['close', 'submit']);

const isVisible = ref(props.show);

watch(() => props.show, (val) => {
    isVisible.value = val;
    if (val) document.body.style.overflow = 'hidden';
    else document.body.style.overflow = '';
});

const close = () => {
    if (!props.loading) {
        emit('close');
    }
};

const sizeClass = computed(() => `modal-content--${props.size}`);
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="isVisible" class="modal-overlay" @click.self="close">
                <div class="modal-content" :class="sizeClass">
                    <!-- Header -->
                    <div class="modal-header">
                        <h3 class="modal-title">{{ title }}</h3>
                        <button class="modal-close" @click="close" :disabled="loading">
                            ×
                        </button>
                    </div>
                    
                    <!-- Body -->
                    <div class="modal-body">
                        <slot />
                    </div>
                    
                    <!-- Footer -->
                    <div class="modal-footer">
                        <slot name="footer">
                            <button class="btn btn--secondary" @click="close" :disabled="loading">
                                Cancelar
                            </button>
                            <button class="btn btn--primary" @click="$emit('submit')" :disabled="loading">
                                <span v-if="loading" class="btn-spinner"></span>
                                {{ loading ? 'Guardando...' : 'Guardar' }}
                            </button>
                        </slot>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: flex-start;
    justify-content: center;
    z-index: 9998;
    padding: 2rem 1rem;
    overflow-y: auto;
}

.modal-content {
    background: white;
    border-radius: 16px;
    width: 100%;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.2);
    margin: auto 0;
}

.modal-content--sm { max-width: 400px; }
.modal-content--md { max-width: 560px; }
.modal-content--lg { max-width: 720px; }
.modal-content--xl { max-width: 960px; }

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #F3F4F6;
}

.modal-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.modal-close {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: #F3F4F6;
    border-radius: 8px;
    font-size: 1.25rem;
    color: #6B7280;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-close:hover:not(:disabled) {
    background: #E5E7EB;
    color: #111827;
}

.modal-close:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.modal-body {
    padding: 1.5rem;
    max-height: 60vh;
    overflow-y: auto;
}

.modal-footer {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
    padding: 1.25rem 1.5rem;
    border-top: 1px solid #F3F4F6;
    background: #F9FAFB;
    border-radius: 0 0 16px 16px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.btn--secondary {
    background: white;
    color: #4B5563;
    border: 1px solid #E5E7EB;
}

.btn--secondary:hover:not(:disabled) {
    background: #F9FAFB;
}

.btn--primary {
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: white;
}

.btn--primary:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(123, 45, 59, 0.25);
}

.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-spinner {
    width: 14px;
    height: 14px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Animations */
.modal-enter-active, .modal-leave-active {
    transition: opacity 0.2s ease;
}
.modal-enter-active .modal-content,
.modal-leave-active .modal-content {
    transition: transform 0.2s ease;
}
.modal-enter-from, .modal-leave-to {
    opacity: 0;
}
.modal-enter-from .modal-content {
    transform: scale(0.95) translateY(-20px);
}

/* Responsive */
@media (max-width: 640px) {
    .modal-overlay {
        padding: 1rem;
        align-items: flex-end;
    }
    
    .modal-content {
        border-radius: 20px 20px 0 0;
        max-height: 90vh;
    }
    
    .modal-body {
        max-height: 50vh;
    }
}
</style>
