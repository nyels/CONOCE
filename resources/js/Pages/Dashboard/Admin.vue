<!-- resources/js/Pages/Dashboard/Admin.vue -->
<script setup>
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    dashboardData: Object,
    userRole: String
});

const refreshing = ref(false);
const activePeriod = ref('month');

// Safe data access with defaults
const financialKpis = computed(() => props.dashboardData?.financialKpis || []);
const trends = computed(() => props.dashboardData?.trends?.monthly || []);
const trendsSummary = computed(() => props.dashboardData?.trends?.summary || { growth_quotes: 0, growth_premium: 0 });
const conversionByInsurer = computed(() => props.dashboardData?.conversionByInsurer || []);
const systemAlerts = computed(() => props.dashboardData?.systemAlerts || []);
const period = computed(() => props.dashboardData?.period || { current: 'Enero 2026', previous: 'Diciembre 2025' });

// Format utilities
const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(value || 0);
};

const formatNumber = (value) => {
    return new Intl.NumberFormat('es-MX').format(value || 0);
};

const formatPercent = (value) => {
    return `${(value || 0).toFixed(1)}%`;
};

const formatValue = (value, format) => {
    switch (format) {
        case 'currency': return formatCurrency(value);
        case 'percent': return formatPercent(value);
        default: return formatNumber(value);
    }
};

// Calculate change percentage
const getChange = (current, previous) => {
    if (!previous || previous === 0) return current > 0 ? 100 : 0;
    return ((current - previous) / previous) * 100;
};

const getChangeClass = (current, previous) => {
    const change = getChange(current, previous);
    if (change > 0) return 'positive';
    if (change < 0) return 'negative';
    return 'neutral';
};

const getChangeIcon = (current, previous) => {
    const change = getChange(current, previous);
    if (change > 0) return '↑';
    if (change < 0) return '↓';
    return '→';
};

// Chart bar height calculation
const maxQuotes = computed(() => {
    if (!trends.value.length) return 1;
    return Math.max(...trends.value.map(t => t.quotes || 0), 1);
});

const getBarHeight = (value) => {
    return ((value || 0) / maxQuotes.value) * 100;
};

// Actions
const refreshData = () => {
    refreshing.value = true;
    router.reload({
        only: ['dashboardData'],
        onFinish: () => {
            refreshing.value = false;
        }
    });
};

const exportReport = () => {
    alert('Exportar reporte - próximamente');
};
</script>

