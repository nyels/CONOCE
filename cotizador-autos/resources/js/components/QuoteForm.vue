<template>
    <div class="app-container">
        <aside class="sidebar">
            <div class="step" :class="{ active: currentStep === 1 }"><span>1</span> Cliente</div>
            <div class="step" :class="{ active: currentStep === 2 }"><span>2</span> Vehículo</div>
            <div class="step" :class="{ active: currentStep === 3 }"><span>3</span> Comparativa</div>
        </aside>

        <main class="content-area">
            <header class="main-header">
                <img :src="'/logo.png'" class="brand-logo">
                <div class="header-info">
                    <h1>Nuevo Prospecto</h1>
                    <p>Folio: <span class="folio-badge">{{ form.folio }}</span></p>
                </div>
            </header>

            <div class="card glass-card">
                <section v-if="currentStep === 1" class="fade-in">
                    <h3 class="section-title">Información del Asegurado</h3>
                    <div class="form-grid">
                        <div class="group">
                            <label>Tipo de Persona</label>
                            <select v-model="form.customer.type" class="modern-input">
                                <option value="F">Persona Física</option>
                                <option value="M">Persona Moral</option>
                            </select>
                        </div>
                        <div class="group full">
                            <label>{{ form.customer.type === 'F' ? 'Nombre Completo' : 'Razón Social' }}</label>
                            <input v-model="form.customer.name" type="text" class="modern-input" placeholder="Nombre que aparecerá en el PDF">
                        </div>
                        <div class="group">
                            <label>Correo Electrónico</label>
                            <input v-model="form.customer.email" type="email" class="modern-input">
                        </div>
                        <div class="group">
                            <label>Teléfono / WhatsApp</label>
                            <input v-model="form.customer.phone" type="text" class="modern-input">
                        </div>
                    </div>
                    <div class="actions">
                        <button @click="currentStep = 2" class="btn-next">Siguiente: Vehículo →</button>
                    </div>
                </section>

                <section v-if="currentStep === 2" class="fade-in">
                    <h3 class="section-title">Detalles de la Unidad</h3>
                    <div class="form-grid">
                        <div class="group">
                            <label>Marca</label>
                            <input v-model="form.vehicle.brand" type="text" class="modern-input">
                        </div>
                        <div class="group">
                            <label>Modelo / Descripción</label>
                            <input v-model="form.vehicle.model" type="text" class="modern-input">
                        </div>
                        <div class="group">
                            <label>Año</label>
                            <input v-model="form.vehicle.year" type="number" class="modern-input">
                        </div>
                    </div>
                    <div class="actions">
                        <button @click="currentStep = 1" class="btn-back">← Volver</button>
                        <button @click="currentStep = 3" class="btn-next">Siguiente: Comparativa →</button>
                    </div>
                </section>

                <section v-if="currentStep === 3" class="fade-in">
                    <div class="flex-between">
                        <h3 class="section-title">Captura de Coberturas y Costos</h3>
                        <div class="table-tools">
                            <button @click="addInsurer" class="btn-tool">+ Aseguradora</button>
                            <button @click="addCoverage" class="btn-tool">+ Cobertura</button>
                        </div>
                    </div>

                    <div class="table-wrapper">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th class="sticky-col">Cobertura</th>
                                    <th v-for="(ins, idx) in form.insurers" :key="idx">
                                        <div class="ins-header">
                                            <select v-model="ins.id" class="mini-select">
                                                <option v-for="o in insurersList" :value="o.id">{{ o.name }}</option>
                                            </select>
                                            <button @click="removeInsurer(idx)" class="btn-close">×</button>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(cov, cIdx) in form.coverages" :key="cIdx">
                                    <td class="sticky-col">
                                        <input v-model="cov.name" class="cell-input bold" placeholder="Ej. Robo Total">
                                    </td>
                                    <td v-for="(ins, iIdx) in form.insurers" :key="iIdx">
                                        <input v-model="ins.values[cIdx]" class="cell-input text-center" placeholder="Suma/Ded.">
                                    </td>
                                </tr>
                                <tr class="divider"><td :colspan="form.insurers.length + 1">COSTOS TOTALES</td></tr>
                                <tr>
                                    <td class="sticky-col bold">Prima Neta</td>
                                    <td v-for="ins in form.insurers"><input v-model.number="ins.net" type="number" class="cell-input cost"></td>
                                </tr>
                                <tr>
                                    <td class="sticky-col bold">Derechos</td>
                                    <td v-for="ins in form.insurers"><input v-model.number="ins.fee" type="number" class="cell-input cost"></td>
                                </tr>
                                <tr class="total-row">
                                    <td class="sticky-col bold">TOTAL (IVA Inc.)</td>
                                    <td v-for="ins in form.insurers" class="text-center">
                                        ${{ calculateTotal(ins) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="actions">
                        <button @click="currentStep = 2" class="btn-back">← Volver</button>
                        <button @click="submit" class="btn-finish" :disabled="loading">
                            {{ loading ? 'Procesando...' : 'FINALIZAR Y GENERAR PDF' }}
                        </button>
                    </div>
                </section>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';

const currentStep = ref(1);
const loading = ref(false);
const insurersList = ref([{id:1, name:'GNP'}, {id:2, name:'Qualitas'}, {id:3, name:'Chubb'}, {id:4, name:'AXA'}]);

const form = reactive({
    folio: 'COT-' + Math.floor(Math.random() * 900000),
    customer: { type: 'F', name: '', email: '', phone: '' },
    vehicle: { brand: '', model: '', year: 2024 },
    coverages: [{ name: 'Daños Materiales' }, { name: 'Robo Total' }, { name: 'Resp. Civil' }],
    insurers: [
        { id: 1, net: 0, fee: 0, values: ['Valor Comercial', 'Valor Comercial', '$3,000,000'] },
        { id: 2, net: 0, fee: 0, values: ['Valor Comercial', 'Valor Comercial', '$4,000,000'] }
    ]
});

const calculateTotal = (ins) => {
    const sum = (parseFloat(ins.net) || 0) + (parseFloat(ins.fee) || 0);
    return (sum * 1.16).toFixed(2);
};

const addInsurer = () => {
    form.insurers.push({ id: 1, net: 0, fee: 0, values: form.coverages.map(() => '') });
};

const addCoverage = () => {
    form.coverages.push({ name: '' });
    form.insurers.forEach(ins => ins.values.push(''));
};

const submit = async () => {
    loading.value = true;
    try {
        const res = await axios.post('/api/quotes', form);
        window.open(`/api/quotes/${res.data.quote_uuid}/pdf`, '_blank');
    } catch (e) {
        alert("Error al guardar cotización");
    } finally { loading.value = false; }
};
</script>

<style scoped>
/* Estética Modern UI */
.app-container { display: flex; min-height: 100vh; background: #f4f7f6; }
.sidebar { width: 260px; background: #632533; color: white; padding: 40px 20px; }
.step { padding: 15px; margin-bottom: 10px; opacity: 0.5; transition: 0.3s; font-weight: bold; }
.step.active { opacity: 1; transform: translateX(10px); color: #ffeb3b; }
.step span { background: rgba(255,255,255,0.2); border-radius: 50%; width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; }

.content-area { flex: 1; padding: 40px; }
.glass-card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }

.modern-input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: 0.3s; }
.modern-input:focus { border-color: #632533; outline: none; box-shadow: 0 0 0 3px rgba(99, 37, 51, 0.1); }

/* Tabla Profesional */
.table-wrapper { overflow-x: auto; margin-top: 20px; border-radius: 8px; border: 1px solid #eee; }
.modern-table { width: 100%; border-collapse: collapse; }
.modern-table th { background: #f9fafb; padding: 15px; border-bottom: 2px solid #eee; }
.cell-input { width: 100%; border: none; padding: 10px; text-align: inherit; background: transparent; }
.cell-input:focus { background: #fffde7; outline: none; }
.cost { text-align: center; font-weight: bold; color: #632533; }
.total-row { background: #632533; color: white; }

.btn-next { background: #632533; color: white; padding: 12px 30px; border-radius: 8px; border: none; cursor: pointer; font-weight: bold; }
.btn-finish { background: #2e7d32; color: white; padding: 12px 40px; border-radius: 8px; border: none; cursor: pointer; font-weight: bold; font-size: 16px; }
</style>