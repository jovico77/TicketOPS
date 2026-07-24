# TicketOPS - Ticket Domain

## Objetivo

Definir el modelo funcional del sistema de tickets antes de comenzar su implementación en Laravel.

---

# Flujo de estados

Todo ticket seguirá el siguiente ciclo de vida:

Open
   ↓
Assigned
   ↓
In Progress
   ↓
Resolved
   ↓
Closed


En caso de que el usuario siga teniendo el problema:

Resolved
   ↓
Reopened
   ↓
Assigned

## Descripción de cada estado

### Open

El ticket acaba de ser creado.

Todavía no ha sido revisado por ningún técnico.

---

### Assigned

El ticket ha sido clasificado y asignado a un departamento o técnico.

Todavía nadie ha comenzado a trabajar sobre él.

---

### In Progress

Un técnico está trabajando activamente en la incidencia.

---

### Resolved

Existe una resolución para el problema.

El usuario puede reabrir el ticket si el problema continúa.

---

### Closed

El ticket queda completamente finalizado.

No debería modificarse nuevamente.

---

### Reopened

El usuario indica que el problema persiste.

El ticket vuelve al flujo de trabajo.

---

# Roles

Actualmente existen tres roles.

- User
- Technician
- Admin

## Permisos

### Crear tickets

- User
- Technician
- Admin

### Cambiar prioridad

- Technician
- Admin

### Resolver tickets

- Technician
- Admin

---

# Estructura del ticket

Cada ticket contendrá, como mínimo:

- UUID interno
- Número visible (TKT-2026-000001)
- Título
- Descripción
- Estado
- Prioridad
- Categoría
- Subcategoría
- Usuario creador
- Técnico asignado
- Resolución
- Tipo de resolución
- Fechas de creación, actualización, resolución y cierre

---

# Categorías

Las categorías agrupan grandes áreas.

Ejemplos:

- Software
- Hardware
- Network
- Other

---

# Subcategorías

Cada categoría contiene sus propias subcategorías.

Ejemplo:

Software

- Outlook
- Office 365
- VPN
- Active Directory
- Teams

Hardware

- Laptop
- Desktop
- Monitor
- Printer

---

# Comentarios

Cada ticket podrá contener múltiples comentarios.

Los comentarios almacenarán el seguimiento técnico de la incidencia.

La resolución final no formará parte de los comentarios, sino que será un campo independiente del ticket.

---

# Resolución

Todo ticket resuelto almacenará:

- Texto de resolución.
- Tipo de resolución.

Ejemplos de tipos:

- Manual Execution
- Password Reset
- Configuration Change
- Software Installation
- Hardware Replacement
- Escalated
- Vendor Fix

---

# Funcionalidades futuras

## IA

La IA podrá utilizar la información del ticket para:

- Generar un resumen automático.
- Encontrar incidencias similares.
- Recomendar documentación.
- Crear una futura base de conocimiento.

---

## Historial

En futuras versiones se registrará un historial completo de cambios:

- Cambio de estado.
- Cambio de prioridad.
- Cambio de técnico asignado.
- Reaperturas.
- Cambios de categoría.

---

## Departamentos

En una futura versión los tickets podrán asignarse inicialmente a departamentos:

- Helpdesk
- Systems
- Networking
- Security
- Development

Posteriormente un técnico del departamento asumirá el ticket.