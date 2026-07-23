# Architecture Decision Records

## ADR-001

Se utilizará PostgreSQL como base de datos principal.

Motivos

- Muy utilizado en empresas.
- Excelente integración con Laravel.
- Compatible con futuras migraciones a producción.

---

## ADR-002

La aplicación se ejecutará mediante Docker Compose.

Motivos

- Entorno reproducible.
- Separación de servicios.
- Facilita el desarrollo.

---

## ADR-003

Nginx actuará como servidor web.

PHP-FPM ejecutará Laravel.

Motivos

- Arquitectura estándar.
- Similar a producción.