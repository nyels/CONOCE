<!-- resources/js/Components/Crud/FormInput.vue -->
<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: { type: [String, Number], default: '' },
    label: { type: String, default: '' },
    type: { type: String, default: 'text' },
    placeholder: { type: String, default: '' },
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
        <input
            v-model="value"
            :type="type"
            :placeholder="placeholder"
            :disabled="disabled"
            class="form-input"
        />
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

.form-input {
    width: 100%;
    padding: 0.625rem 1rem;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 0.9375rem;
    color: #111827;
    transition: all 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #7B2D3B;
    box-shadow: 0 0 0 3px rgba(123, 45, 59, 0.1);
}

.form-input:disabled {
    background: #F3F4F6;
    cursor: not-allowed;
}

.form-input::placeholder {
    color: #9CA3AF;
}

.has-error .form-input {
    border-color: #DC2626;
}

.form-error {
    display: block;
    font-size: 0.8125rem;
    color: #DC2626;
    margin-top: 0.375rem;
}
</style>
