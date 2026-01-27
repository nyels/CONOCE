ESPECIFICACIÓN MAESTRA
SISTEMA EMPRESARIAL DE COTIZACIÓN Y CRM DE PÓLIZAS

Autos · Escalable · Fintech-grade

Estado: Documento Canónico Bloqueado
Uso: Base única de conocimiento (SSOT) + Prompt IA
Audiencia: IAs avanzadas (Claude / GPT / Gemini), Arquitectos Senior, Líderes de Producto
Nivel: Enterprise / Financiero / Producción real

PARTE I — DOCUMENTO MAESTRO EMPRESARIAL
(BASE DE CONOCIMIENTO PERMANENTE)
1. PROPÓSITO DEL DOCUMENTO

Este documento define de forma exhaustiva, técnica y no ambigua el sistema a construir.

Su uso es obligatorio como:

Fuente única de verdad (SSOT)

Base de conocimiento permanente

Entrada directa para IAs (Claude, GPT, Gemini)

Documento rector para arquitectura, UX y desarrollo

❗ Cualquier IA o desarrollador debe seguir este documento literalmente.

2. VISIÓN DEL SISTEMA

Plataforma web empresarial / financiera que permite:

Cotizar seguros automotrices multiaseguradora

Comparar opciones de forma clara y objetiva

Emitir documentos PDF corporativos

Gestionar clientes y seguimientos tipo CRM

Medir desempeño comercial

Operar con roles, auditoría y trazabilidad total

El sistema NO es un demo, NO es un admin genérico, es un producto comercial real.

3. ALCANCE FUNCIONAL PRINCIPAL
3.1 Cotizaciones

Tipo:

NUEVA

RENOVACIÓN

Asociación obligatoria:

Cliente

Operador

Captura de:

Datos del vehículo

Uso

Coberturas

Registro de múltiples opciones por aseguradora (2–5)

Estados del ciclo de vida:

DRAFT → SENT → FOLLOW_UP → CONCLUDED
                  ↓
              REJECTED
                  ↓
               EXPIRED

3.2 Comparativas

Comparación automática entre opciones

Cálculo de:

Prima neta

Derechos

Recargos

Impuestos

Prima total

Identificación clara de:

Opción recomendada

Mejor costo

Mejor cobertura

3.3 PDF

Generación automática

Diseño corporativo financiero

Versionado

Registro en auditoría

Descarga y envío controlado

4. CRM Y SEGUIMIENTO
4.1 Clientes (CRUD)

Personas físicas

Personas morales

Historial completo

Relación con:

Cotizaciones

Pólizas

Interacciones

4.2 Seguimientos

Registro de llamadas, correos y notas

Programación de recordatorios

Alertas por:

Vencimientos

Renovaciones

Inactividad

5. ROLES Y PERMISOS

Roles definidos:

SUPER_ADMIN

ADMIN

MANAGER

OPERATOR

VIEWER

Permisos:

Granulares por módulo

Auditables

No hardcodeados

6. REPORTES

Cotizaciones por periodo

Conversión

Renovaciones vs nuevas

Desempeño por operador

Métricas comerciales reales

7. AUDITORÍA (OBLIGATORIA)

Todo evento relevante genera:

Usuario

Rol

Acción

Entidad afectada

Valores antes / después

IP

User Agent

Timestamp

❗ No existe acción silenciosa.

8. ARQUITECTURA TÉCNICA

Backend: Laravel 12

Arquitectura: Hexagonal / Clean

Frontend: Vue 3 + Inertia

Estado: Pinia

Async: Redis + Queues

API-first

Seguridad y escalabilidad reales

9. MODELO DE DOMINIO (RESUMEN)

Entidades principales:

User

Customer

Insurer

Quote

QuoteOption

Policy

ActivityLog

Relaciones normalizadas y auditables.

10. UX / UI — REQUISITO CRÍTICO (BLOQUEADO)
10.1 PRINCIPIOS DE DISEÑO (NO INTERPRETABLES)

❌ NO dashboards genéricos
❌ NO plantillas admin
❌ NO sobreestilización
❌ NO creatividad libre

✅ Diseño sobrio, financiero, profesional
✅ Jerarquía visual clara
✅ Espacios amplios
✅ Claridad antes que decoración

10.2 DESIGN SYSTEM BLOQUEADO (OBLIGATORIO)

Escala tipográfica:

Título principal: text-lg

Subtítulos: text-sm font-semibold

Texto normal: text-sm

Metadata: text-xs

❌ Prohibido mezclar escalas arbitrarias.

Colores permitidos:

Gris / Slate

Azul sobrio como acento

Verde / Rojo solo para estados semánticos

❌ Prohibido inventar paletas.

Componentes base permitidos:

Cards planas

Tablas limpias

Modales sobrios

Formularios guiados (wizard)

❌ Prohibido:

Gradientes innecesarios

Sombras exageradas

Bordes redundantes

10.3 JERARQUÍA VISUAL OBLIGATORIA

Toda pantalla debe responder claramente:

¿Quién hizo qué?

¿En qué estado está?

¿Qué acción sigue?

Detalles secundarios

❗ La IA no decide jerarquía, la sigue.

10.4 RESPONSIVE & SPA

100% responsive

Comportamiento tipo web app

Navegación fluida

Estados persistentes

Optimizado para operadores reales

PARTE II — PROMPT MAESTRO PARA IA
INSTRUCCIONES PARA LA IA

Actúa como:

Arquitecto de Software Senior

Diseñador UX/UI Empresarial Financiero

Consultor de Producto SaaS

Nivel esperado: Enterprise / Fintech / Banking-grade

OBJETIVO DE LA IA

Diseñar e implementar exactamente el sistema definido en la PARTE I, sin simplificar ni reinterpretar.

ENTREGABLES ESPERADOS

Arquitectura técnica detallada

Flujos UX/UI (no mockups genéricos)

Modelo de datos final

Plan de implementación por fases

Riesgos reales y mitigaciones

REGLAS ESTRICTAS PARA LA IA

❌ NO simplificar
❌ NO asumir lógica no definida
❌ NO inventar diseño
❌ NO usar dashboards genéricos

✅ Pensar como producto comercial real
✅ Seguir el Design System bloqueado
✅ Priorizar claridad operativa

RESULTADO FINAL ESPERADO

Un sistema:

Comercialmente viable

Técnicamente sólido

Visualmente profesional

Auditable

Escalable a múltiples ramos

FIN DEL DOCUMENTO CANÓNICO