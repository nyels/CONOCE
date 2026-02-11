<!-- resources/js/Pages/Customers/Index.vue -->
<script setup>
import { ref, computed, watch } from 'vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
defineOptions({ layout: AppLayout });
import { CrudTable, CrudModal, FormInput, FormSelect } from '@/components/Crud';
import { useConfirm } from '@/composables/useConfirm';
import { useToast } from '@/composables/useToast';

const props = defineProps({
    customers: { type: Object, default: () => ({ data: [] }) },
    filters: { type: Object, default: () => ({}) },
    types: { type: Array, default: () => [] },
    mexicanStates: { type: Array, default: () => [] },
});

// Toast & Confirm (SweetAlert2)
const { confirmDelete } = useConfirm();
const toast = useToast();

// Modal states
const showModal = ref(false);
const showViewModal = ref(false);
const isEditing = ref(false);
const editingItem = ref(null);
const viewingItem = ref(null);

// Form
const form = useForm({
    id: null,
    type: '',
    first_name: '',
    paternal_surname: '',
    maternal_surname: '',
    business_name: '',
    mobile: '',  // Celular en lugar de phone
    email: '',
    rfc: '',
    neighborhood: '',
    city: '',
    state: '',
    zip_code: '',
    is_active: true,
});

// Table columns
const columns = [
    { key: 'name', label: 'Nombre', sortable: true },
    { key: 'mobile', label: 'Celular', sortable: true },
    { key: 'email', label: 'Email', sortable: true },
    { key: 'neighborhood', label: 'Colonia', sortable: true },
    { key: 'type_label', label: 'Tipo', sortable: true },
    { key: 'quotes_count', label: 'Cotizaciones', type: 'number', sortable: true },
    { key: 'is_active', label: 'Estado', type: 'badge', sortable: true },
    { key: 'actions', label: 'Acciones', type: 'actions' },
];

// Type options
const typeOptions = [
    { value: 'physical', label: 'Persona Fisica' },
    { value: 'moral', label: 'Persona Moral' },
];

// Is persona fisica
const isPhysical = computed(() => form.type === 'physical');

// Errores de validación local
const localErrors = ref({});

// Validar formulario frontend
const validateForm = () => {
    const errors = {};

    // Tipo de persona - obligatorio
    if (!form.type) {
        errors.type = 'El tipo de persona es obligatorio';
    }

    // Campos para persona física
    if (form.type === 'physical') {
        if (!form.first_name || form.first_name.trim() === '') {
            errors.first_name = 'El nombre es obligatorio';
        }
        if (!form.paternal_surname || form.paternal_surname.trim() === '') {
            errors.paternal_surname = 'El apellido paterno es obligatorio';
        }
        if (!form.maternal_surname || form.maternal_surname.trim() === '') {
            errors.maternal_surname = 'El apellido materno es obligatorio';
        }
    }

    // Razón social para persona moral
    if (form.type === 'moral') {
        if (!form.business_name || form.business_name.trim() === '') {
            errors.business_name = 'La razón social es obligatoria';
        }
    }

    // Celular - obligatorio
    if (!form.mobile || form.mobile.trim() === '') {
        errors.mobile = 'El celular es obligatorio';
    } else if (!/^\d{10}$/.test(form.mobile.replace(/\D/g, ''))) {
        errors.mobile = 'El celular debe tener 10 dígitos';
    }

    // Email - obligatorio
    if (!form.email || form.email.trim() === '') {
        errors.email = 'El correo electrónico es obligatorio';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
        errors.email = 'El formato del correo electrónico es inválido';
    }

    // RFC - obligatorio
    if (!form.rfc || form.rfc.trim() === '') {
        errors.rfc = 'El RFC es obligatorio';
    }

    // Colonia - obligatoria
    if (!form.neighborhood || form.neighborhood.trim() === '') {
        errors.neighborhood = 'La colonia es obligatoria';
    } else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\.\#\-]+$/.test(form.neighborhood)) {
        errors.neighborhood = 'La colonia solo puede contener letras, números, espacios, #, - y puntos';
    }

    localErrors.value = errors;
    return Object.keys(errors).length === 0;
};

// Helper para obtener error (local o del servidor)
const getError = (field) => localErrors.value[field] || form.errors[field];

// Limpiar error del servidor cuando el usuario modifica un campo
watch(() => [form.mobile, form.email, form.rfc, form.first_name, form.paternal_surname, form.maternal_surname, form.business_name, form.neighborhood, form.city, form.state, form.zip_code], () => {
    if (Object.keys(form.errors).length) {
        form.clearErrors();
    }
});

// Flag para evitar que el watcher borre datos durante edición
const isLoadingForm = ref(false);

