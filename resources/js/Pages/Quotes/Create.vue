<!-- resources/js/Pages/Quotes/Create.vue -->
<!--
    Formulario de cotización de seguros de automóviles
    Migración completa del sistema legacy preservando todos los campos
    y la lógica condicional exacta
-->
<script setup>
import { ref, computed, watch, reactive, onMounted } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
defineOptions({ layout: AppLayout });
import CoverageTable from '@/Components/Quote/CoverageTable.vue';
import { useCurrencyFormat } from '@/composables/useCurrencyFormat';
import Swal from 'sweetalert2';

// Props desde el backend (QuoteController@create)
const props = defineProps({
    customers: { type: Array, default: () => [] },
    contacts: { type: Array, default: () => [] },
    vehicleTypes: { type: Array, default: () => [] },
    brands: { type: Array, default: () => [] },
    years: { type: Array, default: () => [] },
    insurers: { type: Array, default: () => [] },
    coveragePackages: { type: Array, default: () => [] },
});

const { formatInput } = useCurrencyFormat();

// ==========================================
// Valores por defecto para columnas de coberturas
// ==========================================
const getDefaultCoverageColumn = () => ({
    empresa_opcion: '0',
    danos_opcion_selec: '0',
    danos_material_importe_factura: '',
    deducible_opcion: 'na',
    cristales_opcion_selec: 'AMPARADA',
    robo_opcion_selec: '0',
    robo_importe_factura: '',
    deducible_rt: 'na',
    danos_tercero_opcion: '',
    deducible_de_rc_opcion: '',
    fallecimiento_opcion: '',
    gastos_medicos_opcion: '',
    accidente_conducir_opcion: '',
    proteccion_opcion_selec: 'AMPARADA',
    asistencia_vial_opcion_selec: 'AMPARADA',
    danos_carga_opcion_selec: '0',
    adaptaciones_opcion: '',
    extension_rc_opcion: '0',
    cobertura_opcion_1_select: '0',
    cobertura_opcion_2_select: '0',
    cantidad_prima_neta_opcion: '',
    cantidad_total_anual_opcion: '',
    primer_pago_opcion: '',
    subsecuente_opcion: '',
});

// NOTA LEGACY: El paquete NO setea valores automáticamente.
// Solo habilita/deshabilita campos en el UI.
// Los valores deben ser ingresados manualmente por el usuario.
// Ver: public/sistema viejo/js/formulario.js líneas 1292-1349

// Genera estado inicial de coverages con todas las columnas
const getInitialCoverages = () => {
    const coverages = {
        forma_de_pago: '0',
        descripcion_tabla: '',
    };
    for (let col = 1; col <= 5; col++) {
        const defaults = getDefaultCoverageColumn();
        Object.keys(defaults).forEach(key => {
            coverages[`${key}_${col}`] = defaults[key];
        });
    }
    return coverages;
};

// ==========================================
// Estado del formulario (estructura legacy)
// ==========================================
const form = useForm({
    // Encabezado
    // LEGACY: tipo_cotizacion inicia con value="0" (placeholder)
    // Ver: verificar_tipo_cotizacion() línea 6215
    tipo_cotizacion: '0',
    hora_solicitada: '',
    contact_id: null,
    customer_id: null,

    // Datos del asegurado (readonly al seleccionar)
    asegurado: {
        apellido_paterno: '',
        apellido_materno: '',
        nombre: '',
        codigo_postal: '',
        colonia: '',
        estado: '',
    },

    // Descripción del vehículo
    // LEGACY: tipo_auto y carga inician con value="0" (placeholder)
    // Ver: cotizacion_autos.txt líneas 241-247, 261-266
    vehiculo: {
        marca: '',
        descripcion: '',
        modelo: '', // año
        uso_de_unidad: '',
        tipo_auto: '0', // LEGACY: placeholder value="0"
        carga: '0', // LEGACY: placeholder value="0"
    },

    // Información póliza a renovar (solo si tipo_cotizacion === 'RENOVACION')
    renovacion: {
        compania_actual: '',
        fecha_vigencia: '',
        poliza_a_renovar: '',
        prima_año: '',
    },

    // Configuración de aseguradoras
    // LEGACY: Ambos con value="0" como placeholder default
    // Ver: cotizacion_autos.txt líneas 360-365, 384-391
    paquete: '0',
    cantidad_aseguradoras: 0,

    // Coberturas (todos los valores de las 5 columnas)
    coverages: getInitialCoverages(),

    // Coberturas opcionales (nombres dinámicos)
    custom_coverage_1_name: '',
    custom_coverage_2_name: '',

    // Notas internas
    notas: '',
});

// ==========================================
// Funciones de gestión de formulario (PROMPT HARD)
// ==========================================

/**
 * Resetea una columna específica a valores por defecto (placeholders)
 * Se llama cuando cambia la aseguradora de una columna
 */
const resetCoverageColumn = (colIndex) => {
    const defaults = getDefaultCoverageColumn();
    Object.keys(defaults).forEach(key => {
        form.coverages[`${key}_${colIndex}`] = defaults[key];
    });
};

/**
 * El paquete NO aplica defaults de valor según legacy.
 * Solo se usa para habilitar/deshabilitar campos en CoverageTable.
 * Esta función queda como placeholder para futura lógica si se requiere.
 */
const applyPackageDefaults = (packageType) => {
    // LEGACY: No auto-set values. Solo enable/disable en UI.
    // Ver: public/sistema viejo/js/formulario.js
};

/**
 * Configura la cantidad de aseguradoras
 * Habilita columnas 1..n, deshabilita y resetea columnas (n+1)..5
 */
const setAseguradorasCount = (count) => {
    const n = parseInt(count) || 0;

    // Resetear columnas que se deshabilitan (n+1 a 5)
    for (let col = n + 1; col <= 5; col++) {
        resetCoverageColumn(col);
    }
    // NOTA: No se aplican defaults de paquete (legacy no lo hace)
};

/**
 * Inicializa el formulario con valores por defecto coherentes
 * Equivalente a legacy inicio()
 * LEGACY: cantidad_aseguradoras y paquete inician en 0 (placeholder)
 * Ver: cotizacion_autos.txt líneas 360-365, 384-391
 */
const initQuoteForm = () => {
    // Inicializar coverages con defaults
    form.coverages = getInitialCoverages();

    // LEGACY: Ambos placeholders con value="0"
    form.cantidad_aseguradoras = 0;
    form.paquete = '0';

    // No hay columnas habilitadas hasta que el usuario seleccione
    setAseguradorasCount(0);
};

/**
 * Handler cuando cambia la aseguradora de una columna
 * Resetea todos los valores de esa columna excepto la aseguradora
 */
const handleInsurerChanged = ({ colIndex, insurerId }) => {
    // Solo resetear si se seleccionó una aseguradora válida (no placeholder)
    if (insurerId && insurerId !== '0') {
        // Preservar la aseguradora seleccionada y resetear el resto a placeholders
        const defaults = getDefaultCoverageColumn();
        Object.keys(defaults).forEach(key => {
            if (key !== 'empresa_opcion') {
                form.coverages[`${key}_${colIndex}`] = defaults[key];
            }
        });
    }
};

// Ejecutar inicialización al montar
onMounted(() => {
    initQuoteForm();
});

// ==========================================
// Sistema de validación en tiempo real
// ==========================================
// LEGACY: Todos los campos con validación según formulario.js
const fieldErrors = reactive({
    tipo_cotizacion: '',
    hora_solicitada: '',
    contact_id: '',
    customer_id: '',
    'vehiculo.marca': '',
    'vehiculo.descripcion': '',
    'vehiculo.modelo': '',
    'vehiculo.uso_de_unidad': '',
    'vehiculo.tipo_auto': '',
    'vehiculo.carga': '',
    paquete: '',
    cantidad_aseguradoras: '',
    'renovacion.compania_actual': '',
    'renovacion.fecha_vigencia': '',
    'renovacion.poliza_a_renovar': '',
    'renovacion.prima_año': '',
    // LEGACY: Cobertura adicional 1 y 2 - validación bidireccional
    // Ver: formulario.js líneas 9100-9211, 9214-9409
    'custom_coverage_1': '',
    'custom_coverage_2': '',
});

// Track de campos tocados (touched)
const touchedFields = reactive({
    tipo_cotizacion: false,
    hora_solicitada: false,
    contact_id: false,
    customer_id: false,
    'vehiculo.marca': false,
    'vehiculo.descripcion': false,
    'vehiculo.modelo': false,
    'vehiculo.uso_de_unidad': false,
    'vehiculo.tipo_auto': false,
    'vehiculo.carga': false,
    paquete: false,
    cantidad_aseguradoras: false,
    'renovacion.compania_actual': false,
    'renovacion.fecha_vigencia': false,
    'renovacion.poliza_a_renovar': false,
    'renovacion.prima_año': false,
    // LEGACY: Cobertura adicional 1 y 2
    'custom_coverage_1': false,
    'custom_coverage_2': false,
});

// Marcar campo como tocado
const markTouched = (field) => {
    touchedFields[field] = true;
    validateField(field);
};

