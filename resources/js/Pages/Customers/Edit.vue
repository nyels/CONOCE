<!-- resources/js/Pages/Customers/Edit.vue -->
<script setup>
import { ref, computed } from 'vue';
import { Head, router, useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
defineOptions({ layout: AppLayout });
import { ToastContainer } from '@/Components/Ui';
import { FormSelect } from '@/Components/Crud';
import { useToast } from '@/composables/useToast';

const props = defineProps({
    customer: { type: Object, required: true },
    mexicanStates: { type: Array, default: () => [] },
});

const toast = useToast();

const form = useForm({
    name: props.customer.name || '',
    phone: props.customer.phone || '',
    mobile: props.customer.mobile || '',
    email: props.customer.email || '',
    rfc: props.customer.rfc || '',
    curp: props.customer.curp || '',
    type: props.customer.type || 'physical',
    street: props.customer.street || '',
    exterior_number: props.customer.exterior_number || '',
    interior_number: props.customer.interior_number || '',
    neighborhood: props.customer.neighborhood || '',
    city: props.customer.city || '',
    state: props.customer.state || '',
    zip_code: props.customer.zip_code || '',
    is_active: props.customer.is_active ?? true,
});

const typeOptions = [
    { value: 'physical', label: 'Persona Fisica' },
    { value: 'moral', label: 'Persona Moral' },
];

// ============================================
// VALIDACIONES FRONTEND
// ============================================

const validationErrors = ref({});

// Validar nombre (solo letras, espacios, acentos, n, puntos)
const validateName = (value) => {
    if (!value || value.length < 3) {
        return 'El nombre debe tener al menos 3 caracteres';
    }
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.]+$/.test(value)) {
        return 'Solo se permiten letras, espacios y puntos';
    }
    return null;
};

// Validar telefono (10 digitos)
const validatePhone = (value) => {
    if (!value) return null; // Opcional
    const cleaned = value.replace(/\D/g, '');
    if (cleaned.length !== 10) {
        return 'Debe tener exactamente 10 digitos';
    }
    if (/^(\d)\1{9}$/.test(cleaned)) {
        return 'No puede ser un numero repetido';
    }
    if (['0', '1'].includes(cleaned[0])) {
        return 'Debe iniciar con codigo de area valido (2-9)';
    }
    return null;
};

// Validar email
const validateEmail = (value) => {
    if (!value) return null; // Opcional
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
        return 'Formato de email invalido';
    }
    return null;
};

// Validar RFC
const validateRFC = (value) => {
    if (!value) return null; // Opcional
    const rfc = value.toUpperCase();
    if (rfc.length !== 12 && rfc.length !== 13) {
        return 'Debe tener 12 (moral) o 13 (fisica) caracteres';
    }
    const patternFisica = /^[A-ZÑ&]{4}[0-9]{6}[A-Z0-9]{3}$/;
    const patternMoral = /^[A-ZÑ&]{3}[0-9]{6}[A-Z0-9]{3}$/;
    if (rfc.length === 13 && !patternFisica.test(rfc)) {
        return 'Formato invalido para persona fisica';
    }
    if (rfc.length === 12 && !patternMoral.test(rfc)) {
        return 'Formato invalido para persona moral';
    }
    return null;
};

// Validar CURP
const validateCURP = (value) => {
    if (!value) return null; // Opcional
    const curp = value.toUpperCase();
    if (curp.length !== 18) {
        return 'Debe tener exactamente 18 caracteres';
    }
    if (!/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[A-Z0-9][0-9]$/.test(curp)) {
        return 'Formato de CURP invalido';
    }
    return null;
};

// Validar codigo postal (5 digitos)
const validateZipCode = (value) => {
    if (!value) return null; // Opcional
    if (!/^\d{5}$/.test(value)) {
        return 'Debe tener exactamente 5 digitos';
    }
    return null;
};

// Validar direccion (sin caracteres especiales peligrosos)
const validateAddress = (value) => {
    if (!value) return null;
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\.\,\#\-]+$/.test(value)) {
        return 'Contiene caracteres no permitidos';
    }
    return null;
};

// Validar ciudad/estado (solo letras)
const validateLocation = (value) => {
    if (!value) return null;
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.\-]+$/.test(value)) {
        return 'Solo se permiten letras, espacios y guiones';
    }
    return null;
};