// Reset name fields when type changes (solo si no estamos cargando datos)
watch(() => form.type, (newType, oldType) => {
    // No hacer nada si estamos cargando el formulario o si no hay tipo anterior
    if (isLoadingForm.value || !oldType) return;

    if (newType === 'physical') {
        form.business_name = '';
    } else if (newType === 'moral') {
        form.first_name = '';
        form.paternal_surname = '';
        form.maternal_surname = '';
    }
});

// Open create modal
const openCreate = () => {
    isLoadingForm.value = true;
    form.id = null;
    form.type = '';
    form.first_name = '';
    form.paternal_surname = '';
    form.maternal_surname = '';
    form.business_name = '';
    form.mobile = '';
    form.email = '';
    form.rfc = '';
    form.neighborhood = '';
    form.city = '';
    form.state = '';
    form.zip_code = '';
    form.is_active = true;
    form.clearErrors();
    localErrors.value = {};
    isEditing.value = false;
    editingItem.value = null;
    showModal.value = true;
    // Liberar el flag después de que Vue procese los cambios
    setTimeout(() => { isLoadingForm.value = false; }, 0);
};

// Open edit modal
const openEdit = (item) => {
    isLoadingForm.value = true;

    form.id = item.id;
    form.type = item.type || 'physical';
    form.first_name = item.first_name || '';
    form.paternal_surname = item.paternal_surname || '';
    form.maternal_surname = item.maternal_surname || '';
    form.business_name = item.business_name || '';
    form.mobile = item.mobile || item.phone || '';
    form.email = item.email || '';
    form.rfc = item.rfc || '';
    form.neighborhood = item.neighborhood || '';
    form.city = item.city || '';
    form.state = item.state || '';
    form.zip_code = item.zip_code || '';
    form.is_active = item.is_active;
    form.clearErrors();
    localErrors.value = {};
    isEditing.value = true;
    editingItem.value = item;
    showModal.value = true;

    // Liberar el flag después de que Vue procese los cambios
    setTimeout(() => { isLoadingForm.value = false; }, 0);
};

// Open view modal (show details)
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
    // Validación frontend primero
    if (!validateForm()) {
        toast.error('Verifica los errores en el formulario');
        return;
    }

    const url = isEditing.value
        ? route('customers.update', form.id)
        : route('customers.store');

    const method = isEditing.value ? 'put' : 'post';

    form[method](url, {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
            localErrors.value = {};
            toast.success(isEditing.value ? 'Cliente actualizado exitosamente' : 'Cliente creado exitosamente');
        },
        onError: () => {
            toast.error();
        }
    });
};

// Delete
const handleDelete = async (item) => {
    const confirmed = await confirmDelete(`"${item.name}"`);
    if (confirmed) {
        router.delete(route('customers.destroy', item.id), {
            preserveScroll: true,
            onSuccess: () => toast.success('Cliente eliminado exitosamente'),
            onError: () => toast.error(),
        });
    }
};

// Formatted data for table
const tableData = computed(() => {
    if (!props.customers?.data) return [];
    return props.customers.data;
});
</script>

