# Base de datos - TicketOPS

## Objetivo

Este directorio contiene toda la documentación relacionada con el diseño de la base de datos del proyecto.

Antes de implementar las migraciones de Laravel se diseña la estructura completa para asegurar que el modelo de datos es correcto.

---

## Archivos

### schema.dbml

Código fuente del modelo de base de datos utilizando DBML.

Puede abrirse directamente en:

https://dbdiagram.io

---

### schema.png

Diagrama visual exportado desde dbdiagram.

Permite comprender rápidamente la relación entre las tablas.

---

## Modelo actual

Actualmente existen las siguientes entidades:

- Users
- Roles
- Tickets
- Ticket Status
- Priorities
- Categories

---

## Principios de diseño

- UUID como clave primaria para entidades principales.
- Tablas pequeñas para datos estáticos (roles, prioridades, estados...).
- Relaciones mediante claves foráneas.
- Campos `created_at` y `updated_at` para auditoría.
- Diseño preparado para futuras ampliaciones sin romper compatibilidad.

---

## Futuras mejoras

En próximos sprints se añadirán nuevas tablas como:

- Comentarios del ticket
- Historial de estados
- Adjuntos
- Etiquetas
- Auditoría
- Notificaciones