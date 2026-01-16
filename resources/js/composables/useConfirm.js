// resources/js/composables/useConfirm.js
import { ref } from 'vue';

/**
 * Composable for confirmation dialogs
 * Returns reactive state and methods for ConfirmDialog component
 */
export function useConfirm() {
    const isOpen = ref(false);
    const config = ref({
        title: '¿Estás seguro?',
        message: 'Esta acción no se puede deshacer.',
        confirmText: 'Confirmar',
        cancelText: 'Cancelar',
        type: 'danger'
    });

    let resolvePromise = null;

    const confirm = (options = {}) => {
        config.value = { ...config.value, ...options };
        isOpen.value = true;

        return new Promise((resolve) => {
            resolvePromise = resolve;
        });
    };

    const onConfirm = () => {
        isOpen.value = false;
        if (resolvePromise) resolvePromise(true);
    };

    const onCancel = () => {
        isOpen.value = false;
        if (resolvePromise) resolvePromise(false);
    };

    // Convenience methods
    const confirmDelete = (itemName = 'este registro') => {
        return confirm({
            title: '¿Eliminar registro?',
            message: `Se eliminará ${itemName}. Esta acción no se puede deshacer.`,
            confirmText: 'Sí, eliminar',
            cancelText: 'Cancelar',
            type: 'danger'
        });
    };

    return {
        isOpen,
        config,
        confirm,
        confirmDelete,
        onConfirm,
        onCancel
    };
}