// Formatear telefono (solo numeros, max 10)
const formatPhone = (event, field) => {
    const value = event.target.value.replace(/\D/g, '').slice(0, 10);
    form[field] = value;
    validationErrors.value[field] = validatePhone(value);
};

// Formatear RFC (mayusculas)
const formatRFC = (event) => {
    form.rfc = event.target.value.toUpperCase().replace(/[^A-ZÑ&0-9]/g, '').slice(0, 13);
    validationErrors.value.rfc = validateRFC(form.rfc);
};

// Formatear CURP (mayusculas)
const formatCURP = (event) => {
    form.curp = event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 18);
    validationErrors.value.curp = validateCURP(form.curp);
};

// Formatear codigo postal (solo numeros)
const formatZipCode = (event) => {
    form.zip_code = event.target.value.replace(/\D/g, '').slice(0, 5);
    validationErrors.value.zip_code = validateZipCode(form.zip_code);
};

// Validar campo al perder foco
const onBlur = (field) => {
    switch (field) {
        case 'name':
            validationErrors.value.name = validateName(form.name);
            break;
        case 'email':
            validationErrors.value.email = validateEmail(form.email);
            break;
        case 'phone':
            validationErrors.value.phone = validatePhone(form.phone);
            break;
        case 'mobile':
            validationErrors.value.mobile = validatePhone(form.mobile);
            break;
        case 'street':
            validationErrors.value.street = validateAddress(form.street);
            break;
        case 'neighborhood':
            validationErrors.value.neighborhood = !form.neighborhood ? 'La colonia es obligatoria' : validateAddress(form.neighborhood);
            break;
        case 'city':
            validationErrors.value.city = validateLocation(form.city);
            break;
        case 'state':
            validationErrors.value.state = validateLocation(form.state);
            break;
    }
};

// Verificar si el formulario es valido
const isFormValid = computed(() => {
    const nameError = validateName(form.name);
    const phoneError = validatePhone(form.phone);
    const mobileError = validatePhone(form.mobile);
    const emailError = validateEmail(form.email);
    const rfcError = validateRFC(form.rfc);
    const curpError = validateCURP(form.curp);
    const zipError = validateZipCode(form.zip_code);
    const neighborhoodError = !form.neighborhood ? 'required' : validateAddress(form.neighborhood);

    return !nameError && !phoneError && !mobileError && !emailError && !rfcError && !curpError && !zipError && !neighborhoodError;
});

const submit = () => {
    // Validar todos los campos antes de enviar
    validationErrors.value.name = validateName(form.name);
    validationErrors.value.phone = validatePhone(form.phone);
    validationErrors.value.mobile = validatePhone(form.mobile);
    validationErrors.value.email = validateEmail(form.email);
    validationErrors.value.rfc = validateRFC(form.rfc);
    validationErrors.value.curp = validateCURP(form.curp);
    validationErrors.value.zip_code = validateZipCode(form.zip_code);
    validationErrors.value.neighborhood = !form.neighborhood ? 'La colonia es obligatoria' : validateAddress(form.neighborhood);

    if (!isFormValid.value) {
        toast.error('Por favor corrige los errores del formulario');
        return;
    }

    form.put(route('customers.update', props.customer.id), {
        onSuccess: () => {
            toast.success('Cliente actualizado');
        },
        onError: () => {
            toast.error('Error al actualizar');
        }
    });
};

const cancel = () => {
    router.visit(route('customers.show', props.customer.id));
};

// Helper para obtener error (frontend o backend)
const getError = (field) => {
    return validationErrors.value[field] || form.errors[field];
};
</script>

