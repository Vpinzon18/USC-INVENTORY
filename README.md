# SIGMA — Sistema de inventario y mantenimiento

SIGMA es una aplicación institucional para la consulta y gestión de activos, responsables, movimientos, mantenimientos y funciones administrativas. La aplicación está construida con Laravel y PostgreSQL, y el entorno de referencia coordina sus servicios mediante Docker Compose.

## Documentación

- [Manuales básicos de SIGMA](docs/Manual_Basico_SIGMA.docx): guía de usuario, administración, instalación y operación técnica, con una lista de verificación para la entrega al ISTM.
- [Actualizaciones de SIGMA Agent](SIGMA_AGENT_UPDATES.md): procedimiento específico para publicar versiones del agente.

El manual es una base para revisión institucional. Antes de radicarlo, complete responsables y contactos, aplique la plantilla vigente del ISTM y confirme los requisitos operativos y de retención de datos.

## Inicio local con Docker Compose

1. Instale e inicie Docker Desktop.
2. En PowerShell, vaya a la carpeta raíz del proyecto.
3. Si `.env` no existe, cree una copia de `.env.example`. Si ya existe, no la sobrescriba.
4. Configure las variables del entorno local, incluyendo `APP_KEY` y las credenciales de PostgreSQL. Para Compose, `DB_HOST=postgres` y `DB_PORT=5432`.
5. Construya las imágenes y levante los servicios:

```powershell
docker compose build app nginx
docker compose up -d postgres
docker compose ps
docker compose up -d app queue scheduler nginx
docker compose ps
```

La instalación en una base nueva requiere configurar el entorno y ejecutar las migraciones con el procedimiento aprobado. En una instalación existente, respalde y revise las migraciones antes de aplicarlas. **No ejecute `docker compose down -v`**: elimina volúmenes persistentes que pueden contener la base de datos y archivos de la aplicación.

## Tecnologías

- Laravel 12 y PHP 8.4
- PostgreSQL 17
- Nginx y Docker Compose
- Node.js 22, Vite y Alpine.js

Consulte `docker-compose.yml`, `Dockerfile`, `.env.example` y el manual para detalles de configuración. No incluya `.env`, contraseñas, tokens ni claves privadas en el repositorio.
