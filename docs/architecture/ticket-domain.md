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

La aplicación utilizará tres roles:

- User
- Technician
- Administrator

Estos son los nombres canónicos que deben almacenarse en `roles.name`. El valor `Admin` no debe utilizarse como alias. Al implementar la autorización, se actualizarán el seeder y cualquier dato existente para usar `Administrator` antes de depender de la comprobación del rol.

El registro público siempre asigna el rol `User`; los usuarios no pueden elegir ni elevar su propio rol durante el registro.

## Vistas y permisos

### User

- Puede crear tickets.
- Puede consultar únicamente los tickets que ha solicitado.
- No puede consultar ni modificar tickets de otros usuarios, aunque conozca su URL.

### Technician

- Puede consultar y gestionar todos los tickets.
- Puede actualizar la información del ticket y gestionar su flujo de trabajo.
- No puede enviar tickets a la papelera ni restaurarlos.

### Administrator

- Tiene las mismas vistas y capacidades de gestión que Technician.
- Puede enviar tickets a la papelera y restaurar tickets eliminados mediante soft delete.
- Puede ver un campo adicional del CRUD de tickets que no será visible para User ni Technician.
- El nombre y el propósito de ese campo quedan pendientes de definición antes de implementarlo.

La autorización debe aplicarse en el servidor, no solo ocultando controles en las vistas. Las reglas de acceso a un ticket deben comprobar tanto el rol como la propiedad cuando corresponda. El borrado administrativo debe conservar el registro mediante soft delete; no se eliminará físicamente de la base de datos.

## Gestión de usuarios

Solo `Administrator` puede listar, crear y modificar usuarios, asignar roles o cambiar contraseñas desde la aplicación.

La acción de eliminar un usuario es una desactivación lógica: se conserva el registro, su rol y las relaciones con tickets y comentarios, y se muestra la etiqueta `Inactive` junto a su nombre en la gestión de usuarios. Las cuentas inactivas no pueden iniciar sesión; una sesión abierta se cierra en su siguiente petición autenticada. Un administrador puede reactivar la cuenta.

El último administrador activo no puede desactivarse ni cambiarse a otro rol, para evitar dejar el sistema sin una cuenta administrativa.

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