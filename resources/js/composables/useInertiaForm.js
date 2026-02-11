// resources/js/composables/useInertiaForm.js
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useToast } from './useToast';

/**
 * Composable para manejar operaciones de formulario Inertia con manejo de errores centralizado
 *
 * Uso:
 * const { submitForm, deleteRecord, processing } = useInertiaForm();
 *
 * // Para crear/editar
 * submitForm({
 *     url: route('admin.staff.store'),
 *     data: { name: 'Test' },
 *     onSuccess: () => { showModal.value = false; },
 *     onValidationError: (errors) => { formErrors.value = errors; },
 *     successMessage: 'Personal creado exitosamente'
 * });
 *
 * // Para eliminar
 * deleteRecord({
 *     url: route('admin.staff.destroy', id),
 *     successMessage: 'Personal eliminado exitosamente'
 * });
 *
 * // En template: <CrudModal :loading="processing" ...>
 */
export function useInertiaForm() {
    const toast = useToast();
    const processing = ref(false);

    /**
     * Enviar formulario con manejo de errores centralizado
     *
     * @param {Object} options
     * @param {string} options.url - URL de destino
     * @param {Object} options.data - Datos del formulario
     * @param {string} options.method - Método HTTP (post, put, patch). Default: 'post'
     * @param {Function} options.onSuccess - Callback al guardar exitosamente (sin errores)
     * @param {Function} options.onValidationError - Callback cuando hay errores de validación
     * @param {string} options.successMessage - Mensaje de éxito a mostrar
     * @param {string} options.errorMessage - Mensaje de error personalizado (opcional)
     */
    const submitForm = ({
        url,
        data,
        method = 'post',
        onSuccess,
        onValidationError,
        successMessage = 'Guardado exitosamente',
        errorMessage = 'Hubo un error en el servidor, contacte a soporte'
    }) => {
        const routerMethod = method === 'put' || method === 'patch' ? 'post' : method;

        // Si es PUT/PATCH, agregar _method al data
        if (method === 'put' || method === 'patch') {
            if (data instanceof FormData) {
                data.append('_method', method.toUpperCase());
            } else {
                data = { ...data, _method: method.toUpperCase() };
            }
        }

        router[routerMethod](url, data, {
            preserveScroll: true,
            preserveState: true,
            onStart: () => {
                processing.value = true;
            },
            onSuccess: (page) => {
                // Verificar si hay errores de validación en la respuesta
                const errors = page.props.errors;
                if (errors && Object.keys(errors).length > 0) {
                    // Hay errores de validación - NO ejecutar onSuccess
                    if (onValidationError) {
                        onValidationError(errors);
                    }
                    // Mostrar mensaje de error específico si existe
                    const errorMsg = errors.server || errors.error || 'Por favor corrija los errores del formulario';
                    toast.error(errorMsg);
                    return;
                }

                // Sin errores - ejecutar callback de éxito
                if (onSuccess) {
                    onSuccess(page);
                }
                toast.success(successMessage);
            },
            onError: (errors) => {
                // Errores del servidor (catch en el backend)
                if (onValidationError) {
                    onValidationError(errors);
                }
                const errorMsg = errors.server || errors.error || errorMessage;
                toast.error(errorMsg);
            },
            onFinish: () => {
                processing.value = false;
            }
        });
    };

    /**
     * Eliminar registro con manejo de errores centralizado
     *
     * @param {Object} options
     * @param {string} options.url - URL de destino (DELETE)
     * @param {Function} options.onSuccess - Callback al eliminar exitosamente
     * @param {Function} options.onError - Callback cuando hay error
     * @param {string} options.successMessage - Mensaje de éxito a mostrar
     */
    const deleteRecord = ({
        url,
        onSuccess,
        onError,
        successMessage = 'Eliminado exitosamente'
    }) => {
        router.delete(url, {
            preserveScroll: true,
            onSuccess: (page) => {
                // Verificar si hay errores de validación
                const errors = page.props.errors;
                if (errors && Object.keys(errors).length > 0) {
                    // Hay errores (ej: "no se puede eliminar porque tiene registros asociados")
                    const errorMsg = errors.error || errors.server || Object.values(errors)[0];
                    toast.error(errorMsg || 'No se pudo eliminar el registro');
                    if (onError) {
                        onError(errors);
                    }
                    return;
                }

                // Eliminado exitosamente
                if (onSuccess) {
                    onSuccess(page);
                }
                toast.success(successMessage);
            },
            onError: (errors) => {
                const errorMsg = errors.error || errors.server || Object.values(errors)[0];
                toast.error(errorMsg || 'Hubo un error al eliminar');
                if (onError) {
                    onError(errors);
                }
            }
        });
    };

    /**
     * Toggle estado activo/inactivo
     *
     * @param {Object} options
     * @param {string} options.url - URL de destino (PATCH)
     * @param {Function} options.onSuccess - Callback al cambiar exitosamente
     * @param {string} options.successMessage - Mensaje de éxito a mostrar
     */
    const toggleActive = ({
        url,
        onSuccess,
        successMessage = 'Estado actualizado'
    }) => {
        router.patch(url, {}, {
            preserveScroll: true,
            onSuccess: (page) => {
                const errors = page.props.errors;
                if (errors && Object.keys(errors).length > 0) {
                    const errorMsg = errors.error || errors.server || Object.values(errors)[0];
                    toast.error(errorMsg || 'No se pudo cambiar el estado');
                    return;
                }

                if (onSuccess) {
                    onSuccess(page);
                }
                toast.success(successMessage);
            },
            onError: (errors) => {
                const errorMsg = errors.error || errors.server || Object.values(errors)[0];
                toast.error(errorMsg || 'Hubo un error al cambiar el estado');
            }
        });
    };

    return {
        processing,
        submitForm,
        deleteRecord,
        toggleActive
    };
}
