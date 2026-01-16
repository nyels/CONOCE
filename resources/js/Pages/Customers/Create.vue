<!-- resources/js/Pages/Customers/Create.vue -->
<script setup>
import { Head, router, useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ToastContainer } from '@/Components/Ui';
import { useToast } from '@/composables/useToast';

const toast = useToast();

const form = useForm({
    name: '',
    phone: '',
    email: '',
    rfc: '',
    type: 'physical',
    address: '',
    city: '',
    state: '',
    zip_code: '',
});

const typeOptions = [
    { value: 'physical', label: 'Persona Física' },
    { value: 'moral', label: 'Persona Moral' },
];

const submit = () => {
    form.post(route('customers.store'), {
        onSuccess: () => {
            toast.success('Cliente creado exitosamente');
        },
        onError: () => {
            toast.error('Error al crear el cliente');
        }
    });
};

const cancel = () => {
    router.visit(route('customers.index'));
};
</script>

<template>
    <ToastContainer>
        <Head title="Nuevo Cliente" />

        <AppLayout>
            <div class="page-container">
                <!-- Breadcrumb -->
                <div class="breadcrumb">
                    <Link :href="route('customers.index')" class="breadcrumb-link">Clientes</Link>
                    <span class="breadcrumb-sep">/</span>
                    <span class="breadcrumb-current">Nuevo</span>
                </div>

                <!-- Header -->
                <div class="page-header">
                    <h1 class="page-title">👤 Nuevo Cliente</h1>
                </div>

                <!-- Form Card -->
                <div class="form-card">
                    <form @submit.prevent="submit">
                        <div class="form-section">
                            <h3 class="section-title">Información Personal</h3>

                            <div class="form-group">
                                <label class="form-label">Nombre Completo *</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="form-input"
                                    :class="{ 'form-input--error': form.errors.name }"
                                    placeholder="Juan Pérez García"
                                >
                                <span v-if="form.errors.name" class="form-error">{{ form.errors.name }}</span>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Tipo de Persona</label>
                                    <select v-model="form.type" class="form-input">
                                        <option v-for="opt in typeOptions" :key="opt.value" :value="opt.value">
                                            {{ opt.label }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">RFC</label>
                                    <input
                                        v-model="form.rfc"
                                        type="text"
                                        class="form-input"
                                        placeholder="XXXX000000XXX"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="section-title">Contacto</h3>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Teléfono</label>
                                    <input
                                        v-model="form.phone"
                                        type="tel"
                                        class="form-input"
                                        placeholder="999 123 4567"
                                    >
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        class="form-input"
                                        placeholder="cliente@email.com"
                                    >
                                    <span v-if="form.errors.email" class="form-error">{{ form.errors.email }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="section-title">Dirección</h3>

                            <div class="form-group">
                                <label class="form-label">Dirección</label>
                                <input
                                    v-model="form.address"
                                    type="text"
                                    class="form-input"
                                    placeholder="Calle, número, colonia"
                                >
                            </div>

                            <div class="form-row form-row--3">
                                <div class="form-group">
                                    <label class="form-label">Ciudad</label>
                                    <input v-model="form.city" type="text" class="form-input" placeholder="Mérida">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Estado</label>
                                    <input v-model="form.state" type="text" class="form-input" placeholder="Yucatán">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">C.P.</label>
                                    <input v-model="form.zip_code" type="text" class="form-input" placeholder="97000">
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="form-actions">
                            <button type="button" class="btn btn--secondary" @click="cancel">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn--primary" :disabled="form.processing">
                                {{ form.processing ? 'Guardando...' : 'Crear Cliente' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    </ToastContainer>
</template>

<style scoped>
.page-container {
    padding: 1.5rem;
    max-width: 800px;
    margin: 0 auto;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.breadcrumb-link { color: #6B7280; text-decoration: none; }
.breadcrumb-link:hover { color: #7B2D3B; }
.breadcrumb-sep { color: #D1D5DB; }
.breadcrumb-current { color: #111827; font-weight: 500; }

.page-header { margin-bottom: 1.5rem; }

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.form-card {
    background: white;
    border-radius: 16px;
    border: 1px solid #E5E7EB;
    padding: 2rem;
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #F3F4F6;
}

.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
}

.section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #374151;
    margin: 0 0 1.25rem 0;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.375rem;
}

.form-input {
    width: 100%;
    padding: 0.625rem 0.875rem;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 0.9375rem;
    transition: all 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #7B2D3B;
    box-shadow: 0 0 0 3px rgba(123, 45, 59, 0.1);
}

.form-input--error {
    border-color: #DC2626;
}

.form-error {
    display: block;
    font-size: 0.75rem;
    color: #DC2626;
    margin-top: 0.25rem;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.form-row--3 {
    grid-template-columns: repeat(3, 1fr);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #F3F4F6;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9375rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.btn--primary {
    background: linear-gradient(135deg, #7B2D3B 0%, #5C1D2A 100%);
    color: white;
}

.btn--primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(123, 45, 59, 0.3);
}

.btn--primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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

@media (max-width: 640px) {
    .form-row, .form-row--3 {
        grid-template-columns: 1fr;
    }
}
</style>
