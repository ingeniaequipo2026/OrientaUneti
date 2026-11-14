-- =====================================================
-- Esquema de Base de Datos MySQL / MariaDB - OrientaUneti
-- Compatible con XAMPP (MariaDB 10.x / MySQL 8.x)
-- =====================================================

-- 1. Tabla de Roles
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO roles (id, nombre, descripcion) VALUES
(1, 'Administrador', 'Acceso total a la gestión de módulos, reportes y usuarios'),
(2, 'Estudiante', 'Acceso al catálogo de bienestar, inscripciones y perfil')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);

-- 2. Tabla de Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rol_id INT NOT NULL DEFAULT 2,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(25),
    trayectoria VARCHAR(150),
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuarios_rol FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabla de Actividades
CREATE TABLE IF NOT EXISTS actividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    descripcion TEXT NOT NULL,
    instructor VARCHAR(100),
    ubicacion VARCHAR(150) NOT NULL,
    fecha_inicio DATE,
    fecha_fin DATE,
    horario VARCHAR(100),
    cupo_maximo INT NOT NULL DEFAULT 20,
    estado VARCHAR(30) DEFAULT 'Activa',
    imagen VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabla de Centros de Salud Mental y Apoyo Psicológico
CREATE TABLE IF NOT EXISTS centros_salud (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_institucion VARCHAR(150) NOT NULL,
    tipo_servicio VARCHAR(100) NOT NULL,
    direccion TEXT NOT NULL,
    telefono VARCHAR(50),
    correo VARCHAR(150),
    horario_atencion VARCHAR(100),
    modalidad VARCHAR(50) DEFAULT 'Presencial y Virtual',
    descripcion TEXT,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabla de Emprendimientos
CREATE TABLE IF NOT EXISTS emprendimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_proyecto VARCHAR(150) NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    responsable VARCHAR(150) NOT NULL,
    contacto VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    enlace_redes VARCHAR(255),
    estado VARCHAR(50) DEFAULT 'En Incubación',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Tabla Transaccional de Inscripciones
CREATE TABLE IF NOT EXISTS inscripciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    actividad_id INT NOT NULL,
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(30) DEFAULT 'Inscrito',
    asistio BOOLEAN DEFAULT FALSE,
    observaciones TEXT,
    UNIQUE KEY unique_usuario_actividad (usuario_id, actividad_id),
    CONSTRAINT fk_inscripcion_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_inscripcion_actividad FOREIGN KEY (actividad_id) REFERENCES actividades(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Tabla de Contactos / Atención al Usuario
CREATE TABLE IF NOT EXISTS contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    cedula VARCHAR(20),
    tipo VARCHAR(50) NOT NULL,
    asunto VARCHAR(150) NOT NULL,
    mensaje TEXT NOT NULL,
    estado VARCHAR(30) DEFAULT 'Pendiente',
    respuesta TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
