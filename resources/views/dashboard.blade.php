<x-layouts.app title="Dashboard" header="Dashboard">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Cotizaciones del Mes -->
        <div class="stat-card">
            <div class="stat-icon bg-gradient-to-br from-blue-500 to-blue-600">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['quotes_this_month'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">Cotizaciones del Mes</p>
            </div>
        </div>

        <!-- Pendientes de Respuesta -->
        <div class="stat-card">
            <div class="stat-icon bg-gradient-to-br from-amber-500 to-orange-500">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_quotes'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">Pendientes de Respuesta</p>
            </div>
        </div>

        <!-- Concretadas -->
        <div class="stat-card">
            <div class="stat-icon bg-gradient-to-br from-emerald-500 to-green-600">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['concreted_quotes'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">Concretadas</p>
            </div>
        </div>

        <!-- Tasa de Conversión -->
        <div class="stat-card">
            <div class="stat-icon bg-gradient-to-br from-purple-500 to-indigo-600">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['conversion_rate'] ?? 0 }}%</p>
                <p class="text-sm text-gray-500">Tasa de Conversión</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cotizaciones Recientes -->
        <div class="lg:col-span-2 card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Cotizaciones Recientes</h3>
                <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Ver todas →</a>
            </div>

            @if (isset($recentQuotes) && count($recentQuotes) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Folio</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Cliente
                                </th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Vehículo
                                </th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Estado
                                </th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentQuotes as $quote)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        <span class="font-medium text-blue-600">{{ $quote->folio }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-700">{{ $quote->customer->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-gray-600 text-sm">{{ $quote->vehicle_description }}</td>
                                    <td class="py-3 px-4">
                                        <span class="badge badge-{{ $quote->status->color() }}">
                                            {{ $quote->status->label() }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-500 text-sm">
                                        {{ $quote->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <p class="text-gray-500 mb-4">No hay cotizaciones recientes</p>
                    <a href="#" class="btn btn-primary inline-flex">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Nueva Cotización
                    </a>
                </div>
            @endif
        </div>

        <!-- Accesos Rápidos -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Accesos Rápidos</h3>

            <div class="space-y-3">
                <a href="#"
                    class="flex items-center p-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Nueva Cotización</p>
                        <p class="text-sm text-gray-500">Crear cotización rápida</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:translate-x-1 transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </a>

                <a href="#"
                    class="flex items-center p-4 rounded-xl bg-gradient-to-r from-emerald-50 to-green-50 hover:from-emerald-100 hover:to-green-100 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Nuevo Cliente</p>
                        <p class="text-sm text-gray-500">Registrar prospecto</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:translate-x-1 transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </a>

                <a href="#"
                    class="flex items-center p-4 rounded-xl bg-gradient-to-r from-purple-50 to-indigo-50 hover:from-purple-100 hover:to-indigo-100 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-purple-500 flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Reportes</p>
                        <p class="text-sm text-gray-500">Ver estadísticas</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:translate-x-1 transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
