<!-- resources/js/Pages/Admin/Staff/Index.vue -->
<script setup>
import { ref, computed, watch, reactive } from 'vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
defineOptions({ layout: AppLayout });
import { CrudTable, CrudModal, FormInput, FormSelect } from '@/components/Crud';
import { useConfirm } from '@/composables/useConfirm';
import { useToast } from '@/composables/useToast';
import { useInertiaForm } from '@/composables/useInertiaForm';

const props = defineProps({
    staff: { type: Object, default: () => ({ data: [] }) },
    filters: { type: Object, default: () => ({}) },
    positions: { type: Array, default: () => [] },
});

// Toast & Confirm (SweetAlert2)
const { confirmDelete } = useConfirm();
const toast = useToast();
const { processing, submitForm, deleteRecord, toggleActive: toggleActiveRecord } = useInertiaForm();

// Modal state
const showModal = ref(false);
const isEditing = ref(false);
const editingItem = ref(null);

// Email types
const emailTypes = [
    { value: 'work', label: 'Trabajo' },
    { value: 'personal', label: 'Personal' },
    { value: 'other', label: 'Otro' },
];

// Form con validaciones
const form = useForm({
    id: null,
    first_name: '',
    paternal_surname: '',
    maternal_surname: '',
    birth_date: '',
    curp: '',
    rfc: '',
    phone: '',
    mobile: '',
    position_id: null,
    hire_date: '',
    termination_date: '',
    emails: [{ email: '', type: 'work', is_primary: true }],
    notes: '',
    is_active: true,
});

// Validaciones frontend
const errors = ref({});

// Table columns
const columns = [
    { key: 'employee_number', label: 'No. Empleado', sortable: true },
    { key: 'full_name', label: 'Nombre', sortable: true },
    { key: 'position', label: 'Puesto' },
    { key: 'primary_email', label: 'Email' },
    { key: 'phone', label: 'Teléfono' },
    { key: 'is_active', label: 'Estado', type: 'badge' },
    { key: 'actions', label: 'Acciones', type: 'actions' },
];

// Validar nombre: solo letras, espacios, acentos y ñ
const validateName = (value, field) => {
    if (!value || value.trim() === '') {
        return `El ${field} es obligatorio`;
    }
    if (value.length < 2) {
        return `El ${field} debe tener al menos 2 caracteres`;
    }
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-\.]+$/.test(value)) {
        return 'Solo se permiten letras, espacios, guiones y puntos';
    }
    return null;
};

// Validar teléfono: 10 dígitos numéricos
const validatePhone = (value) => {
    if (!value) return null;
    const cleaned = value.replace(/\D/g, '');
    if (cleaned.length !== 10) {
        return 'Debe tener exactamente 10 dígitos';
    }
    return null;
};

// Validar email
const validateEmail = (value) => {
    if (!value) return 'El email es obligatorio';
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
        return 'Formato de email inválido';
    }
    return null;
};

// Validar CURP (mexicano)
const validateCurp = (value) => {
    if (!value) return null;
    if (value.length !== 18) {
        return 'El CURP debe tener 18 caracteres';
    }
    if (!/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z][0-9]$/i.test(value)) {
        return 'Formato de CURP inválido';
    }
    return null;
};

// Validar RFC
const validateRfc = (value) => {
    if (!value) return null;
    if (value.length < 12 || value.length > 13) {
        return 'El RFC debe tener 12 o 13 caracteres';
    }
    if (!/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/i.test(value)) {
        return 'Formato de RFC inválido';
    }
    return null;
};

