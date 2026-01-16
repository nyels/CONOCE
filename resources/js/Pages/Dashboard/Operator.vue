<!-- resources/js/Pages/Dashboard/Operator.vue -->
<script setup>
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    dashboardData: Object,
    userRole: String
});

const refreshing = ref(false);

// Mock data for immediate actions
const immediateActions = ref([
    { id: 1, type: 'pending', count: 3, label: 'Pendientes de envío', icon: '📤', color: 'warning' },
    { id: 2, type: 'followup', count: 2, label: 'Seguimientos hoy', icon: '📞', color: 'info' },
    { id: 3, type: 'expiring', count: 1, label: 'Vence mañana', icon: '⏰', color: 'danger' },
]);

// Mock pipeline data
const pipeline = ref([
    { 
        id: 'COT-2401-0034', 
        customer: 'Juan Pérez García',
        vehicle: 'Toyota Corolla 2024',
        insurer: 'HDI Amplio',
        premium: 9350,
        status: 'sent',
        statusLabel: 'Enviada',
        daysRemaining: 3,
        lastAction: 'Hace 2 días'
    },
    { 
        id: 'COT-2401-0033', 
        customer: 'María García López',
        vehicle: 'Honda Civic 2023',
        insurer: 'Qualitas Básico',
        premium: 8200,
        status: 'followup',
        statusLabel: 'Seguimiento',
        daysRemaining: 5,
        lastAction: 'Hace 1 día'
    },
    { 
        id: 'COT-2401-0032', 
        customer: 'Carlos Hernández',
        vehicle: 'Mazda 3 2024',
        insurer: 'GNP Premium',
        premium: 12500,
        status: 'draft',
        statusLabel: 'Borrador',
        daysRemaining: 7,
        lastAction: 'Hace 3 horas'
    },
]);

// Mock productivity data
const productivity = ref({
    quotesToday: 5,
    quotesGoal: 8,
    conversionRate: 25,
    premiumToday: 45000,
    premiumGoal: 60000,
});

// Mock activity log
const recentActivity = ref([
    { id: 1, action: 'Cotización enviada', target: 'Juan Pérez', time: '10:30 AM' },
    { id: 2, action: 'Cliente creado', target: 'María García', time: '09:15 AM' },
    { id: 3, action: 'Seguimiento registrado', target: 'Carlos H.', time: '08:45 AM' },
    { id: 4, action: 'PDF generado', target: 'COT-2401-0031', time: 'Ayer 5:30 PM' },
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
        'draft': 'status--draft',
        'sent': 'status--sent',
        'followup': 'status--followup',
        'concluded': 'status--concluded',
        'rejected': 'status--rejected',
    };
    return classes[status] || '';
};

const refreshData = () => {
    refreshing.value = true;
    router.reload({
        only: ['dashboardData'],
        onFinish: () => {
            refreshing.value = false;
        }
    });
};

const newQuote = () => {
    alert('Crear nueva cotización - próximamente');
};

const callCustomer = (item) => {
    alert(`Llamar a ${item.customer}`);
};

const viewQuote = (item) => {
    alert(`Ver cotización ${item.id}`);
};

const concludeQuote = (item) => {
    alert(`Concluir cotización ${item.id}`);
};
</script>

