<!-- resources/js/Pages/Quotes/Index.vue -->
<script setup>
import { ref, computed } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
defineOptions({ layout: AppLayout });
import { ConfirmDialog, ToastContainer } from '@/components/Ui';
import { useConfirm } from '@/composables/useConfirm';
import { useToast } from '@/composables/useToast';

const props = defineProps({
    quotes: { type: Object, default: () => ({ data: [] }) },
    statuses: { type: Array, default: () => [] },
});

// Toast & Confirm
const { isOpen: confirmOpen, config: confirmConfig, confirmDelete, onConfirm, onCancel } = useConfirm();
const toast = useToast();

// Client-side filters
const search = ref('');
const statusFilter = ref('');

// Client-side sorting
const sortField = ref('created_at');
const sortDirection = ref('desc');

const sortBy = (field) => {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortField.value = field;
        sortDirection.value = field === 'created_at' ? 'desc' : 'asc';
    }
};

const sortIcon = (field) => {
    if (sortField.value !== field) return '↕';
    return sortDirection.value === 'asc' ? '↑' : '↓';
};

// Parse date "dd/mm/YYYY HH:mm" to sortable timestamp
const parseDate = (str) => {
    if (!str) return 0;
    const [date, time] = str.split(' ');
    const [d, m, y] = date.split('/');
    return new Date(`${y}-${m}-${d}T${time || '00:00'}`).getTime() || 0;
};

const filteredQuotes = computed(() => {
    let data = props.quotes.data || [];

    // Filter by status
    if (statusFilter.value) {
        data = data.filter(q => q.status === statusFilter.value);
    }

    // Filter by search text
    const term = search.value.toLowerCase().trim();
    if (term) {
        data = data.filter(q =>
            (q.folio || '').toLowerCase().includes(term) ||
            (q.customer_name || '').toLowerCase().includes(term) ||
            (q.vehicle || '').toLowerCase().includes(term) ||
            (q.type_label || '').toLowerCase().includes(term) ||
            (q.status_label || '').toLowerCase().includes(term)
        );
    }

    return data;
});

const sortedQuotes = computed(() => {
    const data = [...filteredQuotes.value];
    const field = sortField.value;
    const dir = sortDirection.value === 'asc' ? 1 : -1;

    return data.sort((a, b) => {
        let valA, valB;

        if (field === 'created_at') {
            valA = parseDate(a.created_at);
            valB = parseDate(b.created_at);
        } else if (field === 'options_count') {
            valA = a.options_count ?? 0;
            valB = b.options_count ?? 0;
        } else if (field === 'folio') {
            valA = (a.folio || '').toLowerCase();
            valB = (b.folio || '').toLowerCase();
        } else if (field === 'type') {
            valA = (a.type_label || '').toLowerCase();
            valB = (b.type_label || '').toLowerCase();
        } else if (field === 'customer_name') {
            valA = (a.customer_name || '').toLowerCase();
            valB = (b.customer_name || '').toLowerCase();
        } else if (field === 'vehicle') {
            valA = (a.vehicle || '').toLowerCase();
            valB = (b.vehicle || '').toLowerCase();
        } else if (field === 'status') {
            valA = (a.status_label || '').toLowerCase();
            valB = (b.status_label || '').toLowerCase();
        } else {
            return 0;
        }

        if (valA < valB) return -1 * dir;
        if (valA > valB) return 1 * dir;
        return 0;
    });
});

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

                <!-- Card container (filters + table) -->
                <div class="card">
                    <!-- Filters -->
                    <div class="filters">
                        <div class="search-input-wrapper">
                            <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Buscar por folio, cliente, vehículo..."
                                class="filter-input"
                            >
                            <button v-if="search" class="search-clear" @click="search = ''" title="Limpiar">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                            </button>
                        </div>
                        <select v-model="statusFilter" class="filter-select">
                            <option value="">Todos los estados</option>
                            <option v-for="status in statuses" :key="status.value" :value="status.value">
                                {{ status.label }}
                            </option>
                        </select>
                    </div>

                    <!-- Table -->
                    <div class="table-container">
                        <table class="table">
                        <thead>
                            <tr>
                                <th class="sortable" @click="sortBy('created_at')">
                                    Fecha <span class="sort-icon" :class="{ active: sortField === 'created_at' }">{{ sortIcon('created_at') }}</span>
                                </th>
                                <th class="sortable" @click="sortBy('folio')">
                                    Folio <span class="sort-icon" :class="{ active: sortField === 'folio' }">{{ sortIcon('folio') }}</span>
                                </th>
                                <th class="sortable" @click="sortBy('type')">
                                    Tipo <span class="sort-icon" :class="{ active: sortField === 'type' }">{{ sortIcon('type') }}</span>
                                </th>
                                <th class="sortable" @click="sortBy('options_count')">
                                    Opciones <span class="sort-icon" :class="{ active: sortField === 'options_count' }">{{ sortIcon('options_count') }}</span>
                                </th>
                                <th class="sortable" @click="sortBy('customer_name')">
                                    Cliente <span class="sort-icon" :class="{ active: sortField === 'customer_name' }">{{ sortIcon('customer_name') }}</span>
                                </th>
                                <th class="sortable" @click="sortBy('vehicle')">
                                    Vehículo <span class="sort-icon" :class="{ active: sortField === 'vehicle' }">{{ sortIcon('vehicle') }}</span>
                                </th>
                                <th class="sortable" @click="sortBy('status')">
                                    Estado <span class="sort-icon" :class="{ active: sortField === 'status' }">{{ sortIcon('status') }}</span>
                                </th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="quote in sortedQuotes" :key="quote.id">
                                <td class="date-cell">{{ quote.created_at }}</td>
                                <td>
                                    <Link :href="route('quotes.show', quote.id)" class="folio-link">
                                        {{ quote.folio }}
                                    </Link>
                                </td>
                                <td>
                                    <span class="type-badge">{{ quote.type_label }}</span>
                                </td>
                                <td class="options-count">{{ quote.options_count }}</td>
                                <td>{{ quote.customer_name }}</td>
                                <td class="vehicle-cell">{{ quote.vehicle || '—' }}</td>
                                <td>
                                    <span
                                        class="status-badge"
                                        :class="`status-badge--${quote.status}`"
                                    >
                                        {{ quote.status_label }}
                                    </span>
                                </td>
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
                            <tr v-if="!sortedQuotes.length">
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

