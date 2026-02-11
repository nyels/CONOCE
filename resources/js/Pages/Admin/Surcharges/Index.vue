<!-- resources/js/Pages/Admin/Surcharges/Index.vue -->
<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
defineOptions({ layout: AppLayout });
import { CrudTable, CrudModal, FormSelect } from '@/components/Crud';
import { useConfirm } from '@/composables/useConfirm';
import { useInertiaForm } from '@/composables/useInertiaForm';

const props = defineProps({
    surcharges: { type: Array, default: () => [] },
    insurers: { type: Array, default: () => [] },
    frequencies: { type: Array, default: () => [] }
});

const { confirmDelete } = useConfirm();
const { processing, submitForm, deleteRecord } = useInertiaForm();

const showModal = ref(false);
const isEditing = ref(false);
const editingItem = ref(null);

// History modal state
const showHistoryModal = ref(false);
const historyTitle = ref('');
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

const validateFrequency = (value) => {
    if (!value) return 'Debe seleccionar una forma de pago';
    return null;
};

const validateSurcharge = (value) => {
    if (value === '' || value === null || value === undefined) return 'El recargo es obligatorio';
    const str = String(value);
    if (str.endsWith('.')) return 'Numero incompleto, ingrese los decimales o quite el punto';
    if (!/^\d+(\.\d{1,2})?$/.test(str)) return 'Solo numeros con maximo 2 decimales (ej: 3.50)';
    const num = parseFloat(str);
    if (num < 0) return 'No puede ser negativo';
    if (num > 99.99) return 'No puede exceder 99.99%';
    return null;
};

const onBlur = (field) => {
    if (field === 'insurer_id') validationErrors.value.insurer_id = validateInsurer(form.insurer_id);
    if (field === 'frequency') validationErrors.value.frequency = validateFrequency(form.frequency);
    if (field === 'surcharge') validationErrors.value.surcharge = validateSurcharge(form.surcharge);
};

const getError = (field) => validationErrors.value[field] || form.errors[field];

const hasValidationErrors = () => {
    validationErrors.value.insurer_id = validateInsurer(form.insurer_id);
    validationErrors.value.frequency = validateFrequency(form.frequency);
    validationErrors.value.surcharge = validateSurcharge(form.surcharge);
    return Object.values(validationErrors.value).some(error => error !== null);
};

const form = useForm({
    id: null,
    insurer_id: '',
    frequency: '',
    surcharge: ''
});

const columns = [
    { key: 'insurer_name', label: 'Aseguradora', sortable: true },
    { key: 'frequency_label', label: 'Forma de Pago', sortable: true },
    { key: 'surcharge_display', label: 'Recargo' },
    { key: 'created_at', label: 'Registrado' },
    { key: 'actions', label: 'Acciones', type: 'actions' }
];

// Formatear datos para la tabla
const tableData = computed(() => {
    return props.surcharges.map(s => ({
        ...s,
        surcharge_display: Number(s.surcharge).toFixed(2) + '%'
    }));
});

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
    form.frequency = item.frequency;
    form.surcharge = String(item.surcharge);
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
        ? route('admin.surcharges.update', form.id)
        : route('admin.surcharges.store');

    const data = isEditing.value
        ? { frequency: form.frequency, surcharge: form.surcharge }
        : { insurer_id: form.insurer_id, frequency: form.frequency, surcharge: form.surcharge };

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
        successMessage: isEditing.value ? 'Recargo actualizado exitosamente' : 'Recargo creado exitosamente'
    });
};

const handleDelete = async (item) => {
    const label = item.insurer_name + ' - ' + item.frequency_label;
    const confirmed = await confirmDelete(label);
    if (confirmed) {
        deleteRecord({
            url: route('admin.surcharges.destroy', item.id) + '?frequency=' + item.frequency,
            successMessage: 'Recargo eliminado exitosamente'
        });
    }
};

// History
const openHistory = async (item) => {
    historyLoading.value = true;
    historyTitle.value = item.insurer_name + ' - ' + item.frequency_label;
    historyData.value = [];
    showHistoryModal.value = true;

    try {
        const url = route('admin.surcharges.history', item.insurer_id) + '?frequency=' + item.frequency;
        const response = await fetch(url);
        const json = await response.json();
        historyTitle.value = json.insurer_name + ' - ' + json.frequency_label;
        historyData.value = json.history;
    } catch (e) {
        historyData.value = [];
    } finally {
        historyLoading.value = false;
    }
};

