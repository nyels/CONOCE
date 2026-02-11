<!-- resources/js/Pages/Admin/MexicanStates/Index.vue -->
<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
defineOptions({ layout: AppLayout });
import { CrudTable, CrudModal, FormInput } from '@/components/Crud';
import { useConfirm } from '@/composables/useConfirm';
import { useInertiaForm } from '@/composables/useInertiaForm';

const props = defineProps({
    states: { type: Array, default: () => [] }
});

// Composables
const { confirmDelete } = useConfirm();
const { processing, submitForm, deleteRecord } = useInertiaForm();

// Modal state
const showModal = ref(false);
const isEditing = ref(false);
const editingItem = ref(null);

// Frontend validation errors
const validationErrors = ref({});

// Validation functions
const validateName = (value) => {
    if (!value || value.trim().length < 3) return 'El nombre debe tener al menos 3 caracteres';
    if (value.length > 100) return 'El nombre no puede exceder 100 caracteres';
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.\-]+$/.test(value)) {
        return 'Solo se permiten letras, espacios, puntos y guiones';
    }
    return null;
};

const onBlur = (field) => {
    if (field === 'name') validationErrors.value.name = validateName(form.name);
};

const getError = (field) => validationErrors.value[field] || form.errors[field];

const hasValidationErrors = () => {
    validationErrors.value.name = validateName(form.name);
    return Object.values(validationErrors.value).some(error => error !== null);
};

// Form
const form = useForm({
    id: null,
    name: '',
    is_active: true
});

// Table columns
const columns = [
    { key: 'name', label: 'Nombre', sortable: true },
    { key: 'is_active', label: 'Estado', type: 'badge' },
    { key: 'actions', label: 'Acciones', type: 'actions' }
];

// Open create modal
const openCreate = () => {
    form.reset();
    form.clearErrors();
    validationErrors.value = {};
    isEditing.value = false;
    editingItem.value = null;
    showModal.value = true;
};

// Open edit modal
const openEdit = (item) => {
    form.id = item.id;
    form.name = item.name;
    form.is_active = item.is_active;
    isEditing.value = true;
    editingItem.value = item;
    showModal.value = true;
};

// Submit form
const submit = () => {
    if (hasValidationErrors()) {
        return;
    }

    const url = isEditing.value
        ? route('admin.mexican-states.update', form.id)
        : route('admin.mexican-states.store');

    submitForm({
        url,
        data: { name: form.name, is_active: form.is_active },
        method: isEditing.value ? 'put' : 'post',
        onSuccess: () => {
            showModal.value = false;
            form.reset();
        },
        onValidationError: (errors) => {
            Object.keys(errors).forEach(key => {
                validationErrors.value[key] = errors[key];
            });
        },
        successMessage: isEditing.value ? 'Estado actualizado exitosamente' : 'Estado creado exitosamente'
    });
};

// Delete
const handleDelete = async (item) => {
    const confirmed = await confirmDelete(item.name);
    if (confirmed) {
        deleteRecord({
            url: route('admin.mexican-states.destroy', item.id),
            successMessage: 'Estado eliminado exitosamente'
        });
    }
};
</script>

<template>
    <Head title="Estados" />

        <div class="page-container">
            <!-- Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="page-title">Estados de la Rep. Mexicana</h1>
                    <p class="page-subtitle">Gestiona los estados para direcciones de clientes</p>
                </div>
                <button class="btn btn--primary" @click="openCreate">
                    <span class="btn-icon">+</span>
                    Nuevo Estado
                </button>
            </div>

            <!-- Table -->
            <CrudTable
                :data="states"
                :columns="columns"
                search-placeholder="Buscar estado..."
                empty-message="No hay estados registrados"
                @edit="openEdit"
                @delete="handleDelete"
            />
        </div>

        <!-- Create/Edit Modal -->
        <CrudModal
            :show="showModal"
            :title="isEditing ? 'Editar Estado' : 'Nuevo Estado'"
            :loading="processing"
            size="sm"
            @close="showModal = false"
            @submit="submit"
        >
            <FormInput
                v-model="form.name"
                label="Nombre del Estado"
                placeholder="Ej: Aguascalientes"
                :error="getError('name')"
                @blur="onBlur('name')"
                required
            />

            <div class="form-group">
                <label class="toggle-label">
                    <input type="checkbox" v-model="form.is_active" class="toggle-input" />
                    <span class="toggle-switch"></span>
                    <span class="toggle-text">Estado activo</span>
                </label>
            </div>
        </CrudModal>
</template>

<style scoped>
.page-container {
    padding: 1.5rem;
    max-width: 900px;
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

.btn-icon { font-size: 1.125rem; font-weight: 400; }

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

.toggle-input:checked + .toggle-switch { background: #059669; }
.toggle-input:checked + .toggle-switch::after { transform: translateX(20px); }

.toggle-text { font-size: 0.9375rem; color: #374151; }

@media (max-width: 640px) {
    .page-header { flex-direction: column; align-items: stretch; }
    .btn--primary { justify-content: center; }
}
</style>
