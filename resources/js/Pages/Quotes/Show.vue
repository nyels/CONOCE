<!-- resources/js/Pages/Quotes/Show.vue -->
<script setup>
import { ref } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
defineOptions({ layout: AppLayout });
import { ConfirmDialog, ToastContainer } from '@/components/Ui';
import { useConfirm } from '@/composables/useConfirm';
import { useToast } from '@/composables/useToast';

const props = defineProps({
    quote: { type: Object, required: true },
});

// Toast & Confirm
const { isOpen: confirmOpen, config: confirmConfig, confirm, onConfirm, onCancel } = useConfirm();
const toast = useToast();

// Format currency
const formatCurrency = (v) => new Intl.NumberFormat('es-MX', { 
    style: 'currency', 
    currency: 'MXN',
    minimumFractionDigits: 0 
}).format(v);

// Actions
const handleSend = () => {
    router.post(route('quotes.send', props.quote.id), {}, {
        preserveScroll: true,
        onSuccess: () => toast.success('Cotización enviada al cliente'),
        onError: () => toast.error('No se pudo enviar'),
    });
};

const handleDelete = async () => {
    const confirmed = await confirm({
        title: 'Eliminar Cotización',
        message: `¿Estás seguro de eliminar ${props.quote.folio}?`,
        confirmText: 'Eliminar',
        type: 'danger',
    });
    if (confirmed) {
        router.delete(route('quotes.destroy', props.quote.id), {
            onSuccess: () => {
                toast.success('Cotización eliminada');
                router.visit(route('quotes.index'));
            },
        });
    }
};

// PDF Preview (placeholder)
const showPdfPreview = ref(false);
const openPdfPreview = () => {
    window.open(route('quotes.pdf-preview', props.quote.id), '_blank');
};
</script>

