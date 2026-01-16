<!-- resources/js/Pages/Dashboard/Manager.vue -->
<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    dashboardData: Object,
    userRole: String
});

const refreshing = ref(false);

// Mock team data
const teamStats = ref({
    activeAgents: 8,
    totalAgents: 10,
    quotesToday: 35,
    quotesGoal: 50,
    conversionRate: 28,
    pendingApprovals: 4,
});

// Mock team members
const teamMembers = ref([
    { id: 1, name: 'Juan Pérez', role: 'Operador Sr.', quotesToday: 8, conversionRate: 32, status: 'online' },
    { id: 2, name: 'María García', role: 'Operador', quotesToday: 6, conversionRate: 28, status: 'online' },
    { id: 3, name: 'Carlos López', role: 'Operador', quotesToday: 5, conversionRate: 22, status: 'online' },
    { id: 4, name: 'Ana Martínez', role: 'Operador Jr.', quotesToday: 4, conversionRate: 18, status: 'busy' },
    { id: 5, name: 'Pedro Ruiz', role: 'Operador', quotesToday: 3, conversionRate: 25, status: 'offline' },
]);

// Mock pending approvals
const pendingApprovals = ref([
    { id: 'COT-2401-0045', customer: 'Empresa ABC', amount: 45000, agent: 'Juan Pérez', reason: 'Descuento especial' },
    { id: 'COT-2401-0044', customer: 'María González', amount: 18500, agent: 'María García', reason: 'Prima alta' },
]);

// Mock alerts
const teamAlerts = ref([
    { id: 1, type: 'warning', message: '12 cotizaciones sin seguimiento hace +3 días' },
    { id: 2, type: 'info', message: '3 operadores por debajo de meta diaria' },
]);

const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
        minimumFractionDigits: 0
    }).format(value);
};

const getStatusClass = (status) => {
    const classes = {
        'online': 'status-dot--online',
        'busy': 'status-dot--busy',
        'offline': 'status-dot--offline',
    };
    return classes[status] || '';
};

const refreshData = () => {
    refreshing.value = true;
    setTimeout(() => {
        refreshing.value = false;
    }, 1000);
};

const approveQuote = (quote) => {
    alert(`Aprobar cotización ${quote.id}`);
};

const rejectQuote = (quote) => {
    alert(`Rechazar cotización ${quote.id}`);
};

const viewMember = (member) => {
    alert(`Ver perfil de ${member.name}`);
};
</script>

