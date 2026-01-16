<!-- resources/js/Components/Dashboard/KpiCard.vue -->
<template>
  <div class="card-premium kpi-card" :class="cardClasses">
    <div class="kpi-header">
      <div>
        <h3 class="kpi-title">{{ title }}</h3>
        <p v-if="subtitle" class="kpi-subtitle">{{ subtitle }}</p>
      </div>
      <div v-if="icon" class="kpi-icon">
        {{ icon }}
      </div>
    </div>
    
    <div class="kpi-value-container">
      <div class="kpi-value">{{ formattedValue }}</div>
      <div v-if="previousValue !== null && previousValue !== undefined" class="kpi-change" :class="changeClass">
        <span class="change-arrow">{{ changeArrow }}</span>
        <span class="change-value">{{ changePercent }}%</span>
        <span class="change-period">vs {{ previousPeriod }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  value: {
    type: [Number, String],
    default: 0
  },
  subtitle: {
    type: String,
    default: ''
  },
  icon: {
    type: String,
    default: ''
  },
  previousValue: {
    type: Number,
    default: null
  },
  previousPeriod: {
    type: String,
    default: 'mes anterior'
  },
  format: {
    type: String,
    default: 'number' // 'number', 'currency', 'percent'
  },
  priority: {
    type: Number,
    default: 1,
    validator: value => [1, 2, 3].includes(value)
  }
});

const formattedValue = computed(() => {
  const val = Number(props.value) || 0;
  
  if (props.format === 'currency') {
    return new Intl.NumberFormat('es-MX', {
      style: 'currency',
      currency: 'MXN',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    }).format(val);
  }
  
  if (props.format === 'percent') {
    return `${val.toFixed(1)}%`;
  }
  
  return new Intl.NumberFormat('es-MX').format(val);
});

const changePercent = computed(() => {
  if (props.previousValue === null || props.previousValue === undefined) return 0;
  if (props.previousValue === 0) return props.value > 0 ? 100 : 0;
  
  const change = ((props.value - props.previousValue) / props.previousValue) * 100;
  return Math.abs(change).toFixed(1);
});

const changeClass = computed(() => {
  if (props.previousValue === null || props.previousValue === undefined) return '';
  if (props.value > props.previousValue) return 'positive';
  if (props.value < props.previousValue) return 'negative';
  return 'neutral';
});

const changeArrow = computed(() => {
  if (props.previousValue === null || props.previousValue === undefined) return '';
  if (props.value > props.previousValue) return '↑';
  if (props.value < props.previousValue) return '↓';
  return '→';
});

const cardClasses = computed(() => {
  const classes = [];
  if (props.priority === 1) classes.push('priority-high');
  if (props.priority === 2) classes.push('priority-medium');
  if (props.priority === 3) classes.push('priority-low');
  return classes.join(' ');
});
</script>

<style scoped>
.kpi-card {
  position: relative;
}

.kpi-card.priority-high::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 4px;
  height: 100%;
  background: linear-gradient(to bottom, var(--color-brown-500), var(--color-brown-600));
  border-radius: var(--radius-lg) 0 0 var(--radius-lg);
}

.kpi-card.priority-medium::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 4px;
  height: 100%;
  background: linear-gradient(to bottom, var(--color-gold-500), var(--color-gold-600));
  border-radius: var(--radius-lg) 0 0 var(--radius-lg);
}

.kpi-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: var(--spacing-4);
}

.kpi-title {
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--color-gray-600);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin: 0;
}

.kpi-subtitle {
  font-size: 0.75rem;
  color: var(--color-gray-500);
  margin: var(--spacing-1) 0 0 0;
}

.kpi-icon {
  font-size: 1.5rem;
  padding: var(--spacing-2);
  background: var(--color-brown-50);
  border-radius: var(--radius-md);
}

.kpi-value-container {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-2);
}

.kpi-value {
  font-size: 2rem;
  font-weight: 800;
  color: var(--color-gray-900);
  font-family: var(--font-family-mono);
  line-height: 1;
}

.kpi-change {
  display: inline-flex;
  align-items: center;
  gap: var(--spacing-1);
  font-size: 0.75rem;
  padding: var(--spacing-1) var(--spacing-2);
  border-radius: var(--radius-full);
}

.kpi-change.positive {
  background: var(--color-success-50);
  color: var(--color-success-700);
}

.kpi-change.negative {
  background: var(--color-danger-50);
  color: var(--color-danger-700);
}

.kpi-change.neutral {
  background: var(--color-gray-100);
  color: var(--color-gray-600);
}

.change-arrow {
  font-weight: 700;
}

.change-value {
  font-weight: 600;
}

.change-period {
  color: inherit;
  opacity: 0.7;
}
</style>