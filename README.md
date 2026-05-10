# INF781 - Autenticación en Dos Factores (2FA) con Laravel

Este proyecto implementa un sistema de seguridad 2FA utilizando el paquete Google2FA.

## Requisitos
* PHP >= 8.2
* Composer
* PostgreSQL (U.A.T.F. Server o Local)

## Instalación
1. Clonar el repositorio: `git clone URL_DEL_REPO`
2. Instalar dependencias: `composer install`
3. Configurar el archivo `.env` con tus credenciales de base de datos.
4. Generar la clave de la aplicación: `php artisan key:generate`
5. Ejecutar migraciones: `php artisan migrate`
6. Iniciar servidor: `php artisan serve`