// Validar formulario completo
const validateForm = () => {
    const newErrors = {};

    // Nombres obligatorios
    const firstNameError = validateName(form.first_name, 'nombre');
    if (firstNameError) newErrors.first_name = firstNameError;

    const paternalError = validateName(form.paternal_surname, 'apellido paterno');
    if (paternalError) newErrors.paternal_surname = paternalError;

    // Apellido materno - obligatorio
    const maternalError = validateName(form.maternal_surname, 'apellido materno');
    if (!form.maternal_surname || form.maternal_surname.trim() === '') {
        newErrors.maternal_surname = 'El apellido materno es obligatorio';
    } else if (maternalError) {
        newErrors.maternal_surname = maternalError;
    }

    // Fecha de nacimiento - obligatoria
    if (!form.birth_date) {
        newErrors.birth_date = 'La fecha de nacimiento es obligatoria';
    }

    // Puesto obligatorio
    if (!form.position_id) {
        newErrors.position_id = 'Debe seleccionar un puesto';
    }

    // Teléfono fijo opcional
    const phoneError = validatePhone(form.phone);
    if (phoneError) newErrors.phone = phoneError;

    // Celular - obligatorio
    if (!form.mobile || form.mobile.trim() === '') {
        newErrors.mobile = 'El celular es obligatorio';
    } else {
        const mobileError = validatePhone(form.mobile);
        if (mobileError) newErrors.mobile = mobileError;
    }

    // CURP y RFC opcionales
    const curpError = validateCurp(form.curp);
    if (curpError) newErrors.curp = curpError;

    const rfcError = validateRfc(form.rfc);
    if (rfcError) newErrors.rfc = rfcError;

    // Emails - al menos uno requerido
    if (!form.emails || form.emails.length === 0) {
        newErrors['emails'] = 'Debe agregar al menos un email';
    } else {
        form.emails.forEach((email, index) => {
            const emailError = validateEmail(email.email);
            if (emailError) newErrors[`emails.${index}.email`] = emailError;
        });

        // Verificar duplicados
        const emailList = form.emails.map(e => e.email.toLowerCase());
        const duplicates = emailList.filter((e, i) => emailList.indexOf(e) !== i);
        if (duplicates.length > 0) {
            newErrors['emails'] = 'Los emails no pueden repetirse';
        }
    }

    errors.value = newErrors;
    return Object.keys(newErrors).length === 0;
};

// Formatear teléfono mientras escribe (solo números)
const formatPhone = (event, field) => {
    const value = event.target.value.replace(/\D/g, '').slice(0, 10);
    form[field] = value;
};

// Formatear CURP a mayúsculas
const formatCurp = (event) => {
    form.curp = event.target.value.toUpperCase().slice(0, 18);
};

// Formatear RFC a mayúsculas
const formatRfc = (event) => {
    form.rfc = event.target.value.toUpperCase().slice(0, 13);
};

// Email management
const addEmail = () => {
    if (form.emails.length < 5) {
        form.emails.push({ email: '', type: 'work', is_primary: false });
    }
};

const removeEmail = (index) => {
    if (form.emails.length > 1) {
        const wasPrimary = form.emails[index].is_primary;
        form.emails.splice(index, 1);
        // Si era primario, hacer primario al primero
        if (wasPrimary && form.emails.length > 0) {
            form.emails[0].is_primary = true;
        }
    }
};

const setPrimaryEmail = (index) => {
    form.emails.forEach((email, i) => {
        email.is_primary = i === index;
    });
};

// Open create modal
const openCreate = () => {
    form.reset();
    form.emails = [{ email: '', type: 'work', is_primary: true }];
    form.clearErrors();
    errors.value = {};
    isEditing.value = false;
    editingItem.value = null;
    showModal.value = true;
};

// Open edit modal
const openEdit = async (item) => {
    try {
        // Fetch full staff data via JSON endpoint
        const response = await fetch(route('admin.staff.edit', item.id), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            throw new Error('Error al cargar datos');
        }

        const staffData = await response.json();

        form.id = staffData.id;
        form.first_name = staffData.first_name || '';
        form.paternal_surname = staffData.paternal_surname || '';
        form.maternal_surname = staffData.maternal_surname || '';
        form.birth_date = staffData.birth_date || '';
        form.curp = staffData.curp || '';
        form.rfc = staffData.rfc || '';
        form.phone = staffData.phone || '';
        form.mobile = staffData.mobile || '';
        form.position_id = staffData.position?.id || null;
        form.hire_date = staffData.hire_date || '';
        form.termination_date = staffData.termination_date || '';
        form.emails = staffData.emails?.length > 0
            ? staffData.emails.map(e => ({ ...e }))
            : [{ email: '', type: 'work', is_primary: true }];
        form.notes = staffData.notes || '';
        form.is_active = staffData.is_active;

        errors.value = {};
        isEditing.value = true;
        editingItem.value = item;
        showModal.value = true;
    } catch (error) {
        toast.error('Error al cargar los datos del personal');
        console.error('Error fetching staff data:', error);
    }
};