// Validar campo individual
// LEGACY: Mensajes EXACTOS de formulario.js líneas 14091-14427
const validateField = (field) => {
    switch (field) {
        case 'tipo_cotizacion':
            // LEGACY: verificar_tipo_cotizacion() línea 6215
            fieldErrors.tipo_cotizacion = (!form.tipo_cotizacion || form.tipo_cotizacion === '0')
                ? '¡Debes seleccionar un tipo de cotizacion!'
                : '';
            break;

        case 'hora_solicitada':
            fieldErrors.hora_solicitada = !form.hora_solicitada
                ? '¡Debes ingresar la hora solicitada!'
                : '';
            break;

        case 'contact_id':
            fieldErrors.contact_id = !form.contact_id
                ? '¡Debes seleccionar un contacto!'
                : '';
            break;

        case 'customer_id':
            // No hay validación legacy para este campo (es del nuevo sistema)
            fieldErrors.customer_id = !form.customer_id
                ? '¡Debes seleccionar un asegurado/prospecto!'
                : '';
            break;

        // ==========================================
        // VEHÍCULO - LEGACY: líneas 14091-14243
        // ==========================================
        case 'vehiculo.marca':
            // LEGACY: verificar_marca() línea 14091
            if (!form.vehiculo.marca) {
                fieldErrors['vehiculo.marca'] = '¡Debes seleccionar una marca!';
            } else if (!props.brands.includes(form.vehiculo.marca)) {
                fieldErrors['vehiculo.marca'] = '¡Selecciona una marca válida del catálogo!';
            } else {
                fieldErrors['vehiculo.marca'] = '';
            }
            break;

        case 'vehiculo.descripcion':
            // LEGACY: verificar_descripcion() línea 14119
            if (!form.vehiculo.descripcion) {
                fieldErrors['vehiculo.descripcion'] = '¡Debes ingresar una descripcion!';
            } else if (!/^([a-zA-Z0-9 áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n\.\,\-\.])*$/.test(form.vehiculo.descripcion)) {
                fieldErrors['vehiculo.descripcion'] = '¡Solo esta permitido letras!';
            } else {
                fieldErrors['vehiculo.descripcion'] = '';
            }
            break;

        case 'vehiculo.modelo':
            // LEGACY: verificar_modelo() línea 14147
            const modelo = form.vehiculo.modelo;
            const modeloNum = parseInt(modelo);
            const maxYear = new Date().getFullYear() + 1;
            if (modelo === '' || modelo == 0 || modelo === 'e') {
                fieldErrors['vehiculo.modelo'] = '¡Debes ingresar un modelo!';
            } else if (modelo.length !== 4) {
                fieldErrors['vehiculo.modelo'] = '¡El modelo debe ser un año de 4 dígitos!';
            } else if (modeloNum < 1970) {
                fieldErrors['vehiculo.modelo'] = '¡El modelo no puede ser menor a 1970!';
            } else if (modeloNum > maxYear) {
                fieldErrors['vehiculo.modelo'] = `¡El modelo no puede ser mayor a ${maxYear}!`;
            } else {
                fieldErrors['vehiculo.modelo'] = '';
            }
            break;

        case 'vehiculo.uso_de_unidad':
            // LEGACY: verificar_uso_de_unidad() línea 14172
            if (!form.vehiculo.uso_de_unidad) {
                fieldErrors['vehiculo.uso_de_unidad'] = '¡Debes ingresar uso de la unidad!';
            } else if (!/^([a-zA-Z0-9 áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n\,\-\.])*$/.test(form.vehiculo.uso_de_unidad)) {
                fieldErrors['vehiculo.uso_de_unidad'] = '¡Solo esta permitido letras!';
            } else {
                fieldErrors['vehiculo.uso_de_unidad'] = '';
            }
            break;

        case 'vehiculo.tipo_auto':
            // LEGACY: verificar_tipo_auto() línea 14200
            // value==0 significa placeholder seleccionado
            fieldErrors['vehiculo.tipo_auto'] = (!form.vehiculo.tipo_auto || form.vehiculo.tipo_auto === '0')
                ? '¡Debes seleccionar un tipo de auto!'
                : '';
            break;

        case 'vehiculo.carga':
            // LEGACY: verificar_carga() línea 14218
            // Solo valida si el contenedor de carga es visible
            if (showCargoField.value) {
                fieldErrors['vehiculo.carga'] = (!form.vehiculo.carga || form.vehiculo.carga === '0')
                    ? '¡Debes seleccionar un tipo de carga!'
                    : '';
            } else {
                fieldErrors['vehiculo.carga'] = '';
            }
            break;

        // ==========================================
        // CONFIGURACIÓN - LEGACY: líneas 14378-14410
        // ==========================================
        case 'paquete':
            // LEGACY: verificar_paquete() línea 14395
            fieldErrors.paquete = (!form.paquete || form.paquete === '0')
                ? '¡Debes seleccionar el paquete!'
                : '';
            break;

        case 'cantidad_aseguradoras':
            // LEGACY: verificar_cantidad_aseguradoras() línea 14378
            // if(opcion==0) → error
            fieldErrors.cantidad_aseguradoras = (form.cantidad_aseguradoras == 0)
                ? '¡Debes seleccionar la cantidad de aseguradoras!'
                : '';
            break;

        // ==========================================
        // RENOVACIÓN - LEGACY: líneas 14245-14375
        // Solo validan si sección renovación visible
        // ==========================================
        case 'renovacion.compania_actual':
            // LEGACY: verificar_compañia_actual() línea 14245
            if (showRenewalSection.value) {
                if (!form.renovacion.compania_actual) {
                    fieldErrors['renovacion.compania_actual'] = '¡Debes ingresar un nombre de compañia actual!';
                } else if (!/^([0-9a-zA-Z áéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ_\n\*\+\.\,\-])*$/.test(form.renovacion.compania_actual)) {
                    fieldErrors['renovacion.compania_actual'] = '¡No estan permitidos caracteres especiales!';
                } else {
                    fieldErrors['renovacion.compania_actual'] = '';
                }
            } else {
                fieldErrors['renovacion.compania_actual'] = '';
            }
            break;

        case 'renovacion.fecha_vigencia':
            // LEGACY: verificar_fecha_vigencia() línea 14282
            if (showRenewalSection.value) {
                fieldErrors['renovacion.fecha_vigencia'] = (!form.renovacion.fecha_vigencia)
                    ? '¡Debes ingresar una fecha de vigencia!'
                    : '';
            } else {
                fieldErrors['renovacion.fecha_vigencia'] = '';
            }
            break;

        case 'renovacion.poliza_a_renovar':
            // LEGACY: verificar_poliza_a_renovar() línea 14307
            if (showRenewalSection.value) {
                const poliza = form.renovacion.poliza_a_renovar;
                if (poliza === '' || poliza == 0 || poliza === 'e') {
                    fieldErrors['renovacion.poliza_a_renovar'] = '¡Debes ingresar un poliza_a_renovar!';
                } else if (parseInt(poliza) < 0) {
                    fieldErrors['renovacion.poliza_a_renovar'] = '¡No esta permitido números negativos!';
                } else {
                    fieldErrors['renovacion.poliza_a_renovar'] = '';
                }
            } else {
                fieldErrors['renovacion.poliza_a_renovar'] = '';
            }
            break;

        case 'renovacion.prima_año':
            // LEGACY: verificar_prima_año() línea 14341
            if (showRenewalSection.value) {
                let primaAño = form.renovacion.prima_año;
                if (typeof primaAño === 'string') {
                    primaAño = primaAño.replace(/,/g, '');
                }
                if (primaAño === '' || primaAño == 0 || primaAño === 'e') {
                    fieldErrors['renovacion.prima_año'] = '¡Debes ingresar prima del año anterior!';
                } else if (typeof form.renovacion.prima_año === 'string' && (form.renovacion.prima_año.split('.').length - 1) > 1) {
                    fieldErrors['renovacion.prima_año'] = '¡Ingresa un número valido!';
                } else {
                    fieldErrors['renovacion.prima_año'] = '';
                }
            } else {
                fieldErrors['renovacion.prima_año'] = '';
            }
            break;

        // ==========================================
        // COBERTURAS ADICIONALES - VALIDACIÓN BIDIRECCIONAL
        // LEGACY: formulario.js líneas 9100-9211 (cobertura 1), 9214-9409 (cobertura 2)
        // Regla: Si ALGÚN select tiene valor != '0', el nombre es REQUERIDO
        //        Si el nombre tiene valor, AL MENOS UN select debe tener valor != '0'
        // ==========================================
        case 'custom_coverage_1':
            {
                const n = parseInt(form.cantidad_aseguradoras) || 0;
                const nombre = form.custom_coverage_1_name?.trim() || '';
                let hasSelectValue = false;

                // Verificar si ALGÚN select de cobertura 1 tiene valor != '0'
                for (let col = 1; col <= n; col++) {
                    const selectVal = form.coverages?.[`cobertura_opcion_1_select_${col}`];
                    if (selectVal && selectVal !== '0') {
                        hasSelectValue = true;
                        break;
                    }
                }

                // LEGACY: Si select tiene valor pero nombre vacío
                if (hasSelectValue && !nombre) {
                    fieldErrors['custom_coverage_1'] = '¡Debes ingresar nombre de cobertura!';
                }
                // LEGACY: Si nombre tiene valor pero ningún select seleccionado
                else if (nombre && !hasSelectValue && n > 0) {
                    fieldErrors['custom_coverage_1'] = '¡Debes seleccionar un tipo de cobertura!';
                }
                else {
                    fieldErrors['custom_coverage_1'] = '';
                }
            }
            break;

        case 'custom_coverage_2':
            {
                const n2 = parseInt(form.cantidad_aseguradoras) || 0;
                const nombre2 = form.custom_coverage_2_name?.trim() || '';
                let hasSelectValue2 = false;

                // Verificar si ALGÚN select de cobertura 2 tiene valor != '0'
                for (let col = 1; col <= n2; col++) {
                    const selectVal2 = form.coverages?.[`cobertura_opcion_2_select_${col}`];
                    if (selectVal2 && selectVal2 !== '0') {
                        hasSelectValue2 = true;
                        break;
                    }
                }

                // LEGACY: Si select tiene valor pero nombre vacío
                if (hasSelectValue2 && !nombre2) {
                    fieldErrors['custom_coverage_2'] = '¡Debes ingresar nombre de cobertura!';
                }
                // LEGACY: Si nombre tiene valor pero ningún select seleccionado
                else if (nombre2 && !hasSelectValue2 && n2 > 0) {
                    fieldErrors['custom_coverage_2'] = '¡Debes seleccionar un tipo de cobertura!';
                }
                else {
                    fieldErrors['custom_coverage_2'] = '';
                }
            }
            break;
    }
};

// Validar todos los campos
const validateAllFields = () => {
    Object.keys(fieldErrors).forEach(field => {
        touchedFields[field] = true;
        validateField(field);
    });
};

// Verificar si el formulario es válido
const isFormValid = computed(() => {
    return Object.values(fieldErrors).every(error => !error);
});

// ==========================================
// Estados de UI
// ==========================================
const customerSearch = ref('');
const contactSearch = ref('');
const filteredCustomers = ref([...props.customers]);
const filteredContacts = ref([...props.contacts]);
const isSubmitting = ref(false);
const showCustomerForm = ref(false);



// ==========================================
// Financial Settings por Aseguradora (Legacy: costo_derecho_poliza + recargo_por_cargo_fraccionado)
// ==========================================
const insurerFinancialSettings = ref({});

/**
 * Carga configuración financiera de una aseguradora
 * Equivalente Legacy: SELECT derecho_costo, cantidad_recargo
 */
const loadFinancialSettings = async (insurerId) => {
    if (!insurerId || insurerId === '0') return;

    // Si ya tenemos los datos cacheados, no volver a cargar
    if (insurerFinancialSettings.value[insurerId]) return;

    try {
        const response = await fetch(`/api/insurers/${insurerId}/financial-settings`);
        if (response.ok) {
            const data = await response.json();
            insurerFinancialSettings.value[insurerId] = data;
        }
    } catch (error) {
        console.error('Error cargando financial settings:', error);
    }
};

// ==========================================
// BACKEND AUTORITATIVO: Control de cálculos en tiempo real
// El frontend SOLO renderiza, NUNCA calcula valores monetarios
// ==========================================
const calculationRequestId = ref(0);
const isCalculating = ref(false);
let calculationDebounceTimer = null;

/**
 * Solicita cálculo al backend (ÚNICA fuente de verdad)
 * El frontend envía inputs, el backend responde con TODOS los valores calculados
 *
 * @param {number} column - Columna a calcular (1-5)
 * @param {string} trigger - Qué campo disparó el cálculo ('primer_pago', 'total_anual', 'forma_pago')
 */
