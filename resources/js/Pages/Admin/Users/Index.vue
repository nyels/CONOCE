<!-- resources/js/Pages/Admin/Users/Index.vue -->
<script setup>
import { ref, computed } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { CrudTable, CrudModal, FormInput, FormSelect } from '@/Components/Crud';
import { ConfirmDialog, ToastContainer } from '@/Components/Ui';
import { useConfirm } from '@/composables/useConfirm';
import { useToast } from '@/composables/useToast';

const props = defineProps({
    users: { type: Array, default: () => [] },
    roles: { type: Array, default: () => [] }
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
    email: '',
    password: '',
    role: 'operator',
    phone: '',
    is_active: true
});

// Table columns with role badge
const columns = [
    { key: 'name', label: 'Nombre', sortable: true },
    { key: 'email', label: 'Email', sortable: true },
    { key: 'role_label', label: 'Rol' },
    { key: 'phone', label: 'Teléfono' },
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

// Open create modal
const openCreate = () => {
    form.reset();
    form.clearErrors();
    form.role = 'operator';
    isEditing.value = false;
    editingItem.value = null;
    showModal.value = true;
};

// Open edit modal
const openEdit = (item) => {
    form.id = item.id;
    form.name = item.name;
    form.email = item.email;
    form.password = '';
    form.role = item.role;
    form.phone = item.phone || '';
    form.is_active = item.is_active;
    isEditing.value = true;
    editingItem.value = item;
    showModal.value = true;
};

// Submit form
const submit = () => {
    const url = isEditing.value 
        ? route('admin.users.update', form.id)
        : route('admin.users.store');
    
    const data = {
        name: form.name,
        email: form.email,
        role: form.role,
        phone: form.phone || null,
        is_active: form.is_active,
    };
    
    if (form.password) {
        data.password = form.password;
    }
    
    if (isEditing.value) {
        data._method = 'PUT';
    }
    
    router.post(url, data, {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
            toast.success(isEditing.value ? 'Usuario actualizado' : 'Usuario creado');
        },
        onError: (errors) => {
            form.errors = errors;
            toast.error('Error al guardar');
        }
    });
};

// Delete
const handleDelete = async (item) => {
    const confirmed = await confirmDelete(item.name);
    if (confirmed) {
        router.delete(route('admin.users.destroy', item.id), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Usuario eliminado');
            },
            onError: () => {
                toast.error('No se pudo eliminar');
            }
        });
    }
};
</script>

<template>
    <ToastContainer>
        <Head title="Usuarios" />
        
        <AppLayout>
            <div class="page-container">
                <!-- Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">👥 Usuarios del Sistema</h1>
                        <p class="page-subtitle">Gestiona los usuarios y sus roles de acceso</p>
                    </div>
                    <button class="btn btn--primary" @click="openCreate">
                        <span class="btn-icon">+</span>
                        Nuevo Usuario
                    </button>
                </div>
                
                <!-- Table -->
                <CrudTable
                    :data="users"
                    :columns="columns"
                    search-placeholder="Buscar usuario..."
                    empty-message="No hay usuarios registrados"
                    @edit="openEdit"
                    @delete="handleDelete"
                />
            </div>
            
            <!-- Create/Edit Modal -->
            <CrudModal
                :show="showModal"
                :title="isEditing ? 'Editar Usuario' : 'Nuevo Usuario'"
                :loading="form.processing"
                size="md"
                @close="showModal = false"
                @submit="submit"
            >
                <FormInput
                    v-model="form.name"
                    label="Nombre Completo"
                    placeholder="Ej: María García López"
                    :error="form.errors.name"
                    required
                />
                
                <div class="form-row form-row--2col">
                    <FormInput
                        v-model="form.email"
                        label="Email"
                        type="email"
                        placeholder="usuario@conoce.com"
                        :error="form.errors.email"
                        required
                    />
                    <FormInput
                        v-model="form.phone"
                        label="Teléfono"
                        placeholder="555-0000"
                        :error="form.errors.phone"
                    />
                </div>
                
                <div class="form-row form-row--2col">
                    <FormInput
                        v-model="form.password"
                        label="Contraseña"
                        type="password"
                        :placeholder="isEditing ? 'Dejar vacío para mantener' : 'Mínimo 8 caracteres'"
                        :error="form.errors.password"
                        :required="!isEditing"
                    />
                    <FormSelect
                        v-model="form.role"
                        label="Rol"
                        :options="roleOptions"
                        :error="form.errors.role"
                        required
                    />
                </div>
                
                <div class="form-group">
                    <label class="toggle-label">
                        <input type="checkbox" v-model="form.is_active" class="toggle-input" />
                        <span class="toggle-switch"></span>
                        <span class="toggle-text">Usuario activo</span>
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
    margin-bottom: 1rem;
}

/* Toggle */
.toggle-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
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
