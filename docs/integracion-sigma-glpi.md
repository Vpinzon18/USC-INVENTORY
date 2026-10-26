# Integración SIGMA–GLPI

## Alcance preparado en SIGMA

SIGMA usa la API REST clásica de GLPI 10 (`apirest.php`). La integración se apaga por defecto con `GLPI_ENABLED=false`; mientras esté apagada, los flujos existentes siguen trabajando como siempre y no se envían casos.

Cuando se active, SIGMA genera un ticket para:

| Evento SIGMA | Referencia en GLPI | Asignación prevista |
| --- | --- | --- |
| Bitácora técnica de un activo | `BT-{id}` | Técnico de SIGMA por correo/ID GLPI; grupo predeterminado como respaldo |
| Movimiento masivo o individual | Número de acta | Grupo GLPI predeterminado |
| Mantenimiento programado | `MP-{id}` | Técnico asignado al cronograma por correo/ID GLPI; grupo predeterminado como respaldo |

El ticket incluye el activo, serial/placa/hostname, ubicación, tipo de servicio, fecha, actor y detalle disponible. Cada evento tiene una clave idempotente en `glpi_ticket_syncs`; SIGMA guarda el estado, cantidad de intentos, error y el ID del ticket creado. Los casos se envían mediante la cola existente, por lo que una caída temporal de GLPI no bloquea el guardado del servicio en SIGMA.

Los técnicos deben tener un mapeo explícito entre el correo de SIGMA y el ID de usuario de GLPI. La sincronización LDAP por sí sola no garantiza IDs coincidentes entre ambas bases. El grupo predeterminado permite asignar movimientos aunque no haya un técnico asignado directamente.

## Preparación en GLPI (requiere acceso al servidor)

1. **Rotar el App-Token expuesto.** La captura compartida mostró el valor completo. En GLPI, elimina o regenera ese cliente/token y no lo reutilices.
2. En `Configuración > General > API`, habilita la API REST v1 y revisa el documento integrado de la instancia.
3. Crea un cliente API exclusivo `SIGMA`, actívalo y limita su rango IPv4 a la IP de salida del servidor SIGMA (no a las IP de los puestos). Habilita el registro de conexiones.
4. Publica GLPI mediante HTTPS con certificado válido. La URL de la captura usa HTTP; no se debe enviar el App-Token o User-Token por ese canal.
5. Crea una cuenta técnica de integración sin privilegios de administrador. Asígnale únicamente el perfil, entidad y permisos necesarios para crear/ver tickets y asignarlos al grupo/equipo técnico elegido.
6. Genera el User-Token desde las preferencias de esa cuenta técnica. No uses la contraseña LDAP de una persona ni un token personal de administrador.
7. Identifica el ID de la entidad, del grupo de soporte, las categorías para cada evento y los IDs GLPI de los técnicos que deben recibir bitácoras y mantenimientos.
8. Comprueba que el usuario técnico de integración puede abrir un ticket manual en esa entidad y asignarlo al grupo elegido.

## Configuración SIGMA

Guarda estos valores en el `.env` del servidor SIGMA, nunca en Git, una vista web o un instalador:

```dotenv
GLPI_ENABLED=false
GLPI_BASE_URL=https://glpi.institucion.edu.co/apirest.php
GLPI_APP_TOKEN=<token-nuevo-del-cliente-SIGMA>
GLPI_USER_TOKEN=<token-de-la-cuenta-tecnica>
GLPI_ENTITY_ID=0
GLPI_TICKET_TYPE=2
GLPI_DEFAULT_GROUP_ID=<id-grupo-soporte>
GLPI_CATEGORY_TECHNICAL_SERVICE=<id-categoria-bitacoras>
GLPI_CATEGORY_ASSET_MOVEMENT=<id-categoria-movimientos>
GLPI_CATEGORY_MAINTENANCE_SCHEDULE=<id-categoria-mantenimiento>
GLPI_USER_MAP='{"tecnico1@usc.edu.co":123,"admin1@usc.edu.co":456}'
GLPI_VERIFY_TLS=true
```

El JSON de `GLPI_USER_MAP` relaciona correo SIGMA con ID de usuario GLPI y se puede ampliar según los técnicos/administradores que deban figurar como solicitante o asignado. Mantén el valor entre comillas simples para que Docker Compose y dotenv lo lean como una sola cadena. Si el grupo de soporte o las categorías difieren, usa los IDs reales de la instancia.

Después de establecer los valores, reconstruye/reinicia `app`, `queue` y `scheduler` para que Laravel recargue su configuración y usa:

```sh
docker compose exec -T app php artisan config:clear
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan sigma:glpi-test-connection
```

El comando de conexión solo inicia y cierra una sesión GLPI; no crea tickets. Antes de activar `GLPI_ENABLED=true`, confirma que la prueba pase desde el contenedor `app` y que los logs de API de GLPI muestren la IP autorizada.

## Piloto y activación

1. Elige un activo de prueba, un técnico mapeado y categorías de piloto en GLPI.
2. Activa `GLPI_ENABLED=true` y reinicia los servicios. Registra una bitácora controlada, una programación de un activo de prueba y un movimiento de una sola máquina.
3. En GLPI, confirma entidad, categoría, solicitante/asignado, detalle y referencia SIGMA. En SIGMA, confirma que cada fila de `glpi_ticket_syncs` termina en `synced` y conserva el ID remoto.
4. Revisa errores bajo `storage/logs/laravel.log` y la pestaña de registro del cliente API en GLPI. Errores de autorización, entidad, grupo y certificado deben corregirse antes del despliegue general.
5. Amplía por etapas. No sincronices los registros históricos automáticamente sin acordar primero volumen, fecha de corte y regla para evitar tickets duplicados.

## Operación y límites

- Para cada evento nuevo, el outbox evita que SIGMA inserte dos veces la misma referencia y permite reintentos por cola.
- GLPI API v1 no ofrece una clave idempotente universal para el alta de tickets. Si el servidor crea un ticket pero se pierde la respuesta HTTP, hay que revisar la referencia `BT-*`, número de acta o `MP-*` en GLPI antes de reintentar manualmente.
- Tras confirmar que el ticket no existe en GLPI, un administrador puede reencolar un fallo con `docker compose exec -T app php artisan sigma:glpi-retry <sync_id>`; el comando pide confirmación.
- El ID remoto queda en `glpi_ticket_syncs`; no se alteran las tablas principales de inventario, bitácoras ni movimientos.
- La actualización/cierre del ticket cuando se modifica o completa el registro SIGMA es una segunda fase: requiere decidir reglas de sincronización y verificar permisos GLPI para editar/añadir seguimientos.
- `tickets` es una tabla vacía heredada de SIGMA y no es la fuente de esta integración.
