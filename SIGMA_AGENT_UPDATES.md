# Publicación segura de Sigma Agent

## Requisitos

- El servidor debe publicar Laravel por HTTPS. El cliente rechaza manifiestos y descargas por HTTP.
- El ZIP de versión debe mantenerse fuera de `public/`, en `storage/app/agent-updates/`.
- El ZIP no debe incluir `appsettings.json`; ese archivo contiene configuración local y credenciales. El actualizador conserva la configuración existente. Para una instalación nueva, completar `appsettings.template.json` como `appsettings.json`.
- La descarga exige `auth:sanctum`; el agente 2.3 no envía el token al descargar paquetes. La primera instalación de la versión 2.4 debe distribuirse manualmente por un canal confiable. Una vez instalada, las versiones nuevas se descargarán con autenticación.

## Generar una versión

Desde la solución del agente, publicar para Windows x64 en modo Release y crear un ZIP con el contenido de `SigmaAgent.Service/bin/Release/net10.0-windows/win-x64/publish/`. Confirmar que el ZIP contenga `SigmaAgent.Service.exe` y `SigmaAgent.Updater.exe`, y que no contenga `appsettings.json`.

Copiar el ZIP a `storage/app/agent-updates/` y calcular su SHA-256. Configurar en el `.env` del contenedor Laravel:

```dotenv
SIGMA_AGENT_UPDATE_VERSION=2.4
SIGMA_AGENT_UPDATE_PACKAGE=SigmaAgent-2.4.zip
SIGMA_AGENT_UPDATE_SHA256=<sha256-del-zip>
SIGMA_AGENT_UPDATE_MANDATORY=false
SIGMA_AGENT_UPDATE_NOTES=Actualización segura del agente
```

Después reconstruir el contenedor de la aplicación y limpiar la configuración cacheada. No se requieren migraciones de base de datos.

## Credenciales

No publicar tokens de Sanctum en el ZIP. El manifiesto no requiere autenticación; el endpoint de descarga sí requiere el token Bearer configurado localmente en cada agente. El token incluido en el ZIP antiguo debe considerarse comprometido y rotarse de acuerdo con el alcance de los equipos que lo comparten. Para desarrollo local, configurar el token y la URL mediante `SIGMA_AGENT_API_TOKEN` y `SIGMA_AGENT_API_BASE_URL` en el entorno del servicio.
