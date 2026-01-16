<!-- resources/js/Pages/Quotes/Index.vue -->
<script setup>
import { ref, computed } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ConfirmDialog, ToastContainer } from '@/Components/Ui';
import { useConfirm } from '@/composables/useConfirm';
import { useToast } from '@/composables/useToast';

const props = defineProps({
    quotes: { type: Object, default: () => ({ data: [] }) },
    filters: { type: Object, default: () => ({}) },
    statuses: { type: Array, default: () => [] },
});

// Toast & Confirm
const { isOpen: confirmOpen, config: confirmConfig, confirmDelete, onConfirm, onCancel } = useConfirm();
const toast = useToast();

// Search & filters
const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');

const applyFilters = () => {
    router.get(route('quotes.index'), {
        search: search.value,
        status: statusFilter.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Delete quote
let deleteItem = null;
const handleDelete = async (quote) => {
    deleteItem = quote;
    const confirmed = await confirmDelete(`Cotización ${quote.folio}`);
    if (confirmed) {
        router.delete(route('quotes.destroy', quote.id), {
            preserveScroll: true,
            onSuccess: () => toast.success('Cotización eliminada'),
            onError: () => toast.error('No se pudo eliminar'),
        });
    }
};

// Send quote
const handleSend = (quote) => {
    router.post(route('quotes.send', quote.id), {}, {
        preserveScroll: true,
        onSuccess: () => toast.success(`Cotización ${quote.folio} enviada`),
        onError: () => toast.error('No se pudo enviar'),
    });
};

// Format currency
const formatCurrency = (v) => new Intl.NumberFormat('es-MX', { 
    style: 'currency', 
    currency: 'MXN',
    minimumFractionDigits: 0 
}).format(v);

// Status badge color
const statusColors = {
    draft: 'gray',
    sent: 'blue',
    concreted: 'green',
    issued: 'emerald',
    rejected: 'red',
    expired: 'amber',
    annulled: 'slate',
};
</script>

<template>
    <ToastContainer>
        <Head title="Cotizaciones" />
        
        <AppLayout>
            <div class="page-container">
                <!-- Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">📋 Cotizaciones</h1>
                        <p class="page-subtitle">Gestiona las cotizaciones de seguros</p>
                    </div>
                    <Link :href="route('quotes.create')" class="btn btn--primary">
                        <span class="btn-icon">+</span>
                        Nueva Cotización
                    </Link>
                </div>

                <!-- Filters -->
                <div class="filters">
                    <input 
                        v-model="search"
                        type="text" 
                        placeholder="Buscar por folio, cliente..."
                        class="filter-input"
                        @keyup.enter="applyFilters"
                    >
                    <select v-model="statusFilter" class="filter-select" @change="applyFilters">
                        <option value="">Todos los estados</option>
                        <option v-for="status in statuses" :key="status.value" :value="status.value">
                            {{ status.label }}
                        </option>
                    </select>
                    <button class="btn btn--secondary" @click="applyFilters">Filtrar</button>
                </div>

                <!-- Table -->
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Cliente</th>
                                <th>Vehículo</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="quote in quotes.data" :key="quote.id">
                                <td>
                                    <Link :href="route('quotes.show', quote.id)" class="folio-link">
                                        {{ quote.folio }}
                                    </Link>
                                </td>
                                <td>{{ quote.customer_name }}</td>
                                <td class="vehicle-cell">{{ quote.vehicle || '—' }}</td>
                                <td>
                                    <span class="type-badge">{{ quote.type_label }}</span>
                                </td>
                                <td>
                                    <span 
                                        class="status-badge"
                                        :class="`status-badge--${quote.status}`"
                                    >
                                        {{ quote.status_label }}
                                    </span>
                                </td>
                                <td class="options-count">{{ quote.options_count }}</td>
                                <td class="date-cell">{{ quote.created_at }}</td>
                                <td>
                                    <div class="actions">
                                        <Link :href="route('quotes.show', quote.id)" class="action-btn" title="Ver">
                                            👁️
                                        </Link>
                                        <button 
                                            v-if="quote.status === 'draft'"
                                            class="action-btn" 
                                            title="Enviar"
                                            @click="handleSend(quote)"
                                        >
                                            📤
                                        </button>
                                        <Link 
                                            v-if="quote.status === 'draft'"
                                            :href="route('quotes.edit', quote.id)" 
                                            class="action-btn"
                                            title="Editar"
                                        >
                                            ✏️
                                        </Link>
                                        <button 
                                            v-if="quote.status === 'draft'"
                                            class="action-btn action-btn--danger" 
                                            title="Eliminar"
                                            @click="handleDelete(quote)"
                                        >
                                            🗑️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!quotes.data?.length">
                                <td colspan="8" class="empty-row">
                                    No hay cotizaciones registradas
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="quotes.links?.length > 3" class="pagination">
                    <Link 
                        v-for="link in quotes.links"
                        :key="link.label"
                        :href="link.url"
                        class="pagination-link"
                        :class="{ 'active': link.active, 'disabled': !link.url }"
                        v-html="link.label"
                    />
                </div>
            </div>

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

/* Filters */
.filters {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.filter-input, .filter-select {
    padding: 0.625rem 1rem;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 0.9375rem;
    background: white;
}

.filter-input { flex: 1; min-width: 200px; }
.filter-select { min-width: 180px; }

.filter-input:focus, .filter-select:focus {
    outline: none;
    border-color: #7B2D3B;
    box-shadow: 0 0 0 3px rgba(123,45,59,0.1);
}

/* Table */
.table-container {
    background: white;
    border-radius: 16px;
    border: 1px solid #E5E7EB;
    overflow: hidden;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th {
    background: #F9FAFB;
    padding: 0.875rem 1rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #E5E7EB;
}

.table td {
    padding: 1rem;
    border-bottom: 1px solid #F3F4F6;
    font-size: 0.9375rem;
    color: #374151;
}

.table tr:last-child td { border-bottom: none; }

.folio-link {
    font-family: 'JetBrains Mono', monospace;
    font-weight: 600;
    color: #7B2D3B;
    text-decoration: none;
}

.folio-link:hover { text-decoration: underline; }

.vehicle-cell {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.date-cell {
    font-size: 0.8125rem;
    color: #6B7280;
}

.options-count {
    text-align: center;
    font-weight: 600;
}

/* Badges */
.type-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    background: #F3F4F6;
    color: #374151;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge--draft { background: #F3F4F6; color: #6B7280; }
.status-badge--sent { background: #DBEAFE; color: #1D4ED8; }
.status-badge--concreted { background: #D1FAE5; color: #059669; }
.status-badge--issued { background: #A7F3D0; color: #047857; }
.status-badge--rejected { background: #FEE2E2; color: #DC2626; }
.status-badge--expired { background: #FEF3C7; color: #D97706; }
.status-badge--annulled { background: #E2E8F0; color: #475569; }

/* Actions */
.actions {
    display: flex;
    gap: 0.375rem;
}

.action-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: none;
    background: transparent;
    cursor: pointer;
    transition: background 0.2s;
    font-size: 1rem;
    text-decoration: none;
}

.action-btn:hover { background: #F3F4F6; }
.action-btn--danger:hover { background: #FEE2E2; }

.empty-row {
    text-align: center;
    color: #9CA3AF;
    padding: 3rem 1rem !important;
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

/* Buttons */
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
    text-decoration: none;
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

.btn-icon {
    font-size: 1.125rem;
    font-weight: 400;
}

@media (max-width: 768px) {
    .page-header { flex-direction: column; }
    .filters { flex-direction: column; }
    .filter-input, .filter-select { width: 100%; }
    .table-container { overflow-x: auto; }
    .table { min-width: 800px; }
}
</style>
