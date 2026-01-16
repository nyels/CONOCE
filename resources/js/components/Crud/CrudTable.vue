<!-- resources/js/Components/Crud/CrudTable.vue -->
<script setup>
import { computed, ref } from 'vue';
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';

// ... props ...

const searchValue = ref('');

const props = defineProps({
    data: { type: Array, default: () => [] },
    columns: { type: Array, required: true },
    searchable: { type: Boolean, default: true },
    searchPlaceholder: { type: String, default: 'Buscar...' },
    loading: { type: Boolean, default: false },
    emptyMessage: { type: String, default: 'No hay registros' },
    perPage: { type: Number, default: 10 },
    isServerSide: { type: Boolean, default: false },
    pagination: { type: Object, default: null } // Objeto Paginator de Laravel/Inertia
});

const emit = defineEmits(['edit', 'delete', 'view', 'row-click']);

// Transform columns for vue3-easy-data-table
const headers = computed(() => {
    return props.columns.map(col => ({
        text: col.label,
        value: col.key,
        sortable: col.sortable ?? false,
        width: col.width
    }));
});

// Theme Config
const themeColor = "#7B2D3B";
</script>

<template>
    <div class="crud-table-wrapper">
        <Vue3EasyDataTable
            :headers="headers"
            :items="data"
            :loading="loading"
            :rows-per-page="perPage"
            :show-index="false"
            buttons-pagination
            :empty-message="emptyMessage"
            :search-value="searchValue" 
            :theme-color="themeColor"
            table-class-name="customize-table"
            rows-per-page-message="Filas por página:"
            rows-of-page-separator-message="de"
            @click-row="(item) => $emit('row-click', item)"
        >
            <!-- Loading Slot -->
            <template #loading>
                <div class="loading-spinner"></div>
                <span>Cargando...</span>
            </template>
            
            <!-- Dynamic Slots for Columns -->
            <template v-for="col in columns" :key="col.key" #[`item-${col.key}`]="item">
                <!-- We reuse the existing slot interface so parent components can still override -->
                <slot :name="'cell-' + col.key" :item="item" :value="item[col.key]">
                    
                    <!-- Internal Default Renderers -->
                    
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

                </slot>
            </template>

            <!-- Server Side Pagination (Replaces internal pagination if needed, but for now we rely on the library's props or insert custom pagination below if strictly required by Inertia structure) -->
            <!-- Note: vue3-easy-data-table has server-side-options props. Since we are refactoring, let's keep it simple first. 
                 The library handles client-side pagination on 'items' array automatically.
                 For server-side (Inertia), usually 'data' is just the current page's items. 
                 We hide the library's pagination if it's server-side and show our own, OR we configure the library to be server-side.
                 
                 Current Inertia implementation passes `pagination` object.
                 Let's simply hide the library footer if server-side and use the existing Inertia pagination links below it, 
                 OR better yet, just let the table render the rows and use our custom pagination below it.
             -->
             <template #pagination v-if="isServerSide">
                <!-- Empty template to hide default pagination when using server side -->
             </template>

        </Vue3EasyDataTable>

         <!-- Custom Server Side Pagination (Preserved from old code) -->
        <div v-if="isServerSide && pagination && pagination.links" class="crud-table__pagination">
             <!-- Simplified for brevity, reusing styles -->
             <div class="pagination-wrapper">
                <component 
                    :is="link.url ? 'Link' : 'span'"
                    v-for="(link, k) in pagination.links" 
                    :key="k"
                    :href="link.url"
                    v-html="link.label"
                    class="pagination-link"
                    :class="{ 
                        'active': link.active, 
                        'disabled': !link.url 
                    }"
                />
             </div>
        </div>
    </div>
</template>

<style scoped>
.crud-table-wrapper {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    padding: 1rem;
    --easy-table-border: 1px solid #f3f4f6;
    --easy-table-row-border: 1px solid #f3f4f6;
    --easy-table-header-font-size: 0.75rem;
    --easy-table-header-height: 50px;
    --easy-table-header-font-color: #6b7280;
    --easy-table-header-background-color: #f9fafb;
    --easy-table-header-item-padding: 10px 15px;
    
    --easy-table-body-row-height: 60px;
    --easy-table-body-row-font-size: 0.9375rem;
    --easy-table-body-row-font-color: #111827;
    --easy-table-body-row-hover-background-color: #f9fafb;
}

/* Customizing the table internal classes via deep selector */
:deep(.customize-table) {
    border: none !important;
}

:deep(.vue3-easy-data-table__header th) {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Reusing existing component styles */
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

.badge--success { background: #D1FAE5; color: #059669; }
.badge--inactive { background: #F3F4F6; color: #6B7280; }

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

.action-btn--edit { background: #EFF6FF; }
.action-btn--edit:hover { background: #DBEAFE; }

.action-btn--delete { background: #FEF2F2; }
.action-btn--delete:hover { background: #FEE2E2; }

.loading-spinner {
    width: 24px;
    height: 24px;
    border: 2px solid #E5E7EB;
    border-top-color: #7B2D3B;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 0.75rem;
}

@keyframes spin { to { transform: rotate(360deg); } }

/* Pagination Styles */
.crud-table__pagination {
    display: flex;
    justify-content: center;
    padding: 1rem 0;
    border-top: 1px solid #F3F4F6;
}

.pagination-wrapper {
    display: flex;
    gap: 0.25rem;
}

.pagination-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 0.875rem;
    border-radius: 8px;
    font-size: 0.875rem;
    color: #374151;
    text-decoration: none;
    transition: background 0.2s;
    cursor: pointer;
}

.pagination-link:hover:not(.disabled) { background: #F3F4F6; }
.pagination-link.active { background: #7B2D3B; color: white; }
.pagination-link.disabled { color: #D1D5DB; pointer-events: none; }
</style>
