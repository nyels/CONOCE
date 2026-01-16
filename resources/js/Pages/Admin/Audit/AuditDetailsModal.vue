<script setup>
import { computed } from 'vue';

const props = defineProps({
    show: Boolean,
    activity: Object,
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};

// --- Helpers copiados de Show.vue ---
const formatDate = (date) => {
    if (!date) return '—';
    const d = new Date(date);
    return d.toLocaleString('es-MX', { 
        year: 'numeric', month: '2-digit', day: '2-digit', 
        hour: '2-digit', minute: '2-digit', second: '2-digit' 
    });
};

const getEventColor = (event) => {
    switch (event) {
        case 'created': return 'bg-green-100 text-green-800 border-green-200';
        case 'updated': return 'bg-yellow-100 text-yellow-800 border-yellow-200';
        case 'deleted': return 'bg-red-100 text-red-800 border-red-200';
        default: return 'bg-blue-100 text-blue-800 border-blue-200';
    }
};

const getEventLabel = (event) => {
    if (!event) return 'REGISTRO';
    switch (event) {
        case 'created': return 'CREÓ';
        case 'updated': return 'ACTUALIZÓ';
        case 'deleted': return 'ELIMINÓ';
        default: return event.toUpperCase();
    }
};

const getEventIcon = (event) => {
    switch (event) {
        case 'created': return '➕';
        case 'updated': return '📝';
        case 'deleted': return '🗑️';
        default: return '📋';
    }
};

const changes = computed(() => {
    if (!props.activity) return [];
    
    const properties = props.activity.properties || {};
    const old = properties.old || {};
    const attributes = properties.attributes || {};
    
    // Si solo hay atributos (resource created)
    if (Object.keys(old).length === 0 && Object.keys(attributes).length > 0) {
        return Object.keys(attributes).map(key => ({
            key,
            old: '—', 
            new: attributes[key],
            isNew: true
        }));
    }

    // Si hay old y attributes (resource updated)
    const allKeys = new Set([...Object.keys(old), ...Object.keys(attributes)]);
    return Array.from(allKeys).map(key => ({
        key,
        old: old[key],
        new: attributes[key],
        changed: JSON.stringify(old[key]) !== JSON.stringify(attributes[key])
    })).filter(item => item.changed); 
});
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="close">
            <div class="absolute inset-0 bg-gray-900 opacity-60 backdrop-blur-sm"></div>
        </div>

        <!-- Modal Panel -->
        <div class="bg-white rounded-xl shadow-2xl transform transition-all sm:w-full sm:max-w-3xl relative flex flex-col max-h-[90vh]">
            
            <!-- Header -->
            <div class="bg-white px-6 py-5 border-b border-gray-100 flex justify-between items-start shrink-0 rounded-t-xl">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">
                        Detalle del Movimiento
                    </h3>
                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                        ID: <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-700 text-xs select-all">{{ activity.id }}</span>
                        <span class="text-gray-300">|</span>
                        <span>{{ formatDate(activity.created_at) }}</span>
                    </p>
                </div>
                <button @click="close" class="text-gray-400 hover:text-gray-500 hover:bg-gray-50 rounded-full p-2 transition focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Scrollable Content -->
            <div class="p-8 overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-10" v-if="activity">
                    
                    <!-- Contexto -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Contexto</h4>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Operador Responsable</dt>
                                <dd class="mt-1 flex items-center">
                                    <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-800 text-white text-[10px] uppercase font-bold mr-2">
                                        {{ (activity.causer?.name || 'S').substring(0, 2) }}
                                    </span>
                                    <span class="text-sm font-semibold text-gray-900">{{ activity.causer?.name || 'Sistema' }}</span>
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Acción</dt>
                                <dd class="mt-1">
                                    <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold gap-1.5', getEventColor(activity.event)]">
                                        <span>{{ getEventIcon(activity.event) }}</span>
                                        {{ getEventLabel(activity.event) }}
                                    </span>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Recurso Afectado</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-medium">
                                    {{ activity.subject_type?.split('\\').pop() }} 
                                    <span class="text-gray-400 font-normal">#{{ activity.subject_id }}</span>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                                <dd class="mt-1 text-sm text-gray-700 italic">
                                    "{{ activity.description }}"
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Trazabilidad Técnica -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Trazabilidad</h4>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 flex items-center gap-2">
                                    Dirección IP
                                    <span class="bg-gray-100 text-gray-500 px-1.5 rounded text-[10px] font-mono">v4</span>
                                </dt>
                                <dd class="mt-1 text-sm font-mono text-gray-800 bg-gray-50 px-2 py-1 rounded w-fit border border-gray-200">
                                    {{ activity.properties?.ip || 'Desconocida' }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Navegador (User Agent)</dt>
                                <dd class="mt-1 text-xs text-gray-500 font-mono break-words bg-gray-50 p-2 rounded border border-gray-100">
                                    {{ activity.properties?.user_agent || 'N/A' }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Endpoint Solicitado</dt>
                                <dd class="mt-1 text-xs text-blue-600 font-mono break-all hover:underline cursor-pointer">
                                    {{ activity.properties?.url || 'N/A' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Comparativa -->
                <div v-if="changes.length > 0">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center justify-between border-b pb-2">
                        <span>Comparativa de Cambios</span>
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-[10px] normal-case font-bold">{{ changes.length }} campos modificados</span>
                    </h4>
                    
                    <div class="overflow-hidden bg-white border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/4">Campo</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/3">Antes</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/3">Después</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-for="(change, idx) in changes" :key="idx" class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-700">
                                        {{ change.key }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-red-600 bg-red-50/20 font-mono break-all border-r border-gray-100">
                                        <div class="flex items-start gap-2">
                                            <span class="select-none text-red-300">-</span>
                                            <span v-if="change.old === null || change.old === '—'" class="text-gray-400 italic">null</span>
                                            <span v-else>{{ change.old }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-green-700 bg-green-50/20 font-mono font-medium break-all">
                                        <div class="flex items-start gap-2">
                                            <span class="select-none text-green-300">+</span>
                                            <span v-if="change.new === null" class="text-gray-400 italic">null</span>
                                            <span v-else>{{ change.new }}</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Empty State -->
                <div v-else class="rounded-lg bg-gray-50 border-2 border-dashed border-gray-200 p-8 text-center">
                    <div class="mx-auto h-12 w-12 text-gray-400">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Sin cambios registrados</h3>
                    <p class="mt-1 text-sm text-gray-500">Este evento no incluye modificaciones de atributos (ej. login, acceso, descarga).</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end shrink-0 rounded-b-xl">
                <button type="button" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors" @click="close">
                    Cerrar Detalle
                </button>
            </div>
        </div>
    </div>
</template>
