<!-- resources/js/Layouts/AppLayout.vue -->
<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

// ===== STATE =====
const sidebarCollapsed = ref(false);
const sidebarOpen = ref(false);
const showUserMenu = ref(false);
const showNotifications = ref(false);
const windowWidth = ref(typeof window !== 'undefined' ? window.innerWidth : 1200);
const isDesktop = computed(() => windowWidth.value >= 1024);

// ===== PAGE DATA =====
const page = usePage();
const user = computed(() => page.props.auth?.user || {});
const userRole = computed(() => page.props.userRole || 'operator');

const userInitials = computed(() => {
    if (!user.value.name) return 'U';
    const parts = user.value.name.split(' ');
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return user.value.name.substring(0, 2).toUpperCase();
});

// ===== TIME =====
const currentTime = ref('');
const currentDate = ref('');
const greeting = computed(() => {
    const hour = new Date().getHours();
    if (hour < 12) return 'Buenos días';
    if (hour < 18) return 'Buenas tardes';
    return 'Buenas noches';
});

onMounted(() => {
    const updateTime = () => {
        const now = new Date();
        currentTime.value = now.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });
        currentDate.value = now.toLocaleDateString('es-MX', { weekday: 'long', day: 'numeric', month: 'long' });
    };
    updateTime();
    setInterval(updateTime, 60000);

    // Close mobile sidebar on resize to desktop
    window.addEventListener('resize', () => {
        windowWidth.value = window.innerWidth;
        if (isDesktop.value) {
            sidebarOpen.value = false;
        }
    });
});

// ===== NAVIGATION BY ROLE =====
const operatorNav = [
    { 
        section: 'Principal',
        items: [
            { name: 'Mi Dashboard', route: 'dashboard', icon: 'dashboard', badge: null },
        ]
    },
    {
        section: 'Operaciones',
        items: [
            { name: 'Nueva Cotización', route: 'quotes.create', icon: 'plus', badge: null, highlight: true },
            { name: 'Mis Cotizaciones', route: 'quotes.index', icon: 'document', badge: { count: 5, type: 'warning' } },
            { name: 'Seguimientos Hoy', route: 'follow-ups.today', icon: 'calendar', badge: { count: 3, type: 'info' }, disabled: true },
        ]
    },
    {
        section: 'CRM',
        items: [
            { name: 'Mis Clientes', route: 'customers.index', icon: 'users', badge: null },
            { name: 'Contactos', route: 'contacts.index', icon: 'user-group', badge: null },
            { name: 'Pólizas', route: 'policies.index', icon: 'shield', badge: null, disabled: true },
        ]
    }
];

const managerNav = [
    { 
        section: 'Principal',
        items: [
            { name: 'Dashboard Equipo', route: 'dashboard', icon: 'dashboard', badge: null },
        ]
    },
    {
        section: 'Supervisión',
        items: [
            { name: 'Nueva Cotización', route: 'quotes.create', icon: 'plus', badge: null, highlight: true },
            { name: 'Cotizaciones', route: 'quotes.index', icon: 'document', badge: { count: 12, type: 'warning' } },
            { name: 'Clientes', route: 'customers.index', icon: 'users', badge: null },
            { name: 'Contactos', route: 'contacts.index', icon: 'user-group', badge: null },
        ]
    },
    {
        section: 'Equipo',
        items: [
            { name: 'Mi Equipo', route: 'team.index', icon: 'user-group', badge: null, disabled: true },
            { name: 'Reportes', route: 'reports.index', icon: 'chart', badge: null, disabled: true },
        ]
    }
];