const requestBackendCalculation = async (column, trigger = 'primer_pago') => {
    // No calcular si no hay forma de pago seleccionada
    const formaPago = form.coverages?.forma_de_pago;
    if (!formaPago || formaPago === '0') return;

    const insurerId = form.coverages?.[`empresa_opcion_${column}`];
    if (!insurerId || insurerId === '0') {
        // Si se intenta calcular sin aseguradora, alertar al usuario
        const totalAnual = form.coverages?.[`cantidad_total_anual_opcion_${column}`];
        if (trigger === 'total_anual' && totalAnual && totalAnual !== '' && totalAnual !== '0') {
            Swal.fire({
                icon: 'warning',
                title: 'Aseguradora requerida',
                text: `Seleccione una aseguradora en la opción ${column} antes de ingresar la prima total.`,
                confirmButtonColor: '#7B2D3B',
            });
        }
        return;
    }

    // Control de race conditions: incrementar request_id
    const currentRequestId = ++calculationRequestId.value;

    // Debounce: esperar 300ms antes de enviar
    if (calculationDebounceTimer) {
        clearTimeout(calculationDebounceTimer);
    }

    calculationDebounceTimer = setTimeout(async () => {
        // Si ya hay otro request más reciente, cancelar este
        if (currentRequestId !== calculationRequestId.value) return;

        isCalculating.value = true;

        try {
            const paymentFrequency = formaPago;

            // Parsear valores monetarios (quitar comas y símbolos)
            const parseMoney = (str) => {
                if (!str) return 0;
                return parseFloat(String(str).replace(/[,$]/g, '')) || 0;
            };

            const totalAnual = parseMoney(form.coverages?.[`cantidad_total_anual_opcion_${column}`]);
            const primerPago = parseMoney(form.coverages?.[`primer_pago_opcion_${column}`]);

            const response = await fetch('/api/quotes/calculate-realtime', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    request_id: String(currentRequestId),
                    insurer_id: insurerId,
                    frequency: paymentFrequency,
                    total_annual: totalAnual,
                    first_payment: primerPago,
                }),
            });

            // Si ya hay otro request más reciente, ignorar esta respuesta
            if (currentRequestId !== calculationRequestId.value) return;

            const data = await response.json();

            // Mapa completo de errores financieros → mensajes claros para el usuario
            const financialErrorMessages = {
                'no_derecho': 'No hay derecho de póliza configurado para esta aseguradora. Configure en Catálogos → Derechos de Póliza.',
                'no_recargo': 'No hay recargo configurado para esta aseguradora y forma de pago. Configure en Catálogos → Recargos.',
                'prima_neta_negativa': 'La prima total ingresada es muy baja. No alcanza para cubrir el derecho de póliza y recargo. Aumente el monto.',
                'primer_pago_excede_total': 'El primer pago no puede ser mayor o igual a la prima total anual.',
                'frecuencia_invalida': 'La forma de pago seleccionada no es válida. Seleccione: Anual, Semestral, Trimestral o Mensual.',
                'total_invalido': 'La prima total anual debe ser mayor a cero.',
            };

            if (response.ok && data.calculation) {
                // Verificar si el backend retornó un error embebido en la respuesta
                if (data.calculation.error) {
                    form.coverages[`cantidad_prima_neta_opcion_${column}`] = '';
                    form.coverages[`subsecuente_opcion_${column}`] = '';
                    const errorCode = data.calculation.error;
                    const isConfigError = ['no_derecho', 'no_recargo'].includes(errorCode);
                    Swal.fire({
                        icon: isConfigError ? 'info' : 'warning',
                        title: isConfigError ? 'Configuración incompleta' : 'Error en el cálculo',
                        text: financialErrorMessages[errorCode] || 'Error inesperado en el cálculo financiero. Contacte a soporte.',
                        confirmButtonColor: '#7B2D3B',
                    });
                } else {
                    // Sin error → aplicar valores calculados
                    const formatMoney = (value) => {
                        if (value === null || value === undefined || value === 0) return '0';
                        return parseFloat(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    };

                    // PRIMA NETA ANUAL - DERIVADA, READONLY, BACKEND-AUTORITATIVA
                    if (data.calculation.net_premium !== undefined) {
                        form.coverages[`cantidad_prima_neta_opcion_${column}`] = formatMoney(data.calculation.net_premium);
                    }

                    // Subsecuente (el backend es autoritativo)
                    if (data.calculation.subsequent_payment !== undefined) {
                        form.coverages[`subsecuente_opcion_${column}`] = formatMoney(data.calculation.subsequent_payment);
                    }

                    // ANUAL: primer_pago = total_anual EXACTO
                    if (trigger === 'total_anual') {
                        const formaPagoActual = form.coverages?.forma_de_pago;
                        if (formaPagoActual === 'ANUAL') {
                            const totalAnualStr = form.coverages?.[`cantidad_total_anual_opcion_${column}`] || '';
                            form.coverages[`primer_pago_opcion_${column}`] = totalAnualStr;
                        }
                    }
                }
            } else if (!response.ok) {
                // Error HTTP (422, 500, etc.)
                form.coverages[`cantidad_prima_neta_opcion_${column}`] = '';
                form.coverages[`subsecuente_opcion_${column}`] = '';
                const errorCode = data?.error || data?.calculation?.error;
                if (errorCode && financialErrorMessages[errorCode]) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error en el cálculo',
                        text: financialErrorMessages[errorCode],
                        confirmButtonColor: '#7B2D3B',
                    });
                }
            }
        } catch (_error) {
            // LEGACY: Errores silenciosos — NO mostrar alertas, NO logs
            // El campo simplemente queda sin calcular
        } finally {
            if (currentRequestId === calculationRequestId.value) {
                isCalculating.value = false;
            }
        }
    }, 300);
};

/**
 * Solicita cálculo batch para todas las columnas habilitadas
 * Se usa cuando cambia forma_de_pago (afecta a todas las columnas)
 */
const requestBatchCalculation = async () => {
    const n = parseInt(form.cantidad_aseguradoras) || 0;
    if (n === 0) return;

    // No calcular si no hay forma de pago seleccionada
    const paymentFrequency = form.coverages?.forma_de_pago;
    if (!paymentFrequency || paymentFrequency === '0') return;

    const currentRequestId = ++calculationRequestId.value;
    isCalculating.value = true;

    try {

        const parseMoney = (str) => {
            if (!str) return 0;
            return parseFloat(String(str).replace(/[,$]/g, '')) || 0;
        };

        // Construir array de opciones con datos (formato que espera el backend)
        const options = [];
        for (let col = 1; col <= n; col++) {
            const insurerId = form.coverages?.[`empresa_opcion_${col}`];
            if (insurerId && insurerId !== '0') {
                options.push({
                    column: col,
                    insurer_id: parseInt(insurerId),
                    total_annual: parseMoney(form.coverages?.[`cantidad_total_anual_opcion_${col}`]),
                    first_payment: parseMoney(form.coverages?.[`primer_pago_opcion_${col}`]),
                });
            }
        }

        if (options.length === 0) return;

        const response = await fetch('/api/quotes/calculate-batch', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({
                request_id: String(currentRequestId),
                frequency: paymentFrequency,
                options: options,
            }),
        });

        if (currentRequestId !== calculationRequestId.value) return;

        const data = response.ok ? await response.json() : null;

        if (response.ok && data) {
            const formatMoney = (value) => {
                if (value === null || value === undefined || value === 0) return '0';
                return parseFloat(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            };

            // Mapa completo de errores financieros
            const batchErrorMessages = {
                'no_derecho': 'No hay derecho de póliza configurado para esta aseguradora. Configure en Catálogos → Derechos de Póliza.',
                'no_recargo': 'No hay recargo configurado para esta aseguradora y forma de pago. Configure en Catálogos → Recargos.',
                'prima_neta_negativa': 'La prima total ingresada es muy baja. No alcanza para cubrir el derecho de póliza y recargo. Aumente el monto.',
                'primer_pago_excede_total': 'El primer pago no puede ser mayor o igual a la prima total anual.',
                'frecuencia_invalida': 'La forma de pago seleccionada no es válida. Seleccione: Anual, Semestral, Trimestral o Mensual.',
                'total_invalido': 'La prima total anual debe ser mayor a cero.',
            };

            // Aplicar resultados a cada columna
            // El backend retorna { results: { 1: {...}, 2: {...}, ... } }
            const errorsByType = {};
            if (data.results && typeof data.results === 'object') {
                Object.entries(data.results).forEach(([col, result]) => {
                    // Verificar si hay error en esta columna
                    if (result.error) {
                        form.coverages[`cantidad_prima_neta_opcion_${col}`] = '';
                        form.coverages[`subsecuente_opcion_${col}`] = '';
                        const errorCode = result.error;
                        if (!errorsByType[errorCode]) {
                            errorsByType[errorCode] = [];
                        }
                        errorsByType[errorCode].push(col);
                        return;
                    }

                    if (result.net_premium !== undefined) {
                        form.coverages[`cantidad_prima_neta_opcion_${col}`] = formatMoney(result.net_premium);
                    }
                    if (result.subsequent_payment !== undefined) {
                        form.coverages[`subsecuente_opcion_${col}`] = formatMoney(result.subsequent_payment);
                    }

                    const formaPagoActual = form.coverages?.forma_de_pago;
                    if (formaPagoActual === 'ANUAL') {
                        const totalAnualStr = form.coverages?.[`cantidad_total_anual_opcion_${col}`] || '';
                        form.coverages[`primer_pago_opcion_${col}`] = totalAnualStr;
                    }
                });
            }

            // Alertar si hubo errores agrupados por tipo
            const errorCodes = Object.keys(errorsByType);
            if (errorCodes.length > 0) {
                const configErrors = errorCodes.filter(c => ['no_derecho', 'no_recargo'].includes(c));
                const calcErrors = errorCodes.filter(c => !['no_derecho', 'no_recargo'].includes(c));

                let messages = [];
                for (const code of errorCodes) {
                    const msg = batchErrorMessages[code] || 'Error inesperado en el cálculo. Contacte a soporte.';
                    messages.push(msg);
                }

                Swal.fire({
                    icon: configErrors.length > 0 && calcErrors.length === 0 ? 'info' : 'warning',
                    title: configErrors.length > 0 && calcErrors.length === 0 ? 'Configuración incompleta' : 'Error en el cálculo',
                    html: messages.map(m => `<p style="margin:4px 0;text-align:left;">${m}</p>`).join(''),
                    confirmButtonColor: '#7B2D3B',
                });
            }
        }
    } catch (_error) {
        // LEGACY: Errores silenciosos — NO mostrar alertas, NO logs
    } finally {
        if (currentRequestId === calculationRequestId.value) {
            isCalculating.value = false;
        }
    }
};

// ==========================================
// Datos derivados
// ==========================================

// Determina si mostrar campo de carga (solo para PICK UP o CAMION)
// LEGACY: Solo mostrar si tipo_auto != '0' (placeholder) y requires_cargo
const showCargoField = computed(() => {
    const tipo = form.vehiculo.tipo_auto;
    if (!tipo || tipo === '0') return false;
    const selectedType = props.vehicleTypes.find(t => t.value == tipo);
    return selectedType?.requires_cargo === true;
});

// Determina si mostrar sección de renovación
// LEGACY: Solo mostrar si tipo_cotizacion === 'RENOVACION' (no '0' ni 'NUEVA')
const showRenewalSection = computed(() => {
    return form.tipo_cotizacion === 'RENOVACION';
});

// Columnas habilitadas según cantidad Y paquete
// LEGACY: if(cantidad==0 || paquete==0) → ALL DISABLED
// Ver: formulario.js línea 957: if(cantidad==0|| paquete==0)
const enabledColumns = computed(() => {
    const cantidad = parseInt(form.cantidad_aseguradoras) || 0;
    const paquete = form.paquete;
    // Si paquete no está seleccionado (valor '0' o vacío), retornar 0 columnas habilitadas
    if (!paquete || paquete === '0') {
        return 0;
    }
    return cantidad;
});

// ==========================================
// Watchers para validación en tiempo real
// ==========================================

// Validar customer_id cuando cambie
watch(() => form.customer_id, (newId) => {
    if (touchedFields.customer_id) {
        validateField('customer_id');
    }

    if (!newId) {
        clearAseguradoData();
        return;
    }

    const customer = props.customers.find(c => c.id === newId);
    if (customer) {
        const nameParts = (customer.name || '').split(' ');
        form.asegurado.nombre = nameParts[0] || '';
        form.asegurado.apellido_paterno = nameParts[1] || '';
        form.asegurado.apellido_materno = nameParts.slice(2).join(' ') || '';
        form.asegurado.codigo_postal = customer.zip_code || '';
        form.asegurado.colonia = customer.neighborhood || '';
        form.asegurado.estado = customer.state || '';
    }
});

// Validar marca cuando cambie
watch(() => form.vehiculo.marca, () => {
    if (touchedFields['vehiculo.marca']) {
        validateField('vehiculo.marca');
    }
});

