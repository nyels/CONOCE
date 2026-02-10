<script setup>
defineProps({
    title: String,
    value: [String, Number],
    trend: String,
    trendType: {
        type: String,
        default: 'neutral', // 'positive', 'negative', 'neutral'
    },
    iconName: String, // We'll simply use slots or pass icon component if needed, but string for now simplicity
});
</script>

<template>
    <div class="group relative bg-white rounded-xl p-6 transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 hover:border-slate-200">
        <!-- Hover Glow Effect -->
        <div class="absolute inset-0 bg-gradient-to-br from-transparent via-transparent to-slate-50 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-xl pointer-events-none"></div>
        
        <div class="relative flex justify-between items-start mb-4">
            <h3 class="text-xs font-semibold tracking-widest text-slate-400 uppercase font-sans">{{ title }}</h3>
            
            <!-- Icon Container -->
            <div class="p-2 rounded-lg bg-slate-50 text-slate-400 group-hover:text-[#C7A172] group-hover:bg-[#C7A172]/10 transition-colors duration-300">
                <slot name="icon">
                    <!-- Default fallback icon -->
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </slot>
            </div>
        </div>

        <div class="relative">
            <div class="premium-stat-value font-feature-settings-tnum">
                {{ value }}
            </div>
            
            <div v-if="trend" class="mt-2 flex items-center gap-1.5 text-xs font-medium">
                <span 
                    class="flex items-center gap-1 px-1.5 py-0.5 rounded"
                    :class="{
                        'text-emerald-600 bg-emerald-50': trendType === 'positive',
                        'text-rose-600 bg-rose-50': trendType === 'negative',
                        'text-slate-500 bg-slate-50': trendType === 'neutral'
                    }"
                >
                    <svg v-if="trendType === 'positive'" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    <svg v-if="trendType === 'negative'" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6" /></svg>
                    {{ trend }}
                </span>
                <span class="text-slate-400">vs mes anterior</span>
            </div>
        </div>
    </div>
</template>

<style scoped>
.font-feature-settings-tnum {
    font-feature-settings: 'tnum';
    font-variant-numeric: tabular-nums;
}

.premium-stat-value {
    font-size: clamp(1.25rem, 3.5vw, 1.875rem);
    font-weight: 700;
    letter-spacing: -0.025em;
    color: #0f172a;
    word-break: break-word;
}
</style>
