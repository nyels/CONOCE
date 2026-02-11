<!-- resources/js/Components/Quote/CoverageTable.vue -->
<!--
    Componente de tabla de coberturas para cotización de seguros
    Replica exactamente la estructura del sistema legacy con 5 columnas de aseguradoras

    Props:
    - insurers: Array de aseguradoras disponibles
    - enabledColumns: Número de columnas habilitadas (1-5)
    - modelValue: Objeto con todos los valores de las 5 columnas
    - packageType: Tipo de paquete seleccionado (AMPLIA, LIMITADA, RESPONSABILIDAD CIVIL)

    Emits:
    - update:modelValue: Cuando cambia cualquier valor
-->
<script setup>
import { computed, watch } from 'vue';
import { useCurrencyFormat } from '@/composables/useCurrencyFormat';

const props = defineProps({
    insurers: { type: Array, default: () => [] },
    enabledColumns: { type: Number, default: 0 },
    modelValue: { type: Object, default: () => ({}) },
    packageType: { type: String, default: '' },
    paymentFrequency: { type: String, default: 'ANUAL' },
    customCoverage1Name: { type: String, default: '' },
    customCoverage2Name: { type: String, default: '' },
    coverageErrors: { type: Set, default: () => new Set() },
});

// Verificar si un campo tiene error de validación
const hasError = (field, col) => {
    if (!col) return props.coverageErrors.has(field);
    return props.coverageErrors.has(`${field}_${col}`);
};

const emit = defineEmits([
    'update:modelValue',
    'update:customCoverage1Name',
    'update:customCoverage2Name',
    'insurerChanged', // Emitir cuando cambia la aseguradora de una columna
]);

const { formatInput } = useCurrencyFormat();
// NOTA: parseMoney y formatMoney eliminados - los cálculos ahora los hace el backend

// ==========================================
// BACKEND AUTORITATIVO: Este componente NO calcula valores monetarios
// Solo renderiza los valores que vienen del modelo (actualizados por el backend)
// Los cálculos se hacen en Create.vue -> requestBackendCalculation()
// ==========================================

/**
 * Handler para cuando el usuario sale del campo primer_pago (blur/focusout)
 * NOTA: Este handler solo emite el evento. El cálculo real lo hace el backend
 * a través de Create.vue -> requestBackendCalculation()
 *
 * Este componente NO debe calcular subsecuentes. Solo:
 * 1. Captura el input del usuario
 * 2. Emite update:modelValue
 * 3. El padre (Create.vue) detecta el cambio y solicita cálculo al backend
 * 4. El backend responde y Create.vue actualiza el modelo
 * 5. Este componente re-renderiza con los valores calculados por el backend
 */
const handlePrimerPagoBlur = (_colIndex) => {
    // No hacer nada aquí - el cálculo lo maneja Create.vue via watcher
    // El watcher en Create.vue detecta cambios en primer_pago_opcion_X
    // y llama a requestBackendCalculation(colIndex, 'primer_pago')
    // El parámetro _colIndex se mantiene para compatibilidad con el template
};

// Obtener valor de una columna específica
// IMPORTANTE: Normaliza valores vacíos según tipo de campo
const getValue = (field, colIndex) => {
    const value = props.modelValue?.[`${field}_${colIndex}`];
    // Si es null/undefined/empty, retornar placeholder apropiado
    if (value === null || value === undefined || value === '') {
        // Campos de deducible usan 'na' como placeholder
        if (field === 'deducible_opcion' || field === 'deducible_rt') {
            return 'na';
        }
        // Campos tipo select usan '0' como placeholder
        if (field.includes('_selec') || field === 'empresa_opcion' ||
            field === 'extension_rc_opcion' || field.includes('cobertura_opcion') ||
            field === 'danos_carga_opcion_selec') {
            return '0';
        }
        // Campos de dinero/número retornan ''
        return '';
    }
    return value;
};

// Actualizar valor de una columna específica
const setValue = (field, colIndex, value) => {
    const newValue = { ...props.modelValue, [`${field}_${colIndex}`]: value };
    emit('update:modelValue', newValue);
};

// Handler específico para cambio de aseguradora
const handleInsurerChange = (colIndex, value) => {
    setValue('empresa_opcion', colIndex, value);
    // Emitir evento para que el padre pueda resetear la columna
    emit('insurerChanged', { colIndex, insurerId: value });
};