// Bloquear teclas no numéricas en Modelo (Año) — solo dígitos enteros positivos
const onModeloKeydown = (e) => {
    if (['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(e.key)) return;
    if (e.ctrlKey || e.metaKey) return;
    if (!/^\d$/.test(e.key)) {
        e.preventDefault();
    }
};

const onModeloInput = (e) => {
    const cleaned = e.target.value.replace(/\D/g, '').slice(0, 4);
    e.target.value = cleaned;
    form.vehiculo.modelo = cleaned;
};

// Validar modelo cuando cambie
watch(() => form.vehiculo.modelo, () => {
    if (touchedFields['vehiculo.modelo']) {
        validateField('vehiculo.modelo');
    }
});

// Validar tipo_auto cuando cambie
watch(() => form.vehiculo.tipo_auto, () => {
    if (touchedFields['vehiculo.tipo_auto']) {
        validateField('vehiculo.tipo_auto');
    }
    if (!showCargoField.value) {
        form.vehiculo.carga = '0';
    }
});

// Validar paquete cuando cambie y aplicar defaults
watch(() => form.paquete, (newPaquete, oldPaquete) => {
    if (touchedFields.paquete) {
        validateField('paquete');
    }
    // Aplicar defaults del paquete a todas las columnas habilitadas
    if (newPaquete !== oldPaquete && newPaquete !== '0') {
        applyPackageDefaults(newPaquete);
    }

    // TASK D: Limpieza reactiva cuando el paquete deshabilita campos
    // Esto asegura que los valores se limpien INMEDIATAMENTE al cambiar paquete
    if (newPaquete !== oldPaquete) {
        const n = parseInt(form.cantidad_aseguradoras) || 1;
        for (let col = 1; col <= n; col++) {
            // RESPONSABILIDAD CIVIL: deshabilita DM, cristales, RT
            if (newPaquete === 'RESPONSABILIDAD CIVIL') {
                form.coverages[`danos_opcion_selec_${col}`] = '0';
                form.coverages[`danos_material_importe_factura_${col}`] = '';
                form.coverages[`deducible_opcion_${col}`] = 'na';
                form.coverages[`cristales_opcion_selec_${col}`] = 'AMPARADA';
                form.coverages[`robo_opcion_selec_${col}`] = '0';
                form.coverages[`robo_importe_factura_${col}`] = '';
                form.coverages[`deducible_rt_${col}`] = 'na';
            }
            // LIMITADA: deshabilita DM, cristales (pero RT sigue habilitado)
            else if (newPaquete === 'LIMITADA') {
                form.coverages[`danos_opcion_selec_${col}`] = '0';
                form.coverages[`danos_material_importe_factura_${col}`] = '';
                form.coverages[`deducible_opcion_${col}`] = 'na';
                form.coverages[`cristales_opcion_selec_${col}`] = 'AMPARADA';
            }
        }
    }
});

// Validar cantidad_aseguradoras cuando cambie y actualizar columnas
watch(() => form.cantidad_aseguradoras, (newCount, oldCount) => {
    if (touchedFields.cantidad_aseguradoras) {
        validateField('cantidad_aseguradoras');
    }
    // Aplicar lógica de columnas habilitadas/deshabilitadas
    if (newCount !== oldCount) {
        setAseguradorasCount(newCount);
    }
});

// Validar compania_actual cuando cambie
watch(() => form.renovacion.compania_actual, () => {
    if (touchedFields['renovacion.compania_actual']) {
        validateField('renovacion.compania_actual');
    }
});

// Revalidar cuando cambie tipo de cotización
watch(() => form.tipo_cotizacion, () => {
    // Validar el propio campo
    if (touchedFields.tipo_cotizacion) {
        validateField('tipo_cotizacion');
    }
    // Revalidar campos de renovación (si aplica)
    validateField('renovacion.compania_actual');
    validateField('renovacion.fecha_vigencia');
    validateField('renovacion.poliza_a_renovar');
    validateField('renovacion.prima_año');
});

// Limpiar errores visuales de coberturas cuando el usuario modifica valores
watch(() => form.coverages, () => {
    if (coverageErrorKeys.value.size > 0) {
        coverageErrorKeys.value = new Set();
    }
}, { deep: true });

// LEGACY: Validar cobertura adicional 1 cuando cambie nombre o selects
// Ver: formulario.js líneas 9100-9211
watch(() => form.custom_coverage_1_name, () => {
    touchedFields['custom_coverage_1'] = true;
    validateField('custom_coverage_1');
});

// Watch para los selects de cobertura 1 (todas las columnas)
watch(
    () => [
        form.coverages?.cobertura_opcion_1_select_1,
        form.coverages?.cobertura_opcion_1_select_2,
        form.coverages?.cobertura_opcion_1_select_3,
        form.coverages?.cobertura_opcion_1_select_4,
        form.coverages?.cobertura_opcion_1_select_5,
    ],
    () => {
        // Si todos los selects están desactivados, limpiar nombre
        const n = parseInt(form.cantidad_aseguradoras) || 0;
        let anyActive = false;
        for (let col = 1; col <= n; col++) {
            const val = form.coverages?.[`cobertura_opcion_1_select_${col}`];
            if (val && val !== '0') { anyActive = true; break; }
        }
        if (!anyActive) {
            form.custom_coverage_1_name = '';
            fieldErrors['custom_coverage_1'] = '';
        } else if (touchedFields['custom_coverage_1']) {
            validateField('custom_coverage_1');
        }
    }
);

// LEGACY: Validar cobertura adicional 2 cuando cambie nombre o selects
// Ver: formulario.js líneas 9214-9409
watch(() => form.custom_coverage_2_name, () => {
    touchedFields['custom_coverage_2'] = true;
    validateField('custom_coverage_2');
});

// Watch para los selects de cobertura 2 (todas las columnas)
watch(
    () => [
        form.coverages?.cobertura_opcion_2_select_1,
        form.coverages?.cobertura_opcion_2_select_2,
        form.coverages?.cobertura_opcion_2_select_3,
        form.coverages?.cobertura_opcion_2_select_4,
        form.coverages?.cobertura_opcion_2_select_5,
    ],
    () => {
        // Si todos los selects están desactivados, limpiar nombre
        const n = parseInt(form.cantidad_aseguradoras) || 0;
        let anyActive = false;
        for (let col = 1; col <= n; col++) {
            const val = form.coverages?.[`cobertura_opcion_2_select_${col}`];
            if (val && val !== '0') { anyActive = true; break; }
        }
        if (!anyActive) {
            form.custom_coverage_2_name = '';
            fieldErrors['custom_coverage_2'] = '';
        } else if (touchedFields['custom_coverage_2']) {
            validateField('custom_coverage_2');
        }
    }
);

// ==========================================
// WATCHERS LEGACY — ORDEN EXACTO DE EVENTOS
// Fuente de verdad: LEGACY_SYSTEM_FORENSIC_ANALYSIS.md
//
// ORDEN LEGACY:
// 1. empresas_opcion_N.change → AJAX cargar derecho/recargo + recálculo
// 2. forma_pago.change → AJAX recálculo batch todas las columnas
// 3. cantidad_total_anual_opcion_N.change → AJAX recálculo prima_neta
// 4. primer_pago_opcion_N.change → AJAX recálculo subsecuentes
//
// NO CAMBIAR EL ORDEN. NO OPTIMIZAR. REPLICA EXACTA.
// ==========================================

/**
 * EVENTO 1: empresas_opcion_N.change
 * LEGACY: Cuando cambia aseguradora, cargar derecho/recargo + disparar recálculo
 *
 * Orden legacy:
 * 1. AJAX → obtener derecho_costo + recargos
 * 2. Guardar en variables locales
 * 3. Disparar recálculo prima si hay valores
 */
const handleInsurerChangeWithCalculation = async (column, insurerId) => {
    if (!insurerId || insurerId === '0') return;

    // 1. Cargar financial settings (derecho + recargos)
    await loadFinancialSettings(insurerId);

    // 2. Disparar recálculo si ya hay valores en la columna
    const totalAnual = form.coverages?.[`cantidad_total_anual_opcion_${column}`];
    if (totalAnual && totalAnual !== '' && totalAnual !== '0') {
        requestBackendCalculation(column, 'total_anual');
    }
};

// Watchers para cambio de aseguradora (EVENTO 1)
watch(() => form.coverages?.empresa_opcion_1, (newId) => handleInsurerChangeWithCalculation(1, newId));
watch(() => form.coverages?.empresa_opcion_2, (newId) => handleInsurerChangeWithCalculation(2, newId));
watch(() => form.coverages?.empresa_opcion_3, (newId) => handleInsurerChangeWithCalculation(3, newId));
watch(() => form.coverages?.empresa_opcion_4, (newId) => handleInsurerChangeWithCalculation(4, newId));
watch(() => form.coverages?.empresa_opcion_5, (newId) => handleInsurerChangeWithCalculation(5, newId));

/**
 * EVENTO 2: forma_de_pago.change
 * LEGACY: Cuando cambia forma de pago, recalcular TODAS las columnas
 *
 * Dispara requestBatchCalculation() que actualiza:
 * - cantidad_prima_neta_opcion_N
 * - subsecuente_opcion_N
 */
watch(() => form.coverages?.forma_de_pago, (newVal, oldVal) => {
    if (newVal === oldVal) return;

    const n = parseInt(form.cantidad_aseguradoras) || 0;

    // Reset a '0' (placeholder): limpiar primer_pago y subsecuentes
    if (!newVal || newVal === '0') {
        for (let col = 1; col <= n; col++) {
            form.coverages[`primer_pago_opcion_${col}`] = '';
            form.coverages[`subsecuente_opcion_${col}`] = '';
            form.coverages[`cantidad_prima_neta_opcion_${col}`] = '';
        }
        return;
    }

    // ANUAL: primer_pago = total_anual (un solo pago, no hay subsecuentes)
    if (newVal === 'ANUAL') {
        for (let col = 1; col <= n; col++) {
            const totalStr = form.coverages?.[`cantidad_total_anual_opcion_${col}`] || '';
            form.coverages[`primer_pago_opcion_${col}`] = totalStr;
            form.coverages[`subsecuente_opcion_${col}`] = '';
        }
    } else {
        // SEMESTRAL/TRIMESTRAL/MENSUAL: limpiar primer_pago y subsecuentes
        // El usuario debe ingresar nuevo primer_pago para la nueva frecuencia
        for (let col = 1; col <= n; col++) {
            form.coverages[`primer_pago_opcion_${col}`] = '';
            form.coverages[`subsecuente_opcion_${col}`] = '';
        }
    }

    requestBatchCalculation();
});

/**
 * EVENTO 3: cantidad_total_anual_opcion_N.change
 * LEGACY: Cuando cambia prima total anual, recalcular prima neta
 *
 * Este es el campo que el usuario ingresa manualmente.
 * El backend calcula:
 * - prima_neta = (total / 1.16) - derecho - (recargo si aplica)
 *
 * ANUAL: primer_pago = total_anual (inmediato, sin esperar backend)
 */
const handleTotalAnualChange = (column) => {
    const parseMoney = (str) => {
        if (!str) return 0;
        return parseFloat(String(str).replace(/[,$]/g, '')) || 0;
    };

    const totalStr = form.coverages?.[`cantidad_total_anual_opcion_${column}`] || '';
    const total = parseMoney(totalStr);

    // Si total_anual se borra o queda en 0, limpiar campos dependientes
    if (total <= 0) {
        form.coverages[`primer_pago_opcion_${column}`] = '';
        form.coverages[`subsecuente_opcion_${column}`] = '';
        form.coverages[`cantidad_prima_neta_opcion_${column}`] = '';
        return;
    }

    // ANUAL: copiar inmediatamente a primer_pago (es un solo pago)
    if (form.coverages?.forma_de_pago === 'ANUAL') {
        form.coverages[`primer_pago_opcion_${column}`] = totalStr;
    }
    requestBackendCalculation(column, 'total_anual');
};

watch(() => form.coverages?.cantidad_total_anual_opcion_1, () => handleTotalAnualChange(1));
watch(() => form.coverages?.cantidad_total_anual_opcion_2, () => handleTotalAnualChange(2));
watch(() => form.coverages?.cantidad_total_anual_opcion_3, () => handleTotalAnualChange(3));
watch(() => form.coverages?.cantidad_total_anual_opcion_4, () => handleTotalAnualChange(4));
watch(() => form.coverages?.cantidad_total_anual_opcion_5, () => handleTotalAnualChange(5));

/**
 * EVENTO 4: primer_pago_opcion_N.change
 * LEGACY: Cuando cambia primer pago, recalcular subsecuentes
 *
 * Validación: primer_pago no puede exceder prima_total_anual
 * Si excede, limpiar subsecuente y mostrar alerta
 */
const handlePrimerPagoChange = (column) => {
    const parseMoney = (str) => {
        if (!str) return 0;
        return parseFloat(String(str).replace(/[,$]/g, '')) || 0;
    };

    const primerPagoStr = form.coverages?.[`primer_pago_opcion_${column}`] || '';
    const primerPago = parseMoney(primerPagoStr);
    const totalAnual = parseMoney(form.coverages?.[`cantidad_total_anual_opcion_${column}`]);
    const frecuencia = form.coverages?.forma_de_pago;

    // Si no hay total_anual, no calcular nada
    if (totalAnual <= 0) {
        form.coverages[`subsecuente_opcion_${column}`] = '';
        return;
    }

    // Si primer_pago está vacío, limpiar subsecuente y no calcular
    if (!primerPagoStr || primerPago <= 0) {
        form.coverages[`subsecuente_opcion_${column}`] = '';
        return;
    }

    // Si primer_pago excede total_anual, alertar y limpiar
    if (primerPago > totalAnual) {
        form.coverages[`subsecuente_opcion_${column}`] = '';
        Swal.fire({
            icon: 'warning',
            title: 'Primer pago excede la prima total',
            text: `El primer pago no puede ser mayor que la prima total anual en la opción ${column}.`,
            confirmButtonColor: '#7B2D3B',
        });
        return;
    }

    // Si primer_pago = total_anual en pago fraccionado, advertir
    if (frecuencia && frecuencia !== 'ANUAL' && frecuencia !== '0' && primerPago === totalAnual) {
        form.coverages[`subsecuente_opcion_${column}`] = '';
        Swal.fire({
            icon: 'info',
            title: 'Primer pago igual a prima total',
            text: `Si el primer pago cubre la prima total, los subsecuentes serán $0. Considere usar forma de pago Anual.`,
            confirmButtonColor: '#7B2D3B',
        });
        // Permitir continuar pero con advertencia
    }

    requestBackendCalculation(column, 'primer_pago');
};

watch(() => form.coverages?.primer_pago_opcion_1, () => handlePrimerPagoChange(1));
watch(() => form.coverages?.primer_pago_opcion_2, () => handlePrimerPagoChange(2));
watch(() => form.coverages?.primer_pago_opcion_3, () => handlePrimerPagoChange(3));
watch(() => form.coverages?.primer_pago_opcion_4, () => handlePrimerPagoChange(4));
watch(() => form.coverages?.primer_pago_opcion_5, () => handlePrimerPagoChange(5));

// ==========================================
// Métodos
// ==========================================

const clearAseguradoData = () => {
    form.asegurado = {
        apellido_paterno: '',
        apellido_materno: '',
        nombre: '',
        codigo_postal: '',
        colonia: '',
        estado: '',
    };
};

// Búsqueda de clientes
const searchCustomers = (term) => {
    if (!term || term.length < 2) {
        filteredCustomers.value = props.customers;
        return;
    }
    const lower = term.toLowerCase();
    filteredCustomers.value = props.customers.filter(c =>
        c.name?.toLowerCase().includes(lower) ||
        c.phone?.includes(term) ||
        c.rfc?.toLowerCase().includes(lower)
    );
};

watch(customerSearch, searchCustomers);

// Búsqueda de contactos
const searchContacts = (term) => {
    if (!term || term.length < 2) {
        filteredContacts.value = props.contacts;
        return;
    }
    const lower = term.toLowerCase();
    filteredContacts.value = props.contacts.filter(c =>
        c.name?.toLowerCase().includes(lower)
    );
};

watch(contactSearch, searchContacts);

// Bloquear teclas no permitidas en inputs de dinero (solo dígitos y punto)
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

// Formateo de moneda en campos de renovación
const handleMoneyInput = (event, field) => {
    const formatted = formatInput(event.target.value);
    event.target.value = formatted;
    form.renovacion[field] = formatted;
};

// Validar congruencia de campos financieros antes de enviar
const validateFinancialFields = () => {
    const parseMoney = (str) => {
        if (!str) return 0;
        return parseFloat(String(str).replace(/[,$]/g, '')) || 0;
    };

    const warnings = [];
    const n = parseInt(form.cantidad_aseguradoras) || 0;
    const frecuencia = form.coverages?.forma_de_pago;

    for (let col = 1; col <= n; col++) {
        const empresa = form.coverages?.[`empresa_opcion_${col}`];
        const total = parseMoney(form.coverages?.[`cantidad_total_anual_opcion_${col}`]);
        const primerPago = parseMoney(form.coverages?.[`primer_pago_opcion_${col}`]);
        const primaNeta = parseMoney(form.coverages?.[`cantidad_prima_neta_opcion_${col}`]);

        // Si hay aseguradora pero no total_anual
        if (empresa && empresa !== '0' && total <= 0) {
            warnings.push(`Opción ${col}: Falta prima total anual.`);
        }

        // Si hay total_anual pero no prima_neta (el backend no pudo calcular)
        if (total > 0 && primaNeta <= 0) {
            warnings.push(`Opción ${col}: La prima neta no fue calculada. Verifique la aseguradora y configuración.`);
        }

        // Para pagos fraccionados: primer_pago es obligatorio
        if (frecuencia && frecuencia !== 'ANUAL' && frecuencia !== '0' && total > 0) {
            if (primerPago <= 0) {
                warnings.push(`Opción ${col}: Falta ingresar el primer pago.`);
            }
        }

        // primer_pago no puede exceder total_anual
        if (total > 0 && primerPago > total) {
            warnings.push(`Opción ${col}: El primer pago ($${primerPago.toLocaleString('es-MX')}) excede la prima total ($${total.toLocaleString('es-MX')}).`);
        }

        // Para pagos fraccionados con total y primer_pago: debe haber subsecuente calculado
        if (frecuencia && frecuencia !== 'ANUAL' && frecuencia !== '0' && total > 0 && primerPago > 0 && primerPago < total) {
            const subsecuente = parseMoney(form.coverages?.[`subsecuente_opcion_${col}`]);
            if (subsecuente <= 0) {
                warnings.push(`Opción ${col}: Los pagos subsecuentes no fueron calculados. Verifique la configuración.`);
            }
        }
    }

    return warnings;
};

// Set de claves de campos de cobertura con error (para CoverageTable visual)
const coverageErrorKeys = ref(new Set());

// Validar coberturas según paquete (reutilizable en submit y previewPdf)
// Retorna { errors: string[], keys: Set<string> }
const validateCoverageFields = () => {
    const parseMoney = (str) => {
        if (!str) return 0;
        return parseFloat(String(str).replace(/[,$]/g, '')) || 0;
    };
    const getCov = (field, col) => form.coverages?.[`${field}_${col}`] || '';

    const errors = [];
    const keys = new Set();
    const n = parseInt(form.cantidad_aseguradoras) || 0;
    const paquete = form.paquete;
    const isAmplia = paquete === 'AMPLIA';
    const isAmpliaOLimitada = isAmplia || paquete === 'LIMITADA';

    // Validar forma de pago (global)
    const formaPago = form.coverages?.forma_de_pago;
    if (!formaPago || formaPago === '0') {
        errors.push('Falta seleccionar FORMA DE PAGO.');
        keys.add('forma_de_pago');
    }

    for (let col = 1; col <= n; col++) {
        const empresaId = getCov('empresa_opcion', col);
        if (!empresaId || empresaId === '0') {
            errors.push(`Opción ${col}: Falta seleccionar aseguradora.`);
            keys.add(`empresa_opcion_${col}`);
            continue;
        }

        // DAÑOS MATERIALES (solo AMPLIA)
        if (isAmplia) {
            const danos = getCov('danos_opcion_selec', col);
            if (!danos || danos === '0') {
                errors.push(`Opción ${col}: Falta seleccionar DAÑOS MATERIALES.`);
                keys.add(`danos_opcion_selec_${col}`);
            }
            const dedDM = getCov('deducible_opcion', col);
            if (!dedDM || dedDM === 'na') {
                errors.push(`Opción ${col}: Falta seleccionar DEDUCIBLE DM.`);
                keys.add(`deducible_opcion_${col}`);
            }
            if (danos === 'V.FACTURA') {
                const importeDM = parseMoney(getCov('danos_material_importe_factura', col));
                if (importeDM <= 0) {
                    errors.push(`Opción ${col}: Falta IMPORTE FACTURA de Daños Materiales.`);
                    keys.add(`danos_material_importe_factura_${col}`);
                }
            }
        }

        // ROBO TOTAL (AMPLIA y LIMITADA)
        if (isAmpliaOLimitada) {
            const robo = getCov('robo_opcion_selec', col);
            if (!robo || robo === '0') {
                errors.push(`Opción ${col}: Falta seleccionar ROBO TOTAL.`);
                keys.add(`robo_opcion_selec_${col}`);
            }
            const dedRT = getCov('deducible_rt', col);
            if (!dedRT || dedRT === 'na') {
                errors.push(`Opción ${col}: Falta seleccionar DEDUCIBLE RT.`);
                keys.add(`deducible_rt_${col}`);
            }
            if (robo === 'V.FACTURA') {
                const importeRT = parseMoney(getCov('robo_importe_factura', col));
                if (importeRT <= 0) {
                    errors.push(`Opción ${col}: Falta IMPORTE FACTURA de Robo Total.`);
                    keys.add(`robo_importe_factura_${col}`);
                }
            }
        }

        // RC DAÑOS A TERCEROS (siempre requerido)
        if (parseMoney(getCov('danos_tercero_opcion', col)) <= 0) {
            errors.push(`Opción ${col}: Falta R.C. DAÑOS A TERCEROS.`);
            keys.add(`danos_tercero_opcion_${col}`);
        }

        // RC FALLECIMIENTO (siempre requerido)
        if (parseMoney(getCov('fallecimiento_opcion', col)) <= 0) {
            errors.push(`Opción ${col}: Falta R.C. FALLECIMIENTO.`);
            keys.add(`fallecimiento_opcion_${col}`);
        }

        // GASTOS MEDICOS (siempre requerido)
        if (parseMoney(getCov('gastos_medicos_opcion', col)) <= 0) {
            errors.push(`Opción ${col}: Falta GASTOS MÉDICOS OCUPANTES.`);
            keys.add(`gastos_medicos_opcion_${col}`);
        }

        // ACCIDENTES AL CONDUCTOR (siempre requerido)
        if (parseMoney(getCov('accidente_conducir_opcion', col)) <= 0) {
            errors.push(`Opción ${col}: Falta ACCIDENTES AL CONDUCTOR.`);
            keys.add(`accidente_conducir_opcion_${col}`);
        }

        // DAÑOS POR LA CARGA (siempre requerido)
        const danosCarga = getCov('danos_carga_opcion_selec', col);
        if (!danosCarga || danosCarga === '0') {
            errors.push(`Opción ${col}: Falta seleccionar DAÑOS POR LA CARGA.`);
            keys.add(`danos_carga_opcion_selec_${col}`);
        }

        // EXTENSION DE RC (siempre requerido)
        const extRC = getCov('extension_rc_opcion', col);
        if (!extRC || extRC === '0') {
            errors.push(`Opción ${col}: Falta seleccionar EXTENSIÓN DE RC.`);
            keys.add(`extension_rc_opcion_${col}`);
        }
    }

    // Actualizar el Set reactivo para que CoverageTable muestre los errores
    coverageErrorKeys.value = keys;

    return errors;
};

// Enviar formulario
const submit = () => {
    // Ejecutar TODAS las validaciones para recopilar todos los errores
    validateAllFields();
    const coverageCvErrors = validateCoverageFields(); // también llena coverageErrorKeys
    const financialWarnings = validateFinancialFields();

    // Recopilar errores inline (campos superiores del formulario)
    const inlineErrors = Object.entries(fieldErrors)
        .filter(([_, error]) => error)
        .map(([_, error]) => error);

    // Combinar todos los errores
    const allErrors = [...inlineErrors, ...coverageCvErrors, ...financialWarnings];

    if (allErrors.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            html: `<ul style="text-align: left; margin: 0; padding-left: 1.5rem; font-size: 0.9rem;">${allErrors.map(e => `<li>${e}</li>`).join('')}</ul>`,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#7B2D3B',
        });
        return;
    }

    // TASK C: Sanitizar payload antes de enviar
    // Limpia columnas deshabilitadas y campos según paquete
    sanitizePayload();

    isSubmitting.value = true;

    form.post(route('quotes.store'), {
        preserveScroll: true,
        onSuccess: () => {
            isSubmitting.value = false;
            Swal.fire({
                icon: 'success',
                title: 'Cotización guardada',
                text: 'La cotización se ha creado exitosamente',
                confirmButtonColor: '#7B2D3B',
            });
        },
        onError: (backendErrors) => {
            isSubmitting.value = false;
            const errorList = Object.values(backendErrors).flat();
            Swal.fire({
                icon: 'error',
                title: 'Error al guardar',
                html: `<ul style="text-align: left; margin: 0; padding-left: 1.5rem;">${errorList.map(e => `<li>${e}</li>`).join('')}</ul>`,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#7B2D3B',
            });
        }
    });
};

