# Orbitally — Gestor de Productividad

Aplicación web de productividad con temática espacial, construida con Laravel + Blade, Tailwind CSS y MYSQL.

---

## Tecnologías

- PHP + Laravel
- Blade Templates
- Tailwind CSS
- MySQL

---

## Configuración del entorno

Archivo .env del proyecto.

```env
APP_NAME=Orbitally
APP_ENV=local
APP_KEY=base64:RR040nNpUnTPOM7/VuILAGlsfRn1ODEgzhxWaEXZdl0=
APP_DEBUG=true
APP_URL=http://localhost:8000

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_ES

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=orbitally
DB_USERNAME=root
DB_PASSWORD=Sandia4you

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hola@orbitally.app"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Puesta en marcha

Ejecutar migraciones:
```bash
php artisan migrate
```

Cargar datos de prueba (usuario: `demo@orbitally.app` / contraseña: `password`):
```bash
php artisan db:seed
```

Lanzar el servidor:
```bash
php artisan serve
```

---

## Funcionalidades actuales

- Pantalla de presentación con animación de sistema solar
- Registro e inicio de sesión con sesiones seguras
- Categorías creadas automáticamente al registrarse
- Dashboard con estadísticas y tareas recientes
- Gestion completa de tareas (crear, editar, eliminar)
- Filtrado de tareas por estado
- Calendario mensual con tareas por día
- Datos aislados por usuario

---

## Proximas funcionalidades

- Temporizador Pomodoro
- Asistente IA
- Modulo de escritura
- Etiquetas y filtrado avanzado
- Notificaciones por email