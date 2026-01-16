<script setup>
import { computed } from 'vue';

const props = defineProps({
    show: Boolean,
    activity: Object,
});

const emit = defineEmits(['close']);

// Helpers
const formatDate = (date) =>
    new Date(date).toLocaleString('es-MX', {
        dateStyle: 'long',
        timeStyle: 'medium',
    });

const getEventLabel = (event) => {
    const map = {
        created: 'Creación',
        updated: 'Actualización',
        deleted: 'Eliminación',
        restored: 'Restauración',
    };
    return map[event] || event;
};

const getEventClass = (event) => {
    switch (event) {
        case 'created': return 'event-badge--success';
        case 'updated': return 'event-badge--warning';
        case 'deleted': return 'event-badge--danger';
        default: return 'event-badge--default';
    }
};

const getEventIcon = (event) => {
    switch (event) {
        case 'created': return '✦';
        case 'updated': return '↻';
        case 'deleted': return '✕';
        default: return '●';
    }
};

// Diff logic
const changes = computed(() => {
    if (!props.activity || !props.activity.properties) return [];

    const old = props.activity.properties.old || {};
    const attributes = props.activity.properties.attributes || {};

    const allKeys = new Set([
        ...Object.keys(old),
        ...Object.keys(attributes),
    ]);

    const ignored = [
        'updated_at',
        'created_at',
        'id',
        'password',
        'remember_token',
        'deleted_at',
        'email_verified_at',
        'two_factor_confirmed_at',
        'current_team_id',
        'profile_photo_path',
    ];

    const diffs = [];

    allKeys.forEach((key) => {
        if (ignored.includes(key)) return;

        const oldValue = old[key];
        const newValue = attributes[key];

        if (JSON.stringify(oldValue) !== JSON.stringify(newValue)) {
            diffs.push({
                key,
                old: oldValue ?? '—',
                new: newValue ?? '—',
            });
        }
    });

    return diffs;
});

const getInitials = (name) => {
    return (name || 'SYS').substring(0, 2).toUpperCase();
};