// Cancelar
const cancel = async () => {
    const result = await Swal.fire({
        icon: 'question',
        title: '¿Cancelar cotización?',
        text: 'Se perderán los datos ingresados',
        showCancelButton: true,
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'No, continuar',
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
    });

    if (result.isConfirmed) {
        router.visit(route('quotes.index'));
    }
};

// Vista previa PDF
const previewPdf = async () => {
    // 1. Ejecutar TODAS las validaciones juntas
    validateAllFields();
    const coverageCvErrors = validateCoverageFields(); // llena coverageErrorKeys para bordes rojos

    // Recopilar errores inline (campos superiores)
    const inlineErrors = Object.entries(fieldErrors)
        .filter(([_, error]) => error)
        .map(([_, error]) => error);

    // 2. Validar columnas financieras y construir opciones
    const parseMoney = (str) => {
        if (!str) return 0;
        return parseFloat(String(str).replace(/[,$]/g, '')) || 0;
    };
    const getCov = (field, col) => form.coverages?.[`${field}_${col}`] || '';
    const getCovGlobal = (field) => form.coverages?.[field] || '';
    const frecuencia = form.coverages?.forma_de_pago;
    const n = parseInt(form.cantidad_aseguradoras) || 0;

    const options = [];
    const financialErrors = [];

    for (let col = 1; col <= n; col++) {
        const empresaId = getCov('empresa_opcion', col);
        if (!empresaId || empresaId === '0') continue;

        const total = parseMoney(getCov('cantidad_total_anual_opcion', col));
        const primaNeta = parseMoney(getCov('cantidad_prima_neta_opcion', col));
        const primerPago = parseMoney(getCov('primer_pago_opcion', col));
        const subsecuente = parseMoney(getCov('subsecuente_opcion', col));

        if (total <= 0) {
            financialErrors.push(`Opción ${col}: Falta prima total anual.`);
            continue;
        }
        if (primaNeta <= 0) {
            financialErrors.push(`Opción ${col}: La prima neta no fue calculada.`);
            continue;
        }
        if (frecuencia !== 'ANUAL' && primerPago <= 0) {
            financialErrors.push(`Opción ${col}: Falta ingresar el primer pago.`);
            continue;
        }

        const insurer = props.insurers.find(i => String(i.id) === String(empresaId));
        const insurerName = insurer?.name || `Aseguradora ${empresaId}`;

        const subtotal = total / 1.16;
        const iva = total - subtotal;
        const derechoYRecargo = subtotal - primaNeta;

        options.push({
            insurer_name: insurerName,
            insurer_id: parseInt(empresaId),
            coverage_package: form.paquete || 'AMPLIA',
            payment_frequency: frecuencia,
            net_premium: primaNeta,
            policy_fee: derechoYRecargo,
            iva: Math.round(iva * 100) / 100,
            total: total,
            first_payment: primerPago || total,
            subsequent_payment: subsecuente,
            coverages: {
                danos: getCov('danos_opcion_selec', col),
                danos_importe: getCov('danos_material_importe_factura', col),
                deducible_dm: getCov('deducible_opcion', col),
                cristales: getCov('cristales_opcion_selec', col),
                robo: getCov('robo_opcion_selec', col),
                robo_importe: getCov('robo_importe_factura', col),
                deducible_rt: getCov('deducible_rt', col),
                rc_terceros: getCov('danos_tercero_opcion', col),
                deducible_rc: getCov('deducible_de_rc_opcion', col),
                rc_fallecimiento: getCov('fallecimiento_opcion', col),
                gastos_medicos: getCov('gastos_medicos_opcion', col),
                accidentes_conductor: getCov('accidente_conducir_opcion', col),
                proteccion_legal: getCov('proteccion_opcion_selec', col),
                asistencia_vial: getCov('asistencia_vial_opcion_selec', col),
                danos_carga: getCov('danos_carga_opcion_selec', col),
                adaptaciones: getCov('adaptaciones_opcion', col),
                extension_rc: getCov('extension_rc_opcion', col),
                descripcion: getCovGlobal('descripcion_tabla'),
                custom1_name: form.custom_coverage_1_name || '',
                custom1_value: getCov('cobertura_opcion_1_select', col),
                custom2_name: form.custom_coverage_2_name || '',
                custom2_value: getCov('cobertura_opcion_2_select', col),
            },
        });
    }

    // 3. Combinar TODOS los errores
    const allErrors = [...inlineErrors, ...coverageCvErrors, ...financialErrors];

    if (allErrors.length > 0) {
        // Scroll al primer campo con error visual
        const firstErrorEl = document.querySelector('.form-input--error, .form-select--error');
        if (firstErrorEl) {
            firstErrorEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            html: `<ul style="text-align:left;margin:0;padding-left:1.5rem;font-size:0.9rem;">${allErrors.map(e => `<li>${e}</li>`).join('')}</ul>`,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#7B2D3B',
        });
        return;
    }

    if (options.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Sin opciones válidas',
            text: 'No hay opciones con datos completos para generar la vista previa.',
            confirmButtonColor: '#7B2D3B',
        });
        return;
    }

    // 3. Construir payload
    const aseg = form.asegurado || {};
    const customerName = [aseg.nombre, aseg.apellido_paterno, aseg.apellido_materno]
        .filter(Boolean).join(' ') || 'Cliente Prospecto';

    const payload = {
        quote_type: form.tipo_cotizacion,
        customer_name: customerName,
        customer_zip: aseg.codigo_postal || '',
        customer_neighborhood: aseg.colonia || '',
        customer_state: aseg.estado || '',
        vehicle: {
            brand: form.vehiculo?.marca || '',
            model: form.vehiculo?.descripcion || '',
            year: parseInt(form.vehiculo?.modelo) || new Date().getFullYear(),
            description: form.vehiculo?.descripcion || '',
            type: String(props.vehicleTypes?.find(vt => String(vt.value) === String(form.vehiculo?.tipo_auto))?.label || form.vehiculo?.tipo_auto || ''),
            usage: form.vehiculo?.uso_de_unidad || '',
            cargo: String(form.vehiculo?.carga || ''),
        },
        coverage_package: form.paquete,
        renewal: form.tipo_cotizacion === 'RENOVACION' ? {
            current_insurer: form.renovacion?.compania_actual || '',
            expiry_date: form.renovacion?.fecha_vigencia || '',
            policy_number: form.renovacion?.poliza_a_renovar || '',
            previous_premium: form.renovacion?.prima_año || '',
        } : null,
        options: options,
        payment_frequency: frecuencia,
    };

    // 4. Fetch PDF como blob (no pierde la página si hay error)
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const response = await fetch(route('quotes.preview-draft'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/pdf, application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(payload),
        });

        if (!response.ok) {
            const text = await response.text();
            Swal.fire({
                icon: 'error',
                title: 'Error al generar PDF',
                text: 'Ocurrió un error en el servidor. Verifique los datos e intente de nuevo.',
                confirmButtonColor: '#7B2D3B',
            });
            return;
        }

        const blob = await response.blob();
        const url = URL.createObjectURL(blob);
        window.open(url, '_blank');
        setTimeout(() => URL.revokeObjectURL(url), 60000);
    } catch (err) {
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor.',
            confirmButtonColor: '#7B2D3B',
        });
    }
};

