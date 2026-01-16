<!-- resources/js/Components/Crud/FormImageUpload.vue -->
<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    modelValue: { type: [String, File], default: null },
    label: { type: String, default: 'Imagen' },
    accept: { type: String, default: 'image/png,image/jpeg,image/webp' },
    maxSize: { type: Number, default: 2 }, // MB
    error: { type: String, default: '' },
    previewUrl: { type: String, default: '' }
});

const emit = defineEmits(['update:modelValue', 'error']);

const fileInput = ref(null);
const preview = ref(props.previewUrl);
const isDragging = ref(false);

const previewImage = computed(() => preview.value || props.previewUrl);

const handleFileSelect = (file) => {
    if (!file) return;
    
    // Validate type
    const validTypes = props.accept.split(',').map(t => t.trim());
    if (!validTypes.includes(file.type)) {
        emit('error', 'Tipo de archivo no permitido');
        return;
    }
    
    // Validate size
    const sizeInMB = file.size / (1024 * 1024);
    if (sizeInMB > props.maxSize) {
        emit('error', `El archivo no debe exceder ${props.maxSize}MB`);
        return;
    }
    
    // Create preview
    const reader = new FileReader();
    reader.onload = (e) => {
        preview.value = e.target.result;
    };
    reader.readAsDataURL(file);
    
    emit('update:modelValue', file);
};

const onInputChange = (event) => {
    const file = event.target.files[0];
    handleFileSelect(file);
};

const onDrop = (event) => {
    isDragging.value = false;
    const file = event.dataTransfer.files[0];
    handleFileSelect(file);
};

const removeImage = () => {
    preview.value = '';
    emit('update:modelValue', null);
    if (fileInput.value) fileInput.value.value = '';
};

const openFileDialog = () => {
    fileInput.value?.click();
};
</script>

<template>
    <div class="form-group" :class="{ 'has-error': error }">
        <label v-if="label" class="form-label">{{ label }}</label>
        
        <div 
            class="upload-zone"
            :class="{ 'is-dragging': isDragging, 'has-preview': previewImage }"
            @click="openFileDialog"
            @dragover.prevent="isDragging = true"
            @dragleave="isDragging = false"
            @drop.prevent="onDrop"
        >
            <!-- Preview -->
            <template v-if="previewImage">
                <img :src="previewImage" alt="Preview" class="preview-image" />
                <button class="remove-btn" @click.stop="removeImage" type="button">
                    ×
                </button>
            </template>
            
            <!-- Upload prompt -->
            <template v-else>
                <div class="upload-icon">📷</div>
                <div class="upload-text">
                    <strong>Click para subir</strong>
                    <span>o arrastra y suelta</span>
                </div>
                <div class="upload-hint">PNG, JPG hasta {{ maxSize }}MB</div>
            </template>
        </div>
        
        <input
            ref="fileInput"
            type="file"
            :accept="accept"
            class="hidden-input"
            @change="onInputChange"
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

.upload-zone {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 2px dashed #E5E7EB;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    min-height: 160px;
    background: #FAFAFA;
}

.upload-zone:hover, .upload-zone.is-dragging {
    border-color: #7B2D3B;
    background: rgba(123, 45, 59, 0.02);
}

.upload-zone.has-preview {
    padding: 0.5rem;
}

.upload-icon {
    font-size: 2.5rem;
    margin-bottom: 0.75rem;
}

.upload-text {
    font-size: 0.9375rem;
    color: #374151;
}

.upload-text span {
    color: #6B7280;
    display: block;
    font-size: 0.8125rem;
}

.upload-hint {
    font-size: 0.75rem;
    color: #9CA3AF;
    margin-top: 0.5rem;
}

.preview-image {
    max-width: 100%;
    max-height: 200px;
    object-fit: contain;
    border-radius: 8px;
}

.remove-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.25rem;
    line-height: 1;
}

.remove-btn:hover {
    background: #DC2626;
}

.hidden-input {
    display: none;
}

.form-error {
    display: block;
    font-size: 0.8125rem;
    color: #DC2626;
    margin-top: 0.375rem;
}
</style>