// Submit form
const submit = () => {
    if (!validateForm()) {
        toast.error('Verifica los errores en el formulario');
        return;
    }

    const url = isEditing.value
        ? route('admin.staff.update', form.id)
        : route('admin.staff.store');

    const data = {
        first_name: form.first_name,
        paternal_surname: form.paternal_surname,
        maternal_surname: form.maternal_surname || null,
        birth_date: form.birth_date || null,
        curp: form.curp || null,
        rfc: form.rfc || null,
        phone: form.phone || null,
        mobile: form.mobile || null,
        position_id: form.position_id,
        hire_date: form.hire_date || null,
        termination_date: form.termination_date || null,
        emails: form.emails.filter(e => e.email.trim()),
        notes: form.notes || null,
        is_active: form.is_active,
    };

    submitForm({
        url,
        data,
        method: isEditing.value ? 'put' : 'post',
        onSuccess: () => {
            showModal.value = false;
            form.reset();
        },
        onValidationError: (backendErrors) => {
            Object.keys(backendErrors).forEach(key => {
                errors.value[key] = backendErrors[key];
            });
        },
        successMessage: isEditing.value ? 'Personal actualizado exitosamente' : 'Personal creado exitosamente'
    });
};

// View modal state
const showViewModal = ref(false);
const viewingItem = ref(null);

// Open view modal
const openView = (item) => {
    viewingItem.value = item;
    showViewModal.value = true;
};

// Edit from view modal
const editFromView = () => {
    showViewModal.value = false;
    openEdit(viewingItem.value);
};

// Delete from view modal
const deleteFromView = async () => {
    showViewModal.value = false;
    await handleDelete(viewingItem.value);
};

// Delete
const handleDelete = async (item) => {
    if (item.has_user) {
        toast.error('No se puede eliminar personal con cuenta de usuario');
        return;
    }

    const confirmed = await confirmDelete(`"${item.full_name}"`);
    if (confirmed) {
        deleteRecord({
            url: route('admin.staff.destroy', item.id),
            successMessage: 'Personal eliminado exitosamente'
        });
    }
};

// Toggle active
const toggleActive = (item) => {
    toggleActiveRecord({
        url: route('admin.staff.toggle-active', item.id),
        successMessage: item.is_active ? 'Personal desactivado' : 'Personal activado'
    });
};

// Get error for field
const getError = (field) => errors.value[field] || form.errors[field];
</script>

