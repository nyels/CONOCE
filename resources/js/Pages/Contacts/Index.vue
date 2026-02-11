<!-- resources/js/Pages/Contacts/Index.vue -->
<script setup>
import { ref, computed, watch } from 'vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
defineOptions({ layout: AppLayout });
import { CrudTable, CrudModal, FormInput, FormSelect } from '@/components/Crud';
import { useConfirm } from '@/composables/useConfirm';
import { useToast } from '@/composables/useToast';

const props = defineProps({
    contacts: { type: Object, default: () => ({ data: [] }) },
    types: { type: Array, default: () => [] },
});

// Toast & Confirm (SweetAlert2)
const { confirmDelete } = useConfirm();
const toast = useToast();

// Client-side filter
const typeFilter = ref('');
const filteredContacts = computed(() => {
    if (!typeFilter.value) return props.contacts.data;
    return props.contacts.data.filter(c => c.type === typeFilter.value);
});

// Modal state
const showModal = ref(false);
const isEditing = ref(false);
const editingItem = ref(null);

// Form con campos de nombre separados
const form = useForm({
    id: null,
    first_name: '',
    paternal_surname: '',
    maternal_surname: '',
    type: null,
    email: '',
    phone: '',
    mobile: '',
    notes: '',
    is_active: true,
});

// Validaciones frontend
const errors = ref({});

// Table columns
const columns = [
    { key: 'name', label: 'Nombre', sortable: true },
    { key: 'type_label', label: 'Tipo', sortable: true },
    { key: 'phone', label: 'Teléfono', sortable: true },
    { key: 'mobile', label: 'Celular', sortable: true },
    { key: 'email', label: 'Email', sortable: true },
    { key: 'customers_count', label: 'Clientes', type: 'number', sortable: true },
    { key: 'is_active', label: 'Estado', type: 'badge', sortable: true },
    { key: 'actions', label: 'Acciones', type: 'actions' },
];

// Validar nombre: solo letras, espacios, acentos y ñ
const validateName = (value, fieldName, required = true) => {
    if (!value || value.trim() === '') {
        return required ? `El ${fieldName} es obligatorio` : null;
    }
    if (value.length < 2) {
        return `El ${fieldName} debe tener al menos 2 caracteres`;
    }
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.]+$/.test(value)) {
        return 'Solo se permiten letras, espacios y puntos';
    }
    return null;
};

// Validar teléfono: 10 dígitos numéricos
const validatePhone = (value) => {
    if (!value) return null; // Opcional
    const cleaned = value.replace(/\D/g, '');
    if (cleaned.length !== 10) {
        return 'Debe tener exactamente 10 dígitos';
    }
    return null;
};

// Validar email
const validateEmail = (value) => {
    if (!value) return null; // Opcional
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
        return 'Formato de email inválido';
    }
    return null;
};

// Validar tipo de contacto
const validateType = (value) => {
    if (!value) return 'Debe seleccionar un tipo';
    return null;
};

// Validar formulario completo
const validateForm = () => {
    const newErrors = {};

    // Nombre(s) - obligatorio
    const firstNameError = validateName(form.first_name, 'nombre', true);
    if (firstNameError) newErrors.first_name = firstNameError;

    // Apellido paterno - obligatorio
    const paternalError = validateName(form.paternal_surname, 'apellido paterno', true);
    if (paternalError) newErrors.paternal_surname = paternalError;

    // Apellido materno - obligatorio
    const maternalError = validateName(form.maternal_surname, 'apellido materno', true);
    if (maternalError) newErrors.maternal_surname = maternalError;

    const typeError = validateType(form.type);
    if (typeError) newErrors.type = typeError;

    const phoneError = validatePhone(form.phone);
    if (phoneError) newErrors.phone = phoneError;

    // Celular - obligatorio
    if (!form.mobile || form.mobile.trim() === '') {
        newErrors.mobile = 'El celular es obligatorio';
    } else {
        const mobileError = validatePhone(form.mobile);
        if (mobileError) newErrors.mobile = mobileError;
    }

    const emailError = validateEmail(form.email);
    if (emailError) newErrors.email = emailError;

    errors.value = newErrors;
    return Object.keys(newErrors).length === 0;
};