const adminNav = [
    { 
        section: 'Principal',
        items: [
            { name: 'Dashboard Admin', route: 'dashboard', icon: 'dashboard', badge: null },
        ]
    },
    {
        section: 'Operaciones',
        items: [
            { name: 'Nueva Cotización', route: 'quotes.create', icon: 'plus', badge: null, highlight: true },
            { name: 'Cotizaciones', route: 'quotes.index', icon: 'document', badge: null },
            { name: 'Clientes', route: 'customers.index', icon: 'users', badge: null },
            { name: 'Contactos', route: 'contacts.index', icon: 'user-group', badge: null },
        ]
    },
    {
        section: 'Recursos Humanos',
        items: [
            { name: 'Puestos', route: 'admin.positions.index', icon: 'briefcase', badge: null },
            { name: 'Personal', route: 'admin.staff.index', icon: 'id-card', badge: null },
        ]
    },
    {
        section: 'Catálogos',
        items: [
            { name: 'Marcas Vehículos', route: 'admin.vehicle-brands.index', icon: 'car', badge: null },
            { name: 'Aseguradoras', route: 'admin.insurers.index', icon: 'building', badge: null },
            { name: 'Tipos de Vehículo', route: 'admin.vehicle-types.index', icon: 'truck', badge: null },
            { name: 'Tipos de Contacto', route: 'admin.contact-types.index', icon: 'users', badge: null },
            { name: 'Paquetes Cobertura', route: 'admin.coverage-packages.index', icon: 'shield', badge: null },
            { name: 'Deducibles', route: 'admin.deductible-options.index', icon: 'chart', badge: null },
            { name: 'Formas de Pago', route: 'admin.payment-methods.index', icon: 'currency', badge: null },
            { name: 'Derechos de Póliza', route: 'admin.policy-fees.index', icon: 'shield', badge: null },
            { name: 'Recargos', route: 'admin.surcharges.index', icon: 'chart', badge: null },
            { name: 'Estados', route: 'admin.mexican-states.index', icon: 'shield', badge: null },
        ]
    },
    {
        section: 'Sistema',
        items: [
            { name: 'Usuarios', route: 'admin.users.index', icon: 'user-group', badge: null },
            { name: 'Roles y Permisos', route: 'roles.index', icon: 'key', badge: null, disabled: true },
            { name: 'Auditoría', route: 'admin.audit.index', icon: 'clipboard', badge: null },
        ]
    },
    {
        section: 'Reportes',
        items: [
            { name: 'Producción', route: 'reports.production', icon: 'chart', badge: null, disabled: true },
            { name: 'Conversión', route: 'reports.conversion', icon: 'trending', badge: null, disabled: true },
        ]
    }
];

const navigation = computed(() => {
    switch (userRole.value) {
        case 'admin':
        case 'super_admin':
            return adminNav;
        case 'manager':
            return managerNav;
        default:
            return operatorNav;
    }
});

// ===== NOTIFICATIONS (Mock) =====
const notifications = ref([
    { id: 1, type: 'warning', title: 'Cotización por vencer', message: 'COT-2401-0034 vence mañana', time: 'Hace 5 min' },
    { id: 2, type: 'info', title: 'Seguimiento programado', message: 'Juan Pérez - 3:00 PM', time: 'Hace 1 hora' },
    { id: 3, type: 'success', title: 'Cotización concluida', message: 'María García aceptó HDI Amplio', time: 'Hace 2 horas' },
]);

// ===== ICONS =====
const getIcon = (iconName) => {
    const icons = {
        'dashboard': 'M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z',
        'plus': 'M12 4v16m8-8H4',
        'document': 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'calendar': 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        'users': 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        'shield': 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
        'user-group': 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        'building': 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
        'chart': 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'cog': 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
        'clipboard': 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
        'key': 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z',
        'currency': 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'trending': 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
        'check-circle': 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'clock': 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'car': 'M8 7h8l2 5H6l2-5zm11 5v7a1 1 0 01-1 1h-1a1 1 0 01-1-1v-1H8v1a1 1 0 01-1 1H6a1 1 0 01-1-1v-7l-1-3a2 2 0 012-2h12a2 2 0 012 2l-1 3zm-9 2a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm8 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z',
        'truck': 'M8 17a2 2 0 11-4 0 2 2 0 014 0zm12 0a2 2 0 11-4 0 2 2 0 014 0zM6 5v6h14V5H6zm0 8v2h2a4 4 0 014 0h4a4 4 0 014 0h2v-2H6z',
        'briefcase': 'M20 7h-4V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v3H4a2 2 0 00-2 2v11a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM10 4h4v3h-4V4z',
        'id-card': 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2',
    };
    return icons[iconName] || icons['dashboard'];
};

