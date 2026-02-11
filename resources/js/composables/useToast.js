// resources/js/composables/useToast.js
import Swal from 'sweetalert2';

// Z-index mayor que CrudModal (9998) para que SweetAlert2 aparezca encima
const SWAL_Z_INDEX = 99999;

/**
 * Composable for showing toast notifications using SweetAlert2
 *
 * Mensajes de éxito: específicos por acción
 * Mensajes de error: genéricos sin detalles técnicos
 */
export function useToast() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            container: 'swal-high-z'
        },
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
            // Forzar z-index alto para aparecer sobre modales
            const container = document.querySelector('.swal2-container');
            if (container) container.style.zIndex = SWAL_Z_INDEX;
        }
    });

    return {
        success: (message = 'Operación exitosa', duration = 3000) => {
            return Toast.fire({
                icon: 'success',
                title: message,
                timer: duration
            });
        },

        error: (message = 'Hubo un error en el servidor, contacte a soporte', duration = 4000) => {
            // Para errores usar modal prominente en lugar de toast
            return Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#7B2D3B',
                didOpen: () => {
                    // Forzar z-index alto para aparecer sobre modales
                    const container = document.querySelector('.swal2-container');
                    if (container) container.style.zIndex = SWAL_Z_INDEX;
                }
            });
        },

        warning: (message, duration = 3500) => {
            return Toast.fire({
                icon: 'warning',
                title: message,
                timer: duration
            });
        },

        info: (message, duration = 3000) => {
            return Toast.fire({
                icon: 'info',
                title: message,
                timer: duration
            });
        }
    };
}
