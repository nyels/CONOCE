<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import AuditDetailsModal from './AuditDetailsModal.vue';

defineProps({
    activities: Object, 
});

const showModal = ref(false);
const selectedActivity = ref(null);

const openDetails = (activity) => {
    selectedActivity.value = activity;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    setTimeout(() => selectedActivity.value = null, 300); // Limpiar después de cerrar
};

const getActionColor = (event) => {
    switch (event) {
        case 'created': return 'text-green-600';
        case 'updated': return 'text-amber-600';
        case 'deleted': return 'text-red-600';
        default: return 'text-blue-600';
    }
};

const getActionIcon = (event) => {
    switch (event) {
        case 'created': return '➕';
        case 'updated': return '📝';
        case 'deleted': return '🗑️';
        default: return '📋';
    }
};

const getActionLabel = (event) => {
    switch (event) {
        case 'created': return 'Creó';
        case 'updated': return 'Actualizó';
        case 'deleted': return 'Eliminó';
        default: return event;
    }
};
</script>

<template>
    <AppLayout title="Bitácora de Auditoría">
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                        Bitácora de Auditoría
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Monitorización completa de eventos y seguridad del sistema.
                    </p>
                </div>
                <!-- Stat Cards Simples (Opcional, decorativo) -->
                <div class="flex gap-4">
                    <div class="px-4 py-2 bg-white rounded-lg border border-gray-200 shadow-sm">
                        <span class="block text-xs text-gray-500 uppercase font-bold">Total Eventos</span>
                        <span class="block text-lg font-bold text-gray-800">{{ activities.total }}</span>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-8 bg-gray-50 min-h-screen px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                
                <!-- Main Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    
                    <!-- Table Toolbar -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50 flex items-center justify-between">
                        <div class="relative max-w-sm w-full">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" placeholder="Buscar por usuario, acción o IP..." class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 sm:text-sm h-10">
                        </div>
                        <div class="text-sm text-gray-500 hidden sm:block">
                            <span class="font-medium text-gray-900">{{ activities.from }}-{{ activities.to }}</span> de <span class="font-medium text-gray-900">{{ activities.total }}</span>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Evento</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usuario</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Entidad</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="log in activities.data" :key="log.id" class="hover:bg-gray-50 transition-colors group">
                                    
                                    <!-- Evento (Acción + Descripción) -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full" 
                                                :class="{
                                                    'bg-green-100 text-green-600': log.event === 'created',
                                                    'bg-amber-100 text-amber-600': log.event === 'updated',
                                                    'bg-red-100 text-red-600': log.event === 'deleted',
                                                    'bg-blue-100 text-blue-600': !['created','updated','deleted'].includes(log.event)
                                                }">
                                                <span class="text-lg">{{ getActionIcon(log.event) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                                    {{ getActionLabel(log.event) }}
                                                    <span v-if="log.event === 'deleted'" class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 uppercase tracking-wide">Danger</span>
                                                </div>
                                                <div class="text-sm text-gray-500 truncate max-w-xs" :title="log.description">
                                                    {{ log.description }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Usuario -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-xs font-bold text-white uppercase shadow-sm">
                                                {{ (log.causer?.name || 'S').substring(0, 2) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ log.causer?.name || 'Sistema' }}</div>
                                                <div class="text-xs text-gray-500">{{ log.properties?.ip || 'IP Oculta' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Entidad (Badge Pill) -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ log.subject_type?.split('\\').pop() }}
                                            <span class="ml-1 text-gray-400">#{{ log.subject_id }}</span>
                                        </span>
                                    </td>

                                    <!-- Fecha -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-900">
                                                {{ new Date(log.created_at).toLocaleDateString('es-MX', {day: 'numeric', month: 'short', year: 'numeric'}) }}
                                            </span>
                                            <span class="text-xs">
                                                {{ new Date(log.created_at).toLocaleTimeString('es-MX', {hour: '2-digit', minute:'2-digit'}) }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Action Button -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button 
                                            @click="openDetails(log)"
                                            class="text-gray-400 hover:text-cyan-600 transition-colors p-2 rounded-full hover:bg-cyan-50 group-hover:text-cyan-600"
                                        >
                                            <span class="sr-only">Ver detalles</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 items-center justify-between flex">
                         <div class="flex-1 flex justify-between sm:hidden">
                            <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Anterior</button>
                            <button class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Siguiente</button>
                         </div>
                         <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                               <p class="text-sm text-gray-700">
                                  Mostrando <span class="font-medium">{{ activities.from }}</span> a <span class="font-medium">{{ activities.to }}</span> de <span class="font-medium">{{ activities.total }}</span> resultados
                               </p>
                            </div>
                            <div>
                               <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                  <Link v-for="(link, k) in activities.links" 
                                        :key="k" 
                                        :href="link.url || '#'" 
                                        v-html="link.label"
                                        :class="{'z-10 bg-cyan-50 border-cyan-500 text-cyan-600': link.active, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active, 'cursor-not-allowed opacity-60': !link.url}"
                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium first:rounded-l-md last:rounded-r-md"
                                  />
                               </nav>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>

        <AuditDetailsModal :show="showModal" :activity="selectedActivity" @close="closeModal" />
    </AppLayout>
</template>
