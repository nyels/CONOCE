<!-- resources/js/Pages/Admin/Insurers/Index.vue -->
<script setup>
import { ref, computed } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { CrudTable, CrudModal, FormInput, FormImageUpload } from '@/Components/Crud';
import { ConfirmDialog, ToastContainer } from '@/Components/Ui';
import { useConfirm } from '@/composables/useConfirm';
import { useToast } from '@/composables/useToast';

const props = defineProps({
    insurers: { type: Array, default: () => [] }
});

// Toast & Confirm
const { isOpen: confirmOpen, config: confirmConfig, confirmDelete, onConfirm, onCancel } = useConfirm();
const toast = useToast();

// Modal state
const showModal = ref(false);
const isEditing = ref(false);
const editingItem = ref(null);

// Form
const form = useForm({
    id: null,
    name: '',
    short_name: '',
    code: '',
    primary_color: '#7B2D3B',
    logo: null,
    is_active: true
});

// Table columns
const columns = [
    { key: 'logo_url', label: 'Logo', type: 'image' },
    { key: 'name', label: 'Nombre', sortable: true },
    { key: 'short_name', label: 'Abreviatura' },
    { key: 'code', label: 'Código' },
    { key: 'is_active', label: 'Estado', type: 'badge' },
    { key: 'actions', label: 'Acciones', type: 'actions' }
];

// Open create modal
const openCreate = () => {
    form.reset();
    form.clearErrors();
    form.primary_color = '#7B2D3B';
    isEditing.value = false;
    editingItem.value = null;
    showModal.value = true;
};

// Open edit modal
const openEdit = (item) => {
    form.id = item.id;
    form.name = item.name;
    form.short_name = item.short_name || '';
    form.code = item.code || '';
    form.primary_color = item.primary_color ? `#${item.primary_color}` : '#7B2D3B';
    form.logo = null;
    form.is_active = item.is_active;
    isEditing.value = true;
    editingItem.value = item;
    showModal.value = true;
};

// Submit form
const submit = () => {
    const url = isEditing.value 
        ? route('admin.insurers.update', form.id)
        : route('admin.insurers.store');
    
    form.transform((data) => {
        const formData = new FormData();
        formData.append('name', data.name);
        formData.append('short_name', data.short_name || '');
        formData.append('code', data.code || '');
        formData.append('primary_color', data.primary_color.replace('#', ''));
        formData.append('is_active', data.is_active ? '1' : '0');
        if (data.logo) formData.append('logo', data.logo);
        if (isEditing.value) formData.append('_method', 'PUT');
        return formData;
    }).post(url, {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
            toast.success(isEditing.value ? 'Aseguradora actualizada' : 'Aseguradora creada');
        },
        onError: () => {
            toast.error('Error al guardar');
        }
    });
};

// Delete
const handleDelete = async (item) => {
    const confirmed = await confirmDelete(item.name);
    if (confirmed) {
        router.delete(route('admin.insurers.destroy', item.id), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Aseguradora eliminada');
            },
            onError: () => {
                toast.error('No se pudo eliminar');
            }
        });
    }
};

// Current preview URL for editing
const previewUrl = computed(() => {
    return isEditing.value && editingItem.value?.logo_url ? editingItem.value.logo_url : '';
});
</script>

<template>
    <ToastContainer>
        <Head title="Aseguradoras" />
        
        <AppLayout>
            <div class="page-container">
                <!-- Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">🏢 Aseguradoras</h1>
                        <p class="page-subtitle">Gestiona las compañías de seguros disponibles</p>
                    </div>
                    <button class="btn btn--primary" @click="openCreate">
                        <span class="btn-icon">+</span>
                        Nueva Aseguradora
                    </button>
                </div>
                
                <!-- Table -->
                <CrudTable
                    :data="insurers"
                    :columns="columns"
                    search-placeholder="Buscar aseguradora..."
                    empty-message="No hay aseguradoras registradas"
                    @edit="openEdit"
                    @delete="handleDelete"
                />
            </div>
            
            <!-- Create/Edit Modal -->
            <CrudModal
                :show="showModal"
                :title="isEditing ? 'Editar Aseguradora' : 'Nueva Aseguradora'"
                :loading="form.processing"
                size="md"
                @close="showModal = false"
                @submit="submit"
            >
                <div class="form-row">
                    <FormInput
                        v-model="form.name"
                        label="Nombre Completo"
                        placeholder="Ej: HDI Seguros S.A."
                        :error="form.errors.name"
                        required
                    />
                </div>
                
                <div class="form-row form-row--2col">
                    <FormInput
                        v-model="form.short_name"
                        label="Nombre Corto"
                        placeholder="Ej: HDI"
                        :error="form.errors.short_name"
                    />
                    <FormInput
                        v-model="form.code"
                        label="Código"
                        placeholder="Ej: HDI"
                        :error="form.errors.code"
                    />
                </div>
                
                <div class="form-row form-row--2col">
                    <div class="form-group">
                        <label class="form-label">Color Principal</label>
                        <input type="color" v-model="form.primary_color" class="color-input" />
                    </div>
                    <div class="form-group">
                        <label class="toggle-label">
                            <input type="checkbox" v-model="form.is_active" class="toggle-input" />
                            <span class="toggle-switch"></span>
                            <span class="toggle-text">Activa</span>
                        </label>
                    </div>
                </div>
                
                <FormImageUpload
                    v-model="form.logo"
                    label="Logo de la Aseguradora"
                    :preview-url="previewUrl"
                    :error="form.errors.logo"
                />
            </CrudModal>
            
            <!-- Confirm Dialog -->
            <ConfirmDialog
                :show="confirmOpen"
                :title="confirmConfig.title"
                :message="confirmConfig.message"
                :confirm-text="confirmConfig.confirmText"
                :cancel-text="confirmConfig.cancelText"
                :type="confirmConfig.type"
                @confirm="onConfirm"
                @cancel="onCancel"
                @close="onCancel"
            />
        </AppLayout>
    </ToastContainer>
</template>

<style scoped>
.page-container {
    padding: 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.header-content {
    flex: 1;
}

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

.btn-icon {
    font-size: 1.125rem;
    font-weight: 400;
}

/* Form styles */
.form-row {
    margin-bottom: 1rem;
}

.form-row--2col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    margin-bottom: 0;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.375rem;
}

.color-input {
    width: 100%;
    height: 42px;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    padding: 2px;
    cursor: pointer;
}

/* Toggle */
.toggle-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    margin-top: 1.75rem;
}

.toggle-input {
    display: none;
}

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

@media (max-width: 640px) {
    .page-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn--primary {
        justify-content: center;
    }
    
    .form-row--2col {
        grid-template-columns: 1fr;
    }
}
</style>
