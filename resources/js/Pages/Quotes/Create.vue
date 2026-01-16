<!-- resources/js/Pages/Quotes/Create.vue -->
<script setup>
import { ref, computed, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Simple debounce function (no dependency on lodash)
const debounce = (fn, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
};

// Props desde el backend (QuoteController@create)
const props = defineProps({
    customers: { type: Array, default: () => [] },
    brands: { type: Array, default: () => [] },
    years: { type: Array, default: () => [] },
    insurers: { type: Array, default: () => [] },
    coveragePackages: { type: Array, default: () => [] },
});

// Wizard State
const currentStep = ref(1);
const totalSteps = 5;

// Form Data usando useForm de Inertia
const form = useForm({
    // Step 1: Customer
    customer_id: null,
    new_customer: { name: '', email: '', phone: '', rfc: '', type: 'physical' },
    
    // Step 2: Vehicle
    quote_type: 'new',
    vehicle: { brand: '', model: '', year: '', version: '', value: '', usage: 'personal' },
    renewal: { insurer: '', policy_number: '', previous_premium: '', expires_at: '' },
    
    // Step 3: Coverages
    coverage_package: 'standard',
    
    // Step 4: Insurers (seleccionadas)
    options: [],
    
    // Step 5: Summary
    validity_days: 7,
    notes: ''
});

// Estado local para UI
const customerSearch = ref('');
const isNewCustomer = ref(false);
const selectedCustomer = ref(null);
const filteredCustomers = ref([...props.customers]);

// Búsqueda de clientes con debounce
const searchCustomers = debounce(async (term) => {
    if (!term || term.length < 2) {
        filteredCustomers.value = props.customers;
        return;
    }
    try {
        const response = await fetch(route('quotes.search-customers', { q: term }));
        filteredCustomers.value = await response.json();
    } catch (error) {
        console.error('Error buscando clientes:', error);
    }
}, 300);

watch(customerSearch, (term) => searchCustomers(term));

// Estado para captura manual de opciones (Paso 4)
const manualOptions = ref([]);
const newOption = reactive({
    insurer_id: '',
    coverage_package: 'limited', // Default
    payment_frequency: 'ANNUAL',
    net_premium: 0,
    policy_fee: 400, // Valor común por default
    iva: 0,
    total: 0
});

// Watch para calcular IVA y Total automáticamente al escribir
watch(
    () => [newOption.net_premium, newOption.policy_fee],
    ([net, fee]) => {
        newOption.iva = (net + fee) * 0.16; // 16% IVA
        newOption.total = net + fee + newOption.iva;
    }
);

const addOption = () => {
    if (!newOption.insurer_id) return;
    
    const insurer = props.insurers.find(i => i.id === newOption.insurer_id);
    manualOptions.value.push({
        ...newOption,
        insurer_name: insurer.name,
        logo_url: insurer.logo_url,
        id: Date.now() // Temp ID
    });
    
    // Reset (manteniendo algunos defaults)
    newOption.insurer_id = '';
    newOption.net_premium = 0;
};

const removeOption = (index) => {
    manualOptions.value.splice(index, 1);
};


// Navigation
const canGoNext = computed(() => {
    switch (currentStep.value) {
        case 1: return selectedCustomer.value || isNewCustomer.value;
        case 2: return form.vehicle.brand && form.vehicle.year;
        case 3: return form.coverage_package;
        case 4: return manualOptions.value.length > 0;
        default: return true;
    }
});

const nextStep = () => { if (currentStep.value < totalSteps) currentStep.value++; };
const prevStep = () => { if (currentStep.value > 1) currentStep.value--; };
const goToStep = (step) => { if (step <= currentStep.value) currentStep.value = step; };

const selectCustomer = (customer) => { 
    selectedCustomer.value = customer; 
    form.customer_id = customer.id;
    form.new_customer = { name: '', email: '', phone: '', rfc: '', type: 'physical' }; // Limpiar datos de nuevo cliente
    isNewCustomer.value = false; 
};

const enableNewCustomer = () => {
    selectedCustomer.value = null;
    form.customer_id = null;
    isNewCustomer.value = true;
};

const formatCurrency = (v) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', minimumFractionDigits: 2 }).format(v);

const submit = () => {
    // Construir opciones seleccionadas
    form.options = manualOptions.value.map(opt => ({
        insurer_id: opt.insurer_id,
        coverage_package: opt.coverage_package,
        payment_frequency: opt.payment_frequency,
        net_premium: opt.net_premium,
        policy_fee: opt.policy_fee,
        iva: opt.iva,
        total_premium: opt.total,
        selected: true // Todas las manuales se guardan como opciones válidas
    }));

    form.post(route('quotes.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // Redirección manejada por el controller
        },
        onError: (errors) => {
            console.error('Errores de validación:', errors);
        }
    });
};