// ===== ACTIONS =====
const logout = () => {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = route('logout');
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
    form.appendChild(csrfToken);
    document.body.appendChild(form);
    form.submit();
};

const isActiveRoute = (routeName) => {
    try {
        return route().current(routeName);
    } catch {
        return false;
    }
};


// Lock body scroll when mobile sidebar is open
watch(sidebarOpen, (open) => {
    if (!isDesktop.value) {
        document.body.style.overflow = open ? 'hidden' : '';
    }
});

// Close dropdowns on click outside
const closeDropdowns = () => {
    showUserMenu.value = false;
    showNotifications.value = false;
};
</script>

<template>
    <div class="app-layout" @click="closeDropdowns">
        <!-- Mobile Sidebar Backdrop -->
        <Transition name="fade">
            <div v-if="sidebarOpen && !isDesktop"
                 class="sidebar-backdrop"
                 @click="sidebarOpen = false"></div>
        </Transition>

        <!-- ===== SIDEBAR ===== -->
        <aside class="sidebar" 
               :style="{ 
                   transform: (isDesktop || sidebarOpen) ? 'translateX(0)' : 'translateX(-100%)',
                   width: (isDesktop && sidebarCollapsed) ? '80px' : '280px',
                   position: isDesktop ? 'relative' : 'fixed'
               }"
               :class="{ 
                   'sidebar--collapsed': sidebarCollapsed,
                   'sidebar--open': sidebarOpen 
               }">
            
            <!-- Logo -->
            <div class="sidebar__header" :class="{ 'sidebar__header--collapsed': sidebarCollapsed }">
                <div class="sidebar__logo">
                    <div class="sidebar__logo-icon"
                         :class="{ 'sidebar__logo-icon--clickable': sidebarCollapsed && isDesktop }"
                         @click="sidebarCollapsed && isDesktop && (sidebarCollapsed = false)"
                         :title="sidebarCollapsed ? 'Expandir menú' : ''">
                        <span class="sidebar__logo-letter">C</span>
                    </div>
                    <div v-if="!sidebarCollapsed" class="sidebar__logo-text">
                        <span class="sidebar__logo-name">CONOCE</span>
                        <span class="sidebar__logo-tagline">Cotizador Pro</span>
                    </div>
                </div>

                <button v-if="isDesktop && !sidebarCollapsed"
                        @click="sidebarCollapsed = true"
                        class="sidebar__toggle"
                        style="display: flex !important"
                        title="Colapsar menú">
                    <svg class="w-4 h-4 transition-transform duration-300"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
                
                <button v-if="!isDesktop && sidebarOpen" @click="sidebarOpen = false" class="sidebar__close" style="display: flex !important">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="sidebar__nav">
                <div v-for="group in navigation" :key="group.section" class="nav-group">
                    <div v-if="!sidebarCollapsed" class="nav-group__title">{{ group.section }}</div>
                    
                    <template v-for="item in group.items" :key="item.name">
                        <!-- Active link -->
                        <Link v-if="!item.disabled"
                              :href="route(item.route)"
                              class="nav-item"
                              :class="{
                                  'nav-item--active': isActiveRoute(item.route),
                                  'nav-item--highlight': item.highlight
                              }"
                              @click="!isDesktop && (sidebarOpen = false)">
                            <div class="nav-item__icon">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="getIcon(item.icon)" />
                                </svg>
                            </div>
                            <span v-if="!sidebarCollapsed" class="nav-item__label">{{ item.name }}</span>
                            <span v-if="item.badge && !sidebarCollapsed" 
                                  class="nav-item__badge"
                                  :class="`nav-item__badge--${item.badge.type}`">
                                {{ item.badge.count }}
                            </span>
                        </Link>
                        
                        <!-- Disabled link -->
                        <div v-else
                             class="nav-item nav-item--disabled">
                            <div class="nav-item__icon">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="getIcon(item.icon)" />
                                </svg>
                            </div>
                            <span v-if="!sidebarCollapsed" class="nav-item__label">{{ item.name }}</span>
                            <span v-if="!sidebarCollapsed" class="nav-item__soon">Próx.</span>
                        </div>
                    </template>
                </div>
            </nav>

            <!-- User Section -->
            <div class="sidebar__user">
                <div class="user-card" @click.stop="showUserMenu = !showUserMenu">
                    <div class="user-card__avatar">{{ userInitials }}</div>
                    <div v-if="!sidebarCollapsed" class="user-card__info">
                        <span class="user-card__name">{{ user.name || 'Usuario' }}</span>
                        <span class="user-card__role">{{ userRole }}</span>
                    </div>
                    <svg v-if="!sidebarCollapsed" 
                         class="user-card__arrow"
                         :class="{ 'rotate-180': showUserMenu }"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
                
                <Transition name="slide-up">
                    <div v-if="showUserMenu && !sidebarCollapsed" class="user-menu" @click.stop>
                        <a href="#" class="user-menu__item">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Mi Perfil</span>
                        </a>
                        <a href="#" class="user-menu__item">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getIcon('cog')" />
                            </svg>
                            <span>Preferencias</span>
                        </a>
                        <div class="user-menu__divider"></div>
                        <button @click="logout" class="user-menu__item user-menu__item--danger">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Cerrar Sesión</span>
                        </button>
                    </div>
                </Transition>
            </div>
        </aside>

        <!-- ===== MAIN CONTENT ===== -->
        <main class="main-content">
            <!-- Header -->
            <header class="main-header">
                <div class="main-header__left">
                    <button v-if="!isDesktop" @click="sidebarOpen = true" class="main-header__menu">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <div class="main-header__greeting">
                        <span class="greeting-text">{{ greeting }}, </span>
                        <span class="greeting-name">{{ user.name?.split(' ')[0] || 'Usuario' }}</span>
                    </div>
                </div>

                <div class="main-header__right">
                    <!-- Date/Time -->
                    <div class="header-datetime">
                        <span class="header-datetime__time">{{ currentTime }}</span>
                        <span class="header-datetime__date">{{ currentDate }}</span>
                    </div>

                    <!-- Notifications -->
                    <div class="header-notifications" @click.stop>
                        <button 
                            class="header-btn"
                            @click="showNotifications = !showNotifications"
                            :class="{ 'header-btn--active': showNotifications }">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="header-btn__badge">{{ notifications.length }}</span>
                        </button>
                        
                        <Transition name="dropdown">
                            <div v-if="showNotifications" class="notifications-dropdown">
                                <div class="notifications-dropdown__header">
                                    <span class="font-semibold">Notificaciones</span>
                                    <button class="text-xs text-primary hover:underline">Marcar leídas</button>
                                </div>
                                <div class="notifications-dropdown__list">
                                    <div v-for="notif in notifications" :key="notif.id" class="notification-item">
                                        <div class="notification-item__icon" :class="`notification-item__icon--${notif.type}`">
                                            <svg v-if="notif.type === 'warning'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <svg v-else-if="notif.type === 'success'" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="notification-item__content">
                                            <div class="notification-item__title">{{ notif.title }}</div>
                                            <div class="notification-item__message">{{ notif.message }}</div>
                                            <div class="notification-item__time">{{ notif.time }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="notifications-dropdown__footer">
                                    <a href="#" class="text-sm text-primary hover:underline">Ver todas →</a>
                                </div>
                            </div>
                        </Transition>
                    </div>

                </div>
            </header>

            <!-- Page Content -->
            <div class="main-content__body" scroll-region>
                <slot />
            </div>
        </main>
    </div>
</template>

<style scoped>
/* ===== CSS VARIABLES ===== */
:root {
    --sidebar-width: 280px;
    --sidebar-collapsed-width: 80px;
    --header-height: 64px;
    --primary: #7B2D3B;
    --primary-dark: #5C1D2A;
    --secondary: #C7A172;
}

/* ===== LAYOUT ===== */
.app-layout {
    display: flex;
    height: 100vh;
    overflow: hidden;
    background: #F8FAFC;
}

/* ===== BACKDROP ===== */
.sidebar-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 40;
}