<template>
    <ToastContainer>
        <Head :title="`Editar ${customer.name}`" />

            <div class="page-container">
                <!-- Breadcrumb -->
                <div class="breadcrumb">
                    <Link :href="route('customers.index')" class="breadcrumb-link">Clientes</Link>
                    <span class="breadcrumb-sep">/</span>
                    <Link :href="route('customers.show', customer.id)" class="breadcrumb-link">{{ customer.name }}</Link>
                    <span class="breadcrumb-sep">/</span>
                    <span class="breadcrumb-current">Editar</span>
                </div>

                <!-- Header -->
                <div class="page-header">
                    <h1 class="page-title">Editar Cliente</h1>
                </div>

                <!-- Form Card -->
                <div class="form-card">
                    <form @submit.prevent="submit">
                        <div class="form-section">
                            <h3 class="section-title">Informacion Personal</h3>

                            <div class="form-group">
                                <label class="form-label">Nombre Completo *</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="form-input"
                                    :class="{ 'form-input--error': getError('name') }"
                                    @blur="onBlur('name')"
                                    maxlength="255"
                                >
                                <span v-if="getError('name')" class="form-error">{{ getError('name') }}</span>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Tipo de Persona</label>
                                    <select v-model="form.type" class="form-input">
                                        <option v-for="opt in typeOptions" :key="opt.value" :value="opt.value">
                                            {{ opt.label }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">RFC</label>
                                    <input
                                        :value="form.rfc"
                                        type="text"
                                        class="form-input"
                                        :class="{ 'form-input--error': getError('rfc') }"
                                        placeholder="XXXX000000XXX"
                                        @input="formatRFC"
                                        maxlength="13"
                                    >
                                    <span v-if="getError('rfc')" class="form-error">{{ getError('rfc') }}</span>
                                </div>
                            </div>

                            <div class="form-group" v-if="form.type === 'physical'">
                                <label class="form-label">CURP</label>
                                <input
                                    :value="form.curp"
                                    type="text"
                                    class="form-input"
                                    :class="{ 'form-input--error': getError('curp') }"
                                    placeholder="XXXX000000XXXXXX00"
                                    @input="formatCURP"
                                    maxlength="18"
                                >
                                <span v-if="getError('curp')" class="form-error">{{ getError('curp') }}</span>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="section-title">Contacto</h3>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Celular</label>
                                    <input
                                        :value="form.phone"
                                        type="tel"
                                        class="form-input"
                                        :class="{ 'form-input--error': getError('phone') }"
                                        placeholder="9991234567"
                                        @input="formatPhone($event, 'phone')"
                                        maxlength="10"
                                    >
                                    <span class="form-hint">10 digitos</span>
                                    <span v-if="getError('phone')" class="form-error">{{ getError('phone') }}</span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Celular</label>
                                    <input
                                        :value="form.mobile"
                                        type="tel"
                                        class="form-input"
                                        :class="{ 'form-input--error': getError('mobile') }"
                                        placeholder="9991234567"
                                        @input="formatPhone($event, 'mobile')"
                                        maxlength="10"
                                    >
                                    <span class="form-hint">10 digitos</span>
                                    <span v-if="getError('mobile')" class="form-error">{{ getError('mobile') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="form-input"
                                    :class="{ 'form-input--error': getError('email') }"
                                    placeholder="cliente@email.com"
                                    @blur="onBlur('email')"
                                >
                                <span v-if="getError('email')" class="form-error">{{ getError('email') }}</span>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="section-title">Direccion</h3>

                            <div class="form-row">
                                <div class="form-group" style="grid-column: span 2;">
                                    <label class="form-label">Calle</label>
                                    <input
                                        v-model="form.street"
                                        type="text"
                                        class="form-input"
                                        :class="{ 'form-input--error': getError('street') }"
                                        placeholder="Calle 10"
                                        @blur="onBlur('street')"
                                        maxlength="255"
                                    >
                                    <span v-if="getError('street')" class="form-error">{{ getError('street') }}</span>
                                </div>
                            </div>

                            <div class="form-row form-row--3">
                                <div class="form-group">
                                    <label class="form-label">No. Exterior</label>
                                    <input
                                        v-model="form.exterior_number"
                                        type="text"
                                        class="form-input"
                                        placeholder="123"
                                        maxlength="20"
                                    >
                                </div>
                                <div class="form-group">
                                    <label class="form-label">No. Interior</label>
                                    <input
                                        v-model="form.interior_number"
                                        type="text"
                                        class="form-input"
                                        placeholder="A"
                                        maxlength="20"
                                    >
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Colonia <span class="required">*</span></label>
                                    <input
                                        v-model="form.neighborhood"
                                        type="text"
                                        class="form-input"
                                        :class="{ 'form-input--error': getError('neighborhood') }"
                                        placeholder="Centro"
                                        @blur="onBlur('neighborhood')"
                                        maxlength="100"
                                    >
                                    <span v-if="getError('neighborhood')" class="form-error">{{ getError('neighborhood') }}</span>
                                </div>
                            </div>

                            <div class="form-row form-row--3">
                                <div class="form-group">
                                    <label class="form-label">Ciudad</label>
                                    <input
                                        v-model="form.city"
                                        type="text"
                                        class="form-input"
                                        :class="{ 'form-input--error': getError('city') }"
                                        placeholder="Merida"
                                        @blur="onBlur('city')"
                                        maxlength="100"
                                    >
                                    <span v-if="getError('city')" class="form-error">{{ getError('city') }}</span>
                                </div>
                                <FormSelect
                                    v-model="form.state"
                                    label="Estado"
                                    :options="mexicanStates"
                                    placeholder="Seleccionar estado..."
                                    :error="getError('state')"
                                />
                                <div class="form-group">
                                    <label class="form-label">C.P.</label>
                                    <input
                                        :value="form.zip_code"
                                        type="text"
                                        class="form-input"
                                        :class="{ 'form-input--error': getError('zip_code') }"
                                        placeholder="97000"
                                        @input="formatZipCode"
                                        maxlength="5"
                                    >
                                    <span v-if="getError('zip_code')" class="form-error">{{ getError('zip_code') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="form-group">
                                <label class="toggle-label">
                                    <input type="checkbox" v-model="form.is_active" class="toggle-input" />
                                    <span class="toggle-switch"></span>
                                    <span class="toggle-text">Cliente activo</span>
                                </label>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="form-actions">
                            <button type="button" class="btn btn--secondary" @click="cancel">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn--primary" :disabled="form.processing">
                                {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    </ToastContainer>
</template>

<style scoped>
.page-container {
    padding: 1.5rem;
    max-width: 800px;
    margin: 0 auto;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.breadcrumb-link { color: #6B7280; text-decoration: none; }
.breadcrumb-link:hover { color: #7B2D3B; }
.breadcrumb-sep { color: #D1D5DB; }
.breadcrumb-current { color: #111827; font-weight: 500; }

.page-header { margin-bottom: 1.5rem; }

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.form-card {
    background: white;
    border-radius: 16px;
    border: 1px solid #E5E7EB;
    padding: 2rem;
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #F3F4F6;
}

.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
}

.section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #374151;
    margin: 0 0 1.25rem 0;
}

.form-group { margin-bottom: 1rem; }

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.375rem;
}

.form-input {
    width: 100%;
    padding: 0.625rem 0.875rem;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 0.9375rem;
    transition: all 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #7B2D3B;
    box-shadow: 0 0 0 3px rgba(123, 45, 59, 0.1);
}

.form-input--error { border-color: #DC2626; }

.form-error {
    display: block;
    font-size: 0.75rem;
    color: #DC2626;
    margin-top: 0.25rem;
}

.form-hint {
    display: block;
    font-size: 0.75rem;
    color: #9CA3AF;
    margin-top: 0.25rem;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.form-row--3 { grid-template-columns: repeat(3, 1fr); }

/* Toggle */
.toggle-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
}

.toggle-input { display: none; }

.toggle-switch {
    width: 44px;
    height: 24px;
    background: #E5E7EB;
    border-radius: 12px;
    position: relative;
    transition: background 0.2s;
}

.toggle-switch::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
    transition: transform 0.2s;
}

.toggle-input:checked + .toggle-switch { background: #059669; }
.toggle-input:checked + .toggle-switch::after { transform: translateX(20px); }

.toggle-text {
    font-size: 0.9375rem;
    color: #374151;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #F3F4F6;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9375rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.btn--primary {
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: white;
}

.btn--primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(123, 45, 59, 0.3);
}

.btn--primary:disabled { opacity: 0.6; cursor: not-allowed; }

.btn--secondary {
    background: white;
    border: 1px solid #E5E7EB;
    color: #374151;
}

.btn--secondary:hover { border-color: #7B2D3B; color: #7B2D3B; }

.required { color: #DC2626; margin-left: 2px; }

@media (max-width: 640px) {
    .form-row, .form-row--3 { grid-template-columns: 1fr; }
}
</style>
