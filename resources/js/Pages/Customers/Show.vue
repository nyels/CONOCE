<!-- resources/js/Pages/Customers/Show.vue -->
<script setup>
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
defineOptions({ layout: AppLayout });
import { CrudModal, FormInput, FormSelect } from '@/Components/Crud';
import { ConfirmDialog, ToastContainer } from '@/Components/Ui';
import { useConfirm } from '@/composables/useConfirm';
import { useToast } from '@/composables/useToast';

const props = defineProps({
    customer: { type: Object, required: true },
    recentQuotes: { type: Array, default: () => [] },
});

const { isOpen: confirmOpen, config: confirmConfig, confirm, onConfirm, onCancel } = useConfirm();
const toast = useToast();

// Modal de edición
const showEditModal = ref(false);

// Formulario de edición
const form = useForm({
    id: null,
    type: 'physical',
    first_name: '',
    paternal_surname: '',
    maternal_surname: '',
    business_name: '',
    phone: '',
    email: '',
    rfc: '',
    neighborhood: '',
    city: '',
    state: '',
    zip_code: '',
    is_active: true,
});

// Opciones de tipo
const typeOptions = [
    { value: 'physical', label: 'Persona Física' },
    { value: 'moral', label: 'Persona Moral' },
];

// Abrir modal de edición
const openEditModal = () => {
    form.id = props.customer.id;
    form.type = props.customer.type || 'physical';
    form.first_name = props.customer.first_name || '';
    form.paternal_surname = props.customer.paternal_surname || '';
    form.maternal_surname = props.customer.maternal_surname || '';
    form.business_name = props.customer.business_name || '';
    form.phone = props.customer.phone || '';
    form.email = props.customer.email || '';
    form.rfc = props.customer.rfc || '';
    form.neighborhood = props.customer.neighborhood || '';
    form.city = props.customer.city || '';
    form.state = props.customer.state || '';
    form.zip_code = props.customer.zip_code || '';
    form.is_active = props.customer.is_active;
    form.clearErrors();
    showEditModal.value = true;
};

// Guardar cambios
const submitEdit = () => {
    form.put(route('customers.update', form.id), {
        preserveScroll: true,
        onSuccess: () => {
            showEditModal.value = false;
            toast.success('Cliente actualizado');
        },
        onError: () => {
            toast.error('Error al actualizar');
        }
    });
};

// Eliminar cliente
const handleDelete = async () => {
    const confirmed = await confirm({
        title: 'Eliminar Cliente',
        message: `¿Estás seguro de eliminar a ${props.customer.name}?`,
        confirmText: 'Eliminar',
        type: 'danger',
    });
    if (confirmed) {
        router.delete(route('customers.destroy', props.customer.id), {
            onSuccess: () => {
                toast.success('Cliente eliminado');
                router.visit(route('customers.index'));
            },
            onError: () => toast.error('No se puede eliminar (tiene cotizaciones)'),
        });
    }
};

const formatCurrency = (v) => new Intl.NumberFormat('es-MX', {
    style: 'currency',
    currency: 'MXN',
    minimumFractionDigits: 0
}).format(v);

// Computed para tipo
const isPhysical = () => form.type === 'physical';
</script>

