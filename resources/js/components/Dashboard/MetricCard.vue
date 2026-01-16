<!-- resources/js/Components/Dashboard/MetricCard.vue -->
<template>
  <div class="card-premium metric-card" :class="{'card-with-accent': accent}">
    <div class="flex items-center justify-between mb-2">
      <h3 class="font-semibold text-gray-700">{{ title }}</h3>
      <div v-if="icon" class="text-2xl">{{ icon }}</div>
    </div>
    
    <div class="metric-value">{{ formattedValue }}</div>
    
    <div class="metric-label">{{ label }}</div>
    
    <div v-if="change !== null" class="metric-change" :class="changeClass">
      <span v-if="change > 0">+</span>{{ change }}%
      <span v-if="trendIcon" class="ml-1">{{ trendIcon }}</span>
    </div>
    
    <div v-if="description" class="mt-3 text-sm text-gray-500">
      {{ description }}
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  value: {
    type: [Number, String],
    required: true
  },
  label: {
    type: String,
    default: ''
  },
  icon: {
    type: String,
    default: ''
  },
  change: {
    type: Number,
    default: null
  },
  description: {
    type: String,
    default: ''
  },
  format: {
    type: String,
    default: 'number', // 'number', 'currency', 'percent'
  },
  accent: {
    type: Boolean,
    default: false
  }
})

const formattedValue = computed(() => {
  if (props.format === 'currency') {
    return new Intl.NumberFormat('es-MX', {
      style: 'currency',
      currency: 'MXN',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    }).format(props.value)
  }
  
  if (props.format === 'percent') {
    return `${props.value}%`
  }
  
  // Formato número con separadores
  return new Intl.NumberFormat('es-MX').format(props.value)
})

const changeClass = computed(() => {
  if (props.change > 0) return 'positive'
  if (props.change < 0) return 'negative'
  return ''
})

const trendIcon = computed(() => {
  if (props.change > 0) return '↗️'
  if (props.change < 0) return '↘️'
  return ''
})
</script>

<style scoped>
/* Los estilos están en components.css */
</style>