<template>
    <Head title="Personal" />

            <div class="page-container">
                <!-- Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">Personal</h1>
                        <p class="page-subtitle">Gestiona el personal de la empresa</p>
                    </div>
                    <button class="btn btn--primary" @click="openCreate">
                        <span class="btn-icon">+</span>
                        Nuevo Personal
                    </button>
                </div>

                <!-- Filters -->
                <div class="filters-bar">
                    <div class="filter-group">
                        <select
                            class="filter-select"
                            :value="filters.position || ''"
                            @change="router.get(route('admin.staff.index'), { ...filters, position: $event.target.value || undefined }, { preserveState: true })"
                        >
                            <option value="">Todos los puestos</option>
                            <option v-for="pos in positions" :key="pos.value" :value="pos.value">
                                {{ pos.label }}
                            </option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select
                            class="filter-select"
                            :value="filters.active"
                            @change="router.get(route('admin.staff.index'), { ...filters, active: $event.target.value || undefined }, { preserveState: true })"
                        >
                            <option value="">Todos los estados</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <CrudTable
                    :data="staff.data"
                    :columns="columns"
                    :pagination="staff"
                    searchPlaceholder="Buscar personal..."
                    @view="openView"
                    @edit="openEdit"
                    @delete="handleDelete"
                >
                    <!-- Custom status column -->
                    <template #cell-is_active="{ item }">
                        <button
                            @click.stop="toggleActive(item)"
                            :class="['status-badge', item.is_active ? 'status-active' : 'status-inactive']"
                        >
                            {{ item.is_active ? 'Activo' : 'Inactivo' }}
                        </button>
                    </template>

                    <!-- User indicator -->
                    <template #cell-full_name="{ item }">
                        <div class="name-cell">
                            <span>{{ item.full_name }}</span>
                            <span v-if="item.has_user" class="user-badge" title="Tiene cuenta de usuario">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="user-icon">
                                    <path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" />
                                </svg>
                            </span>
                        </div>
                    </template>
                </CrudTable>

                <!-- Create/Edit Modal -->
                <CrudModal
                    :show="showModal"
                    :title="isEditing ? 'Editar Personal' : 'Nuevo Personal'"
                    @close="showModal = false"
                    @submit="submit"
                    :loading="processing"
                    size="lg"
                >
                    <div class="modal-sections">
                        <!-- Datos Personales -->
                        <div class="form-section">
                            <h3 class="section-title">Datos Personales</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label required">Nombre(s)</label>
                                    <input
                                        type="text"
                                        v-model="form.first_name"
                                        class="form-input"
                                        :class="{ 'input-error': getError('first_name') }"
                                        placeholder="Nombre(s)"
                                        maxlength="100"
                                    />
                                    <span v-if="getError('first_name')" class="error-text">{{ getError('first_name') }}</span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label required">Apellido Paterno</label>
                                    <input
                                        type="text"
                                        v-model="form.paternal_surname"
                                        class="form-input"
                                        :class="{ 'input-error': getError('paternal_surname') }"
                                        placeholder="Apellido paterno"
                                        maxlength="100"
                                    />
                                    <span v-if="getError('paternal_surname')" class="error-text">{{ getError('paternal_surname') }}</span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label required">Apellido Materno</label>
                                    <input
                                        type="text"
                                        v-model="form.maternal_surname"
                                        class="form-input"
                                        :class="{ 'input-error': getError('maternal_surname') }"
                                        placeholder="Apellido materno"
                                        maxlength="100"
                                    />
                                    <span v-if="getError('maternal_surname')" class="error-text">{{ getError('maternal_surname') }}</span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label required">Fecha de Nacimiento</label>
                                    <input
                                        type="date"
                                        v-model="form.birth_date"
                                        class="form-input"
                                        :class="{ 'input-error': getError('birth_date') }"
                                    />
                                    <span v-if="getError('birth_date')" class="error-text">{{ getError('birth_date') }}</span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">CURP</label>
                                    <input
                                        type="text"
                                        :value="form.curp"
                                        @input="formatCurp"
                                        class="form-input"
                                        :class="{ 'input-error': getError('curp') }"
                                        placeholder="18 caracteres"
                                        maxlength="18"
                                    />
                                    <span v-if="getError('curp')" class="error-text">{{ getError('curp') }}</span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">RFC</label>
                                    <input
                                        type="text"
                                        :value="form.rfc"
                                        @input="formatRfc"
                                        class="form-input"
                                        :class="{ 'input-error': getError('rfc') }"
                                        placeholder="12-13 caracteres"
                                        maxlength="13"
                                    />
                                    <span v-if="getError('rfc')" class="error-text">{{ getError('rfc') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Contacto -->
                        <div class="form-section">
                            <h3 class="section-title">Contacto</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Teléfono Fijo</label>
                                    <input
                                        type="tel"
                                        :value="form.phone"
                                        @input="formatPhone($event, 'phone')"
                                        class="form-input"
                                        :class="{ 'input-error': getError('phone') }"
                                        placeholder="10 dígitos"
                                        maxlength="10"
                                    />
                                    <span v-if="getError('phone')" class="error-text">{{ getError('phone') }}</span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label required">Celular</label>
                                    <input
                                        type="tel"
                                        :value="form.mobile"
                                        @input="formatPhone($event, 'mobile')"
                                        class="form-input"
                                        :class="{ 'input-error': getError('mobile') }"
                                        placeholder="10 dígitos"
                                        maxlength="10"
                                    />
                                    <span v-if="getError('mobile')" class="error-text">{{ getError('mobile') }}</span>
                                </div>
                            </div>

                            <!-- Emails múltiples -->
                            <div class="emails-section">
                                <div class="emails-header">
                                    <label class="form-label required">Emails</label>
                                    <button
                                        type="button"
                                        class="btn-add-email"
                                        @click="addEmail"
                                        :disabled="form.emails.length >= 5"
                                    >
                                        + Agregar email
                                    </button>
                                </div>
                                <span v-if="getError('emails')" class="error-text">{{ getError('emails') }}</span>

                                <div v-for="(email, index) in form.emails" :key="index" class="email-row">
                                    <div class="email-input-group">
                                        <input
                                            type="email"
                                            v-model="email.email"
                                            class="form-input email-input"
                                            :class="{ 'input-error': getError(`emails.${index}.email`) }"
                                            placeholder="correo@ejemplo.com"
                                        />
                                        <select v-model="email.type" class="form-select email-type">
                                            <option v-for="type in emailTypes" :key="type.value" :value="type.value">
                                                {{ type.label }}
                                            </option>
                                        </select>
                                        <label class="primary-checkbox" :title="email.is_primary ? 'Email principal' : 'Marcar como principal'">
                                            <input
                                                type="radio"
                                                :checked="email.is_primary"
                                                @change="setPrimaryEmail(index)"
                                                name="primary_email"
                                            />
                                            <span class="primary-star" :class="{ active: email.is_primary }">★</span>
                                        </label>
                                        <button
                                            type="button"
                                            class="btn-remove-email"
                                            @click="removeEmail(index)"
                                            :disabled="form.emails.length <= 1"
                                            title="Eliminar email"
                                        >
                                            ×
                                        </button>
                                    </div>
                                    <span v-if="getError(`emails.${index}.email`)" class="error-text">
                                        {{ getError(`emails.${index}.email`) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Datos Laborales -->
                        <div class="form-section">
                            <h3 class="section-title">Datos Laborales</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label required">Puesto</label>
                                    <select
                                        v-model="form.position_id"
                                        class="form-select"
                                        :class="{ 'input-error': getError('position_id') }"
                                    >
                                        <option :value="null" disabled>Seleccionar puesto...</option>
                                        <option v-for="pos in positions" :key="pos.value" :value="pos.value">
                                            {{ pos.label }}
                                        </option>
                                    </select>
                                    <span v-if="getError('position_id')" class="error-text">{{ getError('position_id') }}</span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Fecha de Alta</label>
                                    <input
                                        type="date"
                                        v-model="form.hire_date"
                                        class="form-input"
                                        :class="{ 'input-error': getError('hire_date') }"
                                    />
                                    <span v-if="getError('hire_date')" class="error-text">{{ getError('hire_date') }}</span>
                                </div>

                                <div class="form-group" v-if="isEditing">
                                    <label class="form-label">Fecha de Baja</label>
                                    <input
                                        type="date"
                                        v-model="form.termination_date"
                                        class="form-input"
                                        :class="{ 'input-error': getError('termination_date') }"
                                    />
                                    <span v-if="getError('termination_date')" class="error-text">{{ getError('termination_date') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Notas y Estado -->
                        <div class="form-section">
                            <div class="form-grid">
                                <div class="form-group form-group--full">
                                    <label class="form-label">Notas</label>
                                    <textarea
                                        v-model="form.notes"
                                        class="form-textarea"
                                        rows="3"
                                        maxlength="1000"
                                        placeholder="Notas adicionales..."
                                    ></textarea>
                                </div>

                                <div class="form-group" v-if="isEditing">
                                    <label class="form-label">Estado</label>
                                    <label class="toggle-switch">
                                        <input type="checkbox" v-model="form.is_active" />
                                        <span class="toggle-slider"></span>
                                        <span class="toggle-label">{{ form.is_active ? 'Activo' : 'Inactivo' }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </CrudModal>

                <!-- View Details Modal -->
                <Teleport to="body">
                    <Transition name="modal">
                        <div v-if="showViewModal" class="modal-overlay">
                            <div class="modal-container modal-view">
                                <div class="modal-header">
                                    <h2 class="modal-title">Detalle del Personal</h2>
                                    <button class="modal-close" @click="showViewModal = false">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M18 6L6 18M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="modal-body" v-if="viewingItem">
                                    <div class="view-header">
                                        <div class="view-avatar">
                                            {{ viewingItem.full_name?.substring(0, 2).toUpperCase() }}
                                        </div>
                                        <div class="view-header-info">
                                            <h3 class="view-name">{{ viewingItem.full_name }}</h3>
                                            <div class="view-badges">
                                                <span class="badge badge--purple">{{ viewingItem.position || 'Sin puesto' }}</span>
                                                <span class="badge" :class="viewingItem.is_active ? 'badge--green' : 'badge--gray'">
                                                    {{ viewingItem.is_active ? 'Activo' : 'Inactivo' }}
                                                </span>
                                                <span v-if="viewingItem.has_user" class="badge badge--blue">Usuario</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Datos Personales -->
                                    <div class="view-section">
                                        <h4 class="section-title">Datos Personales</h4>
                                        <div class="data-row">
                                            <span class="data-label">Nombre(s):</span>
                                            <span class="data-value">{{ viewingItem.first_name || '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Apellido Paterno:</span>
                                            <span class="data-value">{{ viewingItem.paternal_surname || '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Apellido Materno:</span>
                                            <span class="data-value">{{ viewingItem.maternal_surname || '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Fecha de Nacimiento:</span>
                                            <span class="data-value">{{ viewingItem.birth_date || '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">CURP:</span>
                                            <span class="data-value data-mono">{{ viewingItem.curp || '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">RFC:</span>
                                            <span class="data-value data-mono">{{ viewingItem.rfc || '-' }}</span>
                                        </div>
                                    </div>

                                    <!-- Informacion Laboral -->
                                    <div class="view-section">
                                        <h4 class="section-title">Informacion Laboral</h4>
                                        <div class="data-row">
                                            <span class="data-label">No. Empleado:</span>
                                            <span class="data-value data-mono">{{ viewingItem.employee_number || '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Puesto:</span>
                                            <span class="data-value">{{ viewingItem.position || '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Fecha de Alta:</span>
                                            <span class="data-value">{{ viewingItem.hire_date || '-' }}</span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Fecha de Baja:</span>
                                            <span class="data-value">{{ viewingItem.termination_date || '-' }}</span>
                                        </div>
                                    </div>

                                    <!-- Contacto -->
                                    <div class="view-section">
                                        <h4 class="section-title">Contacto</h4>
                                        <div class="data-row">
                                            <span class="data-label">Email Principal:</span>
                                            <span class="data-value">
                                                <a v-if="viewingItem.primary_email" :href="`mailto:${viewingItem.primary_email}`" class="data-link">{{ viewingItem.primary_email }}</a>
                                                <span v-else>-</span>
                                            </span>
                                        </div>
                                        <!-- Emails adicionales -->
                                        <template v-if="viewingItem.emails?.length > 1">
                                            <div class="data-row" v-for="(email, idx) in viewingItem.emails.filter(e => !e.is_primary)" :key="idx">
                                                <span class="data-label">Email ({{ email.type === 'work' ? 'Trabajo' : email.type === 'personal' ? 'Personal' : 'Otro' }}):</span>
                                                <span class="data-value">
                                                    <a :href="`mailto:${email.email}`" class="data-link">{{ email.email }}</a>
                                                </span>
                                            </div>
                                        </template>
                                        <div class="data-row">
                                            <span class="data-label">Telefono Fijo:</span>
                                            <span class="data-value">
                                                <a v-if="viewingItem.phone" :href="`tel:${viewingItem.phone}`" class="data-link">{{ viewingItem.phone }}</a>
                                                <span v-else>-</span>
                                            </span>
                                        </div>
                                        <div class="data-row">
                                            <span class="data-label">Celular:</span>
                                            <span class="data-value">
                                                <a v-if="viewingItem.mobile" :href="`tel:${viewingItem.mobile}`" class="data-link">{{ viewingItem.mobile }}</a>
                                                <span v-else>-</span>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Notas -->
                                    <div class="view-section">
                                        <h4 class="section-title">Notas</h4>
                                        <p class="notes-text">{{ viewingItem.notes || 'Sin notas' }}</p>
                                    </div>

                                    <!-- Informacion del Sistema -->
                                    <div class="view-section">
                                        <h4 class="section-title">Informacion del Sistema</h4>
                                        <div class="data-row">
                                            <span class="data-label">Fecha de Registro:</span>
                                            <span class="data-value">{{ viewingItem.created_at || '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn--secondary" @click="editFromView">Editar</button>
                                    <button
                                        class="btn btn--danger-outline"
                                        @click="deleteFromView"
                                        :disabled="viewingItem?.has_user"
                                        :title="viewingItem?.has_user ? 'No se puede eliminar: tiene cuenta de usuario' : ''"
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </Teleport>
            </div>
</template>

<style scoped>
.page-container {
    padding: 1.5rem;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-content {
    flex: 1;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 0.25rem;
}

.page-subtitle {
    color: #6B7280;
    font-size: 0.875rem;
    margin: 0;
}

.filters-bar {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.filter-select {
    padding: 0.5rem 1rem;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    font-size: 0.875rem;
    background: white;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.btn--primary {
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: white;
}

.btn--primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(123, 45, 59, 0.3);
}

.btn-icon {
    font-size: 1.125rem;
    line-height: 1;
}

/* Modal Sections */
.modal-sections {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-section {
    padding-bottom: 1rem;
    border-bottom: 1px solid #E5E7EB;
}

.form-section:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.section-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #7B2D3B;
    margin: 0 0 1rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Form styles */
.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group--full {
    grid-column: 1 / -1;
}

.form-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.375rem;
    text-transform: uppercase;
}

.form-label.required::after {
    content: ' *';
    color: #DC2626;
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 0.625rem 0.875rem;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.15s;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: #7B2D3B;
    box-shadow: 0 0 0 3px rgba(123, 45, 59, 0.1);
}

.input-error {
    border-color: #DC2626;
}

.error-text {
    font-size: 0.75rem;
    color: #DC2626;
    margin-top: 0.25rem;
}

/* Emails section */
.emails-section {
    margin-top: 1rem;
}

.emails-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.btn-add-email {
    background: none;
    border: 1px dashed #7B2D3B;
    color: #7B2D3B;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.15s;
}

.btn-add-email:hover:not(:disabled) {
    background: rgba(123, 45, 59, 0.05);
}

.btn-add-email:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.email-row {
    margin-bottom: 0.5rem;
}

.email-input-group {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.email-input {
    flex: 1;
}

.email-type {
    width: 120px;
}

.primary-checkbox {
    cursor: pointer;
}

.primary-checkbox input {
    display: none;
}

.primary-star {
    font-size: 1.25rem;
    color: #D1D5DB;
    transition: color 0.15s;
}

.primary-star.active {
    color: #F59E0B;
}

.btn-remove-email {
    background: none;
    border: none;
    color: #DC2626;
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.25rem;
    line-height: 1;
    opacity: 0.6;
    transition: opacity 0.15s;
}

.btn-remove-email:hover:not(:disabled) {
    opacity: 1;
}

.btn-remove-email:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

/* Name cell with user indicator */
.name-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.user-badge {
    display: inline-flex;
    padding: 0.125rem;
    background: #EEF2FF;
    border-radius: 4px;
    color: #4F46E5;
}

.user-icon {
    width: 14px;
    height: 14px;
}

/* Status badge */
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.15s;
}

.status-active {
    background: #D1FAE5;
    color: #065F46;
}

.status-active:hover {
    background: #A7F3D0;
}

.status-inactive {
    background: #FEE2E2;
    color: #991B1B;
}

.status-inactive:hover {
    background: #FECACA;
}

/* Toggle switch */
.toggle-switch {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
}

.toggle-switch input {
    display: none;
}

.toggle-slider {
    width: 44px;
    height: 24px;
    background: #D1D5DB;
    border-radius: 12px;
    position: relative;
    transition: background 0.2s;
}

.toggle-slider::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    transition: transform 0.2s;
}

.toggle-switch input:checked + .toggle-slider {
    background: #7B2D3B;
}

.toggle-switch input:checked + .toggle-slider::after {
    transform: translateX(20px);
}

.toggle-label {
    font-size: 0.875rem;
    color: #374151;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .email-input-group {
        flex-wrap: wrap;
    }

    .email-input {
        width: 100%;
    }

    .email-type {
        flex: 1;
    }
}

/* View Modal Styles */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 1rem;
}

.modal-container {
    background: white;
    border-radius: 16px;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #E5E7EB;
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
    background: transparent;
    color: #6B7280;
    border-radius: 8px;
    cursor: pointer;
}

.modal-close:hover { background: #F3F4F6; color: #111827; }

.modal-body {
    padding: 1.5rem;
    overflow-y: auto;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid #E5E7EB;
    background: #F9FAFB;
}

.view-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #E5E7EB;
}

.view-avatar {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: white;
    font-size: 1.25rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    flex-shrink: 0;
}

.view-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.view-badges { display: flex; gap: 0.5rem; flex-wrap: wrap; }

.badge {
    padding: 0.25rem 0.625rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge--blue { background: #DBEAFE; color: #1D4ED8; }
.badge--purple { background: #EDE9FE; color: #7C3AED; }
.badge--green { background: #D1FAE5; color: #059669; }
.badge--gray { background: #F3F4F6; color: #6B7280; }

.view-section { margin-bottom: 1.25rem; }

.notes-text {
    font-size: 0.875rem;
    color: #4B5563;
    line-height: 1.5;
    margin: 0;
    padding: 0.75rem;
    background: #F9FAFB;
    border-radius: 8px;
    white-space: pre-wrap;
}

.section-title {
    font-size: 0.75rem;
    font-weight: 700;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0 0 0.75rem 0;
}

.data-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #F3F4F6;
}

.data-row:last-child { border-bottom: none; }

.data-label { font-size: 0.875rem; color: #6B7280; }
.data-value { font-size: 0.875rem; color: #111827; font-weight: 500; }
.data-mono { font-family: 'JetBrains Mono', monospace; }
.data-link { color: #7B2D3B; text-decoration: none; }
.data-link:hover { text-decoration: underline; }

.btn--secondary {
    background: white;
    border: 1px solid #E5E7EB;
    color: #374151;
    padding: 0.625rem 1.25rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
}

.btn--secondary:hover { border-color: #7B2D3B; color: #7B2D3B; }

.btn--danger-outline {
    background: white;
    border: 1px solid #FCA5A5;
    color: #DC2626;
    padding: 0.625rem 1.25rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
}

.btn--danger-outline:hover:not(:disabled) { background: #FEE2E2; }
.btn--danger-outline:disabled { opacity: 0.5; cursor: not-allowed; }

/* Modal Transitions */
.modal-enter-active, .modal-leave-active { transition: all 0.3s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from .modal-container, .modal-leave-to .modal-container {
    transform: scale(0.95) translateY(10px);
}
</style>