<template>
    <ToastContainer>
        <Head :title="customer.name" />

            <div class="page-container">
                <!-- Breadcrumb -->
                <div class="breadcrumb">
                    <Link :href="route('customers.index')" class="breadcrumb-link">Clientes</Link>
                    <span class="breadcrumb-sep">/</span>
                    <span class="breadcrumb-current">{{ customer.name }}</span>
                </div>

                <!-- Header -->
                <div class="page-header">
                    <div class="header-content">
                        <div class="customer-avatar">{{ customer.initials || customer.name?.substring(0, 2).toUpperCase() }}</div>
                        <div class="header-info">
                            <h1 class="page-title">{{ customer.name }}</h1>
                            <div class="header-meta">
                                <span class="meta-badge" :class="customer.type === 'moral' ? 'meta-badge--purple' : 'meta-badge--blue'">
                                    {{ customer.type === 'moral' ? 'Persona Moral' : 'Persona Física' }}
                                </span>
                                <span v-if="customer.is_active" class="meta-badge meta-badge--green">Activo</span>
                                <span v-else class="meta-badge meta-badge--gray">Inactivo</span>
                            </div>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button class="btn btn--secondary" @click="openEditModal">
                            ✏️ Editar
                        </button>
                        <button class="btn btn--danger-outline" @click="handleDelete">
                            🗑️ Eliminar
                        </button>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="content-grid">
                    <!-- Contact Info -->
                    <div class="card">
                        <h3 class="card-title">📞 Información de Contacto</h3>
                        <div class="info-list">
                            <div class="info-item" v-if="customer.phone">
                                <span class="info-icon">📱</span>
                                <div>
                                    <span class="info-label">Teléfono</span>
                                    <a :href="`tel:${customer.phone}`" class="info-value info-link">{{ customer.phone }}</a>
                                </div>
                            </div>
                            <div class="info-item" v-if="customer.email">
                                <span class="info-icon">✉️</span>
                                <div>
                                    <span class="info-label">Email</span>
                                    <a :href="`mailto:${customer.email}`" class="info-value info-link">{{ customer.email }}</a>
                                </div>
                            </div>
                            <div class="info-item" v-if="customer.rfc">
                                <span class="info-icon">📄</span>
                                <div>
                                    <span class="info-label">RFC</span>
                                    <span class="info-value info-mono">{{ customer.rfc }}</span>
                                </div>
                            </div>
                            <div class="info-item" v-if="customer.address">
                                <span class="info-icon">📍</span>
                                <div>
                                    <span class="info-label">Dirección</span>
                                    <span class="info-value">{{ customer.formatted_address || customer.address }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="card">
                        <h3 class="card-title">📊 Estadísticas</h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-value">{{ customer.quotes_count || 0 }}</span>
                                <span class="stat-label">Cotizaciones</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value">{{ customer.policies_count || 0 }}</span>
                                <span class="stat-label">Pólizas</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Quotes -->
                <div class="card" v-if="recentQuotes.length">
                    <h3 class="card-title">📋 Cotizaciones Recientes</h3>
                    <div class="quotes-list">
                        <Link
                            v-for="quote in recentQuotes"
                            :key="quote.id"
                            :href="route('quotes.show', quote.id)"
                            class="quote-item"
                        >
                            <div class="quote-folio">{{ quote.folio }}</div>
                            <div class="quote-vehicle">{{ quote.vehicle_description || 'Sin vehículo' }}</div>
                            <div class="quote-status" :class="`quote-status--${quote.status}`">{{ quote.status_label }}</div>
                            <div class="quote-date">{{ quote.created_at }}</div>
                        </Link>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <Link :href="route('quotes.create') + `?customer_id=${customer.id}`" class="action-card">
                        <span class="action-icon">📝</span>
                        <span class="action-text">Nueva Cotización</span>
                    </Link>
                </div>
            </div>

            <!-- Modal de Edición -->
            <CrudModal
                :show="showEditModal"
                title="Editar Cliente"
                :loading="form.processing"
                @close="showEditModal = false"
                @submit="submitEdit"
            >
                <FormSelect
                    v-model="form.type"
                    label="Tipo de Persona"
                    :options="typeOptions"
                    :error="form.errors.type"
                    required
                />

                <!-- Campos para Persona Física -->
                <template v-if="isPhysical()">
                    <FormInput
                        v-model="form.first_name"
                        label="Nombre(s)"
                        placeholder="Ej: Juan Carlos"
                        :error="form.errors.first_name"
                        required
                    />

                    <div class="form-row">
                        <FormInput
                            v-model="form.paternal_surname"
                            label="Apellido Paterno"
                            placeholder="Ej: Pérez"
                            :error="form.errors.paternal_surname"
                            required
                        />
                        <FormInput
                            v-model="form.maternal_surname"
                            label="Apellido Materno"
                            placeholder="Ej: García"
                            :error="form.errors.maternal_surname"
                            required
                        />
                    </div>
                </template>

                <!-- Campos para Persona Moral -->
                <template v-else>
                    <FormInput
                        v-model="form.business_name"
                        label="Razón Social"
                        placeholder="Ej: Empresa S.A. de C.V."
                        :error="form.errors.business_name"
                        required
                    />
                </template>

                <div class="form-row">
                    <FormInput
                        v-model="form.phone"
                        label="Celular"
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

                <FormInput
                    v-model="form.rfc"
                    label="RFC"
                    placeholder="XXXX000000XXX"
                    mask="rfc"
                    :error="form.errors.rfc"
                />

                <FormInput
                    v-model="form.neighborhood"
                    label="Colonia"
                    placeholder="Ej: Centro"
                    :error="form.errors.neighborhood"
                    required
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
                :type="confirmConfig.type"
                @confirm="onConfirm"
                @cancel="onCancel"
                @close="onCancel"
            />
    </ToastContainer>
</template>

<style scoped>
.page-container {
    padding: 1.5rem;
    max-width: 1000px;
    margin: 0 auto;
}

/* Breadcrumb */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.breadcrumb-link {
    color: #6B7280;
    text-decoration: none;
}

.breadcrumb-link:hover { color: #7B2D3B; }
.breadcrumb-sep { color: #D1D5DB; }
.breadcrumb-current { color: #111827; font-weight: 500; }

/* Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1.5rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.customer-avatar {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: white;
    font-size: 1.5rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.header-meta {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.meta-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.meta-badge--blue { background: #DBEAFE; color: #1D4ED8; }
.meta-badge--purple { background: #EDE9FE; color: #7C3AED; }
.meta-badge--green { background: #D1FAE5; color: #059669; }
.meta-badge--gray { background: #F3F4F6; color: #6B7280; }

.header-actions {
    display: flex;
    gap: 0.75rem;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
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

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

/* Cards */
.card {
    background: white;
    border-radius: 16px;
    border: 1px solid #E5E7EB;
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.card-title {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 1rem 0;
}

/* Info List */
.info-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.info-icon {
    font-size: 1.25rem;
}

.info-label {
    display: block;
    font-size: 0.75rem;
    color: #6B7280;
    margin-bottom: 0.125rem;
}

.info-value {
    font-size: 0.9375rem;
    color: #111827;
}

.info-link {
    color: #7B2D3B;
    text-decoration: none;
}

.info-link:hover { text-decoration: underline; }

.info-mono {
    font-family: 'JetBrains Mono', monospace;
}

/* Stats */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: #F9FAFB;
    border-radius: 12px;
}

.stat-value {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #7B2D3B;
}

.stat-label {
    font-size: 0.75rem;
    color: #6B7280;
}

/* Quotes List */
.quotes-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.quote-item {
    display: grid;
    grid-template-columns: auto 1fr auto auto;
    gap: 1rem;
    padding: 0.75rem 1rem;
    background: #F9FAFB;
    border-radius: 10px;
    text-decoration: none;
    color: inherit;
    transition: background 0.2s;
}

.quote-item:hover { background: #F3F4F6; }

.quote-folio {
    font-family: 'JetBrains Mono', monospace;
    font-weight: 600;
    color: #7B2D3B;
}

.quote-vehicle {
    color: #374151;
}

.quote-status {
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.quote-status--draft { background: #F3F4F6; color: #6B7280; }
.quote-status--sent { background: #DBEAFE; color: #1D4ED8; }
.quote-status--concreted { background: #D1FAE5; color: #059669; }

.quote-date {
    font-size: 0.8125rem;
    color: #9CA3AF;
}

/* Quick Actions */
.quick-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.action-card {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.2s;
}

.action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(123, 45, 59, 0.3);
}

.action-icon { font-size: 1.25rem; }
.action-text { font-weight: 600; }

/* Form Layout (para el modal) */
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

/* ===== RESPONSIVE — FASE 2 ===== */
@media (max-width: 768px) {
    .quote-item {
        grid-template-columns: 1fr auto;
        gap: 0.5rem;
    }

    .quote-vehicle {
        grid-column: 1 / -1;
    }
}
</style>
