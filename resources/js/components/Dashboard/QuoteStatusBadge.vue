<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: String,
});

const config = computed(() => {
    switch (props.status) {
        case 'draft':
            return { label: 'Borrador', color: 'text-slate-500', dot: 'bg-slate-400' };
        case 'sent':
            return { label: 'Enviada', color: 'text-blue-600', dot: 'bg-blue-500' };
        case 'concreted':
            return { label: 'Emitida', color: 'text-emerald-600', dot: 'bg-emerald-500' };
        case 'rejected':
            return { label: 'Rechazada', color: 'text-rose-600', dot: 'bg-rose-500' };
        default:
            return { label: props.status, color: 'text-slate-500', dot: 'bg-slate-400' };
    }
});
</script>

<template>
    <div class="inline-flex items-center gap-2">
        <span class="w-1.5 h-1.5 rounded-full" :class="config.dot"></span>
        <span class="text-xs font-medium" :class="config.color">{{ config.label }}</span>
    </div>
</template>