// Bloquear teclas no permitidas en inputs de dinero y porcentaje
// Solo permite: dígitos, punto decimal (una vez), teclas de control
const onMoneyKeydown = (e) => {
    if (['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(e.key)) return;
    if (e.ctrlKey || e.metaKey) return;
    if (e.key === '.') {
        if (e.target.value.includes('.')) e.preventDefault();
        return;
    }
    if (!/^\d$/.test(e.key)) {
        e.preventDefault();
    }
};

// Bloquear teclas no permitidas en inputs de porcentaje (solo enteros)
const onPercentKeydown = (e) => {
    if (['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(e.key)) return;
    if (e.ctrlKey || e.metaKey) return;
    if (!/^\d$/.test(e.key)) {
        e.preventDefault();
    }
};

// Formatear input de moneda mientras escribe
const handleMoneyInput = (field, colIndex, event) => {
    const formatted = formatInput(event.target.value);
    event.target.value = formatted;
    setValue(field, colIndex, formatted);
};

// Limpiar input de porcentaje (solo dígitos enteros, max 100)
const handlePercentInput = (field, colIndex, event) => {
    let cleaned = event.target.value.replace(/\D/g, '');
    if (cleaned.length > 3) cleaned = cleaned.slice(0, 3);
    const num = parseInt(cleaned) || 0;
    if (num > 100) cleaned = '100';
    event.target.value = cleaned;
    setValue(field, colIndex, cleaned);
};

// Verificar si una columna está habilitada (por cantidad seleccionada)
const isColumnEnabled = (colIndex) => colIndex <= props.enabledColumns;

// Verificar si una columna tiene aseguradora seleccionada (habilita los campos de esa columna)
const isColumnReady = (colIndex) => {
    if (!isColumnEnabled(colIndex)) return false;
    const insurerId = getValue('empresa_opcion', colIndex);
    return insurerId && insurerId !== '0' && insurerId !== '';
};

// Verificar si los campos financieros (PRIMA TOTAL, PRIMER PAGO) están listos
// Requiere: columna lista + forma de pago seleccionada
const isPaymentReady = (colIndex) => {
    if (!isColumnReady(colIndex)) return false;
    const freq = props.paymentFrequency;
    return freq && freq !== '0' && freq !== '';
};

// Total de columnas: 1 (label) + 5 (aseguradoras)
const totalColumns = computed(() => 1 + 5);

// ==========================================
// LÓGICA DE ENABLE/DISABLE SEGÚN PAQUETE (LEGACY FIEL)
// Ver: public/sistema viejo/js/formulario.js líneas 1292-1349, 1520-1600, etc.
// ==========================================

/**
 * AMPLIA: Todo habilitado (DM, cristales, RT)
 * LIMITADA: DM y cristales deshabilitados, RT habilitado
 * RESPONSABILIDAD CIVIL: DM, cristales y RT deshabilitados
 */
const isDanosEnabled = (colIndex) => {
    if (!isColumnReady(colIndex)) return false;
    // AMPLIA = habilitado, LIMITADA/RC = deshabilitado
    return props.packageType === 'AMPLIA';
};

const isCristalesEnabled = (colIndex) => {
    if (!isColumnReady(colIndex)) return false;
    // AMPLIA = habilitado, LIMITADA/RC = deshabilitado
    return props.packageType === 'AMPLIA';
};

const isRoboEnabled = (colIndex) => {
    if (!isColumnReady(colIndex)) return false;
    // AMPLIA/LIMITADA = habilitado, RC = deshabilitado
    return props.packageType === 'AMPLIA' || props.packageType === 'LIMITADA';
};

/**
 * IMPORTE FACTURA solo se habilita cuando tipo valor = V.FACTURA
 * Ver legacy: if($('#daños_opcion1_selec option:eq(3)').is(':selected')===true)
 */
const isDanosImporteEnabled = (colIndex) => {
    if (!isDanosEnabled(colIndex)) return false;
    return getValue('danos_opcion_selec', colIndex) === 'V.FACTURA';
};

const isRoboImporteEnabled = (colIndex) => {
    if (!isRoboEnabled(colIndex)) return false;
    return getValue('robo_opcion_selec', colIndex) === 'V.FACTURA';
};

/**
 * LEGACY: Cobertura adicional 1 - El input de texto solo se habilita si
 * ALGÚN select de cobertura_opcion_1_select tiene valor != '0'
 * Ver: formulario.js líneas 1184, 1270, 9100-9211
 */
const isCustomCoverage1InputEnabled = computed(() => {
    if (props.enabledColumns === 0) return false;
    // Verificar si ALGÚN select de cobertura 1 tiene valor seleccionado
    for (let col = 1; col <= props.enabledColumns; col++) {
        const val = props.modelValue?.[`cobertura_opcion_1_select_${col}`];
        if (val && val !== '0') return true;
    }
    return false;
});

/**
 * LEGACY: Cobertura adicional 2 - El input de texto solo se habilita si
 * ALGÚN select de cobertura_opcion_2_select tiene valor != '0'
 * Ver: formulario.js líneas 1198, 1271, 9214-9409
 */
const isCustomCoverage2InputEnabled = computed(() => {
    if (props.enabledColumns === 0) return false;
    // Verificar si ALGÚN select de cobertura 2 tiene valor seleccionado
    for (let col = 1; col <= props.enabledColumns; col++) {
        const val = props.modelValue?.[`cobertura_opcion_2_select_${col}`];
        if (val && val !== '0') return true;
    }
    return false;
});

// Opciones para selects
const valueTypeOptions = [
    { value: '0', label: 'SELECCIONA TIPO' },
    { value: 'V.COMERCIAL', label: 'V.COMERCIAL' }, 
    { value: 'V.CONVENIDO', label: 'V.CONVENIDO' },
    { value: 'V.FACTURA', label: 'V.FACTURA' },
];

const deductibleDMOptions = [
    { value: 'na', label: 'SELECCIONA DEDUCIBLE' },
    { value: '0', label: '0%' },
    { value: '3', label: '3%' },
    { value: '5', label: '5%' },
    { value: '10', label: '10%' },
    { value: '15', label: '15%' },
    { value: '20', label: '20%' },
];

const deductibleRTOptions = [
    { value: 'na', label: 'SELECCIONA DEDUCIBLE' },
    { value: '0', label: '0%' },
    { value: '5', label: '5%' },
    { value: '10', label: '10%' },
    { value: '15', label: '15%' },
    { value: '20', label: '20%' },
];

const coverageStatusOptions = [
    { value: '0', label: 'SELECCIONA TIPO COBERTURA' },
    { value: 'AMPARADA', label: 'AMPARADA' },
    { value: 'EXCLUIDA', label: 'EXCLUIDA' },
];

const paymentOptions = [
    { value: '0', label: 'SELECCIONA LA FORMA DE PAGO' },
    { value: 'ANUAL', label: 'ANUAL' },
    { value: 'SEMESTRAL', label: 'SEMESTRAL' },
    { value: 'TRIMESTRAL', label: 'TRIMESTRAL' },
    { value: 'MENSUAL', label: 'MENSUAL' },
];

// Calcular subsecuentes según forma de pago
// LEGACY FIX: Leer de modelValue.forma_de_pago (GLOBAL) con fallback a prop
// Ver: formulario.js líneas 9800-9946 (divisores: SEMESTRAL=1, TRIMESTRAL=3, MENSUAL=11)
const getSubsequentPayments = computed(() => {
    const formaPago = props.modelValue?.forma_de_pago || props.paymentFrequency;
    return {
        'ANUAL': 0,
        'SEMESTRAL': 1,
        'TRIMESTRAL': 3,
        'MENSUAL': 11,
    }[formaPago] || 0;
});

// ==========================================
// TASK D: Watchers para limpieza reactiva de importe_factura
// Cuando el usuario cambia de V.FACTURA a otro valor, limpiar el campo importe
// ==========================================

// Watch for changes in danos_opcion_selec to clear importe_factura
watch(
    () => props.modelValue,
    (newVal, oldVal) => {
        if (!newVal || !oldVal) return;

        for (let col = 1; col <= 5; col++) {
            const danoKey = `danos_opcion_selec_${col}`;
            const importeKey = `danos_material_importe_factura_${col}`;

            // Si cambió de V.FACTURA a otro valor, limpiar importe
            if (oldVal[danoKey] === 'V.FACTURA' && newVal[danoKey] !== 'V.FACTURA') {
                if (newVal[importeKey] && newVal[importeKey] !== '') {
                    const updated = { ...newVal, [importeKey]: '' };
                    emit('update:modelValue', updated);
                }
            }

            const roboKey = `robo_opcion_selec_${col}`;
            const roboImporteKey = `robo_importe_factura_${col}`;

            // Lo mismo para robo
            if (oldVal[roboKey] === 'V.FACTURA' && newVal[roboKey] !== 'V.FACTURA') {
                if (newVal[roboImporteKey] && newVal[roboImporteKey] !== '') {
                    const updated = { ...newVal, [roboImporteKey]: '' };
                    emit('update:modelValue', updated);
                }
            }
        }
    },
    { deep: true }
);

// ==========================================
// BACKEND AUTORITATIVO: CÁLCULOS ELIMINADOS DE ESTE COMPONENTE
// ==========================================
// Los cálculos de subsecuentes ahora se hacen en el backend a través de:
// - Create.vue -> requestBackendCalculation() para cambios individuales
// - Create.vue -> requestBatchCalculation() para cambios de forma_de_pago
//
// Este componente SOLO:
// 1. Renderiza los valores del modelo
// 2. Emite update:modelValue cuando el usuario cambia inputs
// 3. El padre (Create.vue) detecta cambios y solicita cálculos al backend
// 4. El backend responde con valores calculados
// 5. Create.vue actualiza el modelo y este componente re-renderiza
//
// NOTA: Los watchers de cálculo local fueron eliminados intencionalmente
// para cumplir con el patrón "Backend Autoritativo" donde:
// - Frontend SOLO renderiza, NUNCA calcula valores monetarios
// - Backend es la ÚNICA fuente de verdad para cálculos financieros
// ==========================================

// ==========================================
// TASK LEGACY: Sincronización DAÑOS → ROBO
// Ver: formulario.js líneas 6234-6490
// Cuando daños_opcion_selec_X cambia, robo_opcion_selec_X debe igualarse
// ==========================================
watch(
    () => props.modelValue,
    (newVal, oldVal) => {
        if (!newVal || !oldVal) return;

        for (let col = 1; col <= 5; col++) {
            if (!isColumnEnabled(col)) continue;

            const danosKey = `danos_opcion_selec_${col}`;
            const roboKey = `robo_opcion_selec_${col}`;

            // Solo procesar si cambió el valor de daños
            if (oldVal[danosKey] !== newVal[danosKey]) {
                const danosValue = newVal[danosKey];

                // Legacy: verificar_daños_opcion1_selec() líneas 6253-6273
                // Sincronizar robo con el mismo valor que daños
                // Solo si robo está habilitado (AMPLIA o LIMITADA, no RC)
                if (props.packageType === 'AMPLIA' || props.packageType === 'LIMITADA') {
                    // Evitar loop infinito
                    if (newVal[roboKey] !== danosValue) {
                        setValue('robo_opcion_selec', col, danosValue);
                    }
                }
            }
        }
    },
    { deep: true }
);
</script>

<template>
    <div class="coverage-table-container">
        <table class="coverage-table">
            <thead>
                <tr>
                    <th class="coverage-label">ASEGURADORAS</th>
                    <th v-for="col in 5" :key="col" class="insurer-col" :class="{ disabled: !isColumnEnabled(col) }">
                        <select
                            :value="getValue('empresa_opcion', col)"
                            @change="handleInsurerChange(col, $event.target.value)"
                            :disabled="!isColumnEnabled(col)"
                            class="form-select"
                            :class="{ 'form-select--error': hasError('empresa_opcion', col) }"
                        >
                            <option value="0">SELECCIONA ASEGURADORA</option>
                            <option v-for="ins in insurers" :key="ins.id" :value="ins.id">
                                {{ ins.name }}
                            </option>
                        </select>
                    </th>
                </tr>
            </thead>
            <tbody>
                <!-- DESGLOSE DE COBERTURAS -->
                <tr class="group-header">
                    <th :colspan="totalColumns" class="group-header__title">DESGLOSE DE COBERTURAS</th>
                </tr>

                <!-- DAÑOS MATERIALES (deshabilitado en LIMITADA y RC) -->
                <tr>
                    <td class="coverage-label">DAÑOS MATERIALES</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isDanosEnabled(col) }">
                        <select
                            :value="getValue('danos_opcion_selec', col)"
                            @change="setValue('danos_opcion_selec', col, $event.target.value)"
                            :disabled="!isDanosEnabled(col)"
                            class="form-select"
                            :class="{ 'form-select--error': hasError('danos_opcion_selec', col) }"
                        >
                            <option v-for="opt in valueTypeOptions" :key="opt.value" :value="opt.value">
                                {{ opt.label }}
                            </option>
                        </select>
                    </td>
                </tr>

                <!-- IMPORTE FACTURA (DM) - solo habilitado si V.FACTURA seleccionado -->
                <tr>
                    <td class="coverage-label">IMPORTE FACTURA</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isDanosImporteEnabled(col) }">
                        <div class="money-input">
                            <span class="currency-symbol">$</span>
                            <input
                                type="text"
                                :value="getValue('danos_material_importe_factura', col)"
                                @keydown="onMoneyKeydown"
                                @input="handleMoneyInput('danos_material_importe_factura', col, $event)"
                                :disabled="!isDanosImporteEnabled(col)"
                                class="form-input form-input--money"
                                :class="{ 'form-input--error': hasError('danos_material_importe_factura', col) }"
                                placeholder="0.00"
                            />
                        </div>
                    </td>
                </tr>

                <!-- DEDUCIBLE DM (deshabilitado en LIMITADA y RC) -->
                <tr>
                    <td class="coverage-label">DEDUCIBLE DM</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isDanosEnabled(col) }">
                        <select
                            :value="getValue('deducible_opcion', col)"
                            @change="setValue('deducible_opcion', col, $event.target.value)"
                            :disabled="!isDanosEnabled(col)"
                            class="form-select"
                            :class="{ 'form-select--error': hasError('deducible_opcion', col) }"
                        >
                            <option v-for="opt in deductibleDMOptions" :key="opt.value" :value="opt.value">
                                {{ opt.label }}
                            </option>
                        </select>
                    </td>
                </tr>

                <!-- CRISTALES (deshabilitado en LIMITADA y RC) -->
                <tr>
                    <td class="coverage-label">CRISTALES</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isCristalesEnabled(col) }">
                        <select
                            :value="getValue('cristales_opcion_selec', col) || 'AMPARADA'"
                            @change="setValue('cristales_opcion_selec', col, $event.target.value)"
                            :disabled="!isCristalesEnabled(col)"
                            class="form-select"
                        >
                            <option value="AMPARADA">AMPARADA</option>
                        </select>
                    </td>
                </tr>

                <!-- ROBO TOTAL (deshabilitado solo en RC) -->
                <tr>
                    <td class="coverage-label">ROBO TOTAL</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isRoboEnabled(col) }">
                        <select
                            :value="getValue('robo_opcion_selec', col)"
                            @change="setValue('robo_opcion_selec', col, $event.target.value)"
                            :disabled="!isRoboEnabled(col)"
                            class="form-select"
                            :class="{ 'form-select--error': hasError('robo_opcion_selec', col) }"
                        >
                            <option v-for="opt in valueTypeOptions" :key="opt.value" :value="opt.value">
                                {{ opt.label }}
                            </option>
                        </select>
                    </td>
                </tr>

                <!-- IMPORTE FACTURA (RT) - solo habilitado si V.FACTURA seleccionado -->
                <tr>
                    <td class="coverage-label">IMPORTE FACTURA</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isRoboImporteEnabled(col) }">
                        <div class="money-input">
                            <span class="currency-symbol">$</span>
                            <input
                                type="text"
                                :value="getValue('robo_importe_factura', col)"
                                @keydown="onMoneyKeydown"
                                @input="handleMoneyInput('robo_importe_factura', col, $event)"
                                :disabled="!isRoboImporteEnabled(col)"
                                class="form-input form-input--money"
                                :class="{ 'form-input--error': hasError('robo_importe_factura', col) }"
                                placeholder="0.00"
                            />
                        </div>
                    </td>
                </tr>

                <!-- DEDUCIBLE RT (deshabilitado solo en RC) -->
                <tr>
                    <td class="coverage-label">DEDUCIBLE RT</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isRoboEnabled(col) }">
                        <select
                            :value="getValue('deducible_rt', col)"
                            @change="setValue('deducible_rt', col, $event.target.value)"
                            :disabled="!isRoboEnabled(col)"
                            class="form-select"
                            :class="{ 'form-select--error': hasError('deducible_rt', col) }"
                        >
                            <option v-for="opt in deductibleRTOptions" :key="opt.value" :value="opt.value">
                                {{ opt.label }}
                            </option>
                        </select>
                    </td>
                </tr>

                <!-- R.C. DAÑOS A TERCEROS -->
                <tr>
                    <td class="coverage-label">R.C. DAÑOS A TERCEROS</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isColumnReady(col) }">
                        <div class="money-input">
                            <span class="currency-symbol">$</span>
                            <input
                                type="text"
                                :value="getValue('danos_tercero_opcion', col)"
                                @keydown="onMoneyKeydown"
                                @input="handleMoneyInput('danos_tercero_opcion', col, $event)"
                                :disabled="!isColumnReady(col)"
                                class="form-input form-input--money"
                                :class="{ 'form-input--error': hasError('danos_tercero_opcion', col) }"
                                placeholder="0.00"
                            />
                        </div>
                    </td>
                </tr>

                <!-- DEDUCIBLE DE RC -->
                <tr>
                    <td class="coverage-label">DEDUCIBLE DE RC</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isColumnReady(col) }">
                        <div class="percent-input">
                            <input
                                type="text"
                                inputmode="numeric"
                                :value="getValue('deducible_de_rc_opcion', col)"
                                @keydown="onPercentKeydown"
                                @input="handlePercentInput('deducible_de_rc_opcion', col, $event)"
                                :disabled="!isColumnReady(col)"
                                class="form-input form-input--percent"
                                maxlength="3"
                            />
                            <span class="percent-symbol">%</span>
                        </div>
                    </td>
                </tr>

                <!-- R.C. FALLECIMIENTO -->
                <tr>
                    <td class="coverage-label">R.C. FALLECIMIENTO</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isColumnReady(col) }">
                        <div class="money-input">
                            <span class="currency-symbol">$</span>
                            <input
                                type="text"
                                :value="getValue('fallecimiento_opcion', col)"
                                @keydown="onMoneyKeydown"
                                @input="handleMoneyInput('fallecimiento_opcion', col, $event)"
                                :disabled="!isColumnReady(col)"
                                class="form-input form-input--money"
                                :class="{ 'form-input--error': hasError('fallecimiento_opcion', col) }"
                                placeholder="0.00"
                            />
                        </div>
                    </td>
                </tr>

                <!-- GASTOS MEDICOS OCUPANTES -->
                <tr>
                    <td class="coverage-label">GASTOS MEDICOS OCUPANTES</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isColumnReady(col) }">
                        <div class="money-input">
                            <span class="currency-symbol">$</span>
                            <input
                                type="text"
                                :value="getValue('gastos_medicos_opcion', col)"
                                @keydown="onMoneyKeydown"
                                @input="handleMoneyInput('gastos_medicos_opcion', col, $event)"
                                :disabled="!isColumnReady(col)"
                                class="form-input form-input--money"
                                :class="{ 'form-input--error': hasError('gastos_medicos_opcion', col) }"
                                placeholder="0.00"
                            />
                        </div>
                    </td>
                </tr>

                <!-- ACCIDENTES AL CONDUCTOR -->
                <tr>
                    <td class="coverage-label">ACCIDENTES AL CONDUCTOR</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isColumnReady(col) }">
                        <div class="money-input">
                            <span class="currency-symbol">$</span>
                            <input
                                type="text"
                                :value="getValue('accidente_conducir_opcion', col)"
                                @keydown="onMoneyKeydown"
                                @input="handleMoneyInput('accidente_conducir_opcion', col, $event)"
                                :disabled="!isColumnReady(col)"
                                class="form-input form-input--money"
                                :class="{ 'form-input--error': hasError('accidente_conducir_opcion', col) }"
                                placeholder="0.00"
                            />
                        </div>
                    </td>
                </tr>

                <!-- PROTECCION LEGAL -->
                <tr>
                    <td class="coverage-label">PROTECCION LEGAL</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isColumnReady(col) }">
                        <select
                            :value="getValue('proteccion_opcion_selec', col) || 'AMPARADA'"
                            @change="setValue('proteccion_opcion_selec', col, $event.target.value)"
                            :disabled="!isColumnReady(col)"
                            class="form-select"
                        >
                            <option value="AMPARADA">AMPARADA</option>
                        </select>
                    </td>
                </tr>

                <!-- ASISTENCIA VIAL -->
                <tr>
                    <td class="coverage-label">ASISTENCIA VIAL</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isColumnReady(col) }">
                        <select
                            :value="getValue('asistencia_vial_opcion_selec', col) || 'AMPARADA'"
                            @change="setValue('asistencia_vial_opcion_selec', col, $event.target.value)"
                            :disabled="!isColumnReady(col)"
                            class="form-select"
                        >
                            <option value="AMPARADA">AMPARADA</option>
                        </select>
                    </td>
                </tr>

                <!-- DAÑOS POR LA CARGA -->
                <tr>
                    <td class="coverage-label">DAÑOS POR LA CARGA</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isColumnReady(col) }">
                        <select
                            :value="getValue('danos_carga_opcion_selec', col)"
                            @change="setValue('danos_carga_opcion_selec', col, $event.target.value)"
                            :disabled="!isColumnReady(col)"
                            class="form-select"
                            :class="{ 'form-select--error': hasError('danos_carga_opcion_selec', col) }"
                        >
                            <option v-for="opt in coverageStatusOptions" :key="opt.value" :value="opt.value">
                                {{ opt.label }}
                            </option>
                        </select>
                    </td>
                </tr>

                <!-- ADAPTACIONES, CONVERSIONES Y/O EQUIPO ESPECIAL -->
                <tr>
                    <td class="coverage-label">ADAPTACIONES, CONVERSIONES Y/O EQUIPO ESPECIAL</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isColumnReady(col) }">
                        <div class="money-input">
                            <span class="currency-symbol">$</span>
                            <input
                                type="text"
                                :value="getValue('adaptaciones_opcion', col)"
                                @keydown="onMoneyKeydown"
                                @input="handleMoneyInput('adaptaciones_opcion', col, $event)"
                                :disabled="!isColumnReady(col)"
                                class="form-input form-input--money"
                                placeholder="0.00"
                            />
                        </div>
                    </td>
                </tr>

                <!-- DESCRIPCION -->
                <tr>
                    <td class="coverage-label">DESCRIPCION</td>
                    <td colspan="5">
                        <input
                            type="text"
                            :value="getValue('descripcion_tabla', 1)"
                            @input="setValue('descripcion_tabla', 1, $event.target.value)"
                            :disabled="enabledColumns === 0"
                            class="form-input form-input--full"
                            placeholder="Descripción general"
                        />
                    </td>
                </tr>

                <!-- EXTENSION DE RC -->
                <tr>
                    <td class="coverage-label">EXTENSION DE RC</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isColumnReady(col) }">
                        <select
                            :value="getValue('extension_rc_opcion', col)"
                            @change="setValue('extension_rc_opcion', col, $event.target.value)"
                            :disabled="!isColumnReady(col)"
                            class="form-select"
                            :class="{ 'form-select--error': hasError('extension_rc_opcion', col) }"
                        >
                            <option v-for="opt in coverageStatusOptions" :key="opt.value" :value="opt.value">
                                {{ opt.label }}
                            </option>
                        </select>
                    </td>
                </tr>

                <!-- COBERTURA OPCIONAL 1 -->
                <!-- LEGACY: Input deshabilitado hasta que se seleccione tipo en ALGÚN select -->
                <!-- Ver: formulario.js líneas 1184, 1270, 9100-9211 -->
                <tr>
                    <td class="coverage-label">
                        <input
                            type="text"
                            :value="customCoverage1Name"
                            @input="$emit('update:customCoverage1Name', $event.target.value)"
                            :disabled="!isCustomCoverage1InputEnabled"
                            class="form-input form-input--name"
                            :class="{ 'form-input--error': isCustomCoverage1InputEnabled && !customCoverage1Name }"
                            placeholder="Cobertura adicional 1"
                        />
                    </td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isColumnReady(col) }">
                        <select
                            :value="getValue('cobertura_opcion_1_select', col)"
                            @change="setValue('cobertura_opcion_1_select', col, $event.target.value)"
                            :disabled="!isColumnReady(col)"
                            class="form-select"
                        >
                            <option v-for="opt in coverageStatusOptions" :key="opt.value" :value="opt.value">
                                {{ opt.label }}
                            </option>
                        </select>
                    </td>
                </tr>

                <!-- COBERTURA OPCIONAL 2 -->
                <!-- LEGACY: Input deshabilitado hasta que se seleccione tipo en ALGÚN select -->
                <!-- Ver: formulario.js líneas 1198, 1271, 9214-9409 -->
                <tr>
                    <td class="coverage-label">
                        <input
                            type="text"
                            :value="customCoverage2Name"
                            @input="$emit('update:customCoverage2Name', $event.target.value)"
                            :disabled="!isCustomCoverage2InputEnabled"
                            class="form-input form-input--name"
                            :class="{ 'form-input--error': isCustomCoverage2InputEnabled && !customCoverage2Name }"
                            placeholder="Cobertura adicional 2"
                        />
                    </td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isColumnReady(col) }">
                        <select
                            :value="getValue('cobertura_opcion_2_select', col)"
                            @change="setValue('cobertura_opcion_2_select', col, $event.target.value)"
                            :disabled="!isColumnReady(col)"
                            class="form-select"
                        >
                            <option v-for="opt in coverageStatusOptions" :key="opt.value" :value="opt.value">
                                {{ opt.label }}
                            </option>
                        </select>
                    </td>
                </tr>

                <!-- DESGLOSE DE PRIMA -->
                <tr class="group-header">
                    <th :colspan="totalColumns" class="group-header__title">DESGLOSE DE PRIMA</th>
                </tr>

                <!-- FORMA DE PAGO -->
                <!-- LEGACY FIX: forma_de_pago es GLOBAL (sin índice _X) -->
                <!-- Ver: cotizacion_autos.txt línea 1861: <select id="forma_de_pago"> (colspan="5") -->
                <tr>
                    <td class="coverage-label">FORMA DE PAGO</td>
                    <td colspan="5">
                        <select
                            :value="modelValue?.forma_de_pago || '0'"
                            @change="$emit('update:modelValue', { ...modelValue, forma_de_pago: $event.target.value })"
                            :disabled="enabledColumns === 0"
                            class="form-select form-select--full"
                            :class="{ 'form-select--error': hasError('forma_de_pago') }"
                        >
                            <option v-for="opt in paymentOptions" :key="opt.value" :value="opt.value">
                                {{ opt.label }}
                            </option>
                        </select>
                    </td>
                </tr>

                <!-- PRIMA NETA ANUAL -->
                <!-- BACKEND AUTORITATIVO: Campo readonly, valor calculado por backend -->
                <!-- El valor se actualiza via Create.vue -> requestBackendCalculation() -->
                <tr>
                    <td class="coverage-label">PRIMA NETA ANUAL</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isColumnReady(col) }">
                        <div class="money-input">
                            <span class="currency-symbol">$</span>
                            <input
                                type="text"
                                :value="getValue('cantidad_prima_neta_opcion', col)"
                                readonly
                                :disabled="!isColumnReady(col)"
                                class="form-input form-input--money form-input--readonly"
                                placeholder="0.00"
                                title="Calculado automáticamente por el sistema"
                            />
                        </div>
                    </td>
                </tr>

                <!-- PRIMA TOTAL ANUAL -->
                <tr>
                    <td class="coverage-label">PRIMA TOTAL ANUAL</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isPaymentReady(col) }">
                        <div class="money-input">
                            <span class="currency-symbol">$</span>
                            <input
                                type="text"
                                :value="getValue('cantidad_total_anual_opcion', col)"
                                @keydown="onMoneyKeydown"
                                @input="handleMoneyInput('cantidad_total_anual_opcion', col, $event)"
                                :disabled="!isPaymentReady(col)"
                                class="form-input form-input--money"
                                placeholder="0.00"
                            />
                        </div>
                    </td>
                </tr>

                <!-- PRIMER PAGO -->
                <!-- Legacy: #primer_pago_opcion_X.focusout -> verificar_primer_pago_opcion_X -->
                <tr>
                    <td class="coverage-label">PRIMER PAGO</td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isPaymentReady(col) }">
                        <div class="money-input">
                            <span class="currency-symbol">$</span>
                            <input
                                type="text"
                                :value="getValue('primer_pago_opcion', col)"
                                @keydown="onMoneyKeydown"
                                @input="handleMoneyInput('primer_pago_opcion', col, $event)"
                                @blur="handlePrimerPagoBlur(col)"
                                :disabled="!isPaymentReady(col)"
                                :readonly="props.paymentFrequency === 'ANUAL'"
                                class="form-input form-input--money"
                                placeholder="0.00"
                            />
                        </div>
                    </td>
                </tr>

                <!-- SUBSECUENTES -->
                <!-- Legacy: Calculado automáticamente = (prima_total - primer_pago) / divisor -->
                <!-- Divisores: ANUAL=0, SEMESTRAL=1, TRIMESTRAL=3, MENSUAL=11 -->
                <!-- Ver: formulario.js líneas 9800-9946 -->
                <tr v-if="getSubsequentPayments > 0">
                    <td class="coverage-label">
                        SUBSECUENTES: <strong>{{ getSubsequentPayments }}</strong>
                    </td>
                    <td v-for="col in 5" :key="col" :class="{ disabled: !isPaymentReady(col) }">
                        <div class="money-input">
                            <span class="currency-symbol">$</span>
                            <input
                                type="text"
                                :value="getValue('subsecuente_opcion', col)"
                                readonly
                                :disabled="!isPaymentReady(col)"
                                class="form-input form-input--money form-input--readonly"
                                placeholder="0.00"
                                title="Calculado automáticamente: (Prima Total - Primer Pago) / {{ getSubsequentPayments }}"
                            />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
