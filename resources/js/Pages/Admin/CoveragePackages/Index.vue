<!-- resources/js/Pages/Admin/CoveragePackages/Index.vue -->
<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
defineOptions({ layout: AppLayout });
import { CrudTable, CrudModal, FormInput } from '@/components/Crud';
import { useConfirm } from '@/composables/useConfirm';
import { useInertiaForm } from '@/composables/useInertiaForm';

const props = defineProps({
    packages: { type: Array, default: () => [] }
});

const { confirmDelete } = useConfirm();
const { processing, submitForm, deleteRecord } = useInertiaForm();

const showModal = ref(false);
const isEditing = ref(false);
const editingItem = ref(null);

// Frontend validation errors
const validationErrors = ref({});

// Validation functions
const validateName = (value) => {
    if (!value || value.trim().length < 2) return 'El nombre debe tener al menos 2 caracteres';
    if (value.length > 50) return 'El nombre no puede exceder 50 caracteres';
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\-\.]+$/.test(value)) {
        return 'Solo se permiten letras, números, espacios, guiones y puntos';
    }
    return null;
};

const validateCode = (value) => {
    if (!value || value.trim().length < 2) return 'El código debe tener al menos 2 caracteres';
    if (value.length > 10) return 'El código no puede exceder 10 caracteres';
    if (!/^[A-Z0-9_\-]+$/i.test(value)) {
        return 'Solo se permiten letras, números, guiones y guiones bajos';
    }
    return null;
};

const validateDescription = (value) => {
    if (!value) return null;
    if (value.length > 255) return 'La descripción no puede exceder 255 caracteres';
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\-\.\,]+$/.test(value)) {
        return 'La descripción contiene caracteres no permitidos';
    }
    return null;
};

const onBlur = (field) => {
    if (field === 'name') validationErrors.value.name = validateName(form.name);
    if (field === 'code') validationErrors.value.code = validateCode(form.code);
    if (field === 'description') validationErrors.value.description = validateDescription(form.description);
};

const getError = (field) => validationErrors.value[field] || form.errors[field];

const hasValidationErrors = () => {
    validationErrors.value.name = validateName(form.name);
    validationErrors.value.code = validateCode(form.code);
    validationErrors.value.description = validateDescription(form.description);
    return Object.values(validationErrors.value).some(error => error !== null);
};

const form = useForm({
    id: null,
    name: '',
    code: '',
    description: '',
    is_active: true
});

const columns = [
    { key: 'code', label: 'Código', sortable: true },
    { key: 'name', label: 'Nombre', sortable: true },
    { key: 'description', label: 'Descripción', sortable: true },
    { key: 'is_active', label: 'Estado', type: 'badge', sortable: true },
    { key: 'actions', label: 'Acciones', type: 'actions' }
];

const openCreate = () => {
    form.reset();
    form.clearErrors();
    validationErrors.value = {};
    isEditing.value = false;
    editingItem.value = null;
    showModal.value = true;
};

const openEdit = (item) => {
    form.id = item.id;
    form.name = item.name;
    form.code = item.code;
    form.description = item.description || '';
    form.is_active = item.is_active;
    isEditing.value = true;
    editingItem.value = item;
    showModal.value = true;
};

const submit = () => {
    if (hasValidationErrors()) {
        return;
    }

    const url = isEditing.value
        ? route('admin.coverage-packages.update', form.id)
        : route('admin.coverage-packages.store');

    submitForm({
        url,
        data: { name: form.name, code: form.code, description: form.description, is_active: form.is_active },
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
        successMessage: isEditing.value ? 'Paquete actualizado exitosamente' : 'Paquete creado exitosamente'
    });
};

const handleDelete = async (item) => {
    const confirmed = await confirmDelete(item.name);
    if (confirmed) {
        deleteRecord({
            url: route('admin.coverage-packages.destroy', item.id),
            successMessage: 'Paquete eliminado exitosamente'
        });
    }
};
</script>

<template>
    <Head title="Paquetes de Cobertura" />
            <div class="page-container">
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">📦 Paquetes de Cobertura</h1>
                        <p class="page-subtitle">Gestiona los tipos de paquetes de seguro</p>
                    </div>
                    <button class="btn btn--primary" @click="openCreate">
                        <span class="btn-icon">+</span>
                        Nuevo Paquete
                    </button>
                </div>
                
                <CrudTable :data="packages" :columns="columns" search-placeholder="Buscar paquete..." empty-message="No hay paquetes registrados" @edit="openEdit" @delete="handleDelete" />
            </div>
            
            <CrudModal :show="showModal" :title="isEditing ? 'Editar Paquete' : 'Nuevo Paquete'" :loading="processing" @close="showModal = false" @submit="submit">
                <div class="form-row form-row--2col">
                    <FormInput v-model="form.code" label="Código" placeholder="Ej: AMP" :error="getError('code')" @blur="onBlur('code')" required />
                    <FormInput v-model="form.name" label="Nombre" placeholder="Ej: Amplio" :error="getError('name')" @blur="onBlur('name')" required />
                </div>
                <FormInput v-model="form.description" label="Descripción" placeholder="Descripción del paquete..." :error="getError('description')" @blur="onBlur('description')" />
                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" v-model="form.is_active" class="toggle-input" />
                        <span class="toggle-switch"></span>
                        <span class="toggle-text">Paquete activo</span>
                    </label>
                </div>
            </CrudModal>
</template>

<style scoped>
.page-container { padding: 1.5rem; max-width: 1000px; margin: 0 auto; }
.page-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; }
.header-content { flex: 1; }
.page-title { font-size: 1.5rem; font-weight: 700; color: #111827; margin: 0; }
.page-subtitle { font-size: 0.9375rem; color: #6B7280; margin: 0.25rem 0 0 0; }
.btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; border-radius: 12px; font-weight: 600; font-size: 0.9375rem; border: none; cursor: pointer; transition: all 0.2s; }
.btn--primary { background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%); color: white; }
.btn--primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(123, 45, 59, 0.3); }
.btn-icon { font-size: 1.125rem; font-weight: 400; }
.form-row { margin-bottom: 1rem; }
.form-row--2col { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.form-group { margin-bottom: 1rem; }
.toggle-label { display: flex; align-items: center; gap: 0.75rem; cursor: pointer; }
.toggle-input { display: none; }
.toggle-switch { width: 44px; height: 24px; background: #E5E7EB; border-radius: 12px; position: relative; transition: background 0.2s; }
.toggle-switch::after { content: ''; position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background: white; border-radius: 50%; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15); transition: transform 0.2s; }
.toggle-input:checked + .toggle-switch { background: #059669; }
.toggle-input:checked + .toggle-switch::after { transform: translateX(20px); }
.toggle-text { font-size: 0.9375rem; color: #374151; }
@media (max-width: 640px) { .page-header { flex-direction: column; align-items: stretch; } .btn--primary { justify-content: center; } .form-row--2col { grid-template-columns: 1fr; } }
</style>
