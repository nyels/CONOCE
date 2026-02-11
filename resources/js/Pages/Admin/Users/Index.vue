<!-- resources/js/Pages/Admin/Users/Index.vue -->
<script setup>
import { ref, computed, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
defineOptions({ layout: AppLayout });
import { CrudTable, CrudModal, FormInput, FormSelect } from '@/Components/Crud';
import { useConfirm } from '@/composables/useConfirm';
import { useInertiaForm } from '@/composables/useInertiaForm';
import { useToast } from '@/composables/useToast';

const props = defineProps({
    users: { type: Object, default: () => ({ data: [] }) },
    roles: { type: Array, default: () => [] },
    availableStaff: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

// Composables
const { confirmDelete } = useConfirm();
const { processing, submitForm, deleteRecord, toggleActive: toggleActiveRecord } = useInertiaForm();
const toast = useToast();

// Modal state
const showModal = ref(false);
const isEditing = ref(false);
const editingItem = ref(null);

// View modal state
const showViewModal = ref(false);
const viewingItem = ref(null);

// Frontend validation errors
const localErrors = ref({});

// Password requirements configuration
const PASSWORD_REQUIREMENTS = {
    minLength: 12,
    requireUppercase: true,
    requireLowercase: true,
    requireNumber: true,
    requireSpecial: true,
    specialChars: '!@#$%^&*()_+-=[]{};\':\"\\|,.<>/?'
};

// Password requirement labels for display
const passwordRequirementsList = [
    { key: 'length', label: 'Mínimo 12 caracteres', check: (v) => v && v.length >= 12 },
    { key: 'uppercase', label: 'Al menos una letra mayúscula (A-Z)', check: (v) => /[A-Z]/.test(v) },
    { key: 'lowercase', label: 'Al menos una letra minúscula (a-z)', check: (v) => /[a-z]/.test(v) },
    { key: 'number', label: 'Al menos un número (0-9)', check: (v) => /[0-9]/.test(v) },
    { key: 'special', label: 'Al menos un carácter especial (!@#$%^&*...)', check: (v) => /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(v) },
];

// Form
const form = useForm({
    id: null,
    username: '',
    password: '',
    password_confirmation: '',
    staff_id: null,
    role: 'operator',
    is_active: true
});

// Validation functions
const validateUsername = (value) => {
    if (!value || value.trim() === '') return 'El username es obligatorio';
    if (value.trim().length < 4) return 'El username debe tener al menos 4 caracteres';
    if (value.length > 50) return 'El username no puede exceder 50 caracteres';
    if (!/^[a-zA-Z][a-zA-Z0-9_\.]*$/.test(value)) {
        return 'Debe empezar con letra y solo contener letras, números, _ y .';
    }
    return null;
};

const validatePassword = (value, isEdit) => {
    if (!value && isEdit) return null;
    if (!value || value === '') return 'La contraseña es obligatoria';

    if (value.length < PASSWORD_REQUIREMENTS.minLength) {
        return `La contraseña debe tener mínimo ${PASSWORD_REQUIREMENTS.minLength} caracteres`;
    }
    if (PASSWORD_REQUIREMENTS.requireUppercase && !/[A-Z]/.test(value)) {
        return 'La contraseña debe incluir al menos una letra mayúscula';
    }
    if (PASSWORD_REQUIREMENTS.requireLowercase && !/[a-z]/.test(value)) {
        return 'La contraseña debe incluir al menos una letra minúscula';
    }
    if (PASSWORD_REQUIREMENTS.requireNumber && !/[0-9]/.test(value)) {
        return 'La contraseña debe incluir al menos un número';
    }
    if (PASSWORD_REQUIREMENTS.requireSpecial && !/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(value)) {
        return 'La contraseña debe incluir al menos un carácter especial';
    }
    return null;
};

const validatePasswordConfirmation = (value, password, isEdit) => {
    if (!password && isEdit) return null;
    if (password && (!value || value === '')) {
        return 'La confirmación de contraseña es obligatoria';
    }
    if (password && value !== password) {
        return 'Las contraseñas no coinciden';
    }
    return null;
};

const validateStaffId = (value) => {
    if (!value) return 'El personal asociado es obligatorio';
    return null;
};

const validateRole = (value) => {
    if (!value || value === '') return 'El rol es obligatorio';
    return null;
};

// Validate all fields
const validateForm = () => {
    const errors = {};

    errors.username = validateUsername(form.username);
    errors.password = validatePassword(form.password, isEditing.value);
    errors.password_confirmation = validatePasswordConfirmation(form.password_confirmation, form.password, isEditing.value);
    errors.staff_id = validateStaffId(form.staff_id);
    errors.role = validateRole(form.role);

    Object.keys(errors).forEach(key => {
        if (errors[key] === null) delete errors[key];
    });

    localErrors.value = errors;
    return Object.keys(errors).length === 0;
};

// On blur validation
const onBlur = (field) => {
    if (field === 'username') {
        localErrors.value.username = validateUsername(form.username);
    }
    if (field === 'password') {
        localErrors.value.password = validatePassword(form.password, isEditing.value);
        if (form.password_confirmation) {
            localErrors.value.password_confirmation = validatePasswordConfirmation(
                form.password_confirmation, form.password, isEditing.value
            );
        }
    }
    if (field === 'password_confirmation') {
        localErrors.value.password_confirmation = validatePasswordConfirmation(
            form.password_confirmation, form.password, isEditing.value
        );
    }
    if (field === 'staff_id') {
        localErrors.value.staff_id = validateStaffId(form.staff_id);
    }
    if (field === 'role') {
        localErrors.value.role = validateRole(form.role);
    }
};

// Get error (local or server)
const getError = (field) => localErrors.value[field] || form.errors[field];

// Check if password requirement is met (reactive)
const isRequirementMet = (requirement) => {
    return form.password && requirement.check(form.password);
};

// Check if all password requirements are met
const allPasswordRequirementsMet = computed(() => {
    if (!form.password) return false;
    return passwordRequirementsList.every(req => req.check(form.password));
});

// Check if passwords match (reactive)
const passwordsMatch = computed(() => {
    if (!form.password || !form.password_confirmation) return null;
    return form.password === form.password_confirmation;
});

// Real-time password validation on input
const onPasswordInput = () => {
    if (localErrors.value.password && allPasswordRequirementsMet.value) {
        localErrors.value.password = null;
    }
    if (form.password_confirmation) {
        if (form.password === form.password_confirmation) {
            localErrors.value.password_confirmation = null;
        } else {
            localErrors.value.password_confirmation = 'Las contraseñas no coinciden';
        }
    }
};

// Real-time password confirmation validation on input
const onPasswordConfirmationInput = () => {
    if (!form.password) return;

    if (form.password_confirmation && form.password !== form.password_confirmation) {
        localErrors.value.password_confirmation = 'Las contraseñas no coinciden';
    } else if (form.password_confirmation && form.password === form.password_confirmation) {
        localErrors.value.password_confirmation = null;
    }
};

// Table columns
const columns = [
    { key: 'username', label: 'Username', sortable: true },
    { key: 'staff', label: 'Personal Asociado', sortable: true },
    { key: 'role_label', label: 'Rol' },
    { key: 'is_active', label: 'Estado', type: 'badge' },
    { key: 'last_login_at', label: 'Último Acceso' },
    { key: 'actions', label: 'Acciones', type: 'actions' }
];

// Role options for dropdown
const roleOptions = computed(() =>
    props.roles.length ? props.roles : [
        { value: 'super_admin', label: 'Super Administrador' },
        { value: 'admin', label: 'Administrador' },
        { value: 'manager', label: 'Gerente' },
        { value: 'operator', label: 'Operador' },
        { value: 'viewer', label: 'Visor' },
    ]
);

// Staff options for dropdown
const staffOptions = computed(() => {
    const options = props.availableStaff.map(s => ({
        value: s.id,
        label: `${s.name} (${s.employee_number})`
    }));

    if (isEditing.value && editingItem.value?.staff_id) {
        const currentStaff = {
            value: editingItem.value.staff_id,
            label: editingItem.value.staff || 'Personal actual'
        };
        if (!options.find(o => o.value === currentStaff.value)) {
            options.unshift(currentStaff);
        }
    }

    return options;
});

// Open create modal
const openCreate = () => {
    form.reset();
    form.clearErrors();
    localErrors.value = {};
    form.role = 'operator';
    form.is_active = true;
    isEditing.value = false;
    editingItem.value = null;
    showModal.value = true;
};

// Open edit modal
const openEdit = (item) => {
    form.id = item.id;
    form.username = item.username || '';
    form.password = '';
    form.password_confirmation = '';
    form.staff_id = item.staff_id;
    form.role = item.role;
    form.is_active = item.is_active;
    localErrors.value = {};
    form.clearErrors();
    isEditing.value = true;
    editingItem.value = item;
    showModal.value = true;
};

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

// Submit form
const submit = () => {
    if (!validateForm()) {
        toast.error('Verifica los errores en el formulario');
        return;
    }

    const url = isEditing.value
        ? route('admin.users.update', form.id)
        : route('admin.users.store');

    const data = {
        username: form.username,
        staff_id: form.staff_id,
        role: form.role,
        is_active: form.is_active,
    };

    if (form.password) {
        data.password = form.password;
        data.password_confirmation = form.password_confirmation;
    }

    submitForm({
        url,
        data,
        method: isEditing.value ? 'put' : 'post',
        onSuccess: () => {
            showModal.value = false;
            form.reset();
            localErrors.value = {};
        },
        onValidationError: (errors) => {
            Object.keys(errors).forEach(key => {
                localErrors.value[key] = errors[key];
            });
        },
        successMessage: isEditing.value ? 'Usuario actualizado exitosamente' : 'Usuario creado exitosamente'
    });
};

// Delete
const handleDelete = async (item) => {
    const confirmed = await confirmDelete(item.username);
    if (confirmed) {
        deleteRecord({
            url: route('admin.users.destroy', item.id),
            successMessage: 'Usuario eliminado exitosamente'
        });
    }
};

// Toggle active
const toggleActive = (item) => {
    toggleActiveRecord({
        url: route('admin.users.toggle-active', item.id),
        successMessage: item.is_active ? 'Usuario desactivado exitosamente' : 'Usuario activado exitosamente'
    });
};

// Force password change
const forcePasswordChange = async (item) => {
    const confirmed = await confirmDelete(`forzar cambio de contraseña para ${item.username}`);
    if (confirmed) {
        submitForm({
            url: route('admin.users.force-password-change', item.id),
            data: {},
            method: 'post',
            successMessage: 'Cambio de contraseña forzado exitosamente'
        });
    }
};

// Reset failed logins
const resetFailedLogins = (item) => {
    submitForm({
        url: route('admin.users.reset-failed-logins', item.id),
        data: {},
        method: 'post',
        successMessage: 'Intentos fallidos reseteados exitosamente'
    });
};

// Password status helpers
const getPasswordStatusClass = (item) => {
    if (item.password_expired) return 'status-expired';
    if (item.password_days_remaining !== null && item.password_days_remaining <= 7) return 'status-warning';
    return 'status-ok';
};

const getPasswordStatusText = (item) => {
    if (item.password_expired) return 'Expirada';
    if (item.password_days_remaining !== null) {
        if (item.password_days_remaining <= 0) return 'Expirada';
        if (item.password_days_remaining <= 7) return `${item.password_days_remaining} días`;
        return 'Vigente';
    }
    return '-';
};

// Role color helper
const getRoleColorClass = (role) => {
    const colors = {
        'super_admin': 'badge--red',
        'admin': 'badge--purple',
        'manager': 'badge--blue',
        'operator': 'badge--green',
        'viewer': 'badge--gray'
    };
    return colors[role] || 'badge--gray';
};
</script>

<template>
    <Head title="Usuarios" />

        <div class="page-container">
            <!-- Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="page-title">Usuarios del Sistema</h1>
                    <p class="page-subtitle">Gestiona los usuarios y sus roles de acceso</p>
                </div>
                <button class="btn btn--primary" @click="openCreate">
                    <span class="btn-icon">+</span>
                    Nuevo Usuario
                </button>
            </div>

            <!-- Filters -->
            <div class="filters-bar">
                <div class="filter-group">
                    <select
                        class="filter-select"
                        :value="filters.role || ''"
                        @change="router.get(route('admin.users.index'), { ...filters, role: $event.target.value || undefined }, { preserveState: true })"
                    >
                        <option value="">Todos los roles</option>
                        <option v-for="role in roles" :key="role.value" :value="role.value">
                            {{ role.label }}
                        </option>
                    </select>
                </div>
                <div class="filter-group">
                    <select
                        class="filter-select"
                        :value="filters.active"
                        @change="router.get(route('admin.users.index'), { ...filters, active: $event.target.value || undefined }, { preserveState: true })"
                    >
                        <option value="">Todos los estados</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <CrudTable
                :data="users.data"
                :columns="columns"
                :pagination="users"
                searchPlaceholder="Buscar usuario..."
                emptyMessage="No hay usuarios registrados"
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

                <!-- Custom role column -->
                <template #cell-role_label="{ item }">
                    <span :class="['badge', getRoleColorClass(item.role)]">
                        {{ item.role_label }}
                    </span>
                </template>
            </CrudTable>

            <!-- Create/Edit Modal -->
            <CrudModal
                :show="showModal"
                :title="isEditing ? 'Editar Usuario' : 'Nuevo Usuario'"
                :loading="processing"
                size="md"
                @close="showModal = false"
                @submit="submit"
            >
                <div class="modal-sections">
                    <!-- Credenciales -->
                    <div class="form-section">
                        <h3 class="section-title">Credenciales de Acceso</h3>
                        <div class="form-grid">
                            <!-- Username -->
                            <div class="form-group form-group--full">
                                <label class="form-label required">Username</label>
                                <input
                                    type="text"
                                    v-model="form.username"
                                    class="form-input"
                                    :class="{ 'input-error': getError('username') }"
                                    placeholder="usuario.ejemplo"
                                    maxlength="50"
                                    @blur="onBlur('username')"
                                />
                                <span v-if="getError('username')" class="error-text">{{ getError('username') }}</span>
                                <span v-else class="hint-text">Se usará para iniciar sesión (mín. 4 caracteres)</span>
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label class="form-label" :class="{ required: !isEditing }">Contraseña</label>
                                <input
                                    type="password"
                                    v-model="form.password"
                                    class="form-input"
                                    :class="{ 'input-error': getError('password') }"
                                    :placeholder="isEditing ? 'Dejar vacío para mantener actual' : 'Contraseña segura'"
                                    @blur="onBlur('password')"
                                    @input="onPasswordInput"
                                />
                                <span v-if="getError('password')" class="error-text">{{ getError('password') }}</span>
                            </div>

                            <!-- Password Confirmation -->
                            <div class="form-group">
                                <label class="form-label" :class="{ required: !isEditing || form.password }">Confirmar Contraseña</label>
                                <input
                                    type="password"
                                    v-model="form.password_confirmation"
                                    class="form-input"
                                    :class="{ 'input-error': getError('password_confirmation') }"
                                    placeholder="Repetir contraseña"
                                    @blur="onBlur('password_confirmation')"
                                    @input="onPasswordConfirmationInput"
                                />
                                <span v-if="getError('password_confirmation')" class="error-text">{{ getError('password_confirmation') }}</span>

                                <!-- Password Match Indicator -->
                                <div v-if="form.password && form.password_confirmation && !getError('password_confirmation')" class="password-match-indicator">
                                    <span v-if="passwordsMatch === true" class="match-success">
                                        <span class="match-icon">✓</span> Las contraseñas coinciden
                                    </span>
                                    <span v-else-if="passwordsMatch === false" class="match-error">
                                        <span class="match-icon">✗</span> Las contraseñas no coinciden
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Password Requirements -->
                        <div class="password-requirements" v-if="!isEditing || form.password">
                            <p class="requirements-title">Requisitos de contraseña:</p>
                            <ul class="requirements-list">
                                <li
                                    v-for="req in passwordRequirementsList"
                                    :key="req.key"
                                    :class="{ 'requirement-met': isRequirementMet(req), 'requirement-pending': !isRequirementMet(req) && form.password }"
                                >
                                    <span class="requirement-icon">{{ isRequirementMet(req) ? '✓' : '○' }}</span>
                                    {{ req.label }}
                                </li>
                            </ul>
                            <div v-if="form.password && allPasswordRequirementsMet" class="requirements-success">
                                ✓ Contraseña válida - cumple todos los requisitos
                            </div>
                        </div>
                    </div>

                    <!-- Asignación -->
                    <div class="form-section">
                        <h3 class="section-title">Asignación</h3>
                        <div class="form-grid">
                            <!-- Staff -->
                            <div class="form-group">
                                <label class="form-label required">Personal Asociado</label>
                                <select
                                    v-model="form.staff_id"
                                    class="form-select"
                                    :class="{ 'input-error': getError('staff_id') }"
                                    @change="onBlur('staff_id')"
                                >
                                    <option :value="null">Seleccionar personal...</option>
                                    <option v-for="staff in staffOptions" :key="staff.value" :value="staff.value">
                                        {{ staff.label }}
                                    </option>
                                </select>
                                <span v-if="getError('staff_id')" class="error-text">{{ getError('staff_id') }}</span>
                                <span v-else class="hint-text">Vincular con registro de personal existente</span>
                            </div>

                            <!-- Role -->
                            <div class="form-group">
                                <label class="form-label required">Rol</label>
                                <select
                                    v-model="form.role"
                                    class="form-select"
                                    :class="{ 'input-error': getError('role') }"
                                    @change="onBlur('role')"
                                >
                                    <option v-for="role in roleOptions" :key="role.value" :value="role.value">
                                        {{ role.label }}
                                    </option>
                                </select>
                                <span v-if="getError('role')" class="error-text">{{ getError('role') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Estado (solo en edición) -->
                    <div class="form-section" v-if="isEditing">
                        <h3 class="section-title">Estado</h3>
                        <div class="form-group">
                            <label class="toggle-switch">
                                <input type="checkbox" v-model="form.is_active" />
                                <span class="toggle-slider"></span>
                                <span class="toggle-label">{{ form.is_active ? 'Activo' : 'Inactivo' }}</span>
                            </label>
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
                                <h2 class="modal-title">Detalle del Usuario</h2>
                                <button class="modal-close" @click="showViewModal = false">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 6L6 18M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="modal-body" v-if="viewingItem">
                                <div class="view-header">
                                    <div class="view-avatar">
                                        {{ viewingItem.username?.substring(0, 2).toUpperCase() }}
                                    </div>
                                    <div class="view-header-info">
                                        <h3 class="view-name">{{ viewingItem.name || viewingItem.username }}</h3>
                                        <div class="view-badges">
                                            <span :class="['badge', getRoleColorClass(viewingItem.role)]">
                                                {{ viewingItem.role_label }}
                                            </span>
                                            <span class="badge" :class="viewingItem.is_active ? 'badge--green' : 'badge--gray'">
                                                {{ viewingItem.is_active ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Credenciales -->
                                <div class="view-section">
                                    <h4 class="section-title">Credenciales</h4>
                                    <div class="data-row">
                                        <span class="data-label">Username:</span>
                                        <span class="data-value data-mono">{{ viewingItem.username }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Email:</span>
                                        <span class="data-value">
                                            <a v-if="viewingItem.email" :href="`mailto:${viewingItem.email}`" class="data-link">{{ viewingItem.email }}</a>
                                            <span v-else>-</span>
                                        </span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Teléfono:</span>
                                        <span class="data-value">{{ viewingItem.phone || '-' }}</span>
                                    </div>
                                </div>

                                <!-- Personal Asociado -->
                                <div class="view-section">
                                    <h4 class="section-title">Personal Asociado</h4>
                                    <div class="data-row">
                                        <span class="data-label">Nombre:</span>
                                        <span class="data-value">{{ viewingItem.staff || '-' }}</span>
                                    </div>
                                </div>

                                <!-- Seguridad -->
                                <div class="view-section">
                                    <h4 class="section-title">Seguridad</h4>
                                    <div class="data-row">
                                        <span class="data-label">Estado Contraseña:</span>
                                        <span class="data-value">
                                            <span :class="['password-status', getPasswordStatusClass(viewingItem)]">
                                                {{ getPasswordStatusText(viewingItem) }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Expira:</span>
                                        <span class="data-value">{{ viewingItem.password_expires_at || '-' }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Último Acceso:</span>
                                        <span class="data-value">{{ viewingItem.last_login_at || 'Nunca' }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Intentos Fallidos:</span>
                                        <span class="data-value" :class="{ 'text-danger': viewingItem.failed_login_attempts > 0 }">
                                            {{ viewingItem.failed_login_attempts || 0 }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Acciones Rápidas -->
                                <div class="view-section" v-if="viewingItem.password_days_remaining !== null || viewingItem.failed_login_attempts > 0">
                                    <h4 class="section-title">Acciones Rápidas</h4>
                                    <div class="quick-actions">
                                        <button
                                            v-if="viewingItem.password_days_remaining !== null"
                                            class="btn btn--warning-outline btn--sm"
                                            @click="forcePasswordChange(viewingItem)"
                                        >
                                            Forzar Cambio de Contraseña
                                        </button>
                                        <button
                                            v-if="viewingItem.failed_login_attempts > 0"
                                            class="btn btn--info-outline btn--sm"
                                            @click="resetFailedLogins(viewingItem)"
                                        >
                                            Resetear Intentos Fallidos
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn--secondary" @click="editFromView">Editar</button>
                                <button class="btn btn--danger-outline" @click="deleteFromView">Eliminar</button>
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
    font-size: 0.75rem;
    font-weight: 700;
    color: #7B2D3B;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0 0 1rem 0;
}

/* Form styles */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
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
.form-select {
    width: 100%;
    padding: 0.625rem 0.875rem;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.15s;
}

.form-input:focus,
.form-select:focus {
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

.hint-text {
    font-size: 0.75rem;
    color: #6B7280;
    margin-top: 0.25rem;
}

/* Password requirements */
.password-requirements {
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 0.875rem 1rem;
    margin-top: 1rem;
}

.requirements-title {
    font-size: 0.75rem;
    font-weight: 600;
    color: #374151;
    margin: 0 0 0.5rem;
}

.requirements-list {
    margin: 0;
    padding: 0;
    list-style: none;
    font-size: 0.75rem;
    color: #6B7280;
}

.requirements-list li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
    transition: color 0.2s;
}

.requirements-list li.requirement-met {
    color: #059669;
    font-weight: 500;
}

.requirements-list li.requirement-pending {
    color: #DC2626;
}

.requirement-icon {
    width: 14px;
    text-align: center;
    font-size: 0.75rem;
    font-weight: bold;
}

.requirements-success {
    margin-top: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: #D1FAE5;
    border-radius: 6px;
    color: #065F46;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Password match indicator */
.password-match-indicator {
    margin-top: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.match-success {
    color: #059669;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.match-error {
    color: #DC2626;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.match-icon {
    font-weight: bold;
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

/* Badges */
.badge {
    padding: 0.25rem 0.625rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge--red { background: #FEE2E2; color: #991B1B; }
.badge--purple { background: #EDE9FE; color: #7C3AED; }
.badge--blue { background: #DBEAFE; color: #1D4ED8; }
.badge--green { background: #D1FAE5; color: #059669; }
.badge--gray { background: #F3F4F6; color: #6B7280; }

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

.view-header-info {
    flex: 1;
}

.view-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.view-badges { display: flex; gap: 0.5rem; flex-wrap: wrap; }

.view-section { margin-bottom: 1.25rem; }

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

.text-danger { color: #DC2626; }

/* Password status */
.password-status {
    padding: 0.125rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-ok { background: #D1FAE5; color: #065F46; }
.status-warning { background: #FEF3C7; color: #D97706; }
.status-expired { background: #FEE2E2; color: #DC2626; }

/* Quick actions */
.quick-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.btn--sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
}

.btn--warning-outline {
    background: white;
    border: 1px solid #FCD34D;
    color: #D97706;
}

.btn--warning-outline:hover { background: #FEF3C7; }

.btn--info-outline {
    background: white;
    border: 1px solid #93C5FD;
    color: #2563EB;
}

.btn--info-outline:hover { background: #DBEAFE; }

.btn--secondary {
    background: white;
    border: 1px solid #E5E7EB;
    color: #374151;
}

.btn--secondary:hover { border-color: #7B2D3B; color: #7B2D3B; }

.btn--danger-outline {
    background: white;
    border: 1px solid #FCA5A5;
    color: #DC2626;
}

.btn--danger-outline:hover { background: #FEE2E2; }

/* Modal Transitions */
.modal-enter-active, .modal-leave-active { transition: all 0.3s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from .modal-container, .modal-leave-to .modal-container {
    transform: scale(0.95) translateY(10px);
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .page-header {
        flex-direction: column;
        align-items: stretch;
    }

    .btn--primary {
        justify-content: center;
    }
}
</style>
