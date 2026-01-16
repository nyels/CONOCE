<script setup>
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    activity: Object,
});

const formatDate = (date) => {
    if (!date) return '—';
    const d = new Date(date);
    return d.toLocaleString('es-MX', { 
        year: 'numeric', month: '2-digit', day: '2-digit', 
        hour: '2-digit', minute: '2-digit', second: '2-digit' 
    });
};

const timeAgo = (date) => {
    if (!date) return '';
    const now = new Date();
    const diff = Math.floor((now - new Date(date)) / 1000); // seconds
    
    if (diff < 60) return 'hace unos momentos';
    if (diff < 3600) return `hace ${Math.floor(diff / 60)} min`;
    if (diff < 86400) return `hace ${Math.floor(diff / 3600)} horas`;
    return `hace ${Math.floor(diff / 86400)} días`;
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
    const properties = props.activity.properties || {};
    const old = properties.old || {};
    const attributes = properties.attributes || {};
    
    // Si solo hay atributos (resource created), mostramos esos
    if (Object.keys(old).length === 0 && Object.keys(attributes).length > 0) {
        return Object.keys(attributes).map(key => ({
            key,
            old: '—', // Nulo
            new: attributes[key],
            isNew: true
        }));
    }

    // Si hay old y attributes (resource updated), comparamos
    const allKeys = new Set([...Object.keys(old), ...Object.keys(attributes)]);
    return Array.from(allKeys).map(key => ({
        key,
        old: old[key],
        new: attributes[key],
        changed: JSON.stringify(old[key]) !== JSON.stringify(attributes[key])
    })).filter(item => item.changed); // Solo mostrar lo que cambió
});

</script>

<template>
    <AppLayout title="Detalle de Auditoría">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                    🛡️ Detalle de Auditoría
                </h2>
                <div class="bg-gray-100 text-gray-500 px-3 py-1 rounded text-xs font-mono border border-gray-200">
                    UUID: {{ activity.batch_uuid || activity.id }} <!-- UUID si existe, sino ID -->
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-t-4 border-cyan-500">
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            
                            <!-- Columna 1: Contexto -->
                            <div>
                                <h3 class="text-lg font-medium text-cyan-600 mb-4 flex items-center gap-2">
                                    <span class="text-xl">↺</span> Contexto del Evento
                                </h3>
                                <div class="divide-y divide-gray-100">
                                    <div class="py-3 grid grid-cols-3 gap-4">
                                        <dt class="text-sm font-medium text-gray-900">Fecha y Hora</dt>
                                        <dd class="text-sm text-gray-700 col-span-2">
                                            {{ formatDate(activity.created_at) }}
                                            <span class="text-gray-400 text-xs ml-2">({{ timeAgo(activity.created_at) }})</span>
                                        </dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-3 gap-4">
                                        <dt class="text-sm font-medium text-gray-900">Operador</dt>
                                        <dd class="text-sm col-span-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-600 text-white">
                                                {{ activity.causer?.name || 'Sistema / Anónimo' }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-3 gap-4">
                                        <dt class="text-sm font-medium text-gray-900">Acción</dt>
                                        <dd class="text-sm col-span-2">
                                            <span :class="['inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border gap-1', getEventColor(activity.event)]">
                                                <span>{{ getEventIcon(activity.event) }}</span>
                                                {{ getEventLabel(activity.event) }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-3 gap-4">
                                        <dt class="text-sm font-medium text-gray-900">Módulo / Entidad</dt>
                                        <dd class="text-sm text-blue-600 font-medium col-span-2">
                                            {{ activity.subject_type?.split('\\').pop() }}
                                        </dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-3 gap-4">
                                        <dt class="text-sm font-medium text-gray-900">ID del Registro</dt>
                                        <dd class="text-sm col-span-2">
                                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs font-mono font-bold">#{{ activity.subject_id }}</span>
                                        </dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-3 gap-4">
                                        <dt class="text-sm font-medium text-gray-900">Descripción</dt>
                                        <dd class="text-sm text-gray-900 font-medium col-span-2">
                                            {{ activity.description }}
                                        </dd>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna 2: Trazabilidad Técnica -->
                            <div>
                                <h3 class="text-lg font-medium text-green-600 mb-4 flex items-center gap-2">
                                    <span class="text-xl">🖥️</span> Trazabilidad Técnica
                                </h3>
                                <div class="divide-y divide-gray-100 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <div class="py-2 grid grid-cols-3 gap-4">
                                        <dt class="text-xs font-bold text-gray-500 uppercase">Dirección IP</dt>
                                        <dd class="text-sm font-mono text-gray-800 col-span-2 bg-white px-2 py-1 rounded border border-gray-200 inline-block w-fit">
                                            {{ activity.properties?.ip || '127.0.0.1' }}
                                        </dd>
                                    </div>
                                    <div class="py-2 grid grid-cols-3 gap-4">
                                        <dt class="text-xs font-bold text-gray-500 uppercase">Método HTTP</dt>
                                        <dd class="text-sm font-bold text-gray-800 col-span-2">
                                            {{ activity.properties?.method || 'GUI / CLI' }}
                                        </dd>
                                    </div>
                                    <div class="py-2 grid grid-cols-3 gap-4">
                                        <dt class="text-xs font-bold text-gray-500 uppercase">URL Solicitada</dt>
                                        <dd class="text-xs text-gray-600 col-span-2 break-all">
                                            {{ activity.properties?.url || 'N/A' }}
                                        </dd>
                                    </div>
                                    <div class="py-2 grid grid-cols-3 gap-4">
                                        <dt class="text-xs font-bold text-gray-500 uppercase">Agente</dt>
                                        <dd class="text-xs text-gray-500 col-span-2 italic">
                                            {{ activity.properties?.user_agent || 'N/A' }}
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Comparativa de Cambios -->
                        <div v-if="changes.length > 0">
                            <h3 class="text-lg font-medium text-yellow-500 mb-4 flex items-center gap-2 border-t pt-6">
                                <span class="text-xl">⚖️</span> Comparativa de Cambios
                            </h3>
                            
                            <div class="overflow-x-auto border rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Atributo Modificado</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider text-red-600 bg-red-50">Valor Anterior</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider text-green-600 bg-green-50">Valor Nuevo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(change, idx) in changes" :key="idx">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700 bg-gray-50">
                                                {{ change.key }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 bg-red-50/30 break-all">
                                                <span v-if="change.old === null" class="italic text-gray-400">Nulo</span>
                                                <span v-else>{{ change.old }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-800 font-medium bg-green-50/30 break-all">
                                                <span v-if="change.new === null" class="italic text-gray-400">Nulo</span>
                                                <span v-else>{{ change.new }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <Link :href="route('admin.audit.index')" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition flex items-center gap-2">
                                ⬅ Volver
                            </Link>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
