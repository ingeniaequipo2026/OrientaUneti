# OrientaUneti - Plataforma de Bienestar Estudiantil UNETI 🎓🇻🇪

> **"OrientaUneti centraliza el bienestar estudiantil en una sola plataforma web, conectando a los estudiantes con oportunidades de desarrollo y salud mental, mientras digitaliza y automatiza la gestión administrativa de inscripciones, asistencia y constancias en tiempo real."**

---

## 📌 1. Descripción del Proyecto

OrientaUneti es una aplicación web integral desarrollada para la **Universidad Nacional Experimental de Telecomunicaciones e Informática (UNETI)**. Resuelve la dispersión de información y la fricción burocrática institucional mediante:

1. **Catálogo Unificado de Actividades:** Deportes, cultura, talleres tecnológicos y recreación con cupos en tiempo real.
2. **Control Transaccional de Cupos:** Bloqueo automático e inmediato con mensaje *"Cupo lleno"* al alcanzar el límite estipulado.
3. **Generación Instantánea de Constancias (PDF):** Documento descargable con membrete institucional oficial y código de validación.
4. **Directorio Ético de Salud Mental:** Información y líneas de emergencia 24/7 sin registro de historiales clínicos (garantía de privacidad).
5. **Vitrina de Emprendimientos Estudiantiles:** Visibilización de startups y proyectos TIC universitarios.
6. **Módulo de Atención al Usuario:** Formulario para canalizar propuestas, sugerencias y reclamos.
7. **Control Administrativo y Reportes:** Planillas descargables en formatos **Excel (CSV)** y **PDF** con lista de activos y asistencias.

---

## 🏗️ 2. Arquitectura Técnica

- **Frontend:** HTML5, CSS3, JavaScript (Vanilla), Bootstrap 5.3 y Bootstrap Icons. Paleta institucional oficial de la UNETI: Verde (`#287A38`) y Azul (`#19319C`).
- **Backend:** PHP puro (sin frameworks pesados), estructurado bajo el patrón **MVC (Modelo - Vista - Controlador)** con un **Router** ligero y modular.
- **Seguridad:**
  - Sentencias Preparadas con **PDO** para prevenir inyección SQL.
  - Encriptación de contraseñas con algoritmo `password_hash(PASSWORD_BCRYPT)`.
  - Tokens **CSRF** en todos los formularios de acción.
  - Middleware de control de roles (`AuthMiddleware`) para restringir accesos entre Administrador y Estudiante.
  - Sanitización y escape de datos con `htmlspecialchars()`.
- **Motor de PDF:** Generador nativo en PHP (`FPDF`) sin dependencias de Node ni Composer.
- **Base de Datos:** PostgreSQL con transacciones atómicas (`BEGIN TRANSACTION` ... `SELECT FOR UPDATE` ... `COMMIT`).

---

## 🗄️ 3. Estructura del Directorio

```text
OrientaEduca/
├── config/
│   ├── config.php          # Constantes, sesiones seguras, CSRF y helpers
│   └── database.php        # Conexión Singleton PDO a PostgreSQL (soporta Render y local)
├── database/
│   ├── schema.sql          # Estructura DDL de tablas e índices PostgreSQL
│   └── seed.sql            # Datos de prueba (usuarios, actividades, centros, etc.)
├── public/
│   ├── .htaccess           # Reescribe peticiones al enrutador de Apache
│   ├── index.php           # Punto de entrada y definición de rutas
│   ├── setup.php           # Asistente de instalación/migración en 1 clic
│   └── assets/
│       ├── css/style.css   # Estilos corporativos UNETI (#287A38 y #19319C)
│       └── js/main.js      # Interacciones cliente, tooltips y confirmaciones
├── src/
│   ├── Controllers/        # Controladores MVC (Auth, Dashboard, Actividades, etc.)
│   ├── Lib/
│   │   ├── fpdf.php        # Motor binario PDF en PHP puro
│   │   └── PDFGenerator.php# Generación de constancias y reportes oficiales
│   ├── Middleware/
│   │   └── AuthMiddleware.php # Filtros de seguridad por rol
│   ├── Models/             # Modelos PDO (Usuario, Actividad, Inscripcion, etc.)
│   └── Router.php          # Enrutador HTTP puro con soporte GET/POST y parámetros
├── views/                  # Vistas organizadas por roles y módulos
├── .env.example            # Plantilla de variables de entorno
├── Procfile                # Configuración de despliegue
├── render.yaml             # Blueprint de despliegue en Render
└── README.md               # Documentación general
```

