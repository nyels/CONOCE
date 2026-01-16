<script setup>
import { ref, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import AuditDetailsModal from './AuditDetailsModal.vue';

const props = defineProps({
    activities: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const showModal = ref(false);
const selectedActivity = ref(null);

// Debounce nativo para búsqueda
const debounce = (fn, delay) => {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn(...args), delay);
    };
};

// Paginación
const currentPage = ref(props.activities.current_page);
const perPage = ref(props.activities.per_page);

const goToPage = (page) => {
    if (page < 1 || page > props.activities.last_page) return;
    router.get(route('admin.audit.index'), {
        page: page,
        per_page: perPage.value,
        search: search.value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

// Watch para Búsqueda
watch(search, debounce((value) => {
    router.get(route('admin.audit.index'), { 
        search: value,
        page: 1
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}, 300));

const openDetails = (activity) => {
    selectedActivity.value = activity;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    setTimeout(() => selectedActivity.value = null, 300);
};

// Helpers visuales
const getActionClass = (event) => {
    switch (event) {
        case 'created': return 'action-badge--success';
        case 'updated': return 'action-badge--warning';
        case 'deleted': return 'action-badge--danger';
        default: return 'action-badge--default';
    }
};

const getActionIcon = (event) => {
    switch (event) {
        case 'created': return '✦';
        case 'updated': return '↻';
        case 'deleted': return '✕';
        default: return '●';
    }
};

const getActionLabel = (event) => {
    const map = { created: 'Creación', updated: 'Actualización', deleted: 'Eliminación' };
    return map[event] || event;
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('es-MX', { day: '2-digit', month: 'short', year: 'numeric' });
};

const formatTime = (date) => {
    return new Date(date).toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });
};

const getInitials = (name) => {
    return (name || 'SYS').substring(0, 2).toUpperCase();
};
</script>

<template>
    <AppLayout title="Auditoría">
        <div class="audit-page">
            <!-- Hero Header -->
            <header class="audit-header">
                <div class="audit-header__content">
                    <div class="audit-header__text">
                        <div class="audit-header__badge">
                            <span class="badge-dot"></span>
                            Sistema de Monitoreo
                        </div>
                        <h1 class="audit-header__title">Registro de Auditoría</h1>
                        <p class="audit-header__subtitle">Seguimiento completo de actividad y cambios en el sistema</p>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="audit-stats">
                        <div class="stat-card">
                            <div class="stat-card__value">{{ activities.total }}</div>
                            <div class="stat-card__label">Total Eventos</div>
                        </div>
                        <div class="stat-card stat-card--accent">
                            <div class="stat-card__value">{{ activities.data.filter(a => a.event === 'created').length }}</div>
                            <div class="stat-card__label">Nuevos Hoy</div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="audit-main">
                <!-- Toolbar -->
                <div class="audit-toolbar">
                    <div class="search-container">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                        </svg>
                        <input
                            v-model="search"
                            type="text"
                            class="search-input"
                            placeholder="Buscar por usuario, descripción, entidad..."
                        />
                    </div>
                    <div class="toolbar-meta">
                        <span class="meta-text">Mostrando {{ activities.from }}–{{ activities.to }} de {{ activities.total }}</span>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="table-card">
                    <table class="audit-table">
                        <thead>
                            <tr>
                                <th class="th-event">Evento</th>
                                <th class="th-user">Usuario</th>
                                <th class="th-entity">Recurso</th>
                                <th class="th-date">Fecha</th>
                                <th class="th-actions">Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="activity in activities.data" :key="activity.id" @click="openDetails(activity)">
                                <td>
                                    <div class="event-cell">
                                        <span class="action-badge" :class="getActionClass(activity.event)">
                                            <span class="action-badge__icon">{{ getActionIcon(activity.event) }}</span>
                                            {{ getActionLabel(activity.event) }}
                                        </span>
                                        <span class="event-description">{{ activity.description }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar">{{ getInitials(activity.causer?.name) }}</div>
                                        <div class="user-info">
                                            <span class="user-name">{{ activity.causer?.name || 'Sistema Automático' }}</span>
                                            <span class="user-ip" v-if="activity.properties?.ip">{{ activity.properties.ip }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="entity-cell">
                                        <span class="entity-type">{{ activity.subject_type?.split('\\').pop() }}</span>
                                        <span class="entity-id">#{{ activity.subject_id }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-cell">
                                        <span class="date-main">{{ formatDate(activity.created_at) }}</span>
                                        <span class="date-time">{{ formatTime(activity.created_at) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <button class="view-btn" @click.stop="openDetails(activity)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                            <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 8.201 2.372 9.336 6.41.147.527.147 1.053 0 1.58C18.201 15.628 14.257 18 10 18c-4.257 0-8.201-2.372-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                        Ver
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!activities.data.length">
                                <td colspan="5" class="empty-state">
                                    <div class="empty-state__content">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        <p>No se encontraron registros</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination" v-if="activities.last_page > 1">
                    <button class="pagination-btn" :disabled="activities.current_page === 1" @click="goToPage(activities.current_page - 1)">
                        ← Anterior
                    </button>
                    <div class="pagination-info">
                        Página <strong>{{ activities.current_page }}</strong> de <strong>{{ activities.last_page }}</strong>
                    </div>
                    <button class="pagination-btn" :disabled="activities.current_page === activities.last_page" @click="goToPage(activities.current_page + 1)">
                        Siguiente →
                    </button>
                </div>
            </main>

            <AuditDetailsModal 
                :show="showModal" 
                :activity="selectedActivity" 
                @close="closeModal"
            />
        </div>
    </AppLayout>
</template>

<style scoped>
/* ===== PAGE LAYOUT ===== */
.audit-page {
    min-height: 100vh;
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
}

/* ===== HEADER ===== */
.audit-header {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    padding: 2.5rem 2rem 3rem;
    margin-bottom: -1.5rem;
}

.audit-header__content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
    flex-wrap: wrap;
}

.audit-header__badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.1);
    padding: 0.375rem 0.875rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.75rem;
    backdrop-filter: blur(4px);
}

.badge-dot {
    width: 6px;
    height: 6px;
    background: #22c55e;
    border-radius: 50%;
    animation: pulse-dot 2s infinite;
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.audit-header__title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 0.5rem;
}

.audit-header__subtitle {
    font-size: 0.9375rem;
    color: #94a3b8;
    margin: 0;
}

/* Stats */
.audit-stats {
    display: flex;
    gap: 1rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    min-width: 120px;
    text-align: center;
}

.stat-card--accent {
    background: linear-gradient(135deg, rgba(123, 45, 59, 0.3) 0%, rgba(92, 29, 42, 0.3) 100%);
    border-color: rgba(123, 45, 59, 0.3);
}

.stat-card__value {
    font-size: 1.75rem;
    font-weight: 800;
    color: #ffffff;
    font-family: 'JetBrains Mono', monospace;
}

.stat-card__label {
    font-size: 0.75rem;
    color: #94a3b8;
    margin-top: 0.25rem;
}

/* ===== MAIN ===== */
.audit-main {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem 3rem;
}

/* ===== TOOLBAR ===== */
.audit-toolbar {
    background: #ffffff;
    border-radius: 16px;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.search-container {
    position: relative;
    flex: 1;
    max-width: 400px;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    width: 1.25rem;
    height: 1.25rem;
    color: #9ca3af;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 3rem;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    font-size: 0.9375rem;
    background: #f9fafb;
    transition: all 0.2s;
}

.search-input:focus {
    outline: none;
    border-color: #7B2D3B;
    box-shadow: 0 0 0 3px rgba(123, 45, 59, 0.1);
    background: #ffffff;
}

.toolbar-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.meta-text {
    font-size: 0.875rem;
    color: #6b7280;
}

/* ===== TABLE CARD ===== */
.table-card {
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    border: 1px solid #e5e7eb;
}

.audit-table {
    width: 100%;
    border-collapse: collapse;
}

.audit-table thead {
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.audit-table th {
    padding: 1rem 1.5rem;
    text-align: left;
    font-size: 0.6875rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

.audit-table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    cursor: pointer;
    transition: background-color 0.15s;
}

.audit-table tbody tr:hover {
    background-color: #fafafa;
}

.audit-table tbody tr:last-child {
    border-bottom: none;
}

.audit-table td {
    padding: 1rem 1.5rem;
    vertical-align: middle;
}

/* ===== CELLS ===== */
.event-cell {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.action-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.25rem 0.625rem;
    border-radius: 6px;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    width: fit-content;
}

.action-badge__icon {
    font-size: 0.625rem;
}

.action-badge--success { background: #dcfce7; color: #15803d; }
.action-badge--warning { background: #fef3c7; color: #b45309; }
.action-badge--danger { background: #fee2e2; color: #b91c1c; }
.action-badge--default { background: #f1f5f9; color: #475569; }

.event-description {
    font-size: 0.875rem;
    color: #374151;
    line-height: 1.4;
    max-width: 280px;
}

.user-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    flex-shrink: 0;
}

.user-info {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.user-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: #111827;
}

.user-ip {
    font-size: 0.6875rem;
    color: #9ca3af;
    font-family: 'JetBrains Mono', monospace;
}

.entity-cell {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.entity-type {
    font-size: 0.75rem;
    font-weight: 600;
    color: #475569;
    font-family: 'JetBrains Mono', monospace;
    background: #f1f5f9;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.entity-id {
    font-size: 0.75rem;
    color: #9ca3af;
    font-family: 'JetBrains Mono', monospace;
}

.date-cell {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.date-main {
    font-size: 0.875rem;
    font-weight: 500;
    color: #111827;
}

.date-time {
    font-size: 0.6875rem;
    color: #9ca3af;
}

.view-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 0.875rem;
    border: 1px solid #e5e7eb;
    background: #ffffff;
    border-radius: 8px;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #374151;
    cursor: pointer;
    transition: all 0.15s;
}

.view-btn svg {
    width: 1rem;
    height: 1rem;
}

.view-btn:hover {
    background: #7B2D3B;
    color: #ffffff;
    border-color: #7B2D3B;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    padding: 4rem 2rem;
    text-align: center;
}

.empty-state__content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    color: #9ca3af;
}

.empty-state__content svg {
    width: 3rem;
    height: 3rem;
}

.empty-state__content p {
    margin: 0;
    font-size: 0.9375rem;
}

/* ===== PAGINATION ===== */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1.5rem;
    margin-top: 1.5rem;
    padding: 1rem;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.pagination-btn {
    padding: 0.625rem 1.25rem;
    border: 1px solid #e5e7eb;
    background: #ffffff;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    cursor: pointer;
    transition: all 0.15s;
}

.pagination-btn:hover:not(:disabled) {
    background: #7B2D3B;
    color: #ffffff;
    border-color: #7B2D3B;
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-info {
    font-size: 0.875rem;
    color: #6b7280;
}

.pagination-info strong {
    color: #111827;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .audit-header {
        padding: 1.5rem 1rem 2.5rem;
    }
    
    .audit-header__content {
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .audit-stats {
        width: 100%;
    }
    
    .stat-card {
        flex: 1;
    }
    
    .audit-main {
        padding: 0 1rem 2rem;
    }
    
    .audit-toolbar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-container {
        max-width: none;
    }
    
    .audit-table th,
    .audit-table td {
        padding: 0.75rem 1rem;
    }
    
    .th-entity,
    .audit-table td:nth-child(3) {
        display: none;
    }
    
    .event-description {
        max-width: 180px;
    }
}
</style>
