<!-- resources/js/Pages/Admin/PaymentMethods/Index.vue -->
<script setup>
import { ref, computed } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { CrudTable, CrudModal, FormInput } from '@/Components/Crud';
import { ConfirmDialog, ToastContainer } from '@/Components/Ui';
import { useConfirm } from '@/composables/useConfirm';
import { useToast } from '@/composables/useToast';

const props = defineProps({
    methods: { type: Array, default: () => [] }
});

const { isOpen: confirmOpen, config: confirmConfig, confirmDelete, onConfirm, onCancel } = useConfirm();
const toast = useToast();

const showModal = ref(false);
const isEditing = ref(false);
const editingItem = ref(null);

const form = useForm({
    id: null,
    name: '',
    code: '',
    installments: 1,
    surcharge_percentage: 0,
    is_active: true
});

const columns = [
    { key: 'code', label: 'Código' },
    { key: 'name', label: 'Nombre', sortable: true },
    { key: 'installments', label: 'Pagos' },
    { key: 'surcharge_percentage', label: 'Recargo %' },
    { key: 'is_active', label: 'Estado', type: 'badge' },
    { key: 'actions', label: 'Acciones', type: 'actions' }
];

const openCreate = () => {
    form.reset();
    form.clearErrors();
    form.installments = 1;
    form.surcharge_percentage = 0;
    isEditing.value = false;
    editingItem.value = null;
    showModal.value = true;
};

const openEdit = (item) => {
    form.id = item.id;
    form.name = item.name;
    form.code = item.code;
    form.installments = item.installments;
    form.surcharge_percentage = item.surcharge_percentage;
    form.is_active = item.is_active;
    isEditing.value = true;
    editingItem.value = item;
    showModal.value = true;
};

const submit = () => {
    const url = isEditing.value 
        ? route('admin.payment-methods.update', form.id)
        : route('admin.payment-methods.store');
    
    const data = { 
        name: form.name, 
        code: form.code, 
        installments: form.installments, 
        surcharge_percentage: form.surcharge_percentage, 
        is_active: form.is_active 
    };
    if (isEditing.value) data._method = 'PUT';
    
    router.post(url, data, {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
            toast.success(isEditing.value ? 'Forma de pago actualizada' : 'Forma de pago creada');
        },
        onError: (errors) => { form.errors = errors; toast.error('Error al guardar'); }
    });
};

const handleDelete = async (item) => {
    const confirmed = await confirmDelete(item.name);
    if (confirmed) {
        router.delete(route('admin.payment-methods.destroy', item.id), {
            preserveScroll: true,
            onSuccess: () => toast.success('Forma de pago eliminada'),
            onError: () => toast.error('No se pudo eliminar')
        });
    }
};
</script>

<template>
    <ToastContainer>
        <Head title="Formas de Pago" />
        <AppLayout>
            <div class="page-container">
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">💳 Formas de Pago</h1>
                        <p class="page-subtitle">Gestiona las formas de pago y sus recargos</p>
                    </div>
                    <button class="btn btn--primary" @click="openCreate">
                        <span class="btn-icon">+</span>
                        Nueva Forma de Pago
                    </button>
                </div>
                
                <CrudTable :data="methods" :columns="columns" search-placeholder="Buscar forma de pago..." empty-message="No hay formas de pago registradas" @edit="openEdit" @delete="handleDelete" />
            </div>
            
            <CrudModal :show="showModal" :title="isEditing ? 'Editar Forma de Pago' : 'Nueva Forma de Pago'" :loading="form.processing" @close="showModal = false" @submit="submit">
                <div class="form-row form-row--2col">
                    <FormInput v-model="form.code" label="Código" placeholder="Ej: ANU" :error="form.errors.code" required />
                    <FormInput v-model="form.name" label="Nombre" placeholder="Ej: Anual" :error="form.errors.name" required />
                </div>
                <div class="form-row form-row--2col">
                    <FormInput v-model="form.installments" label="Número de Pagos" type="number" placeholder="1" :error="form.errors.installments" required />
                    <FormInput v-model="form.surcharge_percentage" label="Recargo (%)" type="number" step="0.01" placeholder="0" :error="form.errors.surcharge_percentage" required />
                </div>
                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" v-model="form.is_active" class="toggle-input" />
                        <span class="toggle-switch"></span>
                        <span class="toggle-text">Forma de pago activa</span>
                    </label>
                </div>
            </CrudModal>
            
            <ConfirmDialog :show="confirmOpen" :title="confirmConfig.title" :message="confirmConfig.message" :confirm-text="confirmConfig.confirmText" :cancel-text="confirmConfig.cancelText" :type="confirmConfig.type" @confirm="onConfirm" @cancel="onCancel" @close="onCancel" />
        </AppLayout>
    </ToastContainer>
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
