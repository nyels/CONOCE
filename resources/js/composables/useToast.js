// resources/js/composables/useToast.js
import { inject } from 'vue';

/**
 * Composable for showing toast notifications
 * Must be used within a ToastContainer component
 */
export function useToast() {
    const toast = inject('toast', null);

    if (!toast) {
        // Fallback if not inside ToastContainer
        return {
            success: (msg) => console.log('✓', msg),
            error: (msg) => console.error('✕', msg),
            warning: (msg) => console.warn('!', msg),
            info: (msg) => console.info('ℹ', msg)
        };
    }

    return {
        success: (message, duration = 3000) => toast(message, 'success', duration),
        error: (message, duration = 4000) => toast(message, 'error', duration),
        warning: (message, duration = 3500) => toast(message, 'warning', duration),
        info: (message, duration = 3000) => toast(message, 'info', duration)
    };
}
