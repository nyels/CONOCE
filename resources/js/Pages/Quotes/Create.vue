<!-- resources/js/Pages/Quotes/Create.vue -->
<script setup>
import { ref, computed, watch, reactive } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { FormInput } from '@/Components/Crud';

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

// Vista previa PDF (Borrador)
const previewDraft = async () => {
    if (manualOptions.value.length === 0) {
        alert('Agrega al menos una opción de aseguradora para ver la vista previa.');
        return;
    }
    
    // Crear formulario para enviar por POST
    const formData = {
        customer_name: selectedCustomer.value?.name || form.new_customer?.name || 'Cliente Prospecto',
        vehicle: form.vehicle,
        options: manualOptions.value.map(opt => ({
            insurer_id: opt.insurer_id,
            insurer_name: opt.insurer_name,
            coverage_package: opt.coverage_package,
            payment_frequency: opt.payment_frequency,
            net_premium: opt.net_premium,
            policy_fee: opt.policy_fee,
            iva: opt.iva,
            total: opt.total,
        })),
    };
    
    // Crear form hidden y enviarlo en nueva ventana
    const form_elem = document.createElement('form');
    form_elem.method = 'POST';
    form_elem.action = route('quotes.preview-draft');
    form_elem.target = '_blank';
    
    // CSRF Token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
    form_elem.appendChild(csrfInput);
    
    // Data como JSON
    const dataInput = document.createElement('input');
    dataInput.type = 'hidden';
    dataInput.name = 'data';
    dataInput.value = JSON.stringify(formData);
    form_elem.appendChild(dataInput);
    
    // Añadir campos individuales para validación Laravel
    Object.entries(formData).forEach(([key, value]) => {
        if (typeof value === 'object') {
            if (Array.isArray(value)) {
                value.forEach((item, index) => {
                    Object.entries(item).forEach(([subKey, subValue]) => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `${key}[${index}][${subKey}]`;
                        input.value = subValue ?? '';
                        form_elem.appendChild(input);
                    });
                });
            } else {
                Object.entries(value).forEach(([subKey, subValue]) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `${key}[${subKey}]`;
                    input.value = subValue ?? '';
                    form_elem.appendChild(input);
                });
            }
        } else {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value ?? '';
            form_elem.appendChild(input);
        }
    });
    
    document.body.appendChild(form_elem);
    form_elem.submit();
    document.body.removeChild(form_elem);
};
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

            <!-- Navigation Bar (Top) -->
            <div class="wizard__nav">
                <button v-if="currentStep > 1" class="btn btn--secondary btn--sm" @click="prevStep">
                    ← Anterior
                </button>
                <div v-else class="btn-placeholder"></div>
                
                <span class="nav-indicator">Paso {{ currentStep }} de {{ totalSteps }}</span>
                
                <button v-if="currentStep < totalSteps" class="btn btn--primary btn--sm" :disabled="!canGoNext" @click="nextStep">
                    Siguiente →
                </button>
                <button v-else class="btn btn--ghost btn--sm" @click="cancel">
                    Cancelar
                </button>
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
                        <FormInput
                            v-model="form.new_customer.name"
                            label="Nombre completo"
                            placeholder="Juan Pérez García"
                            required
                        />
                        <div class="form-row-inline">
                            <FormInput
                                v-model="form.new_customer.phone"
                                label="Teléfono"
                                placeholder="999 123 4567"
                                mask="phone"
                            />
                            <FormInput
                                v-model="form.new_customer.email"
                                label="Email"
                                type="email"
                                placeholder="cliente@email.com"
                            />
                        </div>
                        <FormInput
                            v-model="form.new_customer.rfc"
                            label="RFC"
                            placeholder="XXXX000000XXX"
                            mask="rfc"
                        />
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
                <div v-if="currentStep === 4" class="step step--capture">
                    <!-- Main Title Centered -->
                    <div class="capture-header">
                        <h2 class="capture-title">📊 Captura de Cotizaciones</h2>
                        <p class="capture-subtitle">Comparativa de opciones de aseguradoras</p>
                    </div>

                    <!-- Add New Option Card -->
                    <div class="add-option-card">
                        <h3 class="add-option-title">➕ Agregar Nueva Opción</h3>
                        
                        <div class="add-option-form">
                            <div class="form-row">
                                <div class="form-group form-group--large">
                                    <label class="form-label">Aseguradora</label>
                                    <select v-model="newOption.insurer_id" class="form-select">
                                        <option value="">Seleccionar aseguradora...</option>
                                        <option v-for="ins in insurers" :key="ins.id" :value="ins.id">{{ ins.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group form-group--large">
                                    <label class="form-label">Paquete / Cobertura</label>
                                    <select v-model="newOption.coverage_package" class="form-select">
                                        <option v-for="pkg in coveragePackages" :key="pkg.id" :value="pkg.code">{{ pkg.name }}</option>
                                        <option value="full">Cobertura Amplia</option>
                                        <option value="limited">Cobertura Limitada</option>
                                        <option value="liability_only">Responsabilidad Civil</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row form-row--three">
                                <div class="form-group">
                                    <label class="form-label">Forma de Pago</label>
                                    <select v-model="newOption.payment_frequency" class="form-select">
                                        <option value="ANNUAL">Pago Anual</option>
                                        <option value="SEMIANNUAL">Pago Semestral</option>
                                        <option value="QUARTERLY">Pago Trimestral</option>
                                        <option value="MONTHLY">Pago Mensual</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Prima Neta</label>
                                    <div class="input-wrapper">
                                        <span class="input-prefix">$</span>
                                        <input type="number" v-model.number="newOption.net_premium" class="form-input form-input--currency" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Derecho de Póliza</label>
                                    <div class="input-wrapper">
                                        <span class="input-prefix">$</span>
                                        <input type="number" v-model.number="newOption.policy_fee" class="form-input form-input--currency" placeholder="0.00">
                                    </div>
                                </div>
                            </div>

                            <div class="totals-row">
                                <div class="total-item">
                                    <span class="total-label">IVA (16%)</span>
                                    <span class="total-value">{{ formatCurrency(newOption.iva) }}</span>
                                </div>
                                <div class="total-item total-item--primary">
                                    <span class="total-label">Total a Pagar</span>
                                    <span class="total-value total-value--large">{{ formatCurrency(newOption.total) }}</span>
                                </div>
                            </div>

                            <button 
                                @click="addOption" 
                                :disabled="!newOption.insurer_id || newOption.net_premium <= 0"
                                class="btn-add-option"
                            >
                                <span>✓</span> Agregar a Comparativa
                            </button>
                        </div>
                    </div>

                    <!-- Options Table (Below the add button) -->
                    <div class="options-list-section" v-if="manualOptions.length > 0">
                        <h4 class="options-list-title">
                            Opciones Agregadas 
                            <span class="options-count">{{ manualOptions.length }}</span>
                        </h4>
                        
                        <div class="options-table-wrapper">
                            <table class="options-table">
                                <thead>
                                    <tr>
                                        <th>Aseguradora</th>
                                        <th>Paquete</th>
                                        <th>Forma de Pago</th>
                                        <th class="text-right">Prima Neta</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(opt, idx) in manualOptions" :key="idx">
                                        <td>
                                            <div class="insurer-cell">
                                                <img v-if="opt.logo_url && !opt.logo_url.includes('ui-avatars')" 
                                                     :src="opt.logo_url" 
                                                     :alt="opt.insurer_name" 
                                                     class="insurer-logo" />
                                                <div v-else class="insurer-avatar">{{ opt.insurer_name.substring(0, 2).toUpperCase() }}</div>
                                                <span class="insurer-name">{{ opt.insurer_name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="package-badge">{{ { full: 'Cobertura Amplia', limited: 'Cobertura Limitada', liability_only: 'Responsabilidad Civil' }[opt.coverage_package] || opt.coverage_package }}</span>
                                        </td>
                                        <td>{{ { ANNUAL: 'Anual', SEMIANNUAL: 'Semestral', QUARTERLY: 'Trimestral', MONTHLY: 'Mensual' }[opt.payment_frequency] }}</td>
                                        <td class="text-right font-mono">{{ formatCurrency(opt.net_premium) }}</td>
                                        <td class="text-right font-mono font-bold text-primary">{{ formatCurrency(opt.total) }}</td>
                                        <td class="text-center">
                                            <button @click="removeOption(idx)" class="btn-delete" title="Eliminar">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.519.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="empty-options">
                        <div class="empty-options__icon">📋</div>
                        <p class="empty-options__text">Aún no has agregado opciones de cotización</p>
                        <p class="empty-options__hint">Completa el formulario de arriba para agregar aseguradoras</p>
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
                        <div v-for="opt in manualOptions" :key="opt.id" class="summary-option">
                            <span>{{ opt.insurer_name }} - {{ { full: 'Amplia', limited: 'Limitada', liability_only: 'RC' }[opt.coverage_package] || opt.coverage_package }}</span>
                            <span class="summary-option__price">{{ formatCurrency(opt.total) }}</span>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn btn--secondary" @click="previewDraft">📄 Vista Previa PDF</button>
                        <button class="btn btn--primary" @click="submit">✓ Finalizar Cotización</button>
                    </div>
                </div>
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

/* Top Navigation */
.wizard__nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    background: #f8fafc;
    border-radius: 12px;
    margin-bottom: 1rem;
    border: 1px solid #e2e8f0;
}

.nav-indicator {
    font-size: 0.8125rem;
    font-weight: 600;
    color: #64748b;
}

.btn-placeholder { width: 100px; }

.btn--sm {
    padding: 0.5rem 1rem;
    font-size: 0.8125rem;
}

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
.form-row-inline { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
@media (max-width: 640px) { .form-row-inline { grid-template-columns: 1fr; } }

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

/* ===== STEP 4: CAPTURE ===== */
.step--capture { padding: 0; }

.capture-header {
    text-align: center;
    padding: 1.5rem 1rem;
    border-bottom: 1px solid #E5E7EB;
    margin-bottom: 1.5rem;
}

.capture-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #1e293b;
    margin: 0 0 0.25rem;
}

.capture-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
}

/* Add Option Card */
.add-option-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.add-option-title {
    text-align: center;
    font-size: 1rem;
    font-weight: 700;
    color: #334155;
    margin: 0 0 1.25rem;
    padding-bottom: 1rem;
    border-bottom: 1px dashed #cbd5e1;
}

.add-option-form {}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-row--three {
    grid-template-columns: repeat(3, 1fr);
}

@media (max-width: 768px) {
    .form-row, .form-row--three { grid-template-columns: 1fr; }
}

.form-group { display: flex; flex-direction: column; }
.form-group--large { grid-column: span 1; }

.form-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.375rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.form-select, .form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.9375rem;
    background: white;
    transition: all 0.15s;
}

.form-select:focus, .form-input:focus {
    outline: none;
    border-color: #7B2D3B;
    box-shadow: 0 0 0 3px rgba(123, 45, 59, 0.1);
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-prefix {
    position: absolute;
    left: 1rem;
    color: #94a3b8;
    font-weight: 500;
}

.form-input--currency {
    padding-left: 2rem;
    text-align: right;
    font-family: 'JetBrains Mono', monospace;
}

.totals-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.total-item { display: flex; justify-content: space-between; align-items: center; }
.total-item--primary { padding-left: 1rem; border-left: 2px solid #7B2D3B; }

.total-label { font-size: 0.8125rem; color: #64748b; font-weight: 500; }
.total-value { font-family: 'JetBrains Mono', monospace; font-weight: 600; color: #334155; }
.total-value--large { font-size: 1.25rem; color: #7B2D3B; font-weight: 800; }

.btn-add-option {
    width: 100%;
    padding: 0.875rem 1.5rem;
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 0.9375rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-add-option:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(123, 45, 59, 0.3);
}

.btn-add-option:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Options Table */
.options-list-section {
    margin-top: 1rem;
}

.options-list-title {
    font-size: 0.9375rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.options-count {
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: white;
    padding: 0.125rem 0.625rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 700;
}

.options-table-wrapper {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

.options-table {
    width: 100%;
    border-collapse: collapse;
}

.options-table th {
    background: #f8fafc;
    padding: 0.75rem 1rem;
    font-size: 0.6875rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.options-table td {
    padding: 0.875rem 1rem;
    font-size: 0.875rem;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
}

.options-table tr:last-child td { border-bottom: none; }
.options-table tr:hover { background: #fafafa; }

.insurer-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.insurer-avatar {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.6875rem;
    font-weight: 700;
}

.insurer-logo {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #e2e8f0;
}

.insurer-name { font-weight: 600; }

.package-badge {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    background: #f1f5f9;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    color: #475569;
}

.text-primary { color: #7B2D3B !important; }
.font-mono { font-family: 'JetBrains Mono', monospace; }
.text-right { text-align: right; }
.text-center { text-align: center; }
.font-bold { font-weight: 700; }

.btn-delete {
    width: 32px;
    height: 32px;
    background: #fee2e2;
    border: none;
    border-radius: 8px;
    color: #dc2626;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s;
}

.btn-delete:hover {
    background: #dc2626;
    color: white;
}

/* Empty State */
.empty-options {
    text-align: center;
    padding: 3rem 2rem;
    background: #f8fafc;
    border: 2px dashed #e2e8f0;
    border-radius: 16px;
    margin-top: 1rem;
}

.empty-options__icon {
    font-size: 3rem;
    margin-bottom: 0.75rem;
}

.empty-options__text {
    font-size: 1rem;
    font-weight: 600;
    color: #475569;
    margin: 0 0 0.25rem;
}

.empty-options__hint {
    font-size: 0.875rem;
    color: #94a3b8;
    margin: 0;
}
</style>
