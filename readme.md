# Inmo CRM

Una aplicación de demostración para ilustrar cómo [Inertia.js] (https://inertiajs.com/) funciona con [Laravel] (https://laravel.com/) y [Svelte] (https://svelte.dev/).

> Este proyecto de demostración simula un gestor de inmobiliarias un sistema que abarque ambos públicos donde la inmobiliaria pueda gestionar todos sus inmuebles en venta y arriendo con toda la información necesaria como fotografías, descripción completa de sus interiores (habitaciones, baños, garaje, piso, etc), junto a imágenes, dirección y ubicación geográfica (latitud, longitud) para que aquellos clientes cercanos o aun mas lejos puedan ver un inmueble y poder entrar en contacto con la inmobiliaria si se encuentra interesado y seguir un paso más cerca para realzar un cierre.

## Instalacin

Clonar el repositorio localmente:

```sh
git clone https://github.com/jorgeicarrillog/real-estate-manager.git
cd real-estate-manager
```

Instalar dependencias de PHP:

```sh
composer install
```

Instalar dependencias de NPM:

```sh
npm install
```

Construir archivos:

```sh
npm run dev
```

Configuración de instalación:

```sh
cp .env.example .env
```

Generar clave de aplicación:

```sh
php artisan key:generate
```

Crea una base de datos SQLite. También puede usar otra base de datos (MySQL, Postgres), simplemente actualice su configuración en consecuencia.

```sh
touch database/database.sqlite
```

Ejecute migraciones de base de datos:

```sh
php artisan migrate
```

Ejecute la sembradora de base de datos:

```sh
php artisan db:seed
```

Ejecute el servidor artisan:

```sh
php artisan serve
```

Usted está listo para ir! [Visite Gestor Inmobiliario](http://127.0.0.1:8000/) en su navegador e inicie sesión con:

- **Usuario:** johndoe@example.com
- **Contraseña:** secret

## Ejecutando pruebas

Para ejecutar las pruebas del Gestor Inmobiliario, ejecute:

```
phpunit
```

## Créditos

- Jorge Ivan Carrillo Gonzalez (@jorgeicarrillog)
- Platilla original [Ping CRM](https://github.com/zgabievi/pingcrm-svelte.git).
# real-estate-manager