// Formatear teléfono mientras escribe (solo números)
const formatPhone = (event, field) => {
    const value = event.target.value.replace(/\D/g, '').slice(0, 10);
    form[field] = value;
};

// Capitalizar nombres al perder foco
const capitalizeField = (field) => {
    if (form[field]) {
        form[field] = form[field]
            .toLowerCase()
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ')
            .trim();
    }
};

// Open create modal
const openCreate = () => {
    // Reset manual de todos los campos para evitar datos persistentes
    form.id = null;
    form.first_name = '';
    form.paternal_surname = '';
    form.maternal_surname = '';
    form.type = null;
    form.email = '';
    form.phone = '';
    form.mobile = '';
    form.notes = '';
    form.is_active = true;
    form.clearErrors();
    errors.value = {};
    isEditing.value = false;
    editingItem.value = null;
    showModal.value = true;
};

// Open edit modal
const openEdit = (item) => {
    form.id = item.id;
    form.first_name = item.first_name || '';
    form.paternal_surname = item.paternal_surname || '';
    form.maternal_surname = item.maternal_surname || '';
    form.type = item.type || null;
    form.email = item.email || '';
    form.phone = item.phone || '';
    form.mobile = item.mobile || '';
    form.notes = item.notes || '';
    form.is_active = item.is_active;
    errors.value = {};
    isEditing.value = true;
    editingItem.value = item;
    showModal.value = true;
};

