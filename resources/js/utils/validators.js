/**
 * Validadores para datos mexicanos
 * Uso: import { validatePhone, validateRFC, ... } from '@/utils/validators'
 */

/**
 * Valida teléfono mexicano (10 dígitos)
 * @param {string} value - Número de teléfono
 * @returns {boolean|string} - true si es válido, mensaje de error si no
 */
export function validatePhone(value) {
    if (!value) return true; // Campo opcional

    const cleaned = value.replace(/[\s\-\(\)\.]+/g, '');

    if (!/^[0-9]{10}$/.test(cleaned)) {
        return 'Debe tener exactamente 10 dígitos';
    }

    if (/^(\d)\1{9}$/.test(cleaned)) {
        return 'No puede ser un número repetido';
    }

    const firstDigit = cleaned.charAt(0);
    if (firstDigit === '0' || firstDigit === '1') {
        return 'Debe iniciar con un código de área válido (2-9)';
    }

    return true;
}

/**
 * Formatea teléfono mientras se escribe (999 123 4567)
 * @param {string} value - Valor del input
 * @returns {string} - Valor formateado
 */
export function formatPhone(value) {
    const cleaned = value.replace(/\D/g, '').slice(0, 10);

    if (cleaned.length >= 6) {
        return `${cleaned.slice(0, 3)} ${cleaned.slice(3, 6)} ${cleaned.slice(6)}`;
    } else if (cleaned.length >= 3) {
        return `${cleaned.slice(0, 3)} ${cleaned.slice(3)}`;
    }

    return cleaned;
}

/**
 * Valida código postal mexicano (5 dígitos)
 * @param {string} value - Código postal
 * @returns {boolean|string} - true si es válido, mensaje de error si no
 */
export function validateZipCode(value) {
    if (!value) return true; // Campo opcional

    const cleaned = value.trim();

    if (!/^[0-9]{5}$/.test(cleaned)) {
        return 'Debe tener exactamente 5 dígitos';
    }

    const zipInt = parseInt(cleaned, 10);
    if (zipInt < 1000 || zipInt > 99999) {
        return 'Código postal fuera de rango válido';
    }

    return true;
}

/**
 * Valida RFC mexicano
 * @param {string} value - RFC
 * @param {boolean} allowGeneric - Permitir RFCs genéricos (XAXX010101000)
 * @returns {boolean|string} - true si es válido, mensaje de error si no
 */
export function validateRFC(value, allowGeneric = true) {
    if (!value) return true; // Campo opcional

    const rfc = value.toUpperCase().trim();

    // RFCs genéricos
    const genericRFCs = ['XAXX010101000', 'XEXX010101000'];
    if (allowGeneric && genericRFCs.includes(rfc)) {
        return true;
    }

    // Longitud válida
    if (rfc.length !== 12 && rfc.length !== 13) {
        return 'Debe tener 12 caracteres (moral) o 13 (física)';
    }

    // Patrón persona física (13)
    const patternFisica = /^[A-ZÑ&]{4}[0-9]{6}[A-Z0-9]{3}$/;
    // Patrón persona moral (12)
    const patternMoral = /^[A-ZÑ&]{3}[0-9]{6}[A-Z0-9]{3}$/;

    if (rfc.length === 13 && !patternFisica.test(rfc)) {
        return 'Formato inválido para persona física';
    }

    if (rfc.length === 12 && !patternMoral.test(rfc)) {
        return 'Formato inválido para persona moral';
    }

    // Validar fecha
    const dateStart = rfc.length === 13 ? 4 : 3;
    const year = parseInt(rfc.substring(dateStart, dateStart + 2), 10);
    const month = parseInt(rfc.substring(dateStart + 2, dateStart + 4), 10);
    const day = parseInt(rfc.substring(dateStart + 4, dateStart + 6), 10);

    if (month < 1 || month > 12) {
        return 'El RFC contiene un mes inválido';
    }

    if (day < 1 || day > 31) {
        return 'El RFC contiene un día inválido';
    }

    // Validar fecha real
    const fullYear = year <= 30 ? 2000 + year : 1900 + year;
    const testDate = new Date(fullYear, month - 1, day);
    if (testDate.getMonth() !== month - 1 || testDate.getDate() !== day) {
        return 'El RFC contiene una fecha inválida';
    }

    return true;
}

/**
 * Formatea RFC mientras se escribe (mayúsculas)
 * @param {string} value - Valor del input
 * @returns {string} - Valor formateado
 */
export function formatRFC(value) {
    return value.toUpperCase().replace(/[^A-ZÑ&0-9]/g, '').slice(0, 13);
}

/**
 * Valida CURP mexicana
 * @param {string} value - CURP
 * @returns {boolean|string} - true si es válido, mensaje de error si no
 */