const formatFieldName = (key) => {
    return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
};
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="show"
                class="modal-overlay"
                role="dialog"
                aria-modal="true"
            >
                <!-- Backdrop -->
                <div class="modal-backdrop" @click="$emit('close')" />

                <!-- Modal Panel -->
                <div class="modal-panel">
                    <!-- Dark Header -->
                    <header class="modal-header">
                        <div class="modal-header__content">
                            <div class="modal-header__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625zM7.5 15a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 017.5 15zm.75 2.25a.75.75 0 000 1.5H12a.75.75 0 000-1.5H8.25z" clip-rule="evenodd" />
                                    <path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="modal-header__title">Detalle del Evento</h2>
                                <p class="modal-header__subtitle">
                                    Transacción <span class="modal-header__id">#{{ activity?.id }}</span>
                                </p>
                            </div>
                        </div>
                        <button class="modal-close" @click="$emit('close')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </header>

                    <!-- Content -->
                    <div class="modal-body" v-if="activity">
                        <!-- Actor Card -->
                        <div class="actor-card">
                            <div class="actor-avatar">{{ getInitials(activity.causer?.name) }}</div>
                            <div class="actor-info">
                                <span class="actor-name">{{ activity.causer?.name || 'Sistema Automático' }}</span>
                                <div class="actor-meta">
                                    <span class="event-badge" :class="getEventClass(activity.event)">
                                        <span class="event-badge__icon">{{ getEventIcon(activity.event) }}</span>
                                        {{ getEventLabel(activity.event) }}
                                    </span>
                                    <span class="actor-date">{{ formatDate(activity.created_at) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="info-grid">
                            <div class="info-card">
                                <div class="info-card__header">
                                    <span class="info-card__dot info-card__dot--blue"></span>
                                    Descripción
                                </div>
                                <p class="info-card__value">{{ activity.description }}</p>
                            </div>

                            <div class="info-card">
                                <div class="info-card__header">
                                    <span class="info-card__dot info-card__dot--purple"></span>
                                    Recurso Afectado
                                </div>
                                <p class="info-card__value info-card__value--mono">
                                    {{ activity.subject_type?.split('\\').pop() || 'N/A' }}
                                    <span class="info-card__id">#{{ activity.subject_id || 'N/A' }}</span>
                                </p>
                            </div>

                            <div class="info-card" v-if="activity.properties?.ip">
                                <div class="info-card__header">
                                    <span class="info-card__dot info-card__dot--green"></span>
                                    Dirección IP
                                </div>
                                <p class="info-card__value info-card__value--mono">{{ activity.properties.ip }}</p>
                            </div>

                            <div class="info-card" v-if="activity.properties?.user_agent">
                                <div class="info-card__header">
                                    <span class="info-card__dot info-card__dot--orange"></span>
                                    User Agent
                                </div>
                                <p class="info-card__value info-card__value--small">{{ activity.properties.user_agent }}</p>
                            </div>
                        </div>

                        <!-- Changes Section -->
                        <div v-if="changes.length" class="changes-section">
                            <div class="changes-header">
                                <h3 class="changes-title">
                                    Cambios Realizados
                                    <span class="changes-count">{{ changes.length }}</span>
                                </h3>
                            </div>
                            
                            <div class="changes-table-wrapper">
                                <table class="changes-table">
                                    <thead>
                                        <tr>
                                            <th class="th-field">Campo</th>
                                            <th class="th-old">Valor Anterior</th>
                                            <th class="th-new">Valor Nuevo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(change, i) in changes" :key="i">
                                            <td class="td-field">{{ formatFieldName(change.key) }}</td>
                                            <td class="td-old">
                                                <span class="value-pill value-pill--old">{{ change.old }}</span>
                                            </td>
                                            <td class="td-new">
                                                <span class="value-pill value-pill--new">{{ change.new }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Empty State -->
                        <div v-else class="empty-changes">
                            <div class="empty-changes__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                </svg>
                            </div>
                            <h4 class="empty-changes__title">Sin cambios registrados</h4>
                            <p class="empty-changes__text">Este evento no modificó atributos auditables.</p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <footer class="modal-footer">
                        <button class="btn-close" @click="$emit('close')">
                            Cerrar
                        </button>
                    </footer>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
/* ===== MODAL OVERLAY ===== */
.modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(4px);
}

/* ===== MODAL PANEL ===== */
.modal-panel {
    position: relative;
    width: 100%;
    max-width: 720px;
    max-height: 90vh;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

/* ===== HEADER ===== */
.modal-header {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    padding: 1.25rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header__content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.modal-header__icon {
    width: 44px;
    height: 44px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-header__icon svg {
    width: 24px;
    height: 24px;
    color: #94a3b8;
}

.modal-header__title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
}

.modal-header__subtitle {
    font-size: 0.8125rem;
    color: #94a3b8;
    margin: 0.25rem 0 0;
}

.modal-header__id {
    font-family: 'JetBrains Mono', monospace;
    color: #cbd5e1;
}

.modal-close {
    width: 36px;
    height: 36px;
    border: none;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s;
}

.modal-close svg {
    width: 18px;
    height: 18px;
    color: #94a3b8;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.15);
}

.modal-close:hover svg {
    color: #ffffff;
}

/* ===== BODY ===== */
.modal-body {
    flex: 1;
    overflow-y: auto;
    padding: 1.5rem;
    background: #f8fafc;
}

/* ===== ACTOR CARD ===== */
.actor-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    margin-bottom: 1.25rem;
}

.actor-avatar {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 700;
    flex-shrink: 0;
}

.actor-info {
    flex: 1;
    min-width: 0;
}

.actor-name {
    display: block;
    font-size: 0.9375rem;
    font-weight: 700;
    color: #0f172a;
}

.actor-meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 0.375rem;
    flex-wrap: wrap;
}

.event-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.event-badge__icon {
    font-size: 0.625rem;
}

.event-badge--success { background: #dcfce7; color: #15803d; }
.event-badge--warning { background: #fef3c7; color: #b45309; }
.event-badge--danger { background: #fee2e2; color: #b91c1c; }
.event-badge--default { background: #f1f5f9; color: #475569; }

.actor-date {
    font-size: 0.75rem;
    color: #64748b;
}

/* ===== INFO GRID ===== */
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}

.info-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 1rem;
    border: 1px solid #e2e8f0;
}

.info-card__header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.6875rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}

.info-card__dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.info-card__dot--blue { background: #3b82f6; }
.info-card__dot--purple { background: #8b5cf6; }
.info-card__dot--green { background: #22c55e; }
.info-card__dot--orange { background: #f97316; }

.info-card__value {
    font-size: 0.875rem;
    color: #0f172a;
    margin: 0;
    line-height: 1.5;
}

.info-card__value--mono {
    font-family: 'JetBrains Mono', monospace;
    font-weight: 500;
}

.info-card__value--small {
    font-size: 0.75rem;
    color: #475569;
    word-break: break-all;
}

.info-card__id {
    color: #94a3b8;
    margin-left: 0.25rem;
}

/* ===== CHANGES SECTION ===== */
.changes-section {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.changes-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e2e8f0;
    background: #fafafa;
}

.changes-title {
    font-size: 0.875rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.changes-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    height: 22px;
    padding: 0 0.5rem;
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: #ffffff;
    border-radius: 6px;
    font-size: 0.6875rem;
    font-weight: 700;
}

.changes-table-wrapper {
    overflow-x: auto;
}

.changes-table {
    width: 100%;
    border-collapse: collapse;
}

.changes-table th {
    padding: 0.75rem 1rem;
    text-align: left;
    font-size: 0.625rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    border-bottom: 1px solid #e2e8f0;
}

.th-field { color: #64748b; background: #f8fafc; }
.th-old { color: #dc2626; background: #fef2f2; }
.th-new { color: #16a34a; background: #f0fdf4; }

.changes-table td {
    padding: 0.875rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.changes-table tr:last-child td {
    border-bottom: none;
}

.td-field {
    font-size: 0.8125rem;
    font-weight: 600;
    color: #334155;
}

.value-pill {
    display: inline-block;
    font-size: 0.8125rem;
    font-family: 'JetBrains Mono', monospace;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    max-width: 200px;
    word-break: break-all;
}

.value-pill--old {
    background: #fef2f2;
    color: #991b1b;
}

.value-pill--new {
    background: #f0fdf4;
    color: #166534;
}

/* ===== EMPTY STATE ===== */
.empty-changes {
    text-align: center;
    padding: 3rem 2rem;
    background: #ffffff;
    border-radius: 14px;
    border: 2px dashed #e2e8f0;
}

.empty-changes__icon {
    width: 56px;
    height: 56px;
    margin: 0 auto 1rem;
    background: #f1f5f9;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-changes__icon svg {
    width: 28px;
    height: 28px;
    color: #94a3b8;
}

.empty-changes__title {
    font-size: 0.9375rem;
    font-weight: 600;
    color: #334155;
    margin: 0 0 0.25rem;
}

.empty-changes__text {
    font-size: 0.8125rem;
    color: #64748b;
    margin: 0;
}

/* ===== FOOTER ===== */
.modal-footer {
    padding: 1rem 1.5rem;
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
}

.btn-close {
    padding: 0.625rem 1.5rem;
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color: #ffffff;
    border: none;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
}

.btn-close:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3);
}

/* ===== TRANSITIONS ===== */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}

.modal-enter-active .modal-panel,
.modal-leave-active .modal-panel {
    transition: transform 0.2s ease, opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-from .modal-panel,
.modal-leave-to .modal-panel {
    transform: scale(0.95) translateY(10px);
    opacity: 0;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 640px) {
    .modal-panel {
        max-height: 100vh;
        border-radius: 16px 16px 0 0;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .changes-table th,
    .changes-table td {
        padding: 0.625rem 0.75rem;
    }
    
    .value-pill {
        max-width: 120px;
    }
}
</style>