// Submit form
const submit = () => {
    if (!validateForm()) {
        toast.error('Verifica los errores en el formulario');
        return;
    }

    const url = isEditing.value
        ? route('contacts.update', form.id)
        : route('contacts.store');

    const method = isEditing.value ? 'put' : 'post';

    form[method](url, {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
            toast.success(isEditing.value ? 'Contacto actualizado exitosamente' : 'Contacto creado exitosamente');
        },
        onError: (backendErrors) => {
            // Mostrar errores del backend en el formulario
            Object.keys(backendErrors).forEach(key => {
                errors.value[key] = backendErrors[key];
            });
            toast.error();
        }
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
    const confirmed = await confirmDelete(`"${item.name}"`);
    if (confirmed) {
        router.delete(route('contacts.destroy', item.id), {
            preserveScroll: true,
            onSuccess: () => toast.success('Contacto eliminado exitosamente'),
            onError: () => toast.error(),
        });
    }
};

// Toggle active
const toggleActive = (item) => {
    router.patch(route('contacts.toggle-active', item.id), {}, {
        preserveScroll: true,
        onSuccess: () => toast.success(item.is_active ? 'Contacto desactivado' : 'Contacto activado'),
    });
};

// Limpiar errores del servidor cuando el usuario modifica un campo
watch(() => [form.first_name, form.paternal_surname, form.maternal_surname, form.type, form.email, form.phone, form.mobile], () => {
    if (Object.keys(form.errors).length) {
        form.clearErrors();
    }
});

// Type badge color (colores por posición del tipo)
const getTypeColor = (typeId) => {
    const colors = [
        'bg-purple-100 text-purple-800',
        'bg-indigo-100 text-indigo-800',
        'bg-blue-100 text-blue-800',
        'bg-green-100 text-green-800',
        'bg-yellow-100 text-yellow-800',
    ];
    const index = (typeId || 0) % colors.length;
    return colors[index];
};
</script>

<template>
    <Head title="Contactos / Intermediarios" />

        <div class="page-container">
                <!-- Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">Contactos / Intermediarios</h1>
                        <p class="page-subtitle">Gestiona agentes, subagentes y empleados</p>
                    </div>
                    <button class="btn btn--primary" @click="openCreate">
                        <span class="btn-icon">+</span>
                        Nuevo Contacto
                    </button>
                </div>

                <!-- Table -->
                <CrudTable
                    :data="filteredContacts"
                    :columns="columns"
                    :pagination="contacts"
                    searchPlaceholder="Buscar contacto..."
                    @view="openView"
                    @edit="openEdit"
                    @delete="handleDelete"
                >
                    <template #filters>
                        <select v-model="typeFilter" class="filter-select">
                            <option value="">Todos los tipos</option>
                            <option v-for="type in types" :key="type.value" :value="type.value">
                                {{ type.label }}
                            </option>
                        </select>
                    </template>
                    <!-- Custom type column -->
                    <template #cell-type_label="{ item }">
                        <span :class="['type-badge', getTypeColor(item.type)]">
                            {{ item.type_label }}
                        </span>
                    </template>

                    <!-- Custom status column -->
                    <template #cell-is_active="{ item }">
                        <button
                            @click.stop="toggleActive(item)"
                            :class="['status-badge', item.is_active ? 'status-active' : 'status-inactive']"
                        >
                            {{ item.is_active ? 'Activo' : 'Inactivo' }}
                        </button>
                    </template>
                </CrudTable>

                <!-- Create/Edit Modal -->
                <CrudModal
                    :show="showModal"
                    :title="isEditing ? 'Editar Contacto' : 'Nuevo Contacto'"
                    @close="showModal = false"
                    @submit="submit"
                    :loading="form.processing"
                >
                    <div class="form-grid">
                        <!-- Nombre(s) -->
                        <div class="form-group form-group--full">
                            <label class="form-label required">Nombre(s)</label>
                            <input
                                type="text"
                                v-model="form.first_name"
                                @blur="capitalizeField('first_name')"
                                class="form-input"
                                :class="{ 'input-error': errors.first_name || form.errors.first_name }"
                                placeholder="Nombre(s)"
                                maxlength="100"
                            />
                            <span v-if="errors.first_name || form.errors.first_name" class="error-text">
                                {{ errors.first_name || form.errors.first_name }}
                            </span>
                        </div>

                        <!-- Apellido Paterno -->
                        <div class="form-group">
                            <label class="form-label required">Apellido Paterno</label>
                            <input
                                type="text"
                                v-model="form.paternal_surname"
                                @blur="capitalizeField('paternal_surname')"
                                class="form-input"
                                :class="{ 'input-error': errors.paternal_surname || form.errors.paternal_surname }"
                                placeholder="Apellido paterno"
                                maxlength="100"
                            />
                            <span v-if="errors.paternal_surname || form.errors.paternal_surname" class="error-text">
                                {{ errors.paternal_surname || form.errors.paternal_surname }}
                            </span>
                        </div>

                        <!-- Apellido Materno -->
                        <div class="form-group">
                            <label class="form-label required">Apellido Materno</label>
                            <input
                                type="text"
                                v-model="form.maternal_surname"
                                @blur="capitalizeField('maternal_surname')"
                                class="form-input"
                                :class="{ 'input-error': errors.maternal_surname || form.errors.maternal_surname }"
                                placeholder="Apellido materno"
                                maxlength="100"
                            />
                            <span v-if="errors.maternal_surname || form.errors.maternal_surname" class="error-text">
                                {{ errors.maternal_surname || form.errors.maternal_surname }}
                            </span>
                        </div>

                        <!-- Tipo -->
                        <div class="form-group">
                            <label class="form-label required">Tipo</label>
                            <select
                                v-model="form.type"
                                class="form-select"
                                :class="{ 'input-error': errors.type || form.errors.type }"
                            >
                                <option :value="null" disabled>Seleccionar tipo...</option>
                                <option v-for="type in types" :key="type.value" :value="type.value">
                                    {{ type.label }}
                                </option>
                            </select>
                            <span v-if="errors.type || form.errors.type" class="error-text">
                                {{ errors.type || form.errors.type }}
                            </span>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input
                                type="email"
                                v-model="form.email"
                                class="form-input"
                                :class="{ 'input-error': errors.email || form.errors.email }"
                                placeholder="correo@ejemplo.com"
                                maxlength="100"
                            />
                            <span v-if="errors.email || form.errors.email" class="error-text">
                                {{ errors.email || form.errors.email }}
                            </span>
                        </div>

                        <!-- Teléfono -->
                        <FormInput
                            v-model="form.phone"
                            label="Teléfono"
                            placeholder="10 dígitos"
                            mask="phone"
                            :error="errors.phone || form.errors.phone"
                        />

                        <!-- Celular -->
                        <FormInput
                            v-model="form.mobile"
                            label="Celular"
                            placeholder="10 dígitos"
                            mask="phone"
                            :error="errors.mobile || form.errors.mobile"
                            required
                        />

                        <!-- Notas -->
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

                        <!-- Estado (solo en edición) -->
                        <div class="form-group" v-if="isEditing">
                            <label class="form-label">Estado</label>
                            <label class="toggle-switch">
                                <input type="checkbox" v-model="form.is_active" />
                                <span class="toggle-slider"></span>
                                <span class="toggle-label">{{ form.is_active ? 'Activo' : 'Inactivo' }}</span>
                            </label>
                        </div>
                    </div>
                </CrudModal>

                <!-- View Details Modal -->
                <Teleport to="body">
                    <Transition name="modal">
                        <div v-if="showViewModal" class="modal-overlay">
                            <div class="modal-container modal-view">
                                <div class="modal-header">
                                    <h2 class="modal-title">Detalle del Contacto</h2>
                                    <button class="modal-close" @click="showViewModal = false">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M18 6L6 18M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="modal-body" v-if="viewingItem">
                                    <div class="view-header">
                                        <div class="view-avatar">
                                            {{ viewingItem.name?.substring(0, 2).toUpperCase() }}
                                        </div>
                                        <div class="view-header-info">
                                            <h3 class="view-name">{{ viewingItem.name }}</h3>
                                            <div class="view-badges">
                                                <span class="badge badge--purple">{{ viewingItem.type_label }}</span>
                                                <span class="badge" :class="viewingItem.is_active ? 'badge--green' : 'badge--gray'">
                                                    {{ viewingItem.is_active ? 'Activo' : 'Inactivo' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="view-section">
                                        <h4 class="section-title">Informacion Personal</h4>
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
                                    </div>

                                    <div class="view-section">
                                        <h4 class="section-title">Contacto</h4>
                                        <div class="data-row">
                                            <span class="data-label">Telefono:</span>
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
                                        <div class="data-row">
                                            <span class="data-label">Email:</span>
                                            <span class="data-value">
                                                <a v-if="viewingItem.email" :href="`mailto:${viewingItem.email}`" class="data-link">{{ viewingItem.email }}</a>
                                                <span v-else>-</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="view-section" v-if="viewingItem.notes">
                                        <h4 class="section-title">Notas</h4>
                                        <p class="notes-text">{{ viewingItem.notes }}</p>
                                    </div>

                                    <div class="view-section">
                                        <h4 class="section-title">Estadisticas</h4>
                                        <div class="stats-row">
                                            <div class="stat-box">
                                                <span class="stat-number">{{ viewingItem.customers_count || 0 }}</span>
                                                <span class="stat-label">Clientes</span>
                                            </div>
                                            <div class="stat-box">
                                                <span class="stat-number">{{ viewingItem.quotes_count || 0 }}</span>
                                                <span class="stat-label">Cotizaciones</span>
                                            </div>
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

/* Type badge */
.type-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
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

.text-muted {
    color: #9CA3AF;
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

@media (max-width: 640px) {
    .form-grid {
        grid-template-columns: 1fr;
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

.view-badges { display: flex; gap: 0.5rem; }

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
.data-link { color: #7B2D3B; text-decoration: none; }
.data-link:hover { text-decoration: underline; }

.stats-row { display: flex; gap: 1rem; }

.stat-box {
    flex: 1;
    text-align: center;
    padding: 1rem;
    background: #F9FAFB;
    border-radius: 10px;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #7B2D3B;
}

.stat-label { font-size: 0.75rem; color: #6B7280; }

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

.btn--danger-outline:hover { background: #FEE2E2; }

/* Modal Transitions */
.modal-enter-active, .modal-leave-active { transition: all 0.3s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from .modal-container, .modal-leave-to .modal-container {
    transform: scale(0.95) translateY(10px);
}
</style>
