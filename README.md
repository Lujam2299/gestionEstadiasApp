# Gestión de estadías

Aplicación Laravel 11 ubicada en `servicioEstadias/`. El `composer.json` de la raíz pertenece a una instalación auxiliar; los comandos de la aplicación deben ejecutarse dentro de `servicioEstadias`.

## Preparación local

Requiere PHP 8.2 o posterior, Composer 2, Node.js y las extensiones de PHP requeridas por Composer. Para pruebas se necesitan PDO SQLite y SQLite3; configure MySQL en `.env` si utiliza la base habitual del sistema.

```powershell
cd servicioEstadias
composer install
Copy-Item .env.example .env
php artisan key:generate
# Configurar las credenciales de base de datos en .env antes de migrar.
php artisan migrate
npm ci
npm run build
php artisan serve
```

No sobrescriba un `.env` existente ni utilice `migrate:fresh` sobre una base con datos. Las migraciones modificadas de creación se aplican a instalaciones nuevas; las instalaciones existentes conservan sus registros.

## Acceso y flujos

- Desarrollo académico: cuenta `desarrolloacademico@tecvalles.mx`.
- Vinculación: cuenta `vinculacion@tecvalles.mx`.
- Docentes: las demás cuentas. Las cuentas de las áreas se deben aprovisionar por un administrador; el registro público y la edición del perfil no permiten reclamar esos correos.
- Las rutas de cada área validan permisos; un docente solo puede consultar y cancelar sus propias solicitudes.
- Crear solicitudes requiere una convocatoria abierta, fechas válidas y los documentos solicitados. Se impiden solicitudes activas duplicadas.
- El reenvío de informes reutiliza el registro existente. La solicitud se actualiza después de guardar los archivos y la información de forma consistente.

Los permisos mantienen el esquema de correos institucionales del proyecto original. Un cambio de direcciones requiere actualizar esa configuración en el código.

## Verificación

```powershell
php vendor/bin/phpunit
php vendor/bin/pint --test
php artisan view:cache
npm run build
```

PHPUnit usa SQLite en memoria y una clave exclusiva para pruebas; no necesita la base de producción. Las pruebas de archivos usan un directorio temporal dentro de `storage/framework/testing`.

## Aspectos operativos pendientes

Los documentos históricos y las cargas siguen utilizando el almacenamiento público original. Las verificaciones de propiedad de los controladores no protegen archivos servidos directamente por el servidor web. Antes de exponer documentación sensible, se necesita una migración del almacenamiento a un disco privado y adaptar los enlaces de descarga.

Las dependencias conservan las versiones principales del proyecto. Hay avisos de seguridad en sus versiones bloqueadas; una actualización de dependencias y pruebas sobre una copia de la base real es un trabajo adicional necesario antes de desplegar estos cambios. La suite automatizada no sustituye la validación de los flujos institucionales con sus datos reales.