.coverage-table-container {
    overflow-x: auto;
    margin: 1.5rem 0;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
}

.coverage-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.8125rem;
}

.coverage-table th,
.coverage-table td {
    padding: 0.5rem 0.625rem;
    border: 1px solid #E5E7EB;
    vertical-align: middle;
}

.coverage-table th {
    background: #F9FAFB;
    font-weight: 600;
    text-align: center;
}

.coverage-label {
    font-weight: 600;
    color: #374151;
    white-space: nowrap;
    min-width: 180px;
    background: #FAFAFA;
}

.insurer-col {
    min-width: 140px;
}

.group-header__title {
    background: none !important;
    color: #374151;
    font-weight: 700;
    text-align: center;
    padding: 0.75rem;
    border-left: 0 !important;
    border-right: 0 !important;
}

/* Inputs y selects */
.form-select,
.form-input {
    width: 100%;
    padding: 0.375rem 0.5rem;
    border: 1px solid #E5E7EB;
    border-radius: 6px;
    font-size: 0.75rem;
    transition: border-color 0.2s;
}

.form-select:focus,
.form-input:focus {
    outline: none;
    border-color: #7B2D3B;
    box-shadow: 0 0 0 2px rgba(123, 45, 59, 0.1);
}

.form-select:not(:disabled),
.form-input:not(:disabled) {
    background: #FFFFFF;
    opacity: 1;
}