/**
 * TASK C: Sanitiza el payload antes de enviar al backend
 *
 * Reglas de limpieza:
 * 1. Columnas deshabilitadas (colIndex > N): empresa_opcion = null, todos los campos = null
 * 2. Nunca enviar '0' en cols deshabilitadas
 * 3. Campos deshabilitados por paquete: limpiar a null
 * 4. Si tipo != V.FACTURA: limpiar importe_factura
 * 5. forma_de_pago: si es '0' lo convertimos a null para que backend rechace
 */
const sanitizePayload = () => {
    const n = parseInt(form.cantidad_aseguradoras) || 1;
    const paquete = form.paquete;

    // Campos que corresponden a cada columna
    const columnFields = [
        'empresa_opcion',
        'danos_opcion_selec',
        'danos_material_importe_factura',
        'deducible_opcion',
        'cristales_opcion_selec',
        'robo_opcion_selec',
        'robo_importe_factura',
        'deducible_rt',
        'danos_tercero_opcion',
        'deducible_de_rc_opcion',
        'fallecimiento_opcion',
        'gastos_medicos_opcion',
        'accidente_conducir_opcion',
        'proteccion_opcion_selec',
        'asistencia_vial_opcion_selec',
        'danos_carga_opcion_selec',
        'adaptaciones_opcion',
        'extension_rc_opcion',
        'cobertura_opcion_1_select',
        'cobertura_opcion_2_select',
        'cantidad_prima_neta_opcion',
        'cantidad_total_anual_opcion',
        'primer_pago_opcion',
        'subsecuente_opcion',
    ];

    // REGLA 1 & 2: Limpiar columnas deshabilitadas (col > N)
    for (let col = n + 1; col <= 5; col++) {
        columnFields.forEach(field => {
            form.coverages[`${field}_${col}`] = null;
        });
    }

    // REGLA 3 & 4: Limpiar campos deshabilitados por paquete para columnas habilitadas
    for (let col = 1; col <= n; col++) {
        // RESPONSABILIDAD CIVIL: deshabilita DM, cristales, RT
        if (paquete === 'RESPONSABILIDAD CIVIL') {
            form.coverages[`danos_opcion_selec_${col}`] = null;
            form.coverages[`danos_material_importe_factura_${col}`] = null;
            form.coverages[`deducible_opcion_${col}`] = null;
            form.coverages[`cristales_opcion_selec_${col}`] = null;
            form.coverages[`robo_opcion_selec_${col}`] = null;
            form.coverages[`robo_importe_factura_${col}`] = null;
            form.coverages[`deducible_rt_${col}`] = null;
        }
        // LIMITADA: deshabilita DM, cristales (pero RT está habilitado)
        else if (paquete === 'LIMITADA') {
            form.coverages[`danos_opcion_selec_${col}`] = null;
            form.coverages[`danos_material_importe_factura_${col}`] = null;
            form.coverages[`deducible_opcion_${col}`] = null;
            form.coverages[`cristales_opcion_selec_${col}`] = null;
        }

        // REGLA 4: Si daños no es V.FACTURA, limpiar importe_factura
        if (form.coverages[`danos_opcion_selec_${col}`] !== 'V.FACTURA') {
            form.coverages[`danos_material_importe_factura_${col}`] = null;
        }
        // Si robo no es V.FACTURA, limpiar robo_importe_factura
        if (form.coverages[`robo_opcion_selec_${col}`] !== 'V.FACTURA') {
            form.coverages[`robo_importe_factura_${col}`] = null;
        }
    }

    // REGLA 5: Limpiar forma_de_pago si no está seleccionada
    const formaPago = form.coverages.forma_de_pago;
    if (!formaPago || formaPago === '0') {
        form.coverages.forma_de_pago = null;
    }
};
</script>