<template>
    <ToastContainer>
        <Head :title="`Cotización ${quote.folio}`" />
        
        <div class="page-container">
                <!-- Header -->
                <div class="page-header">
                    <div class="header-content">
                        <div class="breadcrumb">
                            <Link :href="route('quotes.index')" class="breadcrumb-link">Cotizaciones</Link>
                            <span class="breadcrumb-sep">/</span>
                            <span class="breadcrumb-current">{{ quote.folio }}</span>
                        </div>
                        <h1 class="page-title">{{ quote.folio }}</h1>
                        <div class="header-meta">
                            <span 
                                class="status-badge"
                                :class="`status-badge--${quote.status}`"
                            >
                                {{ quote.status_label }}
                            </span>
                            <span class="meta-item">{{ quote.type_label }}</span>
                            <span class="meta-item">{{ quote.created_at }}</span>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button 
                            v-if="quote.is_editable"
                            class="btn btn--secondary"
                            @click="openPdfPreview"
                        >
                            📄 Generar PDF
                        </button>
                        <button 
                            v-if="quote.status === 'draft'"
                            class="btn btn--primary"
                            @click="handleSend"
                        >
                            📤 Enviar al Cliente
                        </button>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="content-grid">
                    <!-- Left Column: Details -->
                    <div class="details-column">
                        <!-- Customer Card -->
                        <div class="card">
                            <h3 class="card-title">👤 Cliente</h3>
                            <div class="info-grid" v-if="quote.customer">
                                <div class="info-item" v-if="quote.customer.type_label">
                                    <span class="info-label">Tipo de Persona</span>
                                    <span class="info-value">{{ quote.customer.type_label }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Nombre</span>
                                    <span class="info-value">{{ quote.customer.name }}</span>
                                </div>
                                <div class="info-item" v-if="quote.customer.phone">
                                    <span class="info-label">Teléfono</span>
                                    <span class="info-value">{{ quote.customer.phone }}</span>
                                </div>
                                <div class="info-item" v-if="quote.customer.mobile">
                                    <span class="info-label">Celular</span>
                                    <span class="info-value">{{ quote.customer.mobile }}</span>
                                </div>
                                <div class="info-item" v-if="quote.customer.email">
                                    <span class="info-label">Email</span>
                                    <span class="info-value">{{ quote.customer.email }}</span>
                                </div>
                                <div class="info-item" v-if="quote.customer.rfc">
                                    <span class="info-label">RFC</span>
                                    <span class="info-value info-mono">{{ quote.customer.rfc }}</span>
                                </div>
                            </div>
                            <p v-else class="empty-text">Sin cliente asignado</p>
                        </div>

                        <!-- Vehicle Card -->
                        <div class="card">
                            <h3 class="card-title">🚗 Vehículo</h3>
                            <div class="vehicle-summary" v-if="quote.vehicle">
                                <div class="info-grid">
                                    <div class="info-item" v-if="quote.vehicle.brand">
                                        <span class="info-label">Marca</span>
                                        <span class="info-value">{{ quote.vehicle.brand }}</span>
                                    </div>
                                    <div class="info-item" v-if="quote.vehicle_type">
                                        <span class="info-label">Tipo</span>
                                        <span class="info-value">{{ quote.vehicle_type }}</span>
                                    </div>
                                    <div class="info-item" v-if="quote.vehicle.model">
                                        <span class="info-label">Descripción (Versión/Línea)</span>
                                        <span class="info-value">{{ quote.vehicle.model }}</span>
                                    </div>
                                    <div class="info-item" v-if="quote.vehicle.year">
                                        <span class="info-label">Modelo (Año)</span>
                                        <span class="info-value">{{ quote.vehicle.year }}</span>
                                    </div>
                                    <div class="info-item" v-if="quote.vehicle_usage || quote.vehicle?.usage">
                                        <span class="info-label">Uso de la Unidad</span>
                                        <span class="info-value">{{ quote.vehicle_usage || quote.vehicle.usage }}</span>
                                    </div>
                                    <div class="info-item" v-if="quote.cargo_description">
                                        <span class="info-label">Carga</span>
                                        <span class="info-value">{{ quote.cargo_description }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Card -->
                        <div class="card" v-if="quote.contact">
                            <h3 class="card-title">📇 Contacto</h3>
                            <div class="info-grid">
                                <div class="info-item" v-if="quote.contact.type_label">
                                    <span class="info-label">Tipo de Contacto</span>
                                    <span class="info-value">{{ quote.contact.type_label }}</span>
                                </div>
                                <div class="info-item" v-if="quote.contact.name">
                                    <span class="info-label">Nombre</span>
                                    <span class="info-value">{{ quote.contact.name }}</span>
                                </div>
                                <div class="info-item" v-if="quote.contact.mobile">
                                    <span class="info-label">Celular</span>
                                    <span class="info-value">{{ quote.contact.mobile }}</span>
                                </div>
                                <div class="info-item" v-if="quote.contact.email">
                                    <span class="info-label">Correo</span>
                                    <span class="info-value">{{ quote.contact.email }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Agent & Notes Card -->
                        <div class="card">
                            <h3 class="card-title">📝 Información Adicional</h3>
                            <div class="info-grid">
                                <div class="info-item" v-if="quote.agent">
                                    <span class="info-label">Creado por</span>
                                    <span class="info-value">{{ quote.agent.name }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Fecha y Hora de Cotización</span>
                                    <span class="info-value">{{ quote.created_at }}</span>
                                </div>
                                <div class="info-item" v-if="quote.package_label">
                                    <span class="info-label">Paquete</span>
                                    <span class="info-value">{{ quote.package_label }}</span>
                                </div>
                                <div class="info-item" v-if="quote.quote_valid_until">
                                    <span class="info-label">Vigencia</span>
                                    <span class="info-value">{{ quote.quote_valid_until }}</span>
                                </div>
                            </div>
                            <div v-if="quote.notes" class="notes-section">
                                <span class="info-label">Notas</span>
                                <p class="notes-text">{{ quote.notes }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Options -->
                    <div class="options-column">
                        <div class="card">
                            <div class="card-title-row">
                                <h3 class="card-title">💰 Opciones de Cotización</h3>
                                <span v-if="quote.options?.length" class="option-frequency">{{ quote.options[0].payment_frequency }}</span>
                            </div>

                            <div v-if="quote.options?.length" class="options-list">
                                <div
                                    v-for="option in quote.options"
                                    :key="option.id"
                                    class="option-card"
                                >
                                    <div class="option-header">
                                        <div class="option-insurer">
                                            <img
                                                v-if="option.insurer_logo"
                                                :src="option.insurer_logo"
                                                :alt="option.insurer_name"
                                                class="insurer-logo"
                                            >
                                            <span class="insurer-name">{{ option.insurer_name }}</span>
                                        </div>
                                        <span class="option-number-badge">Opción {{ option.option_number }}</span>
                                    </div>

                                    <div class="option-details">
                                        <div class="detail-row">
                                            <span>Prima Neta Anual</span>
                                            <span class="detail-value">{{ formatCurrency(option.net_premium) }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span>Prima Total Anual</span>
                                            <span class="detail-value">{{ formatCurrency(option.total_premium) }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span>Primer Pago</span>
                                            <span class="detail-value">{{ formatCurrency(option.first_payment) }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span>Subsecuentes</span>
                                            <span class="detail-value">{{ formatCurrency(option.subsequent_payment) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <p v-else class="empty-text">No hay opciones registradas</p>
                        </div>
                    </div>
                </div>

                <!-- Actions Footer -->
                <div class="actions-footer" v-if="quote.is_editable">
                    <Link :href="route('quotes.edit', quote.id)" class="btn btn--secondary">
                        ✏️ Editar Cotización
                    </Link>
                    <button class="btn btn--danger" @click="handleDelete">
                        🗑️ Eliminar
                    </button>
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
    max-width: 1200px;
    margin: 0 auto;
}

/* Breadcrumb */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
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

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
    font-family: 'JetBrains Mono', monospace;
}

.header-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 0.5rem;
}

.meta-item {
    font-size: 0.875rem;
    color: #6B7280;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
}

/* Status Badge */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge--draft { background: #F3F4F6; color: #6B7280; }
.status-badge--sent { background: #DBEAFE; color: #1D4ED8; }
.status-badge--concreted { background: #D1FAE5; color: #059669; }
.status-badge--issued { background: #A7F3D0; color: #047857; }
.status-badge--rejected { background: #FEE2E2; color: #DC2626; }

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

@media (max-width: 1024px) {
    .content-grid { grid-template-columns: 1fr; }
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
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #F3F4F6;
}

.card-title-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #F3F4F6;
}

.card-title-row .card-title {
    margin: 0;
    padding: 0;
    border: none;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

@media (min-width: 768px) {
    .info-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-label {
    font-size: 0.75rem;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.info-value {
    font-size: 0.9375rem;
    color: #111827;
    font-weight: 500;
}

.info-mono {
    font-family: 'JetBrains Mono', monospace;
}

.vehicle-main {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 1rem;
}

.notes-section {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #F3F4F6;
}

.notes-text {
    color: #374151;
    margin: 0.5rem 0 0 0;
    white-space: pre-wrap;
}

.empty-text {
    color: #9CA3AF;
    font-style: italic;
    margin: 0;
}

/* Options */
.options-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.option-card {
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    padding: 1rem;
    transition: all 0.2s;
}

.option-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.option-insurer {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.insurer-logo {
    width: 40px;
    height: 40px;
    object-fit: contain;
    border-radius: 8px;
    background: #F9FAFB;
    padding: 4px;
}

.insurer-name {
    font-weight: 600;
    color: #111827;
}

.option-number-badge {
    background: #F3F4F6;
    color: #374151;
    padding: 0.25rem 0.625rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.option-frequency {
    display: inline-block;
    background: #EEF2FF;
    color: #4338CA;
    padding: 0.2rem 0.625rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.option-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.875rem;
    color: #6B7280;
}

.detail-value {
    font-family: 'JetBrains Mono', monospace;
    color: #374151;
}



/* Actions Footer */
.actions-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #E5E7EB;
}

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

.btn--danger {
    background: white;
    border: 1px solid #FCA5A5;
    color: #DC2626;
}

.btn--danger:hover {
    background: #FEE2E2;
}
</style>
