<!-- resources/js/Pages/Customers/Index.vue -->
<script setup>
import { ref, computed } from 'vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { CrudTable, CrudModal, FormInput, FormSelect } from '@/Components/Crud';
import { ConfirmDialog, ToastContainer } from '@/Components/Ui';
import { useConfirm } from '@/composables/useConfirm';
import { useToast } from '@/composables/useToast';

const props = defineProps({
    customers: { type: Object, default: () => ({ data: [] }) },
    filters: { type: Object, default: () => ({}) },
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
    phone: '',
    email: '',
    rfc: '',
    type: 'physical',
    address: '',
    city: '',
    state: '',
    zip_code: '',
    is_active: true,
});

// Table columns
const columns = [
    { key: 'name', label: 'Nombre', sortable: true },
    { key: 'phone', label: 'Teléfono' },
    { key: 'email', label: 'Email' },
    { key: 'type_label', label: 'Tipo' },
    { key: 'quotes_count', label: 'Cotizaciones', type: 'number' },
    { key: 'is_active', label: 'Estado', type: 'badge' },
    { key: 'actions', label: 'Acciones', type: 'actions' },
];

// Type options
const typeOptions = [
    { value: 'physical', label: 'Persona Física' },
    { value: 'moral', label: 'Persona Moral' },
];

// Open create modal
const openCreate = () => {
    form.reset();
    form.clearErrors();
    isEditing.value = false;
    editingItem.value = null;
    showModal.value = true;
};

// Open edit modal
const openEdit = (item) => {
    form.id = item.id;
    form.name = item.name;
    form.phone = item.phone || '';
    form.email = item.email || '';
    form.rfc = item.rfc || '';
    form.type = item.type || 'physical';
    form.address = item.address || '';
    form.city = item.city || '';
    form.state = item.state || '';
    form.zip_code = item.zip_code || '';
    form.is_active = item.is_active;
    isEditing.value = true;
    editingItem.value = item;
    showModal.value = true;
};

// Submit form
const submit = () => {
    const url = isEditing.value
        ? route('customers.update', form.id)
        : route('customers.store');

    const method = isEditing.value ? 'put' : 'post';

    form[method](url, {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
            toast.success(isEditing.value ? 'Cliente actualizado' : 'Cliente creado');
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
        router.delete(route('customers.destroy', item.id), {
            preserveScroll: true,
            onSuccess: () => toast.success('Cliente eliminado'),
            onError: () => toast.error('No se pudo eliminar (tiene cotizaciones)'),
        });
    }
};

// View customer
const viewCustomer = (item) => {
    router.visit(route('customers.show', item.id));
};

// Formatted data for table
const tableData = computed(() => {
    if (!props.customers?.data) return [];
    return props.customers.data.map(c => ({
        ...c,
        type_label: c.type === 'moral' ? 'Moral' : 'Física',
    }));
});
</script>

<template>
    <ToastContainer>
        <Head title="Clientes" />

        <AppLayout>
            <div class="page-container">
                <!-- Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">👥 Clientes</h1>
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
                    @edit="openEdit"
                    @delete="handleDelete"
                    @row-click="viewCustomer"
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

            <!-- Create/Edit Modal -->
            <CrudModal
                :show="showModal"
                :title="isEditing ? 'Editar Cliente' : 'Nuevo Cliente'"
                :loading="form.processing"
                @close="showModal = false"
                @submit="submit"
            >
                <FormInput
                    v-model="form.name"
                    label="Nombre Completo"
                    placeholder="Ej: Juan Pérez García"
                    :error="form.errors.name"
                    required
                />

                <div class="form-row">
                    <FormInput
                        v-model="form.phone"
                        label="Teléfono"
                        placeholder="999 123 4567"
                        mask="phone"
                        :error="form.errors.phone"
                    />
                    <FormInput
                        v-model="form.email"
                        label="Email"
                        type="email"
                        placeholder="cliente@email.com"
                        :error="form.errors.email"
                    />
                </div>

                <div class="form-row">
                    <FormInput
                        v-model="form.rfc"
                        label="RFC"
                        placeholder="XXXX000000XXX"
                        mask="rfc"
                        :error="form.errors.rfc"
                    />
                    <FormSelect
                        v-model="form.type"
                        label="Tipo de Persona"
                        :options="typeOptions"
                        :error="form.errors.type"
                    />
                </div>

                <FormInput
                    v-model="form.address"
                    label="Dirección"
                    placeholder="Calle, número, colonia"
                    :error="form.errors.address"
                />

                <div class="form-row">
                    <FormInput
                        v-model="form.city"
                        label="Ciudad"
                        placeholder="Mérida"
                        :error="form.errors.city"
                    />
                    <FormInput
                        v-model="form.state"
                        label="Estado"
                        placeholder="Yucatán"
                        :error="form.errors.state"
                    />
                    <FormInput
                        v-model="form.zip_code"
                        label="C.P."
                        placeholder="97000"
                        mask="zipcode"
                        :error="form.errors.zip_code"
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

@media (max-width: 640px) {
    .page-header { flex-direction: column; align-items: stretch; }
    .btn--primary { justify-content: center; }
}
</style>
