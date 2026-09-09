# Entorno de Desarrollo

## WSL2

TicketOPS se desarrolla utilizando WSL2 (Windows Subsystem for Linux 2) con Ubuntu 24.04.

El proyecto está almacenado dentro del sistema de archivos de Linux de WSL2:

/home/joelvicente/projects/TicketOPS

---

## ¿Por qué WSL2?

TicketOPS utiliza contenedores Docker que ejecutan servicios basados en Linux, como:

Nginx
PHP-FPM
PostgreSQL

Inicialmente, el proyecto estaba almacenado en el sistema de archivos de Windows y se montaba en Docker mediante un bind mount (función que conecta archivos con ordenador principal al interior de un contenedor).

Esto provocaba importantes problemas de rendimiento del sistema de archivos, especialmente con Laravel y Composer, debido a la sobrecarga de acceder a los archivos entre los entornos de Windows y Linux.

| Operación               | Windows filesystem | WSL2 filesystem |
| ----------------------- | -----------------: | --------------: |
| `php artisan --version` |             ~7.3 s |         ~0.09 s |
| Laravel `/test`         |             ~4.2 s |         ~0.15 s |


Después de mover el proyecto al sistema de archivos de Linux de WSL2, las mismas operaciones pasaron a ser mucho más rápidas.

---

## Flujo de trabajo recomendado

cd ~/projects/TicketOPS
code .

VS Code debería mostrar WSL: Ubuntu en la esquina inferior izquierda.

Los comandos de Docker, Laravel Artisan y Git deben ejecutarse desde el terminal de WSL2.