<template>
    <AppLayout>
        <div class="dashboard-manager">
            <!-- Header -->
            <header class="dashboard-header">
                <div class="dashboard-header__info">
                    <h1 class="dashboard-header__title">Dashboard de Equipo</h1>
                    <p class="dashboard-header__subtitle">Supervisión y gestión de operadores</p>
                </div>
                <div class="dashboard-header__actions">
                    <button 
                        class="btn btn--secondary" 
                        @click="refreshData" 
                        :disabled="refreshing">
                        <svg class="btn__icon" :class="{ 'animate-spin': refreshing }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="btn__text">Actualizar</span>
                    </button>
                </div>
            </header>

            <!-- Team Stats -->
            <section class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card__icon">👥</div>
                    <div class="stat-card__content">
                        <div class="stat-card__value">{{ teamStats.activeAgents }}/{{ teamStats.totalAgents }}</div>
                        <div class="stat-card__label">Agentes Activos</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__icon">📝</div>
                    <div class="stat-card__content">
                        <div class="stat-card__value">{{ teamStats.quotesToday }}/{{ teamStats.quotesGoal }}</div>
                        <div class="stat-card__label">Cotizaciones Hoy</div>
                    </div>
                </div>
                <div class="stat-card stat-card--highlight">
                    <div class="stat-card__icon">📈</div>
                    <div class="stat-card__content">
                        <div class="stat-card__value">{{ teamStats.conversionRate }}%</div>
                        <div class="stat-card__label">Conversión Equipo</div>
                    </div>
                </div>
                <div class="stat-card stat-card--warning">
                    <div class="stat-card__icon">⏳</div>
                    <div class="stat-card__content">
                        <div class="stat-card__value">{{ teamStats.pendingApprovals }}</div>
                        <div class="stat-card__label">Por Aprobar</div>
                    </div>
                </div>
            </section>

            <!-- Main Grid -->
            <div class="main-grid">
                <!-- Team Performance -->
                <section class="section section--team">
                    <div class="section__header">
                        <h2 class="section__title">
                            <span class="section__icon">🏆</span>
                            Rendimiento del Equipo
                        </h2>
                    </div>
                    
                    <div class="team-list">
                        <div 
                            v-for="member in teamMembers" 
                            :key="member.id"
                            class="team-member"
                            @click="viewMember(member)">
                            <div class="team-member__avatar">
                                {{ member.name.split(' ').map(n => n[0]).join('').substring(0, 2) }}
                                <span class="status-dot" :class="getStatusClass(member.status)"></span>
                            </div>
                            <div class="team-member__info">
                                <div class="team-member__name">{{ member.name }}</div>
                                <div class="team-member__role">{{ member.role }}</div>
                            </div>
                            <div class="team-member__stats">
                                <div class="team-member__stat">
                                    <span class="team-member__stat-value">{{ member.quotesToday }}</span>
                                    <span class="team-member__stat-label">Cot.</span>
                                </div>
                                <div class="team-member__stat">
                                    <span class="team-member__stat-value">{{ member.conversionRate }}%</span>
                                    <span class="team-member__stat-label">Conv.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Right Column -->
                <div class="right-column">
                    <!-- Pending Approvals -->
                    <section class="section section--approvals">
                        <div class="section__header">
                            <h2 class="section__title">
                                <span class="section__icon">⏳</span>
                                Por Aprobar
                            </h2>
                            <span class="section__badge">{{ pendingApprovals.length }}</span>
                        </div>
                        
                        <div class="approvals-list">
                            <div 
                                v-for="quote in pendingApprovals" 
                                :key="quote.id"
                                class="approval-card">
                                <div class="approval-card__header">
                                    <span class="approval-card__id">{{ quote.id }}</span>
                                    <span class="approval-card__amount">{{ formatCurrency(quote.amount) }}</span>
                                </div>
                                <div class="approval-card__body">
                                    <div class="approval-card__customer">{{ quote.customer }}</div>
                                    <div class="approval-card__meta">{{ quote.agent }} • {{ quote.reason }}</div>
                                </div>
                                <div class="approval-card__actions">
                                    <button class="action-btn action-btn--approve" @click.stop="approveQuote(quote)">
                                        ✓ Aprobar
                                    </button>
                                    <button class="action-btn action-btn--reject" @click.stop="rejectQuote(quote)">
                                        ✕ Rechazar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Alerts -->
                    <section class="section section--alerts">
                        <div class="section__header">
                            <h2 class="section__title">
                                <span class="section__icon">⚠️</span>
                                Alertas
                            </h2>
                        </div>
                        
                        <div class="alerts-list">
                            <div 
                                v-for="alert in teamAlerts" 
                                :key="alert.id"
                                class="alert-item"
                                :class="`alert-item--${alert.type}`">
                                <span class="alert-item__icon">{{ alert.type === 'warning' ? '⚠️' : 'ℹ️' }}</span>
                                <span class="alert-item__message">{{ alert.message }}</span>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.dashboard-manager {
    padding: 1rem;
    max-width: 1400px;
    margin: 0 auto;
}

@media (min-width: 768px) {
    .dashboard-manager {
        padding: 1.5rem;
    }
}