export function validateCURP(value) {
    if (!value) return true; // Campo opcional

    const curp = value.toUpperCase().trim();

    if (curp.length !== 18) {
        return 'Debe tener exactamente 18 caracteres';
    }

    // Patrón general
    const pattern = /^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[A-Z0-9][0-9]$/;
    if (!pattern.test(curp)) {
        return 'Formato de CURP inválido';
    }

    // Validar fecha de nacimiento
    const year = parseInt(curp.substring(4, 6), 10);
    const month = parseInt(curp.substring(6, 8), 10);
    const day = parseInt(curp.substring(8, 10), 10);

    if (month < 1 || month > 12) {
        return 'CURP contiene un mes inválido';
    }

    if (day < 1 || day > 31) {
        return 'CURP contiene un día inválido';
    }

    // Validar entidad federativa
    const entidades = [
        'AS', 'BC', 'BS', 'CC', 'CL', 'CM', 'CS', 'CH', 'DF', 'DG',
        'GT', 'GR', 'HG', 'JC', 'MC', 'MN', 'MS', 'NT', 'NL', 'OC',
        'PL', 'QT', 'QR', 'SP', 'SL', 'SR', 'TC', 'TS', 'TL', 'VZ',
        'YN', 'ZS', 'NE'
    ];
    const entidad = curp.substring(11, 13);
    if (!entidades.includes(entidad)) {
        return 'CURP contiene entidad federativa inválida';
    }

    return true;
}

/**
 * Formatea CURP mientras se escribe (mayúsculas)
 * @param {string} value - Valor del input
 * @returns {string} - Valor formateado
 */
export function formatCURP(value) {
    return value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 18);
}

/**
 * Valida email
 * @param {string} value - Email
 * @returns {boolean|string} - true si es válido, mensaje de error si no
 */
export function validateEmail(value) {
    if (!value) return true; // Campo opcional

    const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!pattern.test(value)) {
        return 'Formato de email inválido';
    }

    return true;
}

/**
 * Valida año de vehículo
 * @param {number|string} value - Año
 * @returns {boolean|string} - true si es válido, mensaje de error si no
 */
export function validateVehicleYear(value) {
    if (!value) return 'El año es obligatorio';

    const year = parseInt(value, 10);
    const currentYear = new Date().getFullYear();

    if (isNaN(year)) {
        return 'Debe ser un número válido';
    }

    if (year < 1990) {
        return 'El año no puede ser menor a 1990';
    }

    if (year > currentYear + 1) {
        return `El año no puede ser mayor a ${currentYear + 1}`;
    }

    return true;
}

/**
 * Valida valor monetario
 * @param {number|string} value - Valor
 * @param {number} min - Valor mínimo (default: 0)
 * @param {number} max - Valor máximo (default: 99999999)
 * @returns {boolean|string} - true si es válido, mensaje de error si no
 */
export function validateMoney(value, min = 0, max = 99999999) {
    if (!value && value !== 0) return true; // Campo opcional

    const num = parseFloat(value);

    if (isNaN(num)) {
        return 'Debe ser un número válido';
    }

    if (num < min) {
        return `El valor mínimo es ${formatMoney(min)}`;
    }

    if (num > max) {
        return `El valor máximo es ${formatMoney(max)}`;
    }

    return true;
}

/**
 * Formatea valor como moneda mexicana
 * @param {number} value - Valor numérico
 * @returns {string} - Valor formateado ($1,234.56)
 */
export function formatMoney(value) {
    if (value === null || value === undefined || isNaN(value)) {
        return '$0.00';
    }

    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
    }).format(value);
}

/**
 * Composable para validación en tiempo real
 * Uso en Vue 3:
 *
 * const { errors, validate, isValid } = useValidation({
 *     phone: validatePhone,
 *     rfc: validateRFC,
 * });
 */
export function useValidation(rules) {
    const errors = {};
    Object.keys(rules).forEach(key => {
        errors[key] = null;
    });

    function validate(field, value) {
        const rule = rules[field];
        if (rule) {
            const result = rule(value);
            errors[field] = result === true ? null : result;
            return result === true;
        }
        return true;
    }

    function validateAll(data) {
        let allValid = true;
        Object.keys(rules).forEach(field => {
            if (!validate(field, data[field])) {
                allValid = false;
            }
        });
        return allValid;
    }

    function isValid(field) {
        return errors[field] === null;
    }

    function clearErrors() {
        Object.keys(errors).forEach(key => {
            errors[key] = null;
        });
    }

    return {
        errors,
        validate,
        validateAll,
        isValid,
        clearErrors,
    };
}

export default {
    validatePhone,
    formatPhone,
    validateZipCode,
    validateRFC,
    formatRFC,
    validateCURP,
    formatCURP,
    validateEmail,
    validateVehicleYear,
    validateMoney,
    formatMoney,
    useValidation,
};
