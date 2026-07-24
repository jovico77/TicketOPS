# Sprint 02 - Docker

## ¿Qué he aprendido?

- Cómo construir imágenes Docker.
- Diferencia entre imagen y contenedor.
- Cómo funciona Docker Compose.
- Comunicación entre contenedores.
- Persistencia mediante volúmenes.
- Integración entre Nginx, PHP-FPM y PostgreSQL.

---

## Conceptos importantes

### Dockerfile

- FROM
- RUN
- COPY
- WORKDIR
- EXPOSE

### Docker Compose

- build
- image
- depends_on
- volumes
- networks

### PostgreSQL

- Persistencia.
- Volúmenes.
- Publicación de puertos.

### Nginx

- FastCGI.
- PHP-FPM.
- try_files.
- root.
- fastcgi_pass.

---

## Buenas prácticas

- Utilizar imágenes oficiales.
- Reducir capas del Dockerfile.
- Limpiar la caché de apt.
- Mantener cada servicio en su propio contenedor.
- Fijar versiones de las imágenes.

---

## Errores que cometí

- Confundir `context` con el Dockerfile.
- Pensar que `depends_on` esperaba a PostgreSQL.
- Pensar que Nginx ejecutaba PHP.
- Pensar que localhost funcionaba entre contenedores.

---

## Preguntas de entrevista

- Imagen vs Contenedor.
- build vs image.
- ¿Qué es un volumen?
- ¿Qué hace try_files?
- ¿Cómo se comunican los contenedores?

---

## Conceptos para investigar

- FastCGI.
- PHP-FPM.
- mbstring.
- Healthchecks.
- DNS interno de Docker.

---

## Hito conseguido

Infraestructura Docker completamente funcional.