/* Header */
.dashboard-header {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

@media (min-width: 768px) {
    .dashboard-header {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
}

.dashboard-header__title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #111827;
    margin: 0;
}

.dashboard-header__subtitle {
    font-size: 0.875rem;
    color: #6B7280;
    margin: 0.25rem 0 0 0;
}

.dashboard-header__actions {
    display: flex;
    gap: 0.5rem;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.875rem;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s;
}

.btn__icon {
    width: 18px;
    height: 18px;
}

.btn__text {
    display: none;
}

@media (min-width: 640px) {
    .btn__text {
        display: inline;
    }
}

.btn--secondary {
    background: white;
    border-color: #E5E7EB;
    color: #374151;
}

.btn--secondary:hover:not(:disabled) {
    border-color: #7B2D3B;
    color: #7B2D3B;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

@media (min-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: white;
    border-radius: 12px;
    border: 1px solid #E5E7EB;
}

.stat-card--highlight {
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    border: none;
    color: white;
}

.stat-card--highlight .stat-card__label {
    color: rgba(255, 255, 255, 0.7);
}

.stat-card--warning {
    border-color: #F59E0B;
    background: linear-gradient(135deg, #FFFBEB 0%, white 100%);
}

.stat-card__icon {
    font-size: 1.5rem;
}

.stat-card__content {
    flex: 1;
}

.stat-card__value {
    font-family: 'JetBrains Mono', monospace;
    font-size: 1.25rem;
    font-weight: 700;
}

.stat-card__label {
    font-size: 0.75rem;
    color: #6B7280;
}

/* Main Grid */
.main-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 1024px) {
    .main-grid {
        grid-template-columns: 1.5fr 1fr;
    }
}

.right-column {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Section */
.section {
    background: white;
    border-radius: 16px;
    padding: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
}

@media (min-width: 768px) {
    .section {
        padding: 1.25rem;
    }
}

.section__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.section__title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.section__icon {
    font-size: 1.25rem;
}

.section__badge {
    background: #7B2D3B;
    color: white;
    padding: 0.125rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.section--team {
    border-left: 4px solid #3B82F6;
}

.section--approvals {
    border-left: 4px solid #F59E0B;
}

.section--alerts {
    border-left: 4px solid #EF4444;
}

/* Team List */
.team-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.team-member {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #F9FAFB;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
}

.team-member:hover {
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.team-member__avatar {
    position: relative;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #C7A172, #A8855C);
    color: #2D0F16;
    font-weight: 700;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    flex-shrink: 0;
}

.status-dot {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
}

.status-dot--online {
    background: #10B981;
}

.status-dot--busy {
    background: #F59E0B;
}

.status-dot--offline {
    background: #9CA3AF;
}

.team-member__info {
    flex: 1;
    min-width: 0;
}

.team-member__name {
    font-weight: 600;
    color: #111827;
    font-size: 0.875rem;
}

.team-member__role {
    font-size: 0.75rem;
    color: #6B7280;
}

.team-member__stats {
    display: flex;
    gap: 1rem;
}

.team-member__stat {
    text-align: center;
}

.team-member__stat-value {
    display: block;
    font-family: 'JetBrains Mono', monospace;
    font-weight: 700;
    font-size: 0.875rem;
    color: #7B2D3B;
}

.team-member__stat-label {
    font-size: 0.625rem;
    color: #9CA3AF;
}

/* Approvals */
.approvals-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.approval-card {
    background: #FFFBEB;
    border: 1px solid #FCD34D;
    border-radius: 10px;
    padding: 0.75rem;
}

.approval-card__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.approval-card__id {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.75rem;
    color: #6B7280;
}

.approval-card__amount {
    font-family: 'JetBrains Mono', monospace;
    font-weight: 700;
    color: #7B2D3B;
}

.approval-card__customer {
    font-weight: 600;
    color: #111827;
    font-size: 0.875rem;
}

.approval-card__meta {
    font-size: 0.75rem;
    color: #6B7280;
    margin-top: 0.125rem;
}

.approval-card__actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.75rem;
}

.action-btn {
    flex: 1;
    padding: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.action-btn--approve {
    background: #D1FAE5;
    color: #047857;
}

.action-btn--approve:hover {
    background: #A7F3D0;
}

.action-btn--reject {
    background: #FEE2E2;
    color: #B91C1C;
}

.action-btn--reject:hover {
    background: #FECACA;
}

/* Alerts */
.alerts-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.alert-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: 8px;
    font-size: 0.8125rem;
}

.alert-item--warning {
    background: #FFFBEB;
    color: #92400E;
}

.alert-item--info {
    background: #EFF6FF;
    color: #1E40AF;
}

.alert-item__icon {
    font-size: 1.125rem;
    flex-shrink: 0;
}

/* Utilities */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