/* Card */
.card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    padding: 1rem;
}

/* Filters */
.filters {
    display: flex;
    gap: 0.75rem;
    padding: 0 0 1rem 0;
    flex-wrap: wrap;
    align-items: center;
}

.search-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    flex: 1;
    min-width: 200px;
    max-width: 400px;
}

.search-icon {
    position: absolute;
    left: 12px;
    color: #9CA3AF;
    pointer-events: none;
}

.filter-input {
    width: 100%;
    padding: 0.625rem 2.25rem 0.625rem 2.5rem;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 0.9375rem;
    color: #111827;
    background: #F9FAFB;
    transition: all 0.2s;
}

.filter-input:focus {
    outline: none;
    border-color: #7B2D3B;
    box-shadow: 0 0 0 3px rgba(123,45,59,0.1);
    background: white;
}

.filter-input::placeholder { color: #9CA3AF; }

.search-clear {
    position: absolute;
    right: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border: none;
    border-radius: 50%;
    background: #E5E7EB;
    color: #6B7280;
    cursor: pointer;
    transition: all 0.2s;
}

.search-clear:hover {
    background: #D1D5DB;
    color: #374151;
}

.filter-select {
    padding: 0.625rem 2rem 0.625rem 1rem;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 0.9375rem;
    color: #111827;
    background: #F9FAFB;
    transition: all 0.2s;
    min-width: 180px;
    height: 40px;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    cursor: pointer;
}

.filter-select:focus {
    outline: none;
    border-color: #7B2D3B;
    box-shadow: 0 0 0 3px rgba(123,45,59,0.1);
    background-color: white;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
}

/* Table */
.table-container {
    overflow: hidden;
    border-radius: 8px;
    border: 1px solid #F3F4F6;
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
    white-space: nowrap;
}

.table th.sortable {
    cursor: pointer;
    user-select: none;
    transition: color 0.2s;
}

.table th.sortable:hover {
    color: #7B2D3B;
}

.sort-icon {
    font-size: 0.7rem;
    color: #D1D5DB;
    margin-left: 0.25rem;
}

.sort-icon.active {
    color: #7B2D3B;
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
    padding: 1rem 0 0;
    border-top: 1px solid #F3F4F6;
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

.btn-icon {
    font-size: 1.125rem;
    font-weight: 400;
}

@media (max-width: 768px) {
    .page-header { flex-direction: column; }
    .filters { flex-direction: column; }
    .search-input-wrapper { max-width: 100%; }
    .filter-select { width: 100%; }
    .table-container { overflow-x: auto; }
    .table { min-width: 800px; }
    .card { padding: 0.75rem; }
}
</style>
