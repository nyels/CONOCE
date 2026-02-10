<!-- resources/js/Components/Crud/FormInput.vue -->
<script setup>
import { computed, ref, watch } from 'vue';

const props = defineProps({
    modelValue: { type: [String, Number], default: '' },
    label: { type: String, default: '' },
    type: { type: String, default: 'text' },
    placeholder: { type: String, default: '' },
    error: { type: String, default: '' },
    required: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    // Nuevas props para validación
    maxlength: { type: Number, default: null },
    mask: { type: String, default: null }, // 'phone', 'rfc', 'curp', 'zipcode', 'money'
    validator: { type: Function, default: null }, // Función de validación custom
    validateOnInput: { type: Boolean, default: true }, // Validar mientras escribe
});

const emit = defineEmits(['update:modelValue', 'validation']);

// Error interno de validación en tiempo real
const internalError = ref('');

// Muestra error del servidor o validación local
const displayError = computed(() => props.error || internalError.value);

// Formatters según el tipo de máscara
const formatters = {
    phone: (val) => {
        const cleaned = val.replace(/\D/g, '').slice(0, 10);
        if (cleaned.length >= 6) {
            return `${cleaned.slice(0, 3)} ${cleaned.slice(3, 6)} ${cleaned.slice(6)}`;
        } else if (cleaned.length >= 3) {
            return `${cleaned.slice(0, 3)} ${cleaned.slice(3)}`;
        }
        return cleaned;
    },
    rfc: (val) => val.toUpperCase().replace(/[^A-ZÑ&0-9]/g, '').slice(0, 13),
    curp: (val) => val.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 18),
    zipcode: (val) => val.replace(/\D/g, '').slice(0, 5),
    money: (val) => {
        // Formato: 1,000.00 (comas como separador de miles, punto decimal, 2 decimales)
        // Primero quitamos todo excepto números y punto
        let num = String(val).replace(/[^\d.]/g, '');

        // Separamos parte entera y decimal
        const parts = num.split('.');
        let integerPart = parts[0] || '';
        let decimalPart = parts[1] || '';

        // Limitamos decimales a 2
        decimalPart = decimalPart.slice(0, 2);

        // Formateamos la parte entera con comas cada 3 dígitos
        integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        // Retornamos con o sin decimales según lo que haya escrito
        if (parts.length > 1) {
            return integerPart + '.' + decimalPart;
        }
        return integerPart;
    },
};

// Validators según el tipo de máscara
const validators = {
    phone: (val) => {
        if (!val) return true;
        const cleaned = val.replace(/\D/g, '');
        if (cleaned.length !== 10) return 'El teléfono debe tener exactamente 10 dígitos numéricos.';
        if (/^(\d)\1{9}$/.test(cleaned)) return 'El teléfono no puede ser un número repetido.';
        if (cleaned[0] === '0' || cleaned[0] === '1') return 'El teléfono debe iniciar con código de área válido (2-9).';
        return true;
    },
    rfc: (val) => {
        if (!val) return true;
        const rfc = val.toUpperCase();
        if (rfc.length !== 12 && rfc.length !== 13) return 'El RFC debe tener 12 caracteres (persona moral) o 13 caracteres (persona física).';
        const patternFisica = /^[A-ZÑ&]{4}[0-9]{6}[A-Z0-9]{3}$/;
        const patternMoral = /^[A-ZÑ&]{3}[0-9]{6}[A-Z0-9]{3}$/;
        if (rfc.length === 13 && !patternFisica.test(rfc)) return 'Formato de RFC inválido para persona física.';
        if (rfc.length === 12 && !patternMoral.test(rfc)) return 'Formato de RFC inválido para persona moral.';
        return true;
    },
    curp: (val) => {
        if (!val) return true;
        if (val.length !== 18) return 'La CURP debe tener exactamente 18 caracteres.';
        const pattern = /^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[A-Z0-9][0-9]$/;
        if (!pattern.test(val.toUpperCase())) return 'Formato de CURP inválido.';
        return true;
    },
    zipcode: (val) => {
        if (!val) return true;
        if (!/^[0-9]{5}$/.test(val)) return 'El código postal debe tener exactamente 5 dígitos.';
        return true;
    },
    money: (val) => {
        // Validar formato de moneda
        if (!val) return true;
        // Remover comas para validar el número
        const numStr = String(val).replace(/,/g, '');
        if (isNaN(parseFloat(numStr))) return 'Ingrese un monto válido.';
        return true;
    },
    email: (val) => {
        if (!val) return true;
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!pattern.test(val)) return 'El correo electrónico no tiene un formato válido.';
        return true;
    },
};

