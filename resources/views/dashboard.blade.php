<x-layouts.app title="Panel Principal" header="Resumen General"
    subheader="Vista general de sus actividades y métricas clave.">

    <!-- Welcome Banner / Quick Stats Context -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800">Hola, {{ auth()->user()->name }} 👋</h2>
        <p class="text-slate-500 mt-1">Aquí está lo que está sucediendo con tus cotizaciones hoy.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <!-- Card 1: Cotizaciones del Mes -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div
                class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-primary to-primary-dark opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-red-50 text-primary rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <!-- Trend Indicator (Placeholder logic) -->
                <span
                    class="flex items-center text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                    +12% <svg class="w-3 h-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </span>
            </div>
            <div class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['quotes_this_month'] ?? 0 }}</div>
            <div class="text-sm text-slate-500 font-medium">Cotizaciones este mes</div>
        </div>

        <!-- Card 2: Pendientes -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div
                class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-amber-500 to-amber-600 opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span
                    class="flex items-center text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-1 rounded-full">
                    Actual
                </span>
            </div>
            <div class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['pending_quotes'] ?? 0 }}</div>
            <div class="text-sm text-slate-500 font-medium">Pendientes de respuesta</div>
        </div>

        <!-- Card 3: Concretadas -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div
                class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-emerald-500 to-teal-600 opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span
                    class="flex items-center text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                    Exitoso
                </span>
            </div>
            <div class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['concreted_quotes'] ?? 0 }}</div>
            <div class="text-sm text-slate-500 font-medium">Pólizas emitidas</div>
        </div>

        <!-- Card 4: Tasa de Conversión -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div
                class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-blue-500 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-800 mb-1">{{ $stats['conversion_rate'] ?? 0 }}%</div>
            <div class="text-sm text-slate-500 font-medium">Tasa de conversión</div>

            <!-- Mini Progress Bar -->
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3">
                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $stats['conversion_rate'] ?? 0 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Main Grid Content: Quick Actions & Recent Quotes -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left Column: Recent Activity (Takes up 2/3 space on large screens) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">Cotizaciones Recientes</h3>
                        <p class="text-sm text-slate-400">Últimos movimientos registrados</p>
                    </div>
                    <a href="#"
                        class="text-primary hover:text-primary-dark text-sm font-semibold transition-colors flex items-center gap-1">
                        Ver todas
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-slate-50/50 text-xs uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-4 font-semibold">Folio</th>
                                <th class="px-6 py-4 font-semibold">Cliente</th>
                                <th class="px-6 py-4 font-semibold">Vehículo</th>
                                <th class="px-6 py-4 font-semibold text-center">Estado</th>
                                <th class="px-6 py-4 font-semibold text-right">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($recentQuotes as $quote)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <span
                                                class="font-semibold text-slate-700 group-hover:text-primary transition-colors">{{ $quote->folio }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-slate-700">
                                            {{ $quote->customer->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-slate-400">Particular</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-600 truncate max-w-[200px]"
                                            title="{{ $quote->vehicle_description }}">
                                            {{ $quote->vehicle_description }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if ($quote->status->value === 'draft') bg-slate-100 text-slate-600
                                            @elseif($quote->status->value === 'sent') bg-amber-50 text-amber-600
                                            @elseif($quote->status->value === 'concreted') bg-emerald-50 text-emerald-600
                                            @else bg-gray-100 text-gray-600 @endif">
                                            {{ $quote->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-slate-500">
                                        {{ $quote->created_at->format('d M, Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <div
                                                class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-slate-300" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                            </div>
                                            <p>No hay cotizaciones recientes para mostrar.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Actions & Help (Takes up 1/3 space on large screens) -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Quick Actions Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-bold text-slate-800 text-lg mb-4">Accesos Rápidos</h3>
                <div class="grid grid-cols-1 gap-3">
                    <button
                        class="w-full relative group p-4 rounded-xl border border-slate-200 hover:border-primary hover:bg-primary/5 transition-all text-left flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-lg bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <span
                                class="block font-semibold text-slate-700 group-hover:text-primary transition-colors">Nueva
                                Cotización</span>
                            <span class="text-xs text-slate-500">Iniciar proceso de cotización</span>
                        </div>
                        <svg class="w-5 h-5 text-slate-300 absolute right-4 group-hover:text-primary transition-colors"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <button
                        class="w-full relative group p-4 rounded-xl border border-slate-200 hover:border-secondary hover:bg-secondary/5 transition-all text-left flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-lg bg-white border border-secondary text-secondary flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <span
                                class="block font-semibold text-slate-700 group-hover:text-secondary transition-colors">Nuevo
                                Cliente</span>
                            <span class="text-xs text-slate-500">Registrar prospecto</span>
                        </div>
                    </button>

                    <button
                        class="w-full relative group p-4 rounded-xl border border-slate-200 hover:border-slate-400 hover:bg-slate-50 transition-all text-left flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block font-semibold text-slate-700">Generar Reporte</span>
                            <span class="text-xs text-slate-500">Exportar métricas mensuales</span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Support / Contact Card -->
            <div
                class="bg-gradient-to-br from-primary-dark to-slate-900 rounded-2xl p-6 text-white relative overflow-hidden">
                <!-- Decorative Circles -->
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white/5 blur-xl"></div>
                <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-24 h-24 rounded-full bg-secondary/20 blur-xl"></div>

                <h4 class="font-bold text-lg mb-2 relative z-10">¿Necesitas Ayuda?</h4>
                <p class="text-white/70 text-sm mb-4 relative z-10">Contacta al soporte técnico si tienes problemas con
                    las cotizaciones.</p>
                <button
                    class="w-full py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg text-sm font-medium transition-colors relative z-10">
                    Contactar Soporte
                </button>
            </div>
        </div>
    </div>
</x-layouts.app>