.form-select:disabled,
.form-input:disabled {
    background: #F3F4F6;
    color: #9CA3AF;
    cursor: not-allowed;
    opacity: 0.5;
}

.form-select--full,
.form-input--full {
    text-align: center;
}

/* Money inputs */
.money-input {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.currency-symbol {
    color: #6B7280;
    font-weight: 500;
}

.form-input--money {
    text-align: right;
    font-family: 'JetBrains Mono', monospace;
}

.form-input--readonly {
    background: #F9FAFB;
}

/* Percent inputs */
.percent-input {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.percent-symbol {
    color: #6B7280;
    font-weight: 500;
}

.form-input--percent {
    width: 60px;
    text-align: center;
}

/* Name input for custom coverages */
.form-input--name {
    font-weight: 600;
    font-size: 0.75rem;
}

.form-input--name:not(:disabled) {
    background: #FFFFFF;
    opacity: 1;
}

/* Error state */
.form-select--error,
.form-input--error {
    border-color: #DC2626 !important;
    background-color: #FEF2F2 !important;
}

.form-select--error:focus,
.form-input--error:focus {
    box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.2) !important;
}

/* Disabled state */
td.disabled {
    background: #F0F0F0;
}

/* Enabled state - fondo blanco para contraste visual claro */
tr:not(.group-header) > td:not(.disabled):not(.coverage-label) {
    background: #FFFFFF;
}

/* Responsive */
@media (max-width: 1024px) {
    .coverage-table {
        font-size: 0.75rem;
    }

    .coverage-label {
        min-width: 140px;
    }

    .insurer-col {
        min-width: 120px;
    }
}
</style>