// Bloquear teclas no permitidas en el input de porcentaje
const onSurchargeKeydown = (e) => {
    if (['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(e.key)) return;
    if (e.ctrlKey || e.metaKey) return;
    if (e.key === '.') {
        if (String(form.surcharge).includes('.')) e.preventDefault();
        return;
    }
    if (!/^\d$/.test(e.key)) {
        e.preventDefault();
    }
};

// Validar en tiempo real al escribir
const onSurchargeInput = (e) => {
    let raw = e.target.value;
    let cleaned = raw.replace(/[^\d.]/g, '');
    const dotIndex = cleaned.indexOf('.');
    if (dotIndex !== -1) {
        cleaned = cleaned.slice(0, dotIndex + 1) + cleaned.slice(dotIndex + 1).replace(/\./g, '');
    }
    const parts = cleaned.split('.');
    if (parts.length === 2 && parts[1].length > 2) {
        cleaned = parts[0] + '.' + parts[1].slice(0, 2);
    }
    if (raw !== cleaned) {
        e.target.value = cleaned;
    }
    form.surcharge = cleaned;
    validationErrors.value.surcharge = validateSurcharge(cleaned);
};
</script>

<template>
    <Head title="Recargos por Cargo Fraccionado" />
    <div class="page-container">
        <div class="page-header">
            <div class="header-content">
                <h1 class="page-title">Recargos por Cargo Fraccionado</h1>
                <p class="page-subtitle">Gestiona los recargos por aseguradora y forma de pago</p>
            </div>
            <button class="btn btn--primary" @click="openCreate">
                <span class="btn-icon">+</span>
                Nuevo Recargo
            </button>
        </div>

        <CrudTable
            :data="tableData"
            :columns="columns"
            search-placeholder="Buscar por aseguradora..."
            empty-message="No hay recargos registrados"
            @edit="openEdit"
            @delete="handleDelete"
            @view="openHistory"
        >
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
        :title="isEditing ? 'Editar Recargo' : 'Nuevo Recargo'"
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

        <FormSelect
            v-model="form.frequency"
            label="Forma de Pago"
            :options="frequencies"
            placeholder="Seleccionar forma de pago..."
            :error="getError('frequency')"
            :disabled="isEditing"
            required
            @update:model-value="onBlur('frequency')"
        />

        <div class="form-group">
            <label class="form-label">
                Recargo (%)
                <span class="required">*</span>
            </label>
            <div class="input-wrapper" :class="{ 'has-error': getError('surcharge') }">
                <input
                    type="text"
                    inputmode="decimal"
                    :value="form.surcharge"
                    @input="onSurchargeInput($event)"
                    @blur="onBlur('surcharge')"
                    @keydown="onSurchargeKeydown"
                    placeholder="0.00"
                    class="form-input form-input--suffixed"
                />
                <span class="input-suffix">%</span>
            </div>
            <span v-if="getError('surcharge')" class="form-error">{{ getError('surcharge') }}</span>
        </div>
    </CrudModal>

    <!-- History Modal -->
    <CrudModal
        :show="showHistoryModal"
        :title="'Historial - ' + historyTitle"
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
                    <th>Recargo</th>
                    <th>Vigencia Desde</th>
                    <th>Vigencia Hasta</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="record in historyData" :key="record.id">
                    <td>{{ record.created_at }}</td>
                    <td class="td-surcharge">{{ Number(record.surcharge).toFixed(2) }}%</td>
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

.input-suffix { padding: 0.625rem 1rem 0.625rem 0; font-size: 0.9375rem; font-weight: 600; color: #6B7280; user-select: none; }

.form-input--suffixed { width: 100%; padding: 0.625rem 0.25rem 0.625rem 1rem; border: none; border-radius: 10px; font-size: 0.9375rem; color: #111827; background: transparent; outline: none; }
.form-input--suffixed::placeholder { color: #9CA3AF; }

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
.td-surcharge { font-weight: 600; color: #111827; }

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
