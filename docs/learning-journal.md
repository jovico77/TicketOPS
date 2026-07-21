# TicketOPS - Learning Journal

---

# Sprint 1 - Organización del proyecto

## ¿Qué he aprendido?

- La importancia de diseñar la arquitectura antes de programar.
- La diferencia entre el proyecto y la aplicación Laravel.
- Cómo organizar un repositorio de forma profesional.

## Conceptos importantes

- Laravel no es el proyecto, es un servicio dentro del proyecto.
- Docker, documentación e infraestructura deben vivir fuera de `src/`.

## Errores que cometí

- Pensar que lo primero era desarrollar el CRUD.
- No separar la documentación de la aplicación.

---

# Sprint 2 - Docker

## Dockerfile

### Conceptos aprendidos

- `FROM` selecciona la imagen base.
- `RUN` ejecuta comandos durante la construcción de la imagen.
- `COPY --from` permite reutilizar archivos de otra imagen.
- `WORKDIR` establece el directorio de trabajo.
- `EXPOSE` documenta el puerto utilizado por el contenedor.

### Buenas prácticas

- Agrupar las instalaciones en un único `RUN` para reducir capas.
- Limpiar la caché de `apt` para disminuir el tamaño de la imagen.
- Utilizar imágenes oficiales siempre que sea posible.
- Evitar descargar Composer mediante scripts.

---

## Docker Compose

### Conceptos aprendidos

- `build` construye una imagen a partir de un Dockerfile.
- `image` utiliza una imagen existente.
- `depends_on` únicamente controla el orden de arranque; no espera a que el servicio esté listo.
- Los contenedores se comunican mediante el nombre del servicio (`php`, `postgres`, etc.).
- `volumes` permiten persistir datos o sincronizar archivos.
- `networks` crean una red privada entre contenedores.

---

## PostgreSQL

### Conceptos aprendidos

- Los datos nunca deben almacenarse dentro del contenedor.
- Los volúmenes persistentes sobreviven aunque el contenedor sea eliminado.
- En desarrollo se publica el puerto 5432 para poder usar herramientas como DBeaver.

---

## Nginx

### Conceptos aprendidos

- Nginx no ejecuta PHP.
- PHP-FPM es quien interpreta el código PHP.
- Nginx sirve los archivos estáticos (CSS, JS, imágenes).
- Laravel expone únicamente la carpeta `public/`.

### Configuración

- `root` apunta a `/var/www/html/public`.
- `index index.php index.html` busca los archivos en el orden indicado.
- `try_files` busca un archivo o directorio y, si no existe, redirige la petición a `index.php`.
- `fastcgi_pass php:9000` envía las peticiones PHP al contenedor `php`.

---

## Conceptos que quiero investigar

- ¿Qué hace realmente `mbstring`?
- ¿Cómo funciona FastCGI?
- ¿Cómo funciona internamente PHP-FPM?
- ¿Cómo resuelve Docker el DNS entre contenedores?

---

## Errores que he cometido

- Confundir `context` con la ruta del Dockerfile.
- Pensar que `depends_on` esperaba a que PostgreSQL estuviera listo.
- Creer que Nginx trabajaba directamente con la carpeta `routes`.

---

## Preguntas de entrevista que ya sé responder

- ¿Qué diferencia hay entre una imagen y un contenedor?
- ¿Por qué utilizar Docker?
- ¿Qué diferencia hay entre `build` e `image`?
- ¿Qué diferencia hay entre un volumen y un bind mount?
- ¿Por qué PostgreSQL necesita un volumen persistente?
- ¿Por qué Nginx y PHP-FPM están separados?
- ¿Qué hace `try_files`?
- ¿Cómo se comunican los contenedores entre sí?

# Sprint 2.2 - Infraestructura Docker

## Conceptos aprendidos

- Cómo construir un Dockerfile para PHP-FPM.
- Diferencia entre `build` e `image`.
- Cómo se comunican los contenedores mediante una red Docker.
- Por qué `localhost` no funciona entre contenedores.
- Qué hace `try_files`.
- Cómo Nginx envía las peticiones PHP mediante FastCGI.
- Por qué PostgreSQL necesita un volumen persistente.

## Buenas prácticas

- Fijar versiones de las imágenes (`postgres:17.5` en lugar de `latest`).
- Utilizar un único `RUN` para reducir capas.
- Limpiar la caché de `apt`.
- Compartir el código mediante un bind mount.
- Mantener separados Nginx, PHP-FPM y PostgreSQL.

## Errores que cometí

- Confundir `context` con la ruta del Dockerfile.
- Pensar que Nginx ejecutaba PHP.
- Pensar que `localhost` apuntaba al contenedor PHP.

## Preguntas para investigar

- ¿Qué hace exactamente `mbstring`?
- ¿Cómo funciona FastCGI internamente?
- ¿Qué es un `healthcheck` en Docker Compose?