<template>
    <AppLayout>
        <div class="dashboard-admin">
            <!-- Header -->
            <header class="dashboard-header">
                <div class="dashboard-header__info">
                    <h1 class="dashboard-header__title">Dashboard Administrativo</h1>
                    <p class="dashboard-header__subtitle">
                        Visión estratégica y KPIs financieros — 
                        <strong>{{ period.current }}</strong>
                    </p>
                </div>
                <div class="dashboard-header__actions">
                    <button 
                        class="btn btn--secondary" 
                        @click="refreshData" 
                        :disabled="refreshing">
                        <svg class="btn__icon" :class="{ 'animate-spin': refreshing }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="btn__text">{{ refreshing ? 'Actualizando...' : 'Actualizar' }}</span>
                    </button>
                    <button class="btn btn--primary" @click="exportReport">
                        <svg class="btn__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="btn__text">Exportar</span>
                    </button>
                </div>
            </header>

            <!-- KPIs Section -->
            <section class="section section--kpis">
                <div class="section__header">
                    <h2 class="section__title">
                        <span class="section__icon">💰</span>
                        KPIs Financieros Clave
                    </h2>
                    <span class="section__meta">vs {{ period.previous }}</span>
                </div>
                <div class="kpi-grid">
                    <div 
                        v-for="kpi in financialKpis" 
                        :key="kpi.id"
                        class="kpi-card"
                        :class="{ 'kpi-card--priority': kpi.priority === 1 }">
                        <div class="kpi-card__header">
                            <span class="kpi-card__icon">{{ kpi.icon }}</span>
                            <span class="kpi-card__title">{{ kpi.title }}</span>
                        </div>
                        <div class="kpi-card__value">{{ formatValue(kpi.value, kpi.format) }}</div>
                        <div 
                            v-if="kpi.previousValue !== null"
                            class="kpi-card__change"
                            :class="`kpi-card__change--${getChangeClass(kpi.value, kpi.previousValue)}`">
                            <span class="kpi-card__change-icon">{{ getChangeIcon(kpi.value, kpi.previousValue) }}</span>
                            <span class="kpi-card__change-value">{{ Math.abs(getChange(kpi.value, kpi.previousValue)).toFixed(1) }}%</span>
                        </div>
                        <div class="kpi-card__subtitle">{{ kpi.subtitle }}</div>
                    </div>
                </div>
            </section>

            <!-- Main Grid -->
            <div class="main-grid">
                <!-- Trends Section -->
                <section class="section section--trends">
                    <div class="section__header">
                        <h2 class="section__title">
                            <span class="section__icon">📈</span>
                            Tendencia 6 Meses
                        </h2>
                        <div class="period-tabs">
                            <button 
                                v-for="p in ['month', 'quarter', 'year']"
                                :key="p"
                                class="period-tab"
                                :class="{ 'period-tab--active': activePeriod === p }"
                                @click="activePeriod = p">
                                {{ { month: 'Mes', quarter: 'Trim', year: 'Año' }[p] }}
                            </button>
                        </div>
                    </div>
                    
                    <!-- Chart -->
                    <div class="chart-container">
                        <div class="chart-bars">
                            <div 
                                v-for="(month, index) in trends" 
                                :key="index"
                                class="chart-bar-group">
                                <div class="chart-bar-wrapper">
                                    <div 
                                        class="chart-bar"
                                        :style="{ height: `${getBarHeight(month.quotes)}%` }"
                                        :title="`${month.quotes} cotizaciones`">
                                    </div>
                                </div>
                                <div class="chart-bar-label">{{ month.month }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="trends-summary">
                        <div class="trends-summary__item">
                            <span class="trends-summary__label">Crecimiento Cotizaciones</span>
                            <span 
                                class="trends-summary__value"
                                :class="trendsSummary.growth_quotes >= 0 ? 'text-success' : 'text-danger'">
                                {{ trendsSummary.growth_quotes >= 0 ? '+' : '' }}{{ trendsSummary.growth_quotes }}%
                            </span>
                        </div>
                    </div>
                </section>

                <!-- Insurers Section -->
                <section class="section section--insurers">
                    <div class="section__header">
                        <h2 class="section__title">
                            <span class="section__icon">🏆</span>
                            Top Aseguradoras
                        </h2>
                    </div>
                    
                    <!-- Mobile: Cards / Desktop: Table -->
                    <div class="insurers-list">
                        <div 
                            v-for="(insurer, index) in conversionByInsurer.slice(0, 5)" 
                            :key="insurer.id"
                            class="insurer-card">
                            <div class="insurer-card__rank">#{{ index + 1 }}</div>
                            <div class="insurer-card__info">
                                <div class="insurer-card__name">{{ insurer.name }}</div>
                                <div class="insurer-card__stats">
                                    <span>{{ insurer.quotes_count }} cot.</span>
                                    <span>{{ insurer.concluded_count }} pólizas</span>
                                </div>
                            </div>
                            <div class="insurer-card__rate">
                                <span class="insurer-card__rate-value">{{ insurer.conversion_rate }}%</span>
                                <span 
                                    class="insurer-card__badge"
                                    :class="insurer.is_performing ? 'badge--success' : 'badge--warning'">
                                    {{ insurer.is_performing ? '✓' : '!' }}
                                </span>
                            </div>
                        </div>
                        
                        <div v-if="!conversionByInsurer.length" class="empty-state">
                            <span class="empty-state__icon">📊</span>
                            <span class="empty-state__text">Sin datos de aseguradoras</span>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Alerts Section -->
            <section class="section section--alerts">
                <div class="section__header">
                    <h2 class="section__title">
                        <span class="section__icon">⚠️</span>
                        Alertas del Sistema
                    </h2>
                    <span class="section__badge">{{ systemAlerts.length }}</span>
                </div>
                
                <div v-if="systemAlerts.length" class="alerts-grid">
                    <div 
                        v-for="alert in systemAlerts" 
                        :key="alert.id"
                        class="alert-card"
                        :class="`alert-card--${alert.type}`">
                        <div class="alert-card__icon">
                            <span v-if="alert.type === 'danger'">🚨</span>
                            <span v-else-if="alert.type === 'warning'">⚠️</span>
                            <span v-else>ℹ️</span>
                        </div>
                        <div class="alert-card__content">
                            <div class="alert-card__title">{{ alert.title }}</div>
                            <div class="alert-card__message">{{ alert.message }}</div>
                        </div>
                    </div>
                </div>
                
                <div v-else class="success-state">
                    <span class="success-state__icon">✅</span>
                    <div class="success-state__content">
                        <div class="success-state__title">Sin alertas críticas</div>
                        <div class="success-state__text">El sistema está operando correctamente</div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>

<style scoped>
/* ===== DASHBOARD CONTAINER ===== */
.dashboard-admin {
    padding: 1rem;
    max-width: 1400px;
    margin: 0 auto;
}

@media (min-width: 768px) {
    .dashboard-admin {
        padding: 1.5rem;
    }
}

@media (min-width: 1024px) {
    .dashboard-admin {
        padding: 2rem;
    }
}

/* ===== HEADER ===== */
.dashboard-header {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #E5E7EB;
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

@media (min-width: 768px) {
    .dashboard-header__title {
        font-size: 1.75rem;
    }
}

.dashboard-header__subtitle {
    font-size: 0.875rem;
    color: #6B7280;
    margin: 0.25rem 0 0 0;
}

.dashboard-header__subtitle strong {
    color: #7B2D3B;
}

.dashboard-header__actions {
    display: flex;
    gap: 0.5rem;
}

/* ===== BUTTONS ===== */
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
    white-space: nowrap;
}

.btn__icon {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
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

.btn--primary {
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: white;
}

.btn--primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(123, 45, 59, 0.25);
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* ===== SECTIONS ===== */
.section {
    background: white;
    border-radius: 16px;
    padding: 1rem;
    margin-bottom: 1.25rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
}

@media (min-width: 768px) {
    .section {
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
}

.section__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 0.5rem;
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

.section__meta {
    font-size: 0.75rem;
    color: #9CA3AF;
}

.section__badge {
    background: #7B2D3B;
    color: white;
    padding: 0.125rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.section--kpis {
    border-left: 4px solid #7B2D3B;
}

.section--trends {
    border-left: 4px solid #C7A172;
}

.section--insurers {
    border-left: 4px solid #3B82F6;
}

.section--alerts {
    border-left: 4px solid #F59E0B;
}

/* ===== KPI GRID ===== */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}

@media (min-width: 640px) {
    .kpi-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (min-width: 1024px) {
    .kpi-grid {
        grid-template-columns: repeat(6, 1fr);
    }
}

.kpi-card {
    background: #F9FAFB;
    border-radius: 12px;
    padding: 0.875rem;
    text-align: center;
    border: 1px solid transparent;
    transition: all 0.2s;
}

.kpi-card:hover {
    background: white;
    border-color: #E5E7EB;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.kpi-card--priority {
    background: linear-gradient(135deg, rgba(123, 45, 59, 0.05) 0%, rgba(199, 161, 114, 0.05) 100%);
    border: 1px solid rgba(123, 45, 59, 0.1);
}

.kpi-card__header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
    margin-bottom: 0.5rem;
}

.kpi-card__icon {
    font-size: 1rem;
}

.kpi-card__title {
    font-size: 0.6875rem;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.kpi-card__value {
    font-family: 'JetBrains Mono', monospace;
    font-size: 1.25rem;
    font-weight: 800;
    color: #111827;
    line-height: 1.2;
}

@media (min-width: 768px) {
    .kpi-card__value {
        font-size: 1.5rem;
    }
}

.kpi-card__change {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.125rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.625rem;
    font-weight: 600;
    margin-top: 0.375rem;
}

.kpi-card__change--positive {
    background: #D1FAE5;
    color: #047857;
}

.kpi-card__change--negative {
    background: #FEE2E2;
    color: #B91C1C;
}

.kpi-card__change--neutral {
    background: #F3F4F6;
    color: #6B7280;
}

.kpi-card__subtitle {
    font-size: 0.6875rem;
    color: #9CA3AF;
    margin-top: 0.375rem;
}

/* ===== MAIN GRID ===== */
.main-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.25rem;
}

@media (min-width: 1024px) {
    .main-grid {
        grid-template-columns: 1.5fr 1fr;
    }
}

/* ===== PERIOD TABS ===== */
.period-tabs {
    display: flex;
    gap: 0.25rem;
    background: #F3F4F6;
    padding: 0.25rem;
    border-radius: 8px;
}

.period-tab {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #6B7280;
    background: transparent;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.period-tab:hover {
    color: #111827;
}

.period-tab--active {
    background: white;
    color: #7B2D3B;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* ===== CHART ===== */
.chart-container {
    margin: 1rem 0;
}

.chart-bars {
    display: flex;
    align-items: flex-end;
    gap: 0.5rem;
    height: 160px;
    padding: 0.5rem;
    background: #F9FAFB;
    border-radius: 12px;
}

@media (min-width: 768px) {
    .chart-bars {
        height: 200px;
        gap: 1rem;
    }
}

.chart-bar-group {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
}

.chart-bar-wrapper {
    flex: 1;
    width: 100%;
    display: flex;
    align-items: flex-end;
    justify-content: center;
}

.chart-bar {
    width: 100%;
    max-width: 40px;
    min-height: 4px;
    background: linear-gradient(to top, #7B2D3B, #A0404F);
    border-radius: 4px 4px 0 0;
    transition: height 0.5s ease;
}

.chart-bar-label {
    font-size: 0.625rem;
    color: #6B7280;
    margin-top: 0.5rem;
    text-align: center;
    white-space: nowrap;
}

@media (min-width: 768px) {
    .chart-bar-label {
        font-size: 0.75rem;
    }
}

/* ===== TRENDS SUMMARY ===== */
.trends-summary {
    display: flex;
    gap: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid #E5E7EB;
}

.trends-summary__item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.trends-summary__label {
    font-size: 0.8125rem;
    color: #6B7280;
}

.trends-summary__value {
    font-family: 'JetBrains Mono', monospace;
    font-weight: 700;
    font-size: 1rem;
}

.text-success {
    color: #059669;
}

.text-danger {
    color: #DC2626;
}

/* ===== INSURERS LIST ===== */
.insurers-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.insurer-card {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #F9FAFB;
    border-radius: 10px;
    transition: all 0.2s;
}

.insurer-card:hover {
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.insurer-card__rank {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #C7A172, #A8855C);
    color: #2D0F16;
    font-weight: 700;
    font-size: 0.75rem;
    border-radius: 8px;
    flex-shrink: 0;
}

.insurer-card__info {
    flex: 1;
    min-width: 0;
}

.insurer-card__name {
    font-weight: 600;
    color: #111827;
    font-size: 0.875rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.insurer-card__stats {
    display: flex;
    gap: 0.75rem;
    font-size: 0.6875rem;
    color: #9CA3AF;
    margin-top: 0.125rem;
}

.insurer-card__rate {
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.insurer-card__rate-value {
    font-family: 'JetBrains Mono', monospace;
    font-weight: 700;
    color: #7B2D3B;
    font-size: 0.9375rem;
}

.insurer-card__badge {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 0.625rem;
}

.badge--success {
    background: #D1FAE5;
    color: #047857;
}

.badge--warning {
    background: #FEF3C7;
    color: #B45309;
}

/* ===== ALERTS ===== */
.alerts-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
}

@media (min-width: 768px) {
    .alerts-grid {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }
}

.alert-card {
    display: flex;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 12px;
    border-left: 4px solid;
}

.alert-card--danger {
    background: linear-gradient(135deg, #FEF2F2 0%, white 100%);
    border-color: #EF4444;
}

.alert-card--warning {
    background: linear-gradient(135deg, #FFFBEB 0%, white 100%);
    border-color: #F59E0B;
}

.alert-card--info {
    background: linear-gradient(135deg, #EFF6FF 0%, white 100%);
    border-color: #3B82F6;
}

.alert-card__icon {
    font-size: 1.25rem;
    flex-shrink: 0;
}

.alert-card__content {
    flex: 1;
}

.alert-card__title {
    font-weight: 600;
    color: #111827;
    font-size: 0.875rem;
}

.alert-card__message {
    font-size: 0.8125rem;
    color: #6B7280;
    margin-top: 0.25rem;
}

/* ===== EMPTY & SUCCESS STATES ===== */
.empty-state,
.success-state {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: #F9FAFB;
    border-radius: 12px;
    justify-content: center;
}

.empty-state__icon,
.success-state__icon {
    font-size: 2rem;
}

.empty-state__text {
    color: #6B7280;
    font-size: 0.875rem;
}

.success-state__title {
    font-weight: 600;
    color: #047857;
}

.success-state__text {
    font-size: 0.8125rem;
    color: #6B7280;
}

/* ===== UTILITIES ===== */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>