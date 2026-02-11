<!-- resources/js/Pages/Contacts/Show.vue -->
<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
defineOptions({ layout: AppLayout });
import { useConfirm } from '@/composables/useConfirm';
import { useToast } from '@/composables/useToast';
import { ConfirmDialog, ToastContainer } from '@/Components/Ui';

const props = defineProps({
    contact: { type: Object, required: true },
});

const { isOpen: confirmOpen, config: confirmConfig, confirmDelete, onConfirm, onCancel } = useConfirm();
const toast = useToast();

// Delete contact
const handleDelete = async () => {
    const confirmed = await confirmDelete(props.contact.name);
    if (confirmed) {
        router.delete(route('contacts.destroy', props.contact.id), {
            onSuccess: () => toast.success('Contacto eliminado'),
            onError: () => toast.error('No se pudo eliminar'),
        });
    }
};

// Toggle active
const toggleActive = () => {
    router.patch(route('contacts.toggle-active', props.contact.id), {}, {
        preserveScroll: true,
        onSuccess: () => toast.success(props.contact.is_active ? 'Contacto desactivado' : 'Contacto activado'),
    });
};

// Type badge color
const getTypeColor = (type) => {
    const colors = {
        AGENT: 'bg-purple-100 text-purple-800',
        SUB_AGENT: 'bg-indigo-100 text-indigo-800',
        EMPLOYEE: 'bg-blue-100 text-blue-800',
        DIRECT: 'bg-green-100 text-green-800',
    };
    return colors[type] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <ToastContainer>
        <Head :title="`Contacto: ${contact.name}`" />

        <div class="page-container">
                <!-- Header -->
                <div class="page-header">
                    <div class="header-left">
                        <Link :href="route('contacts.index')" class="back-link">
                            &larr; Volver a contactos
                        </Link>
                        <h1 class="page-title">{{ contact.name }}</h1>
                        <div class="header-meta">
                            <span :class="['type-badge', getTypeColor(contact.type)]">
                                {{ contact.type_label }}
                            </span>
                            <span :class="['status-badge', contact.is_active ? 'status-active' : 'status-inactive']">
                                {{ contact.is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button class="btn btn--secondary" @click="toggleActive">
                            {{ contact.is_active ? 'Desactivar' : 'Activar' }}
                        </button>
                        <Link :href="route('contacts.edit', contact.id)" class="btn btn--primary">
                            Editar
                        </Link>
                        <button class="btn btn--danger" @click="handleDelete">
                            Eliminar
                        </button>
                    </div>
                </div>

                <div class="content-grid">
                    <!-- Main Info -->
                    <div class="card">
                        <h2 class="card-title">Información General</h2>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Nombre</label>
                                <span>{{ contact.name }}</span>
                            </div>
                            <div class="info-item">
                                <label>Tipo</label>
                                <span>{{ contact.type_label }}</span>
                            </div>
                            <div class="info-item">
                                <label>Email</label>
                                <span>{{ contact.email || '-' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Teléfono</label>
                                <span>{{ contact.phone || '-' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Celular</label>
                                <span>{{ contact.mobile || '-' }}</span>
                            </div>
                            <div class="info-item" v-if="contact.commission_rate">
                                <label>Comisión</label>
                                <span>{{ contact.commission_rate }}%</span>
                            </div>
                        </div>
                    </div>

                    <!-- CNSF Info (for agents) -->
                    <div class="card" v-if="contact.type === 'AGENT'">
                        <h2 class="card-title">Datos de Cédula CNSF</h2>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Número de Cédula</label>
                                <span>{{ contact.cnsf_license || '-' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Vencimiento</label>
                                <span :class="{ 'text-danger': !contact.license_valid }">
                                    {{ contact.license_expiry || '-' }}
                                    <span v-if="contact.license_expiry" class="license-status">
                                        ({{ contact.license_valid ? 'Vigente' : 'Vencida' }})
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Parent Agent (for sub-agents) -->
                    <div class="card" v-if="contact.parent_agent">
                        <h2 class="card-title">Agente Padre</h2>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Nombre</label>
                                <Link :href="route('contacts.show', contact.parent_agent.id)" class="link">
                                    {{ contact.parent_agent.name }}
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Sub-agents (for agents) -->
                    <div class="card" v-if="contact.sub_agents?.length">
                        <h2 class="card-title">Subagentes ({{ contact.sub_agents.length }})</h2>
                        <ul class="related-list">
                            <li v-for="sub in contact.sub_agents" :key="sub.id">
                                <Link :href="route('contacts.show', sub.id)" class="link">
                                    {{ sub.name }}
                                </Link>
                                <span v-if="!sub.is_active" class="inactive-tag">Inactivo</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Customers -->
                    <div class="card" v-if="contact.customers?.length">
                        <h2 class="card-title">Clientes Referidos ({{ contact.customers.length }})</h2>
                        <ul class="related-list">
                            <li v-for="customer in contact.customers" :key="customer.id">
                                <Link :href="route('customers.show', customer.id)" class="link">
                                    {{ customer.name }}
                                </Link>
                                <span class="secondary-text">{{ customer.phone }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Quotes -->
                    <div class="card" v-if="contact.quotes?.length">
                        <h2 class="card-title">Cotizaciones Recientes ({{ contact.quotes.length }})</h2>
                        <ul class="related-list">
                            <li v-for="quote in contact.quotes" :key="quote.id">
                                <Link :href="route('quotes.show', quote.id)" class="link">
                                    {{ quote.folio }}
                                </Link>
                                <span class="secondary-text">{{ quote.created_at }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Notes -->
                    <div class="card" v-if="contact.notes">
                        <h2 class="card-title">Notas</h2>
                        <p class="notes-text">{{ contact.notes }}</p>
                    </div>

                    <!-- Meta -->
                    <div class="card card--meta">
                        <div class="meta-info">
                            <span>Creado por: {{ contact.created_by || 'Sistema' }}</span>
                            <span>Fecha: {{ contact.created_at }}</span>
                        </div>
                    </div>
                </div>

                <!-- Confirm Dialog -->
                <ConfirmDialog
                    :show="confirmOpen"
                    :config="confirmConfig"
                    @confirm="onConfirm"
                    @cancel="onCancel"
                />
            </div>
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
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-left {
    flex: 1;
}

.back-link {
    color: #6B7280;
    font-size: 0.875rem;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 0.5rem;
}

.back-link:hover {
    color: #7B2D3B;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 0.5rem;
}

.header-meta {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.header-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
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
    text-decoration: none;
}

.btn--primary {
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: white;
}

.btn--secondary {
    background: white;
    color: #374151;
    border: 1px solid #D1D5DB;
}

.btn--danger {
    background: #FEE2E2;
    color: #991B1B;
}

.btn--danger:hover {
    background: #FECACA;
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
}

.status-active {
    background: #D1FAE5;
    color: #065F46;
}

.status-inactive {
    background: #FEE2E2;
    color: #991B1B;
}

/* Content grid */
.content-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.card--meta {
    grid-column: 1 / -1;
    background: #F9FAFB;
}

.card-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #E5E7EB;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-item label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
}

.info-item span {
    font-size: 0.875rem;
    color: #1F2937;
}

.text-danger {
    color: #DC2626;
}

.license-status {
    font-size: 0.75rem;
    margin-left: 0.25rem;
}

.related-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.related-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #F3F4F6;
}

.related-list li:last-child {
    border-bottom: none;
}

.link {
    color: #7B2D3B;
    text-decoration: none;
    font-weight: 500;
}

.link:hover {
    text-decoration: underline;
}

.secondary-text {
    color: #6B7280;
    font-size: 0.875rem;
}

.inactive-tag {
    background: #FEE2E2;
    color: #991B1B;
    padding: 0.125rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
}

.notes-text {
    color: #374151;
    white-space: pre-wrap;
    margin: 0;
}

.meta-info {
    display: flex;
    justify-content: space-between;
    color: #6B7280;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .content-grid {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