---

## 🚀 4. Guía de Instalación y Ejecución Local

### Paso 1: Configurar PHP y Extensión PostgreSQL (en XAMPP)
1. Instale **XAMPP**.
2. Abra el panel de control de XAMPP y junto a Apache haga clic en **Config** > **PHP (php.ini)**.
3. Busque la línea:
   ```ini
   ;extension=pdo_pgsql
   ;extension=pgsql
   ```
4. Descoméntelas quitando el punto y coma (`;`):
   ```ini
   extension=pdo_pgsql
   extension=pgsql
   ```
5. Guarde el archivo `php.ini` y reinicie el servicio Apache.

### Paso 2: Crear la Base de Datos PostgreSQL
En su consola de PostgreSQL o herramienta visual (pgAdmin / DBeaver / terminal):
```sql
CREATE DATABASE orientauneti;
```

### Paso 3: Configurar el archivo `.env`
Edite o verifique el archivo `.env` en la raíz del proyecto:
```ini
APP_NAME=OrientaUneti
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_DRIVER=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_NAME=orientauneti
DB_USER=postgres
DB_PASS=postgres

MAIL_TO=bienestar@uneti.edu.ve
```

### Paso 4: Inicializar Tablas y Datos de Prueba
Tiene dos opciones:
- **Vía Web (Recomendada):** Abra en su navegador `http://localhost:8000/setup.php` y presione **"Iniciar Instalación / Migración"**.
- **Vía Consola (CLI):**
  ```bash
  php public/setup.php
  ```

### Paso 5: Iniciar el Servidor de Desarrollo
Si no usa la carpeta `htdocs` de XAMPP, puede iniciar el servidor interno de PHP directamente desde la carpeta del proyecto:
```bash
php -S localhost:8000 -t public
```
Luego ingrese en su navegador a: **`http://localhost:8000`**

---

## 🔑 5. Credenciales de Acceso para Pruebas

| Rol | Correo Electrónico | Contraseña | Capacidades |
|---|---|---|---|
| **Administrador** | `admin@uneti.edu.ve` | `Admin123*` | CRUD completo, control de asistencia, planillas Excel/PDF, atención de reclamos |
| **Estudiante** | `estudiante@uneti.edu.ve` | `Estudiante123*` | Catálogo con cupos, botón de inscripción, descarga de constancia PDF, perfil |

---

## ✅ 6. Verificación de Criterios de Aceptación

1. **Seguridad y Roles:**
   - Inicie sesión como estudiante e intente ingresar manualmente a `/admin/actividades/crear`. El middleware interceptará la solicitud y lo redirigirá a su panel estudiantil con un mensaje de alerta de acceso no autorizado.
2. **Control de Cupos:**
   - En el catálogo de actividades, observe cómo el indicador muestra los cupos disponibles. Al completarse los cupos asignados, el botón de inscripción se bloquea con el texto y estilo *"Cupo lleno"*, impidiendo solicitudes adicionales.
3. **Descarga de Constancia en PDF:**
   - Como estudiante, ingrese a *"Mis Actividades"* y presione el botón rojo **"Descargar Constancia en PDF"**. El navegador descargará un archivo PDF formal con membrete del Ministerio, Universidad UNETI, sus datos y las actividades en las que participa.
4. **Planillas y Reportes Administrativos:**
   - Como administrador, diríjase a *"Reportes"* o *"Control de Asistencia"* y presione **"Descargar Planilla Excel"** o **"Descargar Planilla PDF"**.
5. **Atención al Usuario:**
   - Envíe un mensaje desde el formulario de contacto. El sistema lo persistirá en la base de datos y generará el registro en el log de correos `logs/correos_enviados.log`.

---

## ☁️ 7. Despliegue en Render (Producción)

1. Conecte su repositorio en **[Render.com](https://render.com)**.
2. Cree una base de datos PostgreSQL gratuita (**Render PostgreSQL**).
3. Cree un **Web Service (PHP)** vinculando el repositorio.
4. Render inyectará automáticamente la variable `DATABASE_URL` vinculada a la base de datos PostgreSQL.
5. Ingrese una vez a `https://su-app.onrender.com/setup.php` para migrar las tablas en producción y ¡listo!
