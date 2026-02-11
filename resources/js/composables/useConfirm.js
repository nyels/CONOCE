// resources/js/composables/useConfirm.js
import { ref } from 'vue';
import Swal from 'sweetalert2';

// Z-index mayor que CrudModal (9998) para que SweetAlert2 aparezca encima
const SWAL_Z_INDEX = 99999;

/**
 * Composable for confirmation dialogs using SweetAlert2
 * Mantiene compatibilidad con API existente + añade métodos SweetAlert2
 */
export function useConfirm() {
    // Mantener estado reactivo para compatibilidad con ConfirmDialog component
    const isOpen = ref(false);
    const config = ref({
        title: '¿Estás seguro?',
        message: 'Esta acción no se puede deshacer.',
        confirmText: 'Confirmar',
        cancelText: 'Cancelar',
        type: 'danger'
    });

    let resolvePromise = null;

    // Método usando SweetAlert2 directamente (preferido)
    const confirmDelete = async (itemName = 'este registro') => {
        const result = await Swal.fire({
            title: '¿Eliminar registro?',
            html: `Se eliminará <strong>${itemName}</strong>.<br>Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            didOpen: () => {
                // Forzar z-index alto para aparecer sobre modales
                const container = document.querySelector('.swal2-container');
                if (container) container.style.zIndex = SWAL_Z_INDEX;
            }
        });

        return result.isConfirmed;
    };

    // Confirmación genérica con SweetAlert2
    const confirm = async (options = {}) => {
        const result = await Swal.fire({
            title: options.title || '¿Estás seguro?',
            text: options.message || 'Esta acción no se puede deshacer.',
            icon: options.type === 'danger' ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: options.type === 'danger' ? '#DC2626' : '#7B2D3B',
            cancelButtonColor: '#6B7280',
            confirmButtonText: options.confirmText || 'Confirmar',
            cancelButtonText: options.cancelText || 'Cancelar',
            reverseButtons: true,
            didOpen: () => {
                // Forzar z-index alto para aparecer sobre modales
                const container = document.querySelector('.swal2-container');
                if (container) container.style.zIndex = SWAL_Z_INDEX;
            }
        });

        return result.isConfirmed;
    };

    // Métodos legacy para compatibilidad con ConfirmDialog component
    const onConfirm = () => {
        isOpen.value = false;
        if (resolvePromise) resolvePromise(true);
    };

    const onCancel = () => {
        isOpen.value = false;
        if (resolvePromise) resolvePromise(false);
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
