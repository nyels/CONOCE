<!-- resources/js/Components/Crud/CrudTable.vue -->
<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    data: { type: Array, default: () => [] },
    columns: { type: Array, required: true },
    // columns: [{ key: 'name', label: 'Nombre', sortable: true, type: 'text|image|badge|actions' }]
    searchable: { type: Boolean, default: true },
    searchPlaceholder: { type: String, default: 'Buscar...' },
    loading: { type: Boolean, default: false },
    emptyMessage: { type: String, default: 'No hay registros' },
    perPage: { type: Number, default: 10 }
});

const emit = defineEmits(['edit', 'delete', 'view']);

// Search
const searchQuery = ref('');

// Pagination
const currentPage = ref(1);

// Sorting
const sortKey = ref('');
const sortOrder = ref('asc');

const toggleSort = (key) => {
    if (sortKey.value === key) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortKey.value = key;
        sortOrder.value = 'asc';
    }
};

// Filtered and sorted data
const filteredData = computed(() => {
    let result = [...props.data];
    
    // Search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(item => {
            return props.columns.some(col => {
                const value = item[col.key];
                return value && String(value).toLowerCase().includes(query);
            });
        });
    }
    
    // Sort
    if (sortKey.value) {
        result.sort((a, b) => {
            const aVal = a[sortKey.value] || '';
            const bVal = b[sortKey.value] || '';
            const comparison = String(aVal).localeCompare(String(bVal));
            return sortOrder.value === 'asc' ? comparison : -comparison;
        });
    }
    
    return result;
});

// Paginated data
const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * props.perPage;
    return filteredData.value.slice(start, start + props.perPage);
});

const totalPages = computed(() => Math.ceil(filteredData.value.length / props.perPage));

// Reset page when search changes
watch(searchQuery, () => {
    currentPage.value = 1;
});
</script>

<template>
    <div class="crud-table">
        <!-- Search Bar -->
        <div v-if="searchable" class="crud-table__search">
            <div class="search-input">
                <span class="search-icon">🔍</span>
                <input 
                    type="text" 
                    v-model="searchQuery"
                    :placeholder="searchPlaceholder"
                    class="search-field"
                />
            </div>
        </div>
        
        <!-- Table -->
        <div class="crud-table__wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th 
                            v-for="col in columns" 
                            :key="col.key"
                            :class="{ 'sortable': col.sortable, 'sorted': sortKey === col.key }"
                            @click="col.sortable && toggleSort(col.key)"
                        >
                            {{ col.label }}
                            <span v-if="col.sortable && sortKey === col.key" class="sort-icon">
                                {{ sortOrder === 'asc' ? '↑' : '↓' }}
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loading -->
                    <tr v-if="loading">
                        <td :colspan="columns.length" class="loading-cell">
                            <div class="loading-spinner"></div>
                            <span>Cargando...</span>
                        </td>
                    </tr>
                    
                    <!-- Empty -->
                    <tr v-else-if="paginatedData.length === 0">
                        <td :colspan="columns.length" class="empty-cell">
                            <span class="empty-icon">📋</span>
                            <span>{{ emptyMessage }}</span>
                        </td>
                    </tr>
                    
                    <!-- Data Rows -->
                    <tr v-else v-for="item in paginatedData" :key="item.id">
                        <td v-for="col in columns" :key="col.key">
                            <!-- Image -->
                            <template v-if="col.type === 'image'">
                                <img 
                                    v-if="item[col.key]" 
                                    :src="item[col.key]" 
                                    :alt="item.name" 
                                    class="cell-image"
                                />
                                <span v-else class="no-image">Sin imagen</span>
                            </template>
                            
                            <!-- Badge (status) -->
                            <template v-else-if="col.type === 'badge'">
                                <span class="badge" :class="`badge--${item[col.key] ? 'success' : 'inactive'}`">
                                    {{ item[col.key] ? 'Activo' : 'Inactivo' }}
                                </span>
                            </template>
                            
                            <!-- Actions -->
                            <template v-else-if="col.type === 'actions'">
                                <div class="actions">
                                    <button class="action-btn action-btn--edit" @click="$emit('edit', item)" title="Editar">
                                        ✏️
                                    </button>
                                    <button class="action-btn action-btn--delete" @click="$emit('delete', item)" title="Eliminar">
                                        🗑️
                                    </button>
                                </div>
                            </template>
                            
                            <!-- Default text -->
                            <template v-else>
                                {{ item[col.key] }}
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div v-if="totalPages > 1" class="crud-table__pagination">
            <button 
                class="page-btn" 
                :disabled="currentPage === 1"
                @click="currentPage--"
            >
                ←
            </button>
            
            <span class="page-info">
                Página {{ currentPage }} de {{ totalPages }}
            </span>
            
            <button 
                class="page-btn" 
                :disabled="currentPage === totalPages"
                @click="currentPage++"
            >
                →
            </button>
        </div>
    </div>
</template>

<style scoped>
.crud-table {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.crud-table__search {
    padding: 1.25rem;
    border-bottom: 1px solid #F3F4F6;
}

.search-input {
    position: relative;
    max-width: 320px;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.875rem;
}

.search-field {
    width: 100%;
    padding: 0.625rem 1rem 0.625rem 2.5rem;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 0.9375rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.search-field:focus {
    outline: none;
    border-color: #7B2D3B;
    box-shadow: 0 0 0 3px rgba(123, 45, 59, 0.1);
}

.crud-table__wrapper {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th {
    padding: 1rem 1.25rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6B7280;
    background: #F9FAFB;
    border-bottom: 1px solid #E5E7EB;
}

.table th.sortable {
    cursor: pointer;
    user-select: none;
}

.table th.sortable:hover {
    color: #7B2D3B;
}

.sort-icon {
    margin-left: 4px;
}

.table td {
    padding: 1rem 1.25rem;
    font-size: 0.9375rem;
    color: #111827;
    border-bottom: 1px solid #F3F4F6;
}

.table tbody tr:hover {
    background: #F9FAFB;
}

.cell-image {
    width: 40px;
    height: 40px;
    object-fit: contain;
    border-radius: 8px;
    background: #F3F4F6;
}

.no-image {
    color: #9CA3AF;
    font-size: 0.8125rem;
}

.badge {
    display: inline-flex;
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge--success {
    background: #D1FAE5;
    color: #059669;
}

.badge--inactive {
    background: #F3F4F6;
    color: #6B7280;
}

.actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.875rem;
}

.action-btn--edit {
    background: #EFF6FF;
}

.action-btn--edit:hover {
    background: #DBEAFE;
}

.action-btn--delete {
    background: #FEF2F2;
}

.action-btn--delete:hover {
    background: #FEE2E2;
}

.loading-cell, .empty-cell {
    text-align: center;
    padding: 3rem !important;
    color: #6B7280;
}

.loading-spinner {
    width: 24px;
    height: 24px;
    border: 2px solid #E5E7EB;
    border-top-color: #7B2D3B;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 0.75rem;
}

.empty-icon {
    display: block;
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.crud-table__pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    padding: 1rem;
    border-top: 1px solid #F3F4F6;
}

.page-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    background: white;
    cursor: pointer;
    transition: all 0.2s;
}

.page-btn:hover:not(:disabled) {
    background: #7B2D3B;
    color: white;
    border-color: #7B2D3B;
}

.page-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.page-info {
    font-size: 0.875rem;
    color: #6B7280;
}

/* Responsive */
@media (max-width: 768px) {
    .crud-table__search {
        padding: 1rem;
    }
    
    .search-input {
        max-width: 100%;
    }
    
    .table th, .table td {
        padding: 0.75rem 1rem;
    }
}
</style>