/* ===== SIDEBAR ===== */
.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 50;
    width: 280px;
    display: flex;
    flex-direction: column;
    background: linear-gradient(180deg, #7B2D3B 0%, #4A1A25 50%, #2D0F16 100%);
    box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
    transform: translateX(-100%);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar--open {
    transform: translateX(0);
}

@media (min-width: 1024px) {
    .sidebar {
        position: relative;
        transform: translateX(0);
    }
    
    .sidebar--collapsed {
        width: 80px;
    }
}

/* ===== SIDEBAR HEADER ===== */
.sidebar__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    min-height: 72px;
}

.sidebar__logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.sidebar__header--collapsed {
    justify-content: center;
    padding: 1rem 0.5rem;
}

.sidebar__logo-icon {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, #C7A172 0%, #A8855C 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    transition: transform 0.2s, box-shadow 0.2s;
}

.sidebar__logo-icon--clickable {
    cursor: pointer;
}

.sidebar__logo-icon--clickable:hover {
    transform: scale(1.08);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
}

.sidebar__logo-letter {
    font-size: 1.5rem;
    font-weight: 800;
    color: #2D0F16;
}

.sidebar__logo-text {
    display: flex;
    flex-direction: column;
}

.sidebar__logo-name {
    font-size: 1.125rem;
    font-weight: 700;
    color: white;
    letter-spacing: -0.025em;
}

