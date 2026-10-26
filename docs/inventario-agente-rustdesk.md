# Inventario SIGMA Agent y asistencia RustDesk OSS

## Diseño

Los activos administrados por SIGMA Agent se consultan desde **Inventario / HV**; no hay un segundo listado de equipos. La API existente de SIGMA Agent (`POST /api/v1/agent/inventory`, autenticada con Sanctum) registra la identidad del dispositivo y normaliza CPU, módulos RAM, BIOS, red, discos, GPU, batería, sistema operativo y seguridad en `assets` y sus relaciones. La ficha reutiliza esa información; no copia la telemetría a tablas paralelas.

Las rutas de Inventario / HV requieren autenticación; solo el rol 1 puede registrar o cambiar el ID RustDesk. La columna `assets.rustdesk_id` es nullable y única. No se almacena contraseña, token RustDesk ni clave privada.

RustDesk Server OSS se publica bajo AGPL-3.0; revise las obligaciones de licencia antes de redistribuir versiones modificadas. SIGMA Agent puede consultar el ID con la opción oficial `rustdesk.exe --get-id` y enviarlo como `rustDeskId` en el inventario. Si RustDesk no está instalado o no puede responder, el campo se omite y la telemetría normal continúa; el valor existente en SIGMA no se borra. El backend rechaza IDs duplicados. También se puede registrar el ID manualmente desde el detalle del equipo. El enlace `rustdesk://<id>` intenta abrir el cliente local si el sistema operativo tiene registrado el protocolo. No se recolectan contraseñas ni se habilita acceso desatendido.

## Requisitos previos

- SIGMA Laravel 12 / PHP 8.4 con PostgreSQL 17 activo y las migraciones del inventario Agent ya aplicadas.
- Docker Desktop con Docker Compose v2. Para descargar RustDesk OSS se necesita acceso a Docker Hub (`rustdesk/rustdesk-server`).
- Cliente RustDesk instalado en cada equipo que actuará como técnico o remoto. Configurar en ambos el mismo ID Server y la clave pública del servidor.
- Para esta fase local, los puertos se publican únicamente en `127.0.0.1`. No se necesitan cambios de firewall y no son accesibles desde otros equipos.

## Puertos RustDesk OSS

| Puerto | Protocolo | Uso |
| --- | --- | --- |
| 21115 | TCP | Prueba de tipo NAT (`hbbs`) |
| 21116 | TCP y UDP | ID, rendezvous, heartbeat y hole punching (`hbbs`) |
| 21117 | TCP | Relay (`hbbr`) |

La configuración omite 21118/21119 porque son WebSocket para cliente web y no se habilita esa modalidad. El puerto 21114 pertenece a funciones de consola de Server Pro, no al servidor OSS. En un despliegue LAN, configúrese un IP/DNS del host alcanzable por los clientes, cambie `RUSTDESK_BIND_ADDRESS` a `0.0.0.0` y permita únicamente esos puertos en el firewall de red privada. Para uso fuera de la LAN, use una VPN aprobada en lugar de publicar los puertos a Internet sin revisión de seguridad.

## Base de datos

La única migración nueva agrega `assets.rustdesk_id VARCHAR(64) NULL UNIQUE`. No se requieren tablas nuevas para telemetría: `assets` y las relaciones `asset_processors`, `asset_ram_modules`, `asset_networks` / `asset_network_adapters`, `asset_storage_devices`, `asset_gpus`, `asset_batteries` y `asset_monitors` ya existen.

Revise primero `php artisan migrate:status`. Para aplicar solo esta ampliación, sin ejecutar otras migraciones pendientes del proyecto:

```powershell
docker compose exec app php artisan migrate --path=database/migrations/2026_10_17_180000_add_rustdesk_id_to_assets_table.php --force
```

La reversión de esta migración elimina la columna, incluido cualquier ID RustDesk guardado después de aplicarla. Exporte esos IDs antes de revertir si ya se han registrado.

## Habilitar RustDesk en Windows 11 local

1. Añada estas variables al `.env` existente sin sobrescribirlo. La plantilla `.env.example` contiene los mismos valores para instalaciones nuevas:

   ```dotenv
   RUSTDESK_SERVER_IMAGE=rustdesk/rustdesk-server:latest
   RUSTDESK_BIND_ADDRESS=127.0.0.1
   RUSTDESK_RELAY_HOST=127.0.0.1
   ```

