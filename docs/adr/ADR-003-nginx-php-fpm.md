# ADR-003 - Nginx + PHP-FPM

## Decisión
Nginx servirá las peticiones HTTP y PHP-FPM ejecutará Laravel.

## Motivos
- Arquitectura estándar.
- Similar a producción.
- Mejor separación de responsabilidades.