<template>
    <Head title="Clientes" />

            <div class="page-container">
                <!-- Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">Clientes</h1>
                        <p class="page-subtitle">Gestiona tu base de clientes</p>
                    </div>
                    <button class="btn btn--primary" @click="openCreate">
                        <span class="btn-icon">+</span>
                        Nuevo Cliente
                    </button>
                </div>

                <!-- Table -->
                <CrudTable
                    :data="tableData"
                    :columns="columns"
                    search-placeholder="Buscar cliente..."
                    empty-message="No hay clientes registrados"
                    @view="openView"
                    @edit="openEdit"
                    @delete="handleDelete"
                />

                <!-- Pagination -->
                <div v-if="customers.links?.length > 3" class="pagination">
                    <Link
                        v-for="link in customers.links"
                        :key="link.label"
                        :href="link.url"
                        class="pagination-link"
                        :class="{ 'active': link.active, 'disabled': !link.url }"
                        v-html="link.label"
                    />
                </div>
            </div>

            <!-- View Details Modal -->
            <Teleport to="body">
                <Transition name="modal">
                    <div v-if="showViewModal" class="modal-overlay">
                        <div class="modal-container modal-view">
                            <div class="modal-header">
                                <h2 class="modal-title">Detalle del Cliente</h2>
                                <button class="modal-close" @click="showViewModal = false">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 6L6 18M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="modal-body" v-if="viewingItem">
                                <!-- Header con avatar e info principal -->
                                <div class="view-header">
                                    <div class="view-avatar">
                                        {{ viewingItem.name?.substring(0, 2).toUpperCase() }}
                                    </div>
                                    <div class="view-header-info">
                                        <h3 class="view-name">{{ viewingItem.name }}</h3>
                                        <div class="view-badges">
                                            <span class="badge" :class="viewingItem.type === 'moral' ? 'badge--purple' : 'badge--blue'">
                                                {{ viewingItem.type === 'moral' ? 'Persona Moral' : 'Persona Fisica' }}
                                            </span>
                                            <span class="badge" :class="viewingItem.is_active ? 'badge--green' : 'badge--gray'">
                                                {{ viewingItem.is_active ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Datos estructurados -->
                                <div class="view-section">
                                    <h4 class="section-title">Informacion Personal</h4>

                                    <!-- Para Persona Fisica -->
                                    <template v-if="viewingItem.type === 'physical'">
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
                                    </template>

                                    <!-- Para Persona Moral -->
                                    <template v-else>
                                        <div class="data-row">
                                            <span class="data-label">Razon Social:</span>
                                            <span class="data-value">{{ viewingItem.business_name || '-' }}</span>
                                        </div>
                                    </template>

                                    <div class="data-row">
                                        <span class="data-label">RFC:</span>
                                        <span class="data-value data-mono">{{ viewingItem.rfc || '-' }}</span>
                                    </div>
                                </div>

                                <div class="view-section">
                                    <h4 class="section-title">Contacto</h4>
                                    <div class="data-row">
                                        <span class="data-label">Celular:</span>
                                        <span class="data-value">
                                            <a v-if="viewingItem.mobile" :href="`tel:${viewingItem.mobile}`" class="data-link">
                                                {{ viewingItem.mobile }}
                                            </a>
                                            <span v-else>-</span>
                                        </span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Email:</span>
                                        <span class="data-value">
                                            <a v-if="viewingItem.email" :href="`mailto:${viewingItem.email}`" class="data-link">
                                                {{ viewingItem.email }}
                                            </a>
                                            <span v-else>-</span>
                                        </span>
                                    </div>
                                </div>

                                <div class="view-section">
                                    <h4 class="section-title">Ubicacion</h4>
                                    <div class="data-row">
                                        <span class="data-label">Colonia:</span>
                                        <span class="data-value">{{ viewingItem.neighborhood || '-' }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Ciudad:</span>
                                        <span class="data-value">{{ viewingItem.city || '-' }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Estado:</span>
                                        <span class="data-value">{{ viewingItem.state || '-' }}</span>
                                    </div>
                                    <div class="data-row">
                                        <span class="data-label">Codigo Postal:</span>
                                        <span class="data-value data-mono">{{ viewingItem.zip_code || '-' }}</span>
                                    </div>
                                </div>

                                <div class="view-section">
                                    <h4 class="section-title">Informacion Adicional</h4>
                                    <div class="data-row">
                                        <span class="data-label">Fecha de Registro:</span>
                                        <span class="data-value">{{ viewingItem.created_at || '-' }}</span>
                                    </div>
                                </div>

                                <div class="view-section">
                                    <h4 class="section-title">Estadisticas</h4>
                                    <div class="stats-row">
                                        <div class="stat-box">
                                            <span class="stat-number">{{ viewingItem.quotes_count || 0 }}</span>
                                            <span class="stat-label">Cotizaciones</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn--secondary" @click="editFromView">
                                    Editar
                                </button>
                                <button class="btn btn--danger-outline" @click="deleteFromView">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </Transition>
            </Teleport>

            <!-- Create/Edit Modal -->
            <CrudModal
                :show="showModal"
                :title="isEditing ? 'Editar Cliente' : 'Nuevo Cliente'"
                :loading="form.processing"
                @close="showModal = false"
                @submit="submit"
            >
                <FormSelect
                    v-model="form.type"
                    label="Tipo de Persona"
                    :options="typeOptions"
                    placeholder="Seleccionar tipo..."
                    :error="getError('type')"
                    required
                />

                <!-- Campos para Persona Fisica -->
                <template v-if="isPhysical">
                    <FormInput
                        v-model="form.first_name"
                        label="Nombre(s)"
                        placeholder="Ej: Juan Carlos"
                        :error="getError('first_name')"
                        required
                    />

                    <div class="form-row">
                        <FormInput
                            v-model="form.paternal_surname"
                            label="Apellido Paterno"
                            placeholder="Ej: Perez"
                            :error="getError('paternal_surname')"
                            required
                        />
                        <FormInput
                            v-model="form.maternal_surname"
                            label="Apellido Materno"
                            placeholder="Ej: Garcia"
                            :error="getError('maternal_surname')"
                            required
                        />
                    </div>
                </template>

                <!-- Campos para Persona Moral -->
                <template v-if="form.type === 'moral'">
                    <FormInput
                        v-model="form.business_name"
                        label="Razón Social"
                        placeholder="Ej: Empresa S.A. de C.V."
                        :error="getError('business_name')"
                        required
                    />
                </template>

                <div class="form-row">
                    <FormInput
                        v-model="form.mobile"
                        label="Celular"
                        placeholder="10 dígitos"
                        mask="phone"
                        :error="getError('mobile')"
                        required
                    />
                    <FormInput
                        v-model="form.email"
                        label="Email"
                        type="email"
                        placeholder="cliente@email.com"
                        :error="getError('email')"
                        required
                    />
                </div>

                <FormInput
                    v-model="form.rfc"
                    label="RFC"
                    placeholder="XXXX000000XXX"
                    mask="rfc"
                    :error="getError('rfc')"
                    required
                />

                <FormInput
                    v-model="form.neighborhood"
                    label="Colonia"
                    placeholder="Ej: Centro"
                    :error="getError('neighborhood')"
                    required
                />

                <div class="form-row">
                    <FormInput
                        v-model="form.city"
                        label="Ciudad"
                        placeholder="Merida"
                        :error="getError('city')"
                    />
                    <FormSelect
                        v-model="form.state"
                        label="Estado"
                        :options="mexicanStates"
                        placeholder="Seleccionar estado..."
                        :error="getError('state')"
                    />
                    <FormInput
                        v-model="form.zip_code"
                        label="C.P."
                        placeholder="97000"
                        mask="zipcode"
                        :error="getError('zip_code')"
                    />
                </div>

                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" v-model="form.is_active" class="toggle-input" />
                        <span class="toggle-switch"></span>
                        <span class="toggle-text">Cliente activo</span>
                    </label>
                </div>
            </CrudModal>
</template>

<style scoped>
.page-container {
    padding: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.header-content { flex: 1; }

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.page-subtitle {
    font-size: 0.9375rem;
    color: #6B7280;
    margin: 0.25rem 0 0 0;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
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

.btn--primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(123, 45, 59, 0.3);
}

.btn--secondary {
    background: white;
    border: 1px solid #E5E7EB;
    color: #374151;
}

.btn--secondary:hover {
    border-color: #7B2D3B;
    color: #7B2D3B;
}

.btn--danger-outline {
    background: white;
    border: 1px solid #FCA5A5;
    color: #DC2626;
}

.btn--danger-outline:hover {
    background: #FEE2E2;
}

.btn-icon {
    font-size: 1.125rem;
    font-weight: 400;
}

/* Form Layout */
.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

/* Toggle */
.form-group { margin-bottom: 1rem; }

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

.toggle-input:checked + .toggle-switch {
    background: #059669;
}

.toggle-input:checked + .toggle-switch::after {
    transform: translateX(20px);
}

.toggle-text {
    font-size: 0.9375rem;
    color: #374151;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: 0.25rem;
    margin-top: 1.5rem;
}

.pagination-link {
    padding: 0.5rem 0.875rem;
    border-radius: 8px;
    font-size: 0.875rem;
    color: #374151;
    text-decoration: none;
    transition: background 0.2s;
}

.pagination-link:hover:not(.disabled) { background: #F3F4F6; }
.pagination-link.active { background: #7B2D3B; color: white; }
.pagination-link.disabled { color: #D1D5DB; pointer-events: none; }

/* View Modal */
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
    transition: all 0.2s;
}

.modal-close:hover {
    background: #F3F4F6;
    color: #111827;
}

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

/* View Header */
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

.view-badges {
    display: flex;
    gap: 0.5rem;
}

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

/* View Sections */
.view-section {
    margin-bottom: 1.25rem;
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

.data-row:last-child {
    border-bottom: none;
}

.data-label {
    font-size: 0.875rem;
    color: #6B7280;
}

.data-value {
    font-size: 0.875rem;
    color: #111827;
    font-weight: 500;
}

.data-mono {
    font-family: 'JetBrains Mono', monospace;
}

.data-link {
    color: #7B2D3B;
    text-decoration: none;
}

.data-link:hover {
    text-decoration: underline;
}

/* Stats */
.stats-row {
    display: flex;
    gap: 1rem;
}

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

.stat-label {
    font-size: 0.75rem;
    color: #6B7280;
}

/* Modal Transitions */
.modal-enter-active,
.modal-leave-active {
    transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
    transform: scale(0.95) translateY(10px);
}

@media (max-width: 640px) {
    .page-header { flex-direction: column; align-items: stretch; }
    .btn--primary { justify-content: center; }

    .modal-container {
        max-height: 85vh;
    }

    .view-header {
        flex-direction: column;
        text-align: center;
    }
}
</style>
