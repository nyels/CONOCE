<!-- resources/js/Components/Crud/FormSelect.vue -->
<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: { type: [String, Number], default: '' },
    label: { type: String, default: '' },
    options: { type: Array, default: () => [] },
    // options: [{ value: 1, label: 'Option 1' }]
    placeholder: { type: String, default: 'Seleccionar...' },
    error: { type: String, default: '' },
    required: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false }
});

const emit = defineEmits(['update:modelValue']);

const value = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val)
});
</script>

<template>
    <div class="form-group" :class="{ 'has-error': error }">
        <label v-if="label" class="form-label">
            {{ label }}
            <span v-if="required" class="required">*</span>
        </label>
        <div class="select-wrapper">
            <select
                v-model="value"
                :disabled="disabled"
                class="form-select"
            >
                <option value="">{{ placeholder }}</option>
                <option 
                    v-for="opt in options" 
                    :key="opt.value" 
                    :value="opt.value"
                >
                    {{ opt.label }}
                </option>
            </select>
            <span class="select-arrow">▼</span>
        </div>
        <span v-if="error" class="form-error">{{ error }}</span>
    </div>
</template>

<style scoped>
.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.375rem;
}

.required {
    color: #DC2626;
    margin-left: 2px;
}

.select-wrapper {
    position: relative;
}

.form-select {
    width: 100%;
    padding: 0.625rem 2.5rem 0.625rem 1rem;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 0.9375rem;
    color: #111827;
    background: white;
    cursor: pointer;
    appearance: none;
    transition: all 0.2s;
}

.form-select:focus {
    outline: none;
    border-color: #7B2D3B;
    box-shadow: 0 0 0 3px rgba(123, 45, 59, 0.1);
}

.form-select:disabled {
    background: #F3F4F6;
    cursor: not-allowed;
}

.select-arrow {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.625rem;
    color: #6B7280;
    pointer-events: none;
}

.has-error .form-select {
    border-color: #DC2626;
}

.form-error {
    display: block;
    font-size: 0.8125rem;
    color: #DC2626;
    margin-top: 0.375rem;
}
</style>