// Computed para maxlength real (incluye espacios del formato)
const effectiveMaxlength = computed(() => {
    if (props.maxlength) return props.maxlength;
    const defaults = { phone: 12, rfc: 13, curp: 18, zipcode: 5 };
    return defaults[props.mask] || null;
});

// Computed para el contador visual (dígitos reales sin espacios de formato)
const counterMax = computed(() => {
    if (props.mask === 'phone') return 10;
    return effectiveMaxlength.value;
});

const counterCurrent = computed(() => {
    if (!props.modelValue) return 0;
    if (props.mask === 'phone') return String(props.modelValue).replace(/\D/g, '').length;
    return String(props.modelValue).replace(/\s/g, '').length;
});

// Manejo de input
const handleInput = (event) => {
    let newValue = event.target.value;

    // Aplicar formatter si existe
    if (props.mask && formatters[props.mask]) {
        newValue = formatters[props.mask](newValue);
        event.target.value = newValue;
    }

    emit('update:modelValue', newValue);

    // Validar en tiempo real si está habilitado
    if (props.validateOnInput) {
        validateValue(newValue);
    }
};

// Validar valor
const validateValue = (val) => {
    let result = true;

    // Usar validador custom si existe
    if (props.validator) {
        result = props.validator(val);
    }
    // Usar validador de máscara
    else if (props.mask && validators[props.mask]) {
        result = validators[props.mask](val);
    }
    // Validar email por tipo
    else if (props.type === 'email') {
        result = validators.email(val);
    }

    internalError.value = result === true ? '' : result;
    emit('validation', { valid: result === true, error: internalError.value });
};

// Validar al perder foco
const handleBlur = () => {
    validateValue(props.modelValue);
};

// Computed para clases del input
const inputClasses = computed(() => ({
    'form-input': true,
    'form-input--error': !!displayError.value,
    'form-input--valid': props.modelValue && !displayError.value,
}));
</script>

<template>
    <div class="form-group" :class="{ 'has-error': displayError }">
        <label v-if="label" class="form-label">
            {{ label }}
            <span v-if="required" class="required">*</span>
        </label>
        <div class="input-wrapper">
            <input
                :value="modelValue"
                @input="handleInput"
                @blur="handleBlur"
                :type="type"
                :placeholder="placeholder"
                :disabled="disabled"
                :maxlength="effectiveMaxlength"
                :class="inputClasses"
                autocomplete="off"
            />
            <!-- Indicador de caracteres para campos con límite -->
            <span
                v-if="counterMax && modelValue"
                class="char-counter"
                :class="{ 'char-counter--full': counterCurrent >= counterMax }"
            >
                {{ counterCurrent }}/{{ counterMax }}
            </span>
        </div>
        <span v-if="displayError" class="form-error">{{ displayError }}</span>
    </div>
</template>

<style scoped>
.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.375rem;
}

.required {
    color: #DC2626;
    margin-left: 2px;
}

.input-wrapper {
    position: relative;
}

.form-input {
    width: 100%;
    padding: 0.625rem 1rem;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 0.9375rem;
    color: #111827;
    transition: all 0.2s;
    background: white;
}

.form-input:focus {
    outline: none;
    border-color: #7B2D3B;
    box-shadow: 0 0 0 3px rgba(123, 45, 59, 0.1);
}

.form-input:disabled {
    background: #F3F4F6;
    cursor: not-allowed;
}

.form-input::placeholder {
    color: #9CA3AF;
}

/* Estado de error */
.form-input--error {
    border-color: #DC2626;
    background-color: #FEF2F2;
}

.form-input--error:focus {
    border-color: #DC2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

/* Estado válido */
.form-input--valid {
    border-color: #059669;
}

.form-input--valid:focus {
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
}

.form-error {
    display: block;
    font-size: 0.8125rem;
    color: #DC2626;
    margin-top: 0.375rem;
}

/* Contador de caracteres */
.char-counter {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.75rem;
    color: #9CA3AF;
    pointer-events: none;
}

.char-counter--full {
    color: #059669;
    font-weight: 600;
}

.has-error .char-counter {
    color: #DC2626;
}

/* ===== RESPONSIVE — CIERRE ENTERPRISE ===== */

/* E-4: Input counter reposition */
@media (max-width: 425px) {
    .char-counter {
        position: static;
        display: block;
        margin-top: 0.25rem;
        text-align: right;
        font-size: 0.75rem;
        transform: none;
    }
}
</style>