.sidebar__logo-tagline {
    font-size: 0.625rem;
    color: rgba(255, 255, 255, 0.5);
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

.sidebar__toggle,
.sidebar__close {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255, 255, 255, 0.5);
    border-radius: 8px;
    border: none;
    background: transparent;
    cursor: pointer;
    transition: all 0.2s;
}

.sidebar__toggle:hover,
.sidebar__close:hover {
    color: white;
    background: rgba(255, 255, 255, 0.1);
}

/* ===== NAVIGATION ===== */
.sidebar__nav {
    flex: 1;
    overflow-y: auto;
    padding: 0.75rem;
    scrollbar-width: thin;
    scrollbar-color: rgba(199, 161, 114, 0.4) transparent;
}

.sidebar__nav::-webkit-scrollbar {
    width: 5px;
}

.sidebar__nav::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar__nav::-webkit-scrollbar-thumb {
    background: rgba(199, 161, 114, 0.35);
    border-radius: 10px;
}

.sidebar__nav::-webkit-scrollbar-thumb:hover {
    background: rgba(199, 161, 114, 0.6);
}

.nav-group {
    margin-bottom: 1.5rem;
}

.nav-group__title {
    font-size: 0.625rem;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.35);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    padding: 0 0.75rem;
    margin-bottom: 0.5rem;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.625rem 0.75rem;
    margin-bottom: 2px;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.2s;
    position: relative;
    cursor: pointer;
}

.nav-item:hover:not(.nav-item--disabled) {
    background: rgba(255, 255, 255, 0.08);
    color: white;
}

.nav-item--active {
    background: rgba(255, 255, 255, 0.12) !important;
    color: white !important;
}