const cancel = () => { router.visit(route('dashboard')); };
</script>

<template>
    <AppLayout>
        <div class="wizard">
            <!-- Progress -->
            <div class="wizard__progress">
                <div 
                    v-for="step in totalSteps" 
                    :key="step"
                    class="progress-step"
                    :class="{ 
                        'progress-step--active': step === currentStep,
                        'progress-step--done': step < currentStep 
                    }"
                    @click="goToStep(step)">
                    <div class="progress-step__number">
                        <span v-if="step < currentStep">✓</span>
                        <span v-else>{{ step }}</span>
                    </div>
                    <div class="progress-step__label">
                        {{ ['Cliente', 'Vehículo', 'Coberturas', 'Opciones', 'Resumen'][step - 1] }}
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="wizard__content">
                <!-- Step 1: Customer -->
                <div v-if="currentStep === 1" class="step">
                    <h2 class="step__title">👤 Seleccionar o Crear Cliente</h2>
                    
                    <div class="search-box">
                        <input v-model="customerSearch" type="text" placeholder="Buscar cliente..." class="search-box__input">
                    </div>
                    
                    <div class="customer-list">
                        <div 
                            v-for="c in filteredCustomers" 
                            :key="c.id"
                            class="customer-card"
                            :class="{ 'customer-card--selected': selectedCustomer?.id === c.id }"
                            @click="selectCustomer(c)">
                            <div class="customer-card__name">{{ c.name }}</div>
                            <div class="customer-card__phone">{{ c.phone }}</div>
                            <div class="customer-card__quotes">{{ c.quotes }} cot. previas</div>
                        </div>
                    </div>
                    
                    <div class="divider">─── O ───</div>
                    
                    <button class="btn btn--outline" @click="enableNewCustomer">
                        + Crear Nuevo Cliente
                    </button>
                    
                    <div v-if="isNewCustomer" class="new-customer-form">
                        <input v-model="form.new_customer.name" type="text" placeholder="Nombre completo" class="form-input">
                        <input v-model="form.new_customer.phone" type="tel" placeholder="Teléfono" class="form-input">
                        <input v-model="form.new_customer.email" type="email" placeholder="Email" class="form-input">
                    </div>
                </div>

                <!-- Step 2: Vehicle -->
                <div v-if="currentStep === 2" class="step">
                    <h2 class="step__title">🚗 Datos del Vehículo</h2>
                    
                    <div class="radio-group">
                        <label class="radio-option" :class="{ 'radio-option--active': form.quote_type === 'new' }">
                            <input type="radio" v-model="form.quote_type" value="new"> Nueva
                        </label>
                        <label class="radio-option" :class="{ 'radio-option--active': form.quote_type === 'renewal' }">
                            <input type="radio" v-model="form.quote_type" value="renewal"> Renovación
                        </label>
                    </div>
                    
                    <div class="form-grid">
                        <select v-model="form.vehicle.brand" class="form-input">
                            <option value="">Marca</option>
                            <option v-for="b in props.brands" :key="b" :value="b">{{ b }}</option>
                        </select>
                        <input v-model="form.vehicle.model" type="text" placeholder="Modelo/Línea" class="form-input">
                        <select v-model="form.vehicle.year" class="form-input">
                            <option value="">Año</option>
                            <option v-for="y in props.years" :key="y" :value="y">{{ y }}</option>
                        </select>
                        <input v-model="form.vehicle.value" type="number" placeholder="Valor factura $" class="form-input">
                    </div>
                </div>

                <!-- Step 3: Coverages -->
                <div v-if="currentStep === 3" class="step">
                    <h2 class="step__title">🛡️ Seleccionar Paquete</h2>
                    
                    <div class="package-grid">
                        <div 
                            v-for="pkg in ['basic', 'standard', 'premium']"
                            :key="pkg"
                            class="package-card"
                            :class="{ 'package-card--selected': form.coverage_package === pkg }"
                            @click="form.coverage_package = pkg">
                            <div class="package-card__icon">{{ { basic: '📦', standard: '⭐', premium: '💎' }[pkg] }}</div>
                            <div class="package-card__name">{{ { basic: 'Básico', standard: 'Amplio', premium: 'Premium' }[pkg] }}</div>
                            <div class="package-card__features">
                                <div v-if="pkg !== 'basic'">✓ Robo total</div>
                                <div v-if="pkg !== 'basic'">✓ Daños materiales</div>
                                <div v-if="pkg === 'premium'">✓ Auto sustituto</div>
                                <div v-if="pkg === 'premium'">✓ 0 deducible</div>
                            </div>
                            <div v-if="pkg === 'standard'" class="package-card__badge">Recomendado</div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Insurers -->
                <div v-if="currentStep === 4" class="step">
                    <h2 class="step__title">📊 Captura de Cotizaciones (Comparativa)</h2>
                    
                    <!-- Tabla de Opciones Agregadas -->
                    <div class="table-container" v-if="manualOptions.length > 0">
                        <table class="quote-table">
                            <thead>
                                <tr>
                                    <th>Aseguradora</th>
                                    <th>Paquete</th>
                                    <th>Pago</th>
                                    <th class="text-right">Prima Neta</th>
                                    <th class="text-right">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(opt, idx) in manualOptions" :key="idx">
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <img v-if="opt.logo_url" :src="opt.logo_url" class="h-6 w-6 object-contain">
                                            <span>{{ opt.insurer_name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ opt.coverage_package }}</td>
                                    <td>{{ { ANNUAL: 'Anual', SEMIANNUAL: 'Semestral', QUARTERLY: 'Trimestral', MONTHLY: 'Mensual' }[opt.payment_frequency] }}</td>
                                    <td class="text-right">{{ formatCurrency(opt.net_premium) }}</td>
                                    <td class="text-right font-bold">{{ formatCurrency(opt.total) }}</td>
                                    <td class="text-right">
                                        <button @click="removeOption(idx)" class="btn-icon text-red-500">🗑️</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-else class="empty-state">
                        <p>No has agregado cotizaciones aún.</p>
                    </div>

                    <div class="divider"></div>

                    <!-- Formulario de Captura Manual -->
                    <div class="manual-entry-card fade-in">
                        <h3 class="font-bold mb-4">➕ Agregar Nueva Opción</h3>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="form-group">
                                <label>Aseguradora</label>
                                <select v-model="newOption.insurer_id" class="input">
                                    <option value="">Seleccionar...</option>
                                    <option v-for="ins in insurers" :key="ins.id" :value="ins.id">{{ ins.name }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Paquete / Cobertura</label>
                                <select v-model="newOption.coverage_package" class="input">
                                    <option v-for="pkg in coveragePackages" :key="pkg.id" :value="pkg.code">{{ pkg.name }}</option>
                                    <!-- Fallback si no hay paquetes cargados -->
                                    <option value="full">Amplia</option>
                                    <option value="limited">Limitada</option>
                                    <option value="liability_only">RC</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-4">
                             <div class="form-group">
                                <label>Forma de Pago</label>
                                <select v-model="newOption.payment_frequency" class="input">
                                    <option value="ANNUAL">Anual</option>
                                    <option value="SEMIANNUAL">Semestral</option>
                                    <option value="QUARTERLY">Trimestral</option>
                                    <option value="MONTHLY">Mensual</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Prima Neta</label>
                                <input type="number" v-model.number="newOption.net_premium" class="input text-right" placeholder="0.00">
                            </div>
                            <div class="form-group">
                                <label>Der. Póliza</label>
                                <input type="number" v-model.number="newOption.policy_fee" class="input text-right" placeholder="0.00">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4 bg-gray-50 p-3 rounded">
                            <div class="form-group">
                                <label>IVA (16%)</label>
                                <div class="text-right font-mono">{{ formatCurrency(newOption.iva) }}</div>
                            </div>
                            <div class="form-group">
                                <label class="font-bold">Total a Pagar</label>
                                <div class="text-right font-bold text-lg text-blue-600">{{ formatCurrency(newOption.total) }}</div>
                            </div>
                        </div>

                        <button 
                            @click="addOption" 
                            :disabled="!newOption.insurer_id || newOption.net_premium <= 0"
                            class="btn btn--primary w-full"
                        >
                            Agregar a Comparativa
                        </button>
                    </div>
                </div>

                <!-- Step 5: Summary -->
                <div v-if="currentStep === 5" class="step">
                    <h2 class="step__title">📄 Resumen y Generación</h2>
                    
                    <div class="summary-grid">
                        <div class="summary-card">
                            <div class="summary-card__title">Cliente</div>
                            <div class="summary-card__value">{{ selectedCustomer?.name || form.new_customer.name || '—' }}</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-card__title">Vehículo</div>
                            <div class="summary-card__value">{{ form.vehicle.brand }} {{ form.vehicle.model }} {{ form.vehicle.year }}</div>
                        </div>
                    </div>
                    
                    <div class="summary-options">
                        <div class="summary-options__title">Opciones seleccionadas</div>
                        <div v-for="ins in insurerOptions.filter(i => i.selected)" :key="ins.id" class="summary-option">
                            <span>{{ ins.name }} {{ { basic: 'Básico', standard: 'Amplio', premium: 'Premium' }[form.coverage_package] }}</span>
                            <span class="summary-option__price">{{ formatCurrency(ins.total) }}</span>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn btn--secondary">📄 Vista Previa PDF</button>
                        <button class="btn btn--primary" @click="submit">✓ Finalizar Cotización</button>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="wizard__footer">
                <button v-if="currentStep > 1" class="btn btn--secondary" @click="prevStep">← Anterior</button>
                <button class="btn btn--ghost" @click="cancel">Cancelar</button>
                <button v-if="currentStep < totalSteps" class="btn btn--primary" :disabled="!canGoNext" @click="nextStep">
                    Siguiente →
                </button>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.wizard { max-width: 900px; margin: 0 auto; padding: 1rem; }

/* Progress */
.wizard__progress { display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 2rem; overflow-x: auto; padding: 0.5rem; }
.progress-step { display: flex; flex-direction: column; align-items: center; gap: 0.375rem; cursor: pointer; opacity: 0.4; transition: 0.2s; min-width: 60px; }
.progress-step--active, .progress-step--done { opacity: 1; }
.progress-step__number { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #E5E7EB; font-weight: 700; font-size: 0.875rem; }
.progress-step--active .progress-step__number { background: #7B2D3B; color: white; }
.progress-step--done .progress-step__number { background: #059669; color: white; }
.progress-step__label { font-size: 0.6875rem; color: #6B7280; text-align: center; }

/* Content */
.wizard__content { background: white; border-radius: 16px; padding: 1.5rem; min-height: 400px; border: 1px solid #E5E7EB; }
.step__title { font-size: 1.25rem; font-weight: 700; margin: 0 0 1.5rem 0; }

/* Forms */
.search-box__input, .form-input { width: 100%; padding: 0.75rem 1rem; border: 1px solid #E5E7EB; border-radius: 10px; font-size: 0.9375rem; margin-bottom: 0.75rem; }
.form-input:focus { outline: none; border-color: #7B2D3B; box-shadow: 0 0 0 3px rgba(123,45,59,0.1); }
.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
@media (max-width: 640px) { .form-grid { grid-template-columns: 1fr; } }

/* Customers */
.customer-list { display: flex; flex-direction: column; gap: 0.5rem; margin: 1rem 0; }
.customer-card { padding: 0.875rem; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; transition: 0.2s; }
.customer-card:hover { border-color: #7B2D3B; }
.customer-card--selected { border-color: #7B2D3B; background: rgba(123,45,59,0.05); }
.customer-card__name { font-weight: 600; }
.customer-card__phone, .customer-card__quotes { font-size: 0.8125rem; color: #6B7280; }

.divider { text-align: center; color: #9CA3AF; margin: 1.5rem 0; }
.new-customer-form { margin-top: 1rem; padding: 1rem; background: #F9FAFB; border-radius: 10px; }

/* Radio */
.radio-group { display: flex; gap: 1rem; margin-bottom: 1.5rem; }
.radio-option { flex: 1; padding: 0.875rem; border: 1px solid #E5E7EB; border-radius: 10px; text-align: center; cursor: pointer; font-weight: 500; }
.radio-option--active { border-color: #7B2D3B; background: rgba(123,45,59,0.05); color: #7B2D3B; }
.radio-option input { display: none; }

/* Packages */
.package-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
@media (max-width: 768px) { .package-grid { grid-template-columns: 1fr; } }
.package-card { padding: 1.25rem; border: 2px solid #E5E7EB; border-radius: 12px; text-align: center; cursor: pointer; position: relative; transition: 0.2s; }
.package-card:hover { border-color: #C7A172; }
.package-card--selected { border-color: #7B2D3B; background: rgba(123,45,59,0.03); }
.package-card__icon { font-size: 2rem; margin-bottom: 0.5rem; }
.package-card__name { font-weight: 700; font-size: 1.125rem; margin-bottom: 0.75rem; }
.package-card__features { font-size: 0.8125rem; color: #6B7280; line-height: 1.6; }
.package-card__badge { position: absolute; top: -10px; right: -10px; background: #C7A172; color: #2D0F16; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.625rem; font-weight: 700; }

/* Insurers */
.insurer-list { display: flex; flex-direction: column; gap: 0.5rem; }
.insurer-row { display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #F9FAFB; border-radius: 10px; border: 1px solid transparent; }
.insurer-row--selected { background: white; border-color: #7B2D3B; }
.insurer-row__check input { width: 18px; height: 18px; accent-color: #7B2D3B; }
.insurer-row__name { flex: 1; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
.insurer-row__premium { font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #7B2D3B; }
.best-badge { background: #FEF3C7; color: #92400E; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.625rem; font-weight: 600; }
.tip { margin-top: 1rem; padding: 0.75rem; background: #EFF6FF; border-radius: 8px; font-size: 0.8125rem; color: #1E40AF; }

/* Summary */
.summary-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
@media (max-width: 640px) { .summary-grid { grid-template-columns: 1fr; } }
.summary-card { padding: 1rem; background: #F9FAFB; border-radius: 10px; }
.summary-card__title { font-size: 0.75rem; color: #6B7280; margin-bottom: 0.25rem; }
.summary-card__value { font-weight: 600; color: #111827; }
.summary-options { padding: 1rem; background: white; border: 1px solid #E5E7EB; border-radius: 10px; margin-bottom: 1.5rem; }
.summary-options__title { font-weight: 600; margin-bottom: 0.75rem; }
.summary-option { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px dashed #E5E7EB; }
.summary-option:last-child { border: none; }
.summary-option__price { font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #7B2D3B; }

/* Actions */
.action-buttons { display: flex; gap: 1rem; flex-wrap: wrap; }

/* Footer */
.wizard__footer { display: flex; justify-content: space-between; gap: 0.75rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #E5E7EB; flex-wrap: wrap; }

/* Buttons */
.btn { padding: 0.625rem 1.25rem; border-radius: 10px; font-weight: 600; font-size: 0.875rem; border: none; cursor: pointer; transition: 0.2s; }
.btn--primary { background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%); color: white; }
.btn--primary:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(123,45,59,0.25); }
.btn--primary:disabled { opacity: 0.5; cursor: not-allowed; }
.btn--secondary { background: white; border: 1px solid #E5E7EB; color: #374151; }
.btn--secondary:hover { border-color: #7B2D3B; color: #7B2D3B; }
.btn--outline { width: 100%; background: transparent; border: 2px dashed #C7A172; color: #7B2D3B; }
.btn--outline:hover { background: rgba(199,161,114,0.1); }
.btn--ghost { background: transparent; color: #6B7280; }
.btn--ghost:hover { color: #7B2D3B; }
</style>