<template>
    <AppLayout>
        <div class="dashboard-operator">
            <!-- Quick Actions Bar -->
            <div class="quick-bar">
                <button class="quick-bar__btn quick-bar__btn--primary" @click="newQuote">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Nueva Cotización</span>
                </button>
                <button class="quick-bar__btn" @click="refreshData" :disabled="refreshing">
                    <svg class="w-5 h-5" :class="{ 'animate-spin': refreshing }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>{{ refreshing ? 'Actualizando...' : 'Actualizar' }}</span>
                </button>
            </div>

            <!-- Immediate Actions Section -->
            <section class="section section--urgent">
                <div class="section__header">
                    <h2 class="section__title">
                        <span class="section__icon">🎯</span>
                        Acción Inmediata
                    </h2>
                </div>
                <div class="action-cards">
                    <div 
                        v-for="action in immediateActions" 
                        :key="action.id"
                        class="action-card"
                        :class="`action-card--${action.color}`">
                        <div class="action-card__icon">{{ action.icon }}</div>
                        <div class="action-card__content">
                            <div class="action-card__count">{{ action.count }}</div>
                            <div class="action-card__label">{{ action.label }}</div>
                        </div>
                        <svg class="action-card__arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </section>

            <!-- Main Grid -->
            <div class="dashboard-grid">
                <!-- Pipeline Section -->
                <section class="section section--pipeline">
                    <div class="section__header">
                        <h2 class="section__title">
                            <span class="section__icon">📋</span>
                            Pipeline Activo
                        </h2>
                        <a href="#" class="section__link">Ver todo →</a>
                    </div>
                    <div class="pipeline-list">
                        <div 
                            v-for="item in pipeline" 
                            :key="item.id"
                            class="pipeline-card">
                            <div class="pipeline-card__header">
                                <span class="pipeline-card__id">{{ item.id }}</span>
                                <span class="pipeline-card__status" :class="getStatusClass(item.status)">
                                    {{ item.statusLabel }}
                                </span>
                            </div>
                            <div class="pipeline-card__body">
                                <div class="pipeline-card__customer">{{ item.customer }}</div>
                                <div class="pipeline-card__vehicle">{{ item.vehicle }}</div>
                                <div class="pipeline-card__insurer">{{ item.insurer }}</div>
                            </div>
                            <div class="pipeline-card__footer">
                                <div class="pipeline-card__premium">{{ formatCurrency(item.premium) }}</div>
                                <div class="pipeline-card__meta">
                                    <span>Vence: {{ item.daysRemaining }} días</span>
                                    <span>{{ item.lastAction }}</span>
                                </div>
                            </div>
                            <div class="pipeline-card__actions">
                                <button class="action-btn action-btn--call" @click="callCustomer(item)" title="Llamar">
                                    📞
                                </button>
                                <button class="action-btn action-btn--view" @click="viewQuote(item)" title="Ver">
                                    👁️
                                </button>
                                <button class="action-btn action-btn--conclude" @click="concludeQuote(item)" title="Concluir">
                                    ✅
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Right Column -->
                <div class="dashboard-right">
                    <!-- Productivity Section -->
                    <section class="section section--productivity">
                        <div class="section__header">
                            <h2 class="section__title">
                                <span class="section__icon">📈</span>
                                Mi Productividad Hoy
                            </h2>
                        </div>
                        <div class="productivity-grid">
                            <div class="productivity-item">
                                <div class="productivity-item__label">Cotizaciones</div>
                                <div class="productivity-item__value">
                                    {{ productivity.quotesToday }}/{{ productivity.quotesGoal }}
                                </div>
                                <div class="productivity-item__bar">
                                    <div 
                                        class="productivity-item__fill"
                                        :style="{ width: `${(productivity.quotesToday / productivity.quotesGoal) * 100}%` }">
                                    </div>
                                </div>
                            </div>
                            <div class="productivity-item">
                                <div class="productivity-item__label">Prima Acumulada</div>
                                <div class="productivity-item__value">
                                    {{ formatCurrency(productivity.premiumToday) }}
                                </div>
                                <div class="productivity-item__bar">
                                    <div 
                                        class="productivity-item__fill productivity-item__fill--gold"
                                        :style="{ width: `${(productivity.premiumToday / productivity.premiumGoal) * 100}%` }">
                                    </div>
                                </div>
                                <div class="productivity-item__meta">
                                    Meta: {{ formatCurrency(productivity.premiumGoal) }}
                                </div>
                            </div>
                            <div class="productivity-item productivity-item--highlight">
                                <div class="productivity-item__label">Tasa Conversión</div>
                                <div class="productivity-item__value productivity-item__value--large">
                                    {{ productivity.conversionRate }}%
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Recent Activity Section -->
                    <section class="section section--activity">
                        <div class="section__header">
                            <h2 class="section__title">
                                <span class="section__icon">🕐</span>
                                Actividad Reciente
                            </h2>
                        </div>
                        <div class="activity-list">
                            <div v-for="activity in recentActivity" :key="activity.id" class="activity-item">
                                <div class="activity-item__dot"></div>
                                <div class="activity-item__content">
                                    <span class="activity-item__action">{{ activity.action }}</span>
                                    <span class="activity-item__target">{{ activity.target }}</span>
                                </div>
                                <div class="activity-item__time">{{ activity.time }}</div>
                            </div>
                        </div>
                        <a href="#" class="section__footer-link">Ver historial completo →</a>
                    </section>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.dashboard-operator {
    padding: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* Quick Bar */
.quick-bar {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.quick-bar__btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.875rem;
    border: 1px solid #E5E7EB;
    background: white;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s;
}

.quick-bar__btn:hover {
    border-color: #7B2D3B;
    color: #7B2D3B;
}

.quick-bar__btn--primary {
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    border: none;
    color: white;
    box-shadow: 0 4px 12px rgba(123, 45, 59, 0.25);
}

.quick-bar__btn--primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(123, 45, 59, 0.35);
}

/* Section */
.section {
    background: white;
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
    margin-bottom: 1.5rem;
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

.section__link {
    font-size: 0.8125rem;
    color: #7B2D3B;
    text-decoration: none;
    font-weight: 500;
}

.section__link:hover {
    text-decoration: underline;
}

.section__footer-link {
    display: block;
    text-align: center;
    padding-top: 1rem;
    margin-top: 1rem;
    border-top: 1px solid #F3F4F6;
    font-size: 0.8125rem;
    color: #7B2D3B;
    text-decoration: none;
}

/* Urgent Section */
.section--urgent {
    border-left: 4px solid #7B2D3B;
}

/* Action Cards */
.action-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
}

.action-card {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
    background: #F9FAFB;
    border: 1px solid transparent;
}