2. Arranque solo los dos servicios opcionales. No arranque el perfil durante el `docker compose up` habitual de SIGMA:

   ```powershell
   docker compose --profile remote-support up -d rustdesk-hbbs rustdesk-hbbr
   docker compose ps
   docker compose logs --tail 50 rustdesk-hbbs rustdesk-hbbr
   ```

3. Obtenga la clave pública que comparten los clientes. Nunca distribuya `id_ed25519` (clave privada):

   ```powershell
   docker exec sigma_rustdesk_hbbs cat /root/id_ed25519.pub
   ```

4. En el cliente RustDesk de cada equipo, abra **Configuración → Red → Servidor ID** y configure `127.0.0.1:21116` y la clave pública. Deje el relay vacío para que el cliente lo infiera, o use `127.0.0.1:21117`.
5. Compile y despliegue la nueva versión de SIGMA Agent en cada equipo. En el siguiente inventario, el ID se asociará automáticamente al activo. Para clientes aún no actualizados, abra SIGMA → **Inventario / HV** → ficha del equipo → **Asistencia remota** y registre el ID manualmente. **Abrir RustDesk** intenta usar el protocolo local; el técnico debe comprobar el equipo y completar la autorización requerida por el cliente.

### Instalación reutilizable de SIGMA Agent

El instalador 2.3.0 es común a todos los equipos: solo contiene la URL de SIGMA y no lleva un token o código compartido. Al iniciar, cada agente genera una identidad criptográfica local y presenta una solicitud pendiente. Un administrador verifica el nombre, fabricante, modelo y serial del equipo en **Administración → Solicitudes de Agentes** y aprueba o rechaza la solicitud. SIGMA crea una credencial independiente, limitada al activo, y la cifra para la clave privada de ese equipo. El agente la recibe automáticamente y la protege con DPAPI. Las actualizaciones normales del agente conservan esta identidad y credencial, por lo que no requieren un instalador personalizado para cada PC.

La migración `2026_10_21_220000_create_sigma_agent_enrollment_requests_table.php` agrega la cola de solicitudes pendientes. Es aditiva y no modifica las tablas de inventario ni los datos existentes. Para probar la implementación, compile y publique el agente 2.3.0 y el backend; luego aplique esta migración, distribuya el mismo instalador a los equipos y apruebe sus solicitudes en SIGMA. No reutilice ni distribuya los instaladores individualizados de versiones 2.2.x.

Las solicitudes de enrolamiento y las credenciales deben transmitirse mediante HTTPS en la implementación institucional. El endpoint HTTP actual de la red de pruebas no protege la descripción del equipo ni evita que un intermediario sustituya una solicitud antes de la aprobación.

Para probar con otros equipos de la LAN, cambie en el `.env` `RUSTDESK_BIND_ADDRESS=0.0.0.0` y `RUSTDESK_RELAY_HOST=<IP-o-DNS-del-host>`, reinicie solo `rustdesk-hbbs` y `rustdesk-hbbr`, configure los clientes con `<IP-o-DNS-del-host>:21116` y la clave pública, y permita los puertos de la tabla únicamente desde la red privada autorizada. `localhost`/`127.0.0.1` solo funciona para clientes en el mismo computador.

Para detener RustDesk sin borrar datos ni claves:

```powershell
docker compose --profile remote-support stop rustdesk-hbbs rustdesk-hbbr
```

El volumen `rustdesk_data` conserva `id_ed25519`, `id_ed25519.pub` y los datos del servidor. No use `docker compose down -v`.

## Publicación de cambios SIGMA

Después de reconstruir las imágenes `app` y `nginx`, aplique únicamente la migración nueva con el comando anterior. El seguimiento técnico del equipo se consulta desde Inventario/HV; la API de agentes continúa recibiendo telemetría y estados. Los servicios RustDesk son opt-in y no arrancan junto al stack normal.

Antes de pasar de localhost a LAN, confirme la política institucional de soporte remoto, autorización del usuario del equipo, inventario de claves, responsable de actualización de imagen y alcance del firewall. Fije la imagen `rustdesk/rustdesk-server` a una versión que la organización haya validado en vez de mantener `latest` indefinidamente.

## Referencias oficiales

- [RustDesk Server OSS en Docker](https://rustdesk.com/docs/en/self-host/rustdesk-server-oss/docker/)
- [Configurar clientes autohospedados](https://rustdesk.com/docs/en/self-host/client-configuration/)
- [Código oficial del servidor](https://github.com/rustdesk/rustdesk-server)