.nav-item--active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 20px;
    background: #C7A172;
    border-radius: 0 3px 3px 0;
}

.nav-item--active .nav-item__icon {
    color: #C7A172 !important;
}

.nav-item--highlight {
    background: linear-gradient(135deg, rgba(199, 161, 114, 0.2) 0%, rgba(199, 161, 114, 0.1) 100%);
    border: 1px solid rgba(199, 161, 114, 0.3);
}

.nav-item--disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.nav-item__icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    color: rgba(255, 255, 255, 0.6);
    flex-shrink: 0;
    transition: all 0.2s;
}

.nav-item:hover:not(.nav-item--disabled) .nav-item__icon {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

.nav-item__label {
    font-size: 0.875rem;
    font-weight: 500;
    flex: 1;
}

.nav-item__badge {
    padding: 0.125rem 0.5rem;
    font-size: 0.625rem;
    font-weight: 700;
    border-radius: 9999px;
}

.nav-item__badge--warning {
    background: #F59E0B;
    color: white;
}

.nav-item__badge--danger {
    background: #EF4444;
    color: white;
}

.nav-item__badge--info {
    background: #3B82F6;
    color: white;
}

.nav-item__soon {
    font-size: 0.5rem;
    font-weight: 700;
    color: #C7A172;
    text-transform: uppercase;
    padding: 0.125rem 0.375rem;
    background: rgba(199, 161, 114, 0.15);
    border-radius: 4px;
}

/* ===== USER SECTION ===== */
.sidebar__user {
    padding: 0.75rem;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(0, 0, 0, 0.2);
    position: relative;
}

.user-card {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.625rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
}

.user-card:hover {
    background: rgba(255, 255, 255, 0.08);
}

.user-card__avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #C7A172 0%, #A8855C 100%);
    color: #2D0F16;
    font-weight: 700;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    flex-shrink: 0;
}

.user-card__info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
}

.user-card__name {
    font-size: 0.875rem;
    font-weight: 600;
    color: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-card__role {
    font-size: 0.6875rem;
    color: rgba(255, 255, 255, 0.5);
    text-transform: capitalize;
}

.user-card__arrow {
    width: 16px;
    height: 16px;
    color: rgba(255, 255, 255, 0.4);
    transition: transform 0.2s;
}

.user-menu {
    position: absolute;
    bottom: 100%;
    left: 0.75rem;
    right: 0.75rem;
    margin-bottom: 0.5rem;
    background: #1F1215;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
}

.user-menu__item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.2s;
    border: none;
    background: transparent;
    width: 100%;
    cursor: pointer;
}

.user-menu__item svg {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

.user-menu__item:hover {
    background: rgba(255, 255, 255, 0.08);
    color: white;
}

.user-menu__item--danger:hover {
    background: rgba(239, 68, 68, 0.15);
    color: #FCA5A5;
}

.user-menu__divider {
    height: 1px;
    background: rgba(255, 255, 255, 0.08);
}

/* ===== MAIN CONTENT ===== */
.main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
    overflow: hidden;
}

/* ===== HEADER ===== */
.main-header {
    height: 64px;
    min-height: 64px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1rem;
    background: white;
    border-bottom: 1px solid #E5E7EB;
    z-index: 20;
    gap: 0.5rem;
    position: sticky;
    top: 0;
    flex-shrink: 0;
}

@media (min-width: 768px) {
    .main-header {
        padding: 0 1.5rem;
        gap: 1rem;
    }
}

.main-header__left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.main-header__menu {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6B7280;
    border: none;
    background: transparent;
    border-radius: 10px;
    cursor: pointer;
}

.main-header__menu:hover {
    background: #F3F4F6;
    color: #7B2D3B;
}

.main-header__greeting {
    font-size: 0.875rem;
}

@media (min-width: 768px) {
    .main-header__greeting {
        font-size: 0.9375rem;
    }
}

.greeting-text {
    color: #6B7280;
}

.greeting-name {
    font-weight: 600;
    color: #111827;
}

.main-header__right {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* DateTime */
.header-datetime {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    padding: 0 0.5rem;
    border-left: 1px solid #E5E7EB;
}

@media (min-width: 768px) {
    .header-datetime {
        padding: 0 1rem;
    }
}

.header-datetime__time {
    font-size: 0.8125rem;
    font-weight: 700;
    color: #111827;
    font-family: 'JetBrains Mono', monospace;
}

@media (min-width: 768px) {
    .header-datetime__time {
        font-size: 0.9375rem;
    }
}

.header-datetime__date {
    font-size: 0.5625rem;
    color: #6B7280;
    text-transform: capitalize;
    white-space: nowrap;
}

@media (min-width: 768px) {
    .header-datetime__date {
        font-size: 0.6875rem;
    }
}

/* Header Buttons */
.header-btn {
    position: relative;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6B7280;
    background: transparent;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
}

.header-btn:hover,
.header-btn--active {
    background: #F3F4F6;
    color: #7B2D3B;
}

.header-btn__badge {
    position: absolute;
    top: 4px;
    right: 4px;
    min-width: 18px;
    height: 18px;
    padding: 0 4px;
    background: #EF4444;
    color: white;
    font-size: 0.625rem;
    font-weight: 700;
    border-radius: 9999px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
}

/* Notifications Dropdown */
.header-notifications {
    position: relative;
}

.notifications-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    width: 360px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    border: 1px solid #E5E7EB;
    z-index: 100;
}

.notifications-dropdown__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #E5E7EB;
}

.notifications-dropdown__list {
    max-height: 320px;
    overflow-y: auto;
}

.notification-item {
    display: flex;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    transition: background 0.2s;
}

.notification-item:hover {
    background: #F9FAFB;
}

.notification-item__icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    flex-shrink: 0;
}

.notification-item__icon--warning {
    background: #FEF3C7;
    color: #D97706;
}

.notification-item__icon--success {
    background: #D1FAE5;
    color: #059669;
}

.notification-item__icon--info {
    background: #DBEAFE;
    color: #2563EB;
}

.notification-item__content {
    flex: 1;
    min-width: 0;
}

.notification-item__title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #111827;
}

.notification-item__message {
    font-size: 0.8125rem;
    color: #6B7280;
    margin-top: 0.125rem;
}

.notification-item__time {
    font-size: 0.6875rem;
    color: #9CA3AF;
    margin-top: 0.25rem;
}

.notifications-dropdown__footer {
    padding: 0.75rem 1rem;
    border-top: 1px solid #E5E7EB;
    text-align: center;
}

/* ===== CONTENT BODY ===== */
.main-content__body {
    flex: 1;
    overflow: auto;
    background: #F8FAFC;
}

/* ===== UTILITIES ===== */
.text-primary {
    color: #7B2D3B;
}

.rotate-180 {
    transform: rotate(180deg);
}

/* ===== TRANSITIONS ===== */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-up-enter-active,
.slide-up-leave-active {
    transition: all 0.2s ease;
}

.slide-up-enter-from,
.slide-up-leave-to {
    opacity: 0;
    transform: translateY(8px);
}

.dropdown-enter-active,
.dropdown-leave-active {
    transition: all 0.2s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}

/* ===== RESPONSIVE — FASE 1 ===== */
@media (max-width: 425px) {
    .notifications-dropdown {
        width: calc(100vw - 2rem);
        max-width: 360px;
        right: -0.5rem;
    }
}

/* ===== RESPONSIVE — CIERRE ENTERPRISE ===== */

/* E-1: Sidebar proporcional */
@media (max-width: 640px) {
    .sidebar {
        width: min(90vw, 280px);
    }
}

/* E-2: Header legibility hardening */
@media (max-width: 640px) {
    .header-datetime {
        font-size: 0.75rem;
    }
}
</style>