.action-card:hover {
    background: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.action-card--warning {
    border-color: #FCD34D;
    background: linear-gradient(135deg, #FFFBEB 0%, white 100%);
}

.action-card--info {
    border-color: #93C5FD;
    background: linear-gradient(135deg, #EFF6FF 0%, white 100%);
}

.action-card--danger {
    border-color: #FCA5A5;
    background: linear-gradient(135deg, #FEF2F2 0%, white 100%);
}

.action-card__icon {
    font-size: 1.5rem;
}

.action-card__content {
    flex: 1;
}

.action-card__count {
    font-size: 1.5rem;
    font-weight: 800;
    color: #111827;
    line-height: 1;
}

.action-card__label {
    font-size: 0.75rem;
    color: #6B7280;
    margin-top: 0.25rem;
}

.action-card__arrow {
    width: 20px;
    height: 20px;
    color: #9CA3AF;
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 1024px) {
    .dashboard-grid {
        grid-template-columns: 2fr 1fr;
    }
}

.dashboard-right {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Pipeline */
.section--pipeline {
    margin-bottom: 0;
}

.pipeline-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.pipeline-card {
    background: #F9FAFB;
    border-radius: 12px;
    padding: 1rem;
    border: 1px solid #E5E7EB;
    transition: all 0.2s;
}

.pipeline-card:hover {
    background: white;
    border-color: #7B2D3B;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.pipeline-card__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.pipeline-card__id {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.75rem;
    font-weight: 600;
    color: #6B7280;
}

.pipeline-card__status {
    padding: 0.25rem 0.625rem;
    border-radius: 9999px;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status--draft {
    background: #E5E7EB;
    color: #4B5563;
}

.status--sent {
    background: #DBEAFE;
    color: #1D4ED8;
}

.status--followup {
    background: #FEF3C7;
    color: #B45309;
}

.status--concluded {
    background: #D1FAE5;
    color: #047857;
}

.status--rejected {
    background: #FEE2E2;
    color: #B91C1C;
}

.pipeline-card__body {
    margin-bottom: 0.75rem;
}

.pipeline-card__customer {
    font-weight: 600;
    color: #111827;
    font-size: 0.9375rem;
}

.pipeline-card__vehicle,
.pipeline-card__insurer {
    font-size: 0.8125rem;
    color: #6B7280;
}

.pipeline-card__footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 0.75rem;
    border-top: 1px solid #E5E7EB;
}

.pipeline-card__premium {
    font-family: 'JetBrains Mono', monospace;
    font-weight: 700;
    color: #7B2D3B;
    font-size: 1rem;
}

.pipeline-card__meta {
    display: flex;
    gap: 0.75rem;
    font-size: 0.6875rem;
    color: #9CA3AF;
}

.pipeline-card__actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px dashed #E5E7EB;
}

.action-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem;
    border-radius: 8px;
    border: 1px solid #E5E7EB;
    background: white;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 1rem;
}

.action-btn:hover {
    transform: scale(1.05);
}

.action-btn--call:hover {
    background: #DBEAFE;
    border-color: #3B82F6;
}

.action-btn--view:hover {
    background: #F3F4F6;
    border-color: #6B7280;
}

.action-btn--conclude:hover {
    background: #D1FAE5;
    border-color: #059669;
}

/* Productivity */
.section--productivity {
    margin-bottom: 0;
}

.productivity-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.productivity-item {
    padding: 0.75rem;
    background: #F9FAFB;
    border-radius: 10px;
}

.productivity-item--highlight {
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: white;
    text-align: center;
    padding: 1rem;
}

.productivity-item--highlight .productivity-item__label {
    color: rgba(255, 255, 255, 0.7);
}

.productivity-item--highlight .productivity-item__value {
    color: white;
}

.productivity-item__label {
    font-size: 0.75rem;
    color: #6B7280;
    margin-bottom: 0.25rem;
}

.productivity-item__value {
    font-family: 'JetBrains Mono', monospace;
    font-weight: 700;
    font-size: 1.125rem;
    color: #111827;
}

.productivity-item__value--large {
    font-size: 2rem;
}

.productivity-item__bar {
    height: 6px;
    background: #E5E7EB;
    border-radius: 9999px;
    margin-top: 0.5rem;
    overflow: hidden;
}

.productivity-item__fill {
    height: 100%;
    background: linear-gradient(90deg, #7B2D3B 0%, #A0404F 100%);
    border-radius: 9999px;
    transition: width 0.5s ease;
}

.productivity-item__fill--gold {
    background: linear-gradient(90deg, #C7A172 0%, #A8855C 100%);
}

.productivity-item__meta {
    font-size: 0.6875rem;
    color: #9CA3AF;
    margin-top: 0.375rem;
    text-align: right;
}

/* Activity */
.section--activity {
    margin-bottom: 0;
}

.activity-list {
    display: flex;
    flex-direction: column;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.625rem 0;
    border-bottom: 1px solid #F3F4F6;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item__dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #C7A172;
    margin-top: 0.375rem;
    flex-shrink: 0;
}

.activity-item__content {
    flex: 1;
    font-size: 0.8125rem;
}

.activity-item__action {
    color: #374151;
}

.activity-item__target {
    font-weight: 600;
    color: #111827;
}

.activity-item__time {
    font-size: 0.6875rem;
    color: #9CA3AF;
    white-space: nowrap;
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