<!-- resources/js/Pages/Admin/PolicyFees/Index.vue -->
<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
defineOptions({ layout: AppLayout });
import { CrudTable, CrudModal, FormInput, FormSelect } from '@/Components/Crud';
import { useConfirm } from '@/composables/useConfirm';
import { useInertiaForm } from '@/composables/useInertiaForm';

const props = defineProps({
    policyFees: { type: Array, default: () => [] },
    insurers: { type: Array, default: () => [] }
});

const { confirmDelete } = useConfirm();
const { processing, submitForm, deleteRecord } = useInertiaForm();

const showModal = ref(false);
const isEditing = ref(false);
const editingItem = ref(null);

// History modal state
const showHistoryModal = ref(false);
const historyInsurerName = ref('');
const historyData = ref([]);
const historyLoading = ref(false);

// Frontend validation errors
const validationErrors = ref({});

// Todas las aseguradoras disponibles para el select
const availableInsurers = computed(() => props.insurers);

// Validation functions
const validateInsurer = (value) => {
    if (!value) return 'Debe seleccionar una aseguradora';
    return null;
};

const validatePolicyFee = (value) => {
    if (value === '' || value === null || value === undefined) return 'El derecho de poliza es obligatorio';
    const str = String(value);
    if (str.endsWith('.')) return 'Numero incompleto, ingrese los decimales o quite el punto';
    if (!/^\d+(\.\d{1,2})?$/.test(str)) return 'Solo numeros con maximo 2 decimales (ej: 350.00)';
    const num = parseFloat(str);
    if (num < 0) return 'No puede ser negativo';
    if (num > 999999.99) return 'No puede exceder $999,999.99';
    return null;
};

const onBlur = (field) => {
    if (field === 'insurer_id') validationErrors.value.insurer_id = validateInsurer(form.insurer_id);
    if (field === 'policy_fee') validationErrors.value.policy_fee = validatePolicyFee(form.policy_fee);
};

const getError = (field) => validationErrors.value[field] || form.errors[field];

const hasValidationErrors = () => {
    validationErrors.value.insurer_id = validateInsurer(form.insurer_id);
    validationErrors.value.policy_fee = validatePolicyFee(form.policy_fee);
    return Object.values(validationErrors.value).some(error => error !== null);
};

const form = useForm({
    id: null,
    insurer_id: '',
    policy_fee: ''
});

const columns = [
    { key: 'insurer_name', label: 'Aseguradora', sortable: true },
    { key: 'policy_fee_display', label: 'Derecho de Poliza' },
    { key: 'created_at', label: 'Registrado' },
    { key: 'actions', label: 'Acciones', type: 'actions' }
];

// Formatear datos para la tabla
const tableData = computed(() => {
    return props.policyFees.map(pf => ({
        ...pf,
        policy_fee_display: '$' + Number(pf.policy_fee).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
    }));
});

const formatMoney = (value) => {
    return '$' + Number(value).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const openCreate = () => {
    form.reset();
    form.clearErrors();
    validationErrors.value = {};
    isEditing.value = false;
    editingItem.value = null;
    showModal.value = true;
};

const openEdit = (item) => {
    form.id = item.id;
    form.insurer_id = item.insurer_id;
    form.policy_fee = item.policy_fee;
    form.clearErrors();
    validationErrors.value = {};
    isEditing.value = true;
    editingItem.value = item;
    showModal.value = true;
};

const submit = () => {
    if (hasValidationErrors()) {
        return;
    }

    const url = isEditing.value
        ? route('admin.policy-fees.update', form.id)
        : route('admin.policy-fees.store');

    const data = isEditing.value
        ? { policy_fee: form.policy_fee }
        : { insurer_id: form.insurer_id, policy_fee: form.policy_fee };

    submitForm({
        url,
        data,
        method: isEditing.value ? 'put' : 'post',
        onSuccess: () => {
            showModal.value = false;
            form.reset();
        },
        onValidationError: (errors) => {
            Object.keys(errors).forEach(key => {
                validationErrors.value[key] = errors[key];
            });
        },
        successMessage: isEditing.value ? 'Derecho de poliza actualizado exitosamente' : 'Derecho de poliza creado exitosamente'
    });
};

const handleDelete = async (item) => {
    const confirmed = await confirmDelete(item.insurer_name);
    if (confirmed) {
        deleteRecord({
            url: route('admin.policy-fees.destroy', item.id),
            successMessage: 'Derecho de poliza eliminado exitosamente'
        });
    }
};

// History
const openHistory = async (item) => {
    historyLoading.value = true;
    historyInsurerName.value = item.insurer_name;
    historyData.value = [];
    showHistoryModal.value = true;

    try {
        const response = await fetch(route('admin.policy-fees.history', item.insurer_id));
        const json = await response.json();
        historyInsurerName.value = json.insurer_name;
        historyData.value = json.history;
    } catch (e) {
        historyData.value = [];
    } finally {
        historyLoading.value = false;
    }
};

// Bloquear teclas no permitidas en el input de monto
// Solo permite: dígitos, punto decimal (una vez), Backspace, Delete, Tab, flechas, Home, End, Ctrl+A/C/V/X
const onPolicyFeeKeydown = (e) => {
    // Permitir teclas de control/navegación
    if (['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(e.key)) return;
    // Permitir Ctrl/Cmd combinaciones (copiar, pegar, seleccionar todo)
    if (e.ctrlKey || e.metaKey) return;
    // Permitir punto decimal solo si no existe ya uno
    if (e.key === '.') {
        if (String(form.policy_fee).includes('.')) e.preventDefault();
        return;
    }
    // Solo permitir dígitos (0-9)
    if (!/^\d$/.test(e.key)) {
        e.preventDefault();
    }
};

// Validar en tiempo real al escribir (también limpia pegado con Ctrl+V)
const onPolicyFeeInput = (e) => {
    let raw = e.target.value;
    // Eliminar TODO lo que no sea dígito o punto
    let cleaned = raw.replace(/[^\d.]/g, '');
    // Solo un punto decimal permitido
    const dotIndex = cleaned.indexOf('.');
    if (dotIndex !== -1) {
        cleaned = cleaned.slice(0, dotIndex + 1) + cleaned.slice(dotIndex + 1).replace(/\./g, '');
    }
    // Máximo 2 decimales
    const parts = cleaned.split('.');
    if (parts.length === 2 && parts[1].length > 2) {
        cleaned = parts[0] + '.' + parts[1].slice(0, 2);
    }
    // Actualizar el input si se limpió algo
    if (raw !== cleaned) {
        e.target.value = cleaned;
    }
    form.policy_fee = cleaned;
    validationErrors.value.policy_fee = validatePolicyFee(cleaned);
};
</script>

<template>
    <Head title="Derechos de Poliza" />
    <div class="page-container">
        <div class="page-header">
            <div class="header-content">
                <h1 class="page-title">Derechos de Poliza</h1>
                <p class="page-subtitle">Gestiona el derecho de poliza por aseguradora</p>
            </div>
            <button class="btn btn--primary" @click="openCreate">
                <span class="btn-icon">+</span>
                Nuevo Derecho de Poliza
            </button>
        </div>

        <CrudTable
            :data="tableData"
            :columns="columns"
            search-placeholder="Buscar por aseguradora..."
            empty-message="No hay derechos de poliza registrados"
            @edit="openEdit"
            @delete="handleDelete"
            @view="openHistory"
        >
            <!-- Override actions column to add history button -->
            <template #cell-actions="{ item }">
                <div class="actions">
                    <button class="action-btn action-btn--history" @click.stop="openHistory(item)" title="Ver historial">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12,6 12,12 16,14"/>
                        </svg>
                    </button>
                    <button class="action-btn action-btn--edit" @click.stop="openEdit(item)" title="Editar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                    <button class="action-btn action-btn--delete" @click.stop="handleDelete(item)" title="Eliminar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3,6 5,6 21,6"/>
                            <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                            <line x1="10" y1="11" x2="10" y2="17"/>
                            <line x1="14" y1="11" x2="14" y2="17"/>
                        </svg>
                    </button>
                </div>
            </template>
        </CrudTable>
    </div>

    <!-- Create/Edit Modal -->
    <CrudModal
        :show="showModal"
        :title="isEditing ? 'Editar Derecho de Poliza' : 'Nuevo Derecho de Poliza'"
        :loading="processing"
        @close="showModal = false"
        @submit="submit"
    >
        <FormSelect
            v-model="form.insurer_id"
            label="Aseguradora"
            :options="availableInsurers"
            placeholder="Seleccionar aseguradora..."
            :error="getError('insurer_id')"
            :disabled="isEditing"
            required
            @update:model-value="onBlur('insurer_id')"
        />

        <div class="form-group">
            <label class="form-label">
                Derecho de Poliza (MXN)
                <span class="required">*</span>
            </label>
            <div class="input-wrapper" :class="{ 'has-error': getError('policy_fee') }">
                <span class="input-prefix">$</span>
                <input
                    type="text"
                    inputmode="decimal"
                    :value="form.policy_fee"
                    @input="onPolicyFeeInput($event)"
                    @blur="onBlur('policy_fee')"
                    @keydown="onPolicyFeeKeydown"
                    placeholder="0.00"
                    class="form-input form-input--prefixed"
                />
            </div>
            <span v-if="getError('policy_fee')" class="form-error">{{ getError('policy_fee') }}</span>
        </div>
    </CrudModal>

    <!-- History Modal -->
    <CrudModal
        :show="showHistoryModal"
        :title="'Historial - ' + historyInsurerName"
        size="lg"
        @close="showHistoryModal = false"
        @submit="showHistoryModal = false"
    >
        <div v-if="historyLoading" class="history-loading">
            <div class="spinner"></div>
            <span>Cargando historial...</span>
        </div>

        <div v-else-if="historyData.length === 0" class="history-empty">
            Sin registros de historial.
        </div>

        <table v-else class="history-table">
            <thead>
                <tr>
                    <th>Fecha y Hora</th>
                    <th>Derecho de Poliza</th>
                    <th>Vigencia Desde</th>
                    <th>Vigencia Hasta</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="record in historyData" :key="record.id">
                    <td>{{ record.created_at }}</td>
                    <td class="td-money">{{ formatMoney(record.policy_fee) }}</td>
                    <td>{{ record.valid_from }}</td>
                    <td>
                        <span v-if="record.valid_until">{{ record.valid_until }}</span>
                        <span v-else class="badge-active">Vigente</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <template #footer>
            <button class="btn btn--secondary" @click="showHistoryModal = false">
                Cerrar
            </button>
        </template>
    </CrudModal>
</template>

<style scoped>
.page-container { padding: 1.5rem; max-width: 1000px; margin: 0 auto; }
.page-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; }
.header-content { flex: 1; }
.page-title { font-size: 1.5rem; font-weight: 700; color: #111827; margin: 0; }
.page-subtitle { font-size: 0.9375rem; color: #6B7280; margin: 0.25rem 0 0 0; }
.btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; border-radius: 12px; font-weight: 600; font-size: 0.9375rem; border: none; cursor: pointer; transition: all 0.2s; }
.btn--primary { background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%); color: white; }
.btn--primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(123, 45, 59, 0.3); }
.btn--secondary { background: white; color: #4B5563; border: 1px solid #E5E7EB; }
.btn--secondary:hover { background: #F9FAFB; }
.btn-icon { font-size: 1.125rem; font-weight: 400; }

.form-group { margin-bottom: 1rem; }
.form-label { display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem; }
.required { color: #DC2626; margin-left: 2px; }

.input-wrapper { position: relative; display: flex; align-items: center; border: 1px solid #E5E7EB; border-radius: 10px; transition: all 0.2s; background: white; }
.input-wrapper:focus-within { border-color: #7B2D3B; box-shadow: 0 0 0 3px rgba(123, 45, 59, 0.1); }
.input-wrapper.has-error { border-color: #DC2626; }
.input-wrapper.has-error:focus-within { box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1); }

.input-prefix { padding: 0.625rem 0 0.625rem 1rem; font-size: 0.9375rem; font-weight: 600; color: #6B7280; user-select: none; }

.form-input--prefixed { width: 100%; padding: 0.625rem 1rem 0.625rem 0.25rem; border: none; border-radius: 10px; font-size: 0.9375rem; color: #111827; background: transparent; outline: none; }
.form-input--prefixed::placeholder { color: #9CA3AF; }

.form-error { display: block; font-size: 0.8125rem; color: #DC2626; margin-top: 0.375rem; }

/* Actions */
.actions { display: flex; gap: 0.5rem; }
.action-btn { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border: none; border-radius: 8px; cursor: pointer; transition: all 0.2s; }
.action-btn--history { background: #F0FDF4; color: #16A34A; }
.action-btn--history:hover { background: #DCFCE7; }
.action-btn--edit { background: #EFF6FF; color: #3B82F6; }
.action-btn--edit:hover { background: #DBEAFE; }
.action-btn--delete { background: #FEF2F2; color: #EF4444; }
.action-btn--delete:hover { background: #FEE2E2; }

/* History table */
.history-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
.history-table th { text-align: left; padding: 0.75rem 1rem; background: #F9FAFB; color: #6B7280; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #E5E7EB; }
.history-table td { padding: 0.75rem 1rem; border-bottom: 1px solid #F3F4F6; color: #374151; }
.history-table tbody tr:hover { background: #F9FAFB; }
.td-money { font-weight: 600; color: #111827; }

.badge-active { display: inline-flex; padding: 0.125rem 0.5rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; background: #D1FAE5; color: #059669; }

.history-loading { display: flex; flex-direction: column; align-items: center; gap: 0.75rem; padding: 2rem; color: #6B7280; }
.spinner { width: 24px; height: 24px; border: 2px solid #E5E7EB; border-top-color: #7B2D3B; border-radius: 50%; animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

.history-empty { text-align: center; padding: 2rem; color: #9CA3AF; font-size: 0.9375rem; }

@media (max-width: 640px) {
    .page-header { flex-direction: column; align-items: stretch; }
    .btn--primary { justify-content: center; }
    .history-table { font-size: 0.8125rem; }
    .history-table th, .history-table td { padding: 0.5rem; }
}
</style>
