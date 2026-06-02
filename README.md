# 🪐 Orbitally — Gestor de Productividad

Aplicación web de productividad con temática espacial, construida con Laravel + Blade, Tailwind CSS y MySQL.

---

## 🚀 Tecnologías

- PHP + Laravel
- Blade Templates
- Tailwind CSS
- MySQL

---

## ⚙️ Configuración del entorno

Copia el archivo de ejemplo y edítalo con tus valores:

```bash
cp .env.example .env
```

Variables principales a configurar en `.env`:

```env
APP_NAME=Orbitally
APP_URL=http://localhost:8000
APP_LOCALE=es

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=orbitally
DB_USERNAME=root
DB_PASSWORD=tu_contraseña
```

Genera tu propia APP_KEY:

```bash
php artisan key:generate
```

---

## 🛠️ Puesta en marcha

Instala las dependencias:

```bash
composer install
npm install && npm run build
```

Ejecuta las migraciones:

```bash
php artisan migrate
```

Carga datos de prueba:

```bash
php artisan db:seed
```

> Usuarios de prueba: `fernando@orbitally.test` / `diego@orbitally.test`  
> Contraseña: `password123`

Lanza el servidor:

```bash
php artisan serve
```

---

## ✅ Funcionalidades actuales

- Pantalla de presentación con animación de sistema solar
- Registro e inicio de sesión con sesiones seguras
- Categorías creadas automáticamente al registrarse
- Dashboard con estadísticas y tareas recientes
- Gestión completa de tareas (crear, editar, eliminar)
- Filtrado de tareas por estado
- Calendario mensual con tareas por día
- Datos aislados por usuario

---

## 🔭 Próximas funcionalidades

- Guardado de conversaciones de IA
- Posibilidad de organizarse entre sí con otros usuarios de la app
- Módulo de escritura (para dispositivos de sobremesa)
- Notificaciones por email