<template>
    <div class="quote-form">
            <!-- Título -->
            <div class="form-header">
                <h1 class="form-title">COTIZACION DE SEGURO DE AUTOMOVILES</h1>
            </div>

            <form @submit.prevent="submit" class="form-content">
                <!-- ==========================================
                     SECCIÓN 1: ENCABEZADO
                     ========================================== -->
                <section class="form-section">
                    <div class="section-header">
                        <h2>Datos de la Cotización</h2>
                    </div>

                    <div class="form-grid form-grid--4">
                        <!-- Tipo de Cotización -->
                        <!-- LEGACY: verificar_tipo_cotizacion() línea 6215 -->
                        <div class="form-group">
                            <label class="form-label required">Tipo de Cotización</label>
                            <select
                                v-model="form.tipo_cotizacion"
                                class="form-select"
                                :class="{ 'form-select--error': touchedFields.tipo_cotizacion && fieldErrors.tipo_cotizacion }"
                                @blur="markTouched('tipo_cotizacion')"
                            >
                                <option value="0">SELECCIONA TIPO DE COTIZACIÓN</option>
                                <option value="NUEVA">NUEVA</option>
                                <option value="RENOVACION">RENOVACION</option>
                            </select>
                            <span v-if="touchedFields.tipo_cotizacion && fieldErrors.tipo_cotizacion" class="field-error">
                                {{ fieldErrors.tipo_cotizacion }}
                            </span>
                        </div>

                        <!-- Hora Solicitada -->
                        <div class="form-group">
                            <label class="form-label required">Hora Solicitada</label>
                            <input
                                type="time"
                                v-model="form.hora_solicitada"
                                class="form-input"
                                :class="{ 'form-input--error': touchedFields.hora_solicitada && fieldErrors.hora_solicitada }"
                                @blur="markTouched('hora_solicitada')"
                            />
                            <span v-if="touchedFields.hora_solicitada && fieldErrors.hora_solicitada" class="field-error">
                                {{ fieldErrors.hora_solicitada }}
                            </span>
                        </div>

                        <!-- Contacto -->
                        <div class="form-group">
                            <label class="form-label required">Contacto</label>
                            <select
                                v-model="form.contact_id"
                                class="form-select"
                                :class="{ 'form-select--error': touchedFields.contact_id && fieldErrors.contact_id }"
                                @blur="markTouched('contact_id')"
                            >
                                <option :value="null">SELECCIONA CONTACTO</option>
                                <option v-for="contact in contacts" :key="contact.id" :value="contact.id">
                                    {{ contact.name }}
                                </option>
                            </select>
                            <span v-if="touchedFields.contact_id && fieldErrors.contact_id" class="field-error">
                                {{ fieldErrors.contact_id }}
                            </span>
                        </div>

                        <!-- Prospecto/Asegurado -->
                        <div class="form-group">
                            <label class="form-label required">Prospecto/Asegurado</label>
                            <select
                                v-model="form.customer_id"
                                class="form-select"
                                :class="{ 'form-select--error': touchedFields.customer_id && fieldErrors.customer_id }"
                                @blur="markTouched('customer_id')"
                            >
                                <option :value="null">SELECCIONA ASEGURADO</option>
                                <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                                    {{ customer.name }}
                                </option>
                            </select>
                            <span v-if="touchedFields.customer_id && fieldErrors.customer_id" class="field-error">
                                {{ fieldErrors.customer_id }}
                            </span>
                        </div>
                    </div>
                </section>

                <!-- ==========================================
                     SECCIÓN 2: INFORMACIÓN DEL ASEGURADO (readonly)
                     ========================================== -->
                <section class="form-section" v-if="form.customer_id">
                    <div class="section-header">
                        <h2>Información del Asegurado</h2>
                        <span class="section-badge">Datos del cliente seleccionado</span>
                    </div>

                    <div class="form-grid form-grid--3">
                        <div class="form-group">
                            <label class="form-label">Apellido Paterno</label>
                            <input
                                type="text"
                                v-model="form.asegurado.apellido_paterno"
                                class="form-input form-input--readonly"
                                readonly
                            />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Apellido Materno</label>
                            <input
                                type="text"
                                v-model="form.asegurado.apellido_materno"
                                class="form-input form-input--readonly"
                                readonly
                            />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nombre</label>
                            <input
                                type="text"
                                v-model="form.asegurado.nombre"
                                class="form-input form-input--readonly"
                                readonly
                            />
                        </div>

                        <div class="form-group">
                            <label class="form-label">C.P.</label>
                            <input
                                type="text"
                                v-model="form.asegurado.codigo_postal"
                                class="form-input form-input--readonly"
                                readonly
                            />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Colonia</label>
                            <input
                                type="text"
                                v-model="form.asegurado.colonia"
                                class="form-input form-input--readonly"
                                readonly
                            />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Estado</label>
                            <input
                                type="text"
                                v-model="form.asegurado.estado"
                                class="form-input form-input--readonly"
                                readonly
                            />
                        </div>
                    </div>
                </section>

                <!-- ==========================================
                     SECCIÓN 3: DESCRIPCIÓN DEL VEHÍCULO
                     ========================================== -->
                <section class="form-section">
                    <div class="section-header">
                        <h2>Descripción del Vehículo</h2>
                    </div>

                    <div class="form-grid form-grid--3">
                        <div class="form-group">
                            <label class="form-label required">Marca</label>
                            <select
                                v-model="form.vehiculo.marca"
                                class="form-select"
                                :class="{ 'form-select--error': touchedFields['vehiculo.marca'] && fieldErrors['vehiculo.marca'] }"
                                @change="markTouched('vehiculo.marca')"
                                @blur="markTouched('vehiculo.marca')"
                            >
                                <option value="" disabled>SELECCIONA MARCA</option>
                                <option v-for="brand in brands" :key="brand" :value="brand">{{ brand }}</option>
                            </select>
                            <span v-if="touchedFields['vehiculo.marca'] && fieldErrors['vehiculo.marca']" class="field-error">
                                {{ fieldErrors['vehiculo.marca'] }}
                            </span>
                        </div>

                        <!-- LEGACY: verificar_descripcion() línea 14119 -->
                        <div class="form-group">
                            <label class="form-label required">Descripción (Versión/Línea)</label>
                            <input
                                type="text"
                                v-model="form.vehiculo.descripcion"
                                class="form-input"
                                :class="{ 'form-input--error': touchedFields['vehiculo.descripcion'] && fieldErrors['vehiculo.descripcion'] }"
                                placeholder="Ej: VERSA ADVANCE CVT"
                                @blur="markTouched('vehiculo.descripcion')"
                            />
                            <span v-if="touchedFields['vehiculo.descripcion'] && fieldErrors['vehiculo.descripcion']" class="field-error">
                                {{ fieldErrors['vehiculo.descripcion'] }}
                            </span>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Modelo (Año)</label>
                            <input
                                type="text"
                                inputmode="numeric"
                                :value="form.vehiculo.modelo"
                                @input="onModeloInput"
                                @keydown="onModeloKeydown"
                                class="form-input"
                                :class="{ 'form-input--error': touchedFields['vehiculo.modelo'] && fieldErrors['vehiculo.modelo'] }"
                                placeholder="2024"
                                maxlength="4"
                                @blur="markTouched('vehiculo.modelo')"
                            />
                            <span v-if="touchedFields['vehiculo.modelo'] && fieldErrors['vehiculo.modelo']" class="field-error">
                                {{ fieldErrors['vehiculo.modelo'] }}
                            </span>
                        </div>

                        <!-- LEGACY: verificar_uso_de_unidad() línea 14172 -->
                        <div class="form-group">
                            <label class="form-label required">Uso de la Unidad</label>
                            <input
                                type="text"
                                v-model="form.vehiculo.uso_de_unidad"
                                class="form-input"
                                :class="{ 'form-input--error': touchedFields['vehiculo.uso_de_unidad'] && fieldErrors['vehiculo.uso_de_unidad'] }"
                                placeholder="Ej: PARTICULAR, COMERCIAL..."
                                @blur="markTouched('vehiculo.uso_de_unidad')"
                            />
                            <span v-if="touchedFields['vehiculo.uso_de_unidad'] && fieldErrors['vehiculo.uso_de_unidad']" class="field-error">
                                {{ fieldErrors['vehiculo.uso_de_unidad'] }}
                            </span>
                        </div>

                        <!-- LEGACY: cotizacion_autos.txt líneas 241-247 -->
                        <div class="form-group">
                            <label class="form-label required">Tipo de Vehículo</label>
                            <select
                                v-model="form.vehiculo.tipo_auto"
                                class="form-select"
                                :class="{ 'form-select--error': touchedFields['vehiculo.tipo_auto'] && fieldErrors['vehiculo.tipo_auto'] }"
                                @blur="markTouched('vehiculo.tipo_auto')"
                            >
                                <option value="0">SELECCIONA TIPO DE AUTO</option>
                                <option v-for="tipo in vehicleTypes" :key="tipo.value" :value="tipo.value">
                                    {{ tipo.label }}
                                </option>
                            </select>
                            <span v-if="touchedFields['vehiculo.tipo_auto'] && fieldErrors['vehiculo.tipo_auto']" class="field-error">
                                {{ fieldErrors['vehiculo.tipo_auto'] }}
                            </span>
                        </div>

                        <!-- Campo de Carga (solo para PICK UP/CAMION) -->
                        <!-- LEGACY: verificar_carga() línea 14218, cotizacion_autos.txt líneas 261-266 -->
                        <div class="form-group" v-if="showCargoField">
                            <label class="form-label required">Descripción de Carga</label>
                            <select
                                v-model="form.vehiculo.carga"
                                class="form-select"
                                :class="{ 'form-select--error': touchedFields['vehiculo.carga'] && fieldErrors['vehiculo.carga'] }"
                                @blur="markTouched('vehiculo.carga')"
                            >
                                <option value="0">SELECCIONA TIPO DE CARGA</option>
                                <option value="A NO PELIGROSA">A NO PELIGROSA</option>
                                <option value="B PELIGROSA">B PELIGROSA</option>
                                <option value="C MUY PELIGROSA">C MUY PELIGROSA</option>
                            </select>
                            <span v-if="touchedFields['vehiculo.carga'] && fieldErrors['vehiculo.carga']" class="field-error">
                                {{ fieldErrors['vehiculo.carga'] }}
                            </span>
                        </div>
                    </div>
                </section>

                <!-- ==========================================
                     SECCIÓN 4: INFORMACIÓN PÓLIZA A RENOVAR (condicional)
                     ========================================== -->
                <section class="form-section form-section--highlight" v-if="showRenewalSection">
                    <div class="section-header">
                        <h2>Información de Póliza a Renovar</h2>
                        <span class="section-badge section-badge--warning">Datos de la póliza actual</span>
                    </div>

                    <div class="form-grid form-grid--4">
                        <div class="form-group">
                            <label class="form-label required">Compañía Actual</label>
                            <input
                                type="text"
                                v-model="form.renovacion.compania_actual"
                                class="form-input"
                                :class="{ 'form-input--error': touchedFields['renovacion.compania_actual'] && fieldErrors['renovacion.compania_actual'] }"
                                placeholder="Nombre de la aseguradora"
                                @blur="markTouched('renovacion.compania_actual')"
                            />
                            <span v-if="touchedFields['renovacion.compania_actual'] && fieldErrors['renovacion.compania_actual']" class="field-error">
                                {{ fieldErrors['renovacion.compania_actual'] }}
                            </span>
                        </div>

                        <!-- LEGACY: verificar_fecha_vigencia() línea 14282 -->
                        <div class="form-group">
                            <label class="form-label required">Fin de Vigencia</label>
                            <input
                                type="date"
                                v-model="form.renovacion.fecha_vigencia"
                                class="form-input"
                                :class="{ 'form-input--error': touchedFields['renovacion.fecha_vigencia'] && fieldErrors['renovacion.fecha_vigencia'] }"
                                @blur="markTouched('renovacion.fecha_vigencia')"
                            />
                            <span v-if="touchedFields['renovacion.fecha_vigencia'] && fieldErrors['renovacion.fecha_vigencia']" class="field-error">
                                {{ fieldErrors['renovacion.fecha_vigencia'] }}
                            </span>
                        </div>

                        <!-- LEGACY: verificar_poliza_a_renovar() línea 14307 -->
                        <div class="form-group">
                            <label class="form-label required">Póliza a Renovar</label>
                            <input
                                type="text"
                                v-model="form.renovacion.poliza_a_renovar"
                                class="form-input"
                                :class="{ 'form-input--error': touchedFields['renovacion.poliza_a_renovar'] && fieldErrors['renovacion.poliza_a_renovar'] }"
                                placeholder="Número de póliza"
                                @blur="markTouched('renovacion.poliza_a_renovar')"
                            />
                            <span v-if="touchedFields['renovacion.poliza_a_renovar'] && fieldErrors['renovacion.poliza_a_renovar']" class="field-error">
                                {{ fieldErrors['renovacion.poliza_a_renovar'] }}
                            </span>
                        </div>

                        <!-- LEGACY: verificar_prima_año() línea 14341 -->
                        <div class="form-group">
                            <label class="form-label required">Prima del Año Anterior</label>
                            <div class="input-with-prefix">
                                <span class="input-prefix">$</span>
                                <input
                                    type="text"
                                    inputmode="decimal"
                                    :value="form.renovacion.prima_año"
                                    @keydown="onMoneyKeydown"
                                    @input="handleMoneyInput($event, 'prima_año')"
                                    class="form-input form-input--money"
                                    :class="{ 'form-input--error': touchedFields['renovacion.prima_año'] && fieldErrors['renovacion.prima_año'] }"
                                    placeholder="0.00"
                                    @blur="markTouched('renovacion.prima_año')"
                                />
                            </div>
                            <span v-if="touchedFields['renovacion.prima_año'] && fieldErrors['renovacion.prima_año']" class="field-error">
                                {{ fieldErrors['renovacion.prima_año'] }}
                            </span>
                        </div>
                    </div>
                </section>

                <!-- ==========================================
                     SECCIÓN 5: CONFIGURACIÓN DE ASEGURADORAS
                     ========================================== -->
                <section class="form-section">
                    <div class="section-header">
                        <h2>Configuración de Cotización</h2>
                    </div>

                    <div class="form-grid form-grid--2">
                        <div class="form-group">
                            <label class="form-label required">Tipo de Paquete</label>
                            <select
                                v-model="form.paquete"
                                class="form-select"
                                :class="{ 'form-select--error': touchedFields.paquete && fieldErrors.paquete }"
                                @blur="markTouched('paquete')"
                            >
                                <option value="0">SELECCIONA PAQUETE</option>
                                <option value="AMPLIA">AMPLIA</option>
                                <option value="LIMITADA">LIMITADA</option>
                                <option value="RESPONSABILIDAD CIVIL">RESPONSABILIDAD CIVIL</option>
                            </select>
                            <span v-if="touchedFields.paquete && fieldErrors.paquete" class="field-error">
                                {{ fieldErrors.paquete }}
                            </span>
                        </div>

                        <!-- LEGACY: cotizacion_autos.txt líneas 384-391 -->
                        <div class="form-group">
                            <label class="form-label required">Cantidad de Aseguradoras</label>
                            <select
                                v-model.number="form.cantidad_aseguradoras"
                                class="form-select"
                                :class="{ 'form-select--error': touchedFields.cantidad_aseguradoras && fieldErrors.cantidad_aseguradoras }"
                                @blur="markTouched('cantidad_aseguradoras')"
                            >
                                <option :value="0">SELECCIONA CANTIDAD DE ASEGURADORAS</option>
                                <option :value="1">1</option>
                                <option :value="2">2</option>
                                <option :value="3">3</option>
                                <option :value="4">4</option>
                                <option :value="5">5</option>
                            </select>
                            <span v-if="touchedFields.cantidad_aseguradoras && fieldErrors.cantidad_aseguradoras" class="field-error">
                                {{ fieldErrors.cantidad_aseguradoras }}
                            </span>
                        </div>
                    </div>
                </section>

                <!-- ==========================================
                     SECCIÓN 6: TABLA DE COBERTURAS
                     ========================================== -->
                <section class="form-section form-section--full" v-if="enabledColumns > 0">
                    <div class="section-header">
                        <h2>Desglose de Coberturas y Prima</h2>
                        <span class="section-badge">{{ enabledColumns }} aseguradora(s) configurada(s)</span>
                    </div>

                    <CoverageTable
                        v-model="form.coverages"
                        v-model:customCoverage1Name="form.custom_coverage_1_name"
                        v-model:customCoverage2Name="form.custom_coverage_2_name"
                        :insurers="insurers"
                        :enabledColumns="enabledColumns"
                        :packageType="form.paquete"
                        :paymentFrequency="form.coverages.forma_de_pago || 'ANUAL'"
                        :coverageErrors="coverageErrorKeys"
                        @insurerChanged="handleInsurerChanged"
                    />
                </section>

                <!-- Mensaje si no hay columnas habilitadas -->
                <section class="form-section" v-else>
                    <div class="empty-state">
                        <div class="empty-state__icon">📊</div>
                        <h3 class="empty-state__title">Tabla de Coberturas</h3>
                        <p class="empty-state__text">
                            Seleccione el tipo de paquete y la cantidad de aseguradoras
                            para habilitar la tabla de coberturas.
                        </p>
                    </div>
                </section>

                <!-- ==========================================
                     SECCIÓN 7: NOTAS INTERNAS
                     ========================================== -->
                <section class="form-section">
                    <div class="section-header">
                        <h2>Notas Internas</h2>
                        <span class="section-badge section-badge--muted">Opcional - No visible para el cliente</span>
                    </div>

                    <div class="form-group">
                        <textarea
                            v-model="form.notas"
                            class="form-textarea"
                            rows="3"
                            placeholder="Notas adicionales sobre esta cotización..."
                        ></textarea>
                    </div>
                </section>

                <!-- ==========================================
                     ACCIONES
                     ========================================== -->
                <div class="form-actions">
                    <button type="button" class="btn btn--ghost" @click="cancel">
                        Cancelar
                    </button>

                    <div class="form-actions__right">
                        <button type="button" class="btn btn--secondary" @click="previewPdf">
                            Vista Previa PDF
                        </button>

                        <button
                            type="submit"
                            class="btn btn--primary"
                            :disabled="isSubmitting"
                        >
                            <span v-if="isSubmitting">Guardando...</span>
                            <span v-else>Guardar Cotización</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
</template>

<style scoped>
/* ==========================================
   LAYOUT PRINCIPAL
   ========================================== */
.quote-form {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1rem;
}

.form-header {
    text-align: center;
    padding: 1.5rem;
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    border-radius: 12px 12px 0 0;
    margin-bottom: 0;
}

.form-title {
    color: white;
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0;
    letter-spacing: 0.025em;
}

.form-content {
    background: white;
    border: 1px solid #E5E7EB;
    border-top: none;
    border-radius: 0 0 12px 12px;
    padding: 1.5rem;
}

/* ==========================================
   SECCIONES
   ========================================== */
.form-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #E5E7EB;
}

.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 1rem;
}

.form-section--highlight {
    background: #FEF3C7;
    margin: 0 -1.5rem 2rem;
    padding: 1.5rem;
    border-bottom: 2px solid #F59E0B;
}

.form-section--full {
    margin: 0 -1.5rem 2rem;
    padding: 0 1.5rem 1.5rem;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.section-header h2 {
    font-size: 1rem;
    font-weight: 700;
    color: #1F2937;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.section-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    background: #EFF6FF;
    color: #1D4ED8;
    border-radius: 9999px;
    font-weight: 500;
}

.section-badge--warning {
    background: #FEF3C7;
    color: #92400E;
}

.section-badge--muted {
    background: #F3F4F6;
    color: #6B7280;
}

/* ==========================================
   GRIDS
   ========================================== */
.form-grid {
    display: grid;
    gap: 1rem;
}

.form-grid--2 {
    grid-template-columns: repeat(2, 1fr);
}

.form-grid--3 {
    grid-template-columns: repeat(3, 1fr);
}

.form-grid--4 {
    grid-template-columns: repeat(4, 1fr);
}

@media (max-width: 1024px) {
    .form-grid--4 {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .form-grid--2,
    .form-grid--3,
    .form-grid--4 {
        grid-template-columns: 1fr;
    }
}

/* ==========================================
   FORM GROUPS
   ========================================== */
.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.375rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.form-label.required::after {
    content: ' *';
    color: #DC2626;
}

/* ==========================================
   INPUTS Y SELECTS
   ========================================== */
.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 0.625rem 0.875rem;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    font-size: 0.875rem;
    color: #1F2937;
    background: white;
    transition: all 0.15s ease;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: #7B2D3B;
    box-shadow: 0 0 0 3px rgba(123, 45, 59, 0.1);
}

.form-input--readonly {
    background: #F9FAFB;
    color: #6B7280;
    cursor: not-allowed;
}

/* Estados de error */
.form-input--error,
.form-select--error {
    border-color: #DC2626;
    background: #FEF2F2;
}

.form-input--error:focus,
.form-select--error:focus {
    border-color: #DC2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

/* Mensaje de error debajo del campo */
.field-error {
    display: block;
    margin-top: 0.375rem;
    font-size: 0.75rem;
    color: #DC2626;
    font-weight: 500;
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
}

/* Input con prefijo */
.input-with-prefix {
    position: relative;
    display: flex;
    align-items: center;
}

.input-prefix {
    position: absolute;
    left: 0.875rem;
    color: #6B7280;
    font-weight: 500;
    pointer-events: none;
}

.form-input--money {
    padding-left: 1.75rem;
    text-align: right;
    font-family: 'JetBrains Mono', 'Consolas', monospace;
}

/* ==========================================
   EMPTY STATE
   ========================================== */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: #F9FAFB;
    border: 2px dashed #D1D5DB;
    border-radius: 12px;
}

.empty-state__icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.empty-state__title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #374151;
    margin: 0 0 0.5rem;
}

.empty-state__text {
    font-size: 0.875rem;
    color: #6B7280;
    margin: 0;
    max-width: 400px;
    margin: 0 auto;
}

/* ==========================================
   ACCIONES
   ========================================== */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1.5rem;
    border-top: 1px solid #E5E7EB;
    margin-top: 1rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.form-actions__right {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

/* ==========================================
   BOTONES
   ========================================== */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn--primary {
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: white;
}

.btn--primary:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(123, 45, 59, 0.3);
}

.btn--primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.btn--secondary {
    background: white;
    color: #374151;
    border: 1px solid #D1D5DB;
}

.btn--secondary:hover {
    background: #F9FAFB;
    border-color: #7B2D3B;
    color: #7B2D3B;
}

.btn--ghost {
    background: transparent;
    color: #6B7280;
}

.btn--ghost:hover {
    color: #DC2626;
    background: #FEE2E2;
}

/* ==========================================
   RESPONSIVE
   ========================================== */
@media (max-width: 640px) {
    .quote-form {
        padding: 0.5rem;
    }

    .form-header {
        padding: 1rem;
    }

    .form-title {
        font-size: 1.125rem;
    }

    .form-content {
        padding: 1rem;
    }

    .form-section--highlight,
    .form-section--full {
        margin: 0 -1rem 1.5rem;
        padding: 1rem;
    }

    .form-actions {
        flex-direction: column;
    }

    .form-actions__right {
        width: 100%;
        justify-content: stretch;
    }

    .form-actions__right .btn {
        flex: 1;
    }
}
</style>
