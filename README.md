# Orbitally — Gestor de Productividad

Aplicación web de productividad con temática espacial desarrollada con **Laravel 11**, **Blade**, **HTML** y **Tailwind CSS** (CDN).

---

## Requisitos

- PHP 8.2+
- Composer
- MySQL 8+
- Node.js (opcional, solo si quieres compilar assets propios)

---

## Instalación paso a paso

### 1. Crear el proyecto base de Laravel

```bash
composer create-project laravel/laravel orbitally
cd orbitally
```

### 2. Copiar los archivos de este repositorio

Copia todos los archivos respetando la estructura de directorios:

```
app/
  Http/Controllers/
    AuthController.php
    DashboardController.php
    TaskController.php
  Models/
    User.php
    Categoria.php
    Tarea.php

bootstrap/
  app.php

config/
  auth.php

database/
  migrations/
    2024_01_01_000000_create_users_table.php
    2024_01_01_000001_create_categorias_table.php
    2024_01_01_000002_create_tareas_table.php
    2024_01_01_000003_create_etiquetas_table.php
    2024_01_01_000004_create_sesiones_pomodoro_table.php
    2024_01_01_000005_create_chat_ia_table.php
    2024_01_01_000006_create_sessions_table.php
    2024_01_01_000007_create_cache_table.php
    2024_01_01_000008_create_jobs_table.php
  seeders/
    DatabaseSeeder.php

resources/views/
  layouts/
    app.blade.php
    auth.blade.php
  auth/
    login.blade.php
    register.blade.php
  dashboard/
    index.blade.php
    calendar.blade.php
  tasks/
    index.blade.php
    form.blade.php
  welcome.blade.php

routes/
  web.php
```

### 3. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

Edita `.env` con tus datos de MySQL:

```env
DB_DATABASE=orbitally
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 4. Crear la base de datos

En MySQL:
```sql
CREATE DATABASE orbitally CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Ejecutar migraciones

```bash
php artisan migrate
```

### 6. (Opcional) Cargar datos de prueba

```bash
php artisan db:seed
```
Esto crea el usuario **demo@orbitally.app** con contraseña **password**.

### 7. Lanzar el servidor

```bash
php artisan serve
```

Accede a `http://localhost:8000`

---

## Estructura de la aplicación

| Ruta | Descripción |
|------|-------------|
| `/` | Pantalla de presentación |
| `/login` | Inicio de sesión |
| `/register` | Registro de usuario |
| `/dashboard` | Panel principal |
| `/calendario` | Calendario mensual |
| `/tasks` | Lista de tareas |
| `/tasks/create` | Nueva tarea |
| `/tasks/{id}/edit` | Editar tarea |

---

## Tecnologías utilizadas

- **Backend:** PHP 8.2 + Laravel 11, patrón MVC
- **Frontend:** Blade templates, HTML5, CSS3, Tailwind CSS (CDN)
- **Base de datos:** MySQL con Eloquent ORM
- **Autenticación:** Sistema propio con `Auth` facade de Laravel (bcrypt)
- **Fuentes:** Google Fonts (Cinzel + Jost)

---

## Funcionalidades implementadas (v1)

- [x] Pantalla de presentación con animación sistema solar
- [x] Registro e inicio de sesión (bcrypt, sesiones seguras)
- [x] Creación automática de 4 categorías por defecto al registrarse
- [x] Dashboard con estadísticas, tareas recientes y mini calendario
- [x] Gestión completa de tareas (CRUD)
- [x] Filtrado de tareas por estado
- [x] Toggle de completar/descompletar tarea
- [x] Calendario mensual con tareas marcadas por día
- [x] Protección de rutas (middleware `auth`)
- [x] Aislamiento de datos por usuario

## Próximas funcionalidades

- [ ] Temporizador Pomodoro
- [ ] Asistente IA (API Groq)
- [ ] Módulo de escritura (desktop)
- [ ] Etiquetas y filtrado transversal
- [ ] Notificaciones por email
