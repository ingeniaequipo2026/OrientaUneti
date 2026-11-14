-- =====================================================
-- Esquema de Base de Datos PostgreSQL - OrientaUneti
-- Plataforma de Bienestar Estudiantil UNETI
-- =====================================================

-- 1. Tabla de Roles
CREATE TABLE IF NOT EXISTS roles (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion VARCHAR(255)
);

-- Inserción de roles iniciales
INSERT INTO roles (id, nombre, descripcion) VALUES
(1, 'Administrador', 'Acceso total a la gestión de módulos, reportes y usuarios'),
(2, 'Estudiante', 'Acceso al catálogo de bienestar, inscripciones y perfil')
ON CONFLICT (id) DO NOTHING;

-- 2. Tabla de Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    rol_id INT NOT NULL DEFAULT 2 REFERENCES roles(id) ON DELETE RESTRICT,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(25),
    trayectoria VARCHAR(150), -- Carrera y Trayecto / Semestre
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Tabla de Actividades (Deportes, Cultura, Talleres, Recreación)
CREATE TABLE IF NOT EXISTS actividades (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    categoria VARCHAR(50) NOT NULL, -- 'Deporte', 'Cultura', 'Talleres', 'Recreación', 'Salud'
    descripcion TEXT NOT NULL,
    instructor VARCHAR(100),
    ubicacion VARCHAR(150) NOT NULL,
    fecha_inicio DATE,
    fecha_fin DATE,
    horario VARCHAR(100),
    cupo_maximo INT NOT NULL DEFAULT 20,
    estado VARCHAR(30) DEFAULT 'Activa', -- 'Activa', 'En Curso', 'Finalizada', 'Cancelada'
    imagen VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Tabla de Centros de Salud Mental y Apoyo Psicológico
-- Nota de Privacidad y Ética: Solo registra entidades y centros de atención oficial,
-- NO almacena historiales clínicos ni diagnósticos de estudiantes.
CREATE TABLE IF NOT EXISTS centros_salud (
    id SERIAL PRIMARY KEY,
    nombre_institucion VARCHAR(150) NOT NULL,
    tipo_servicio VARCHAR(100) NOT NULL, -- 'Atención Psicológica', 'Orientación y Asesoría', 'Línea de Crisis'
    direccion TEXT NOT NULL,
    telefono VARCHAR(50),
    correo VARCHAR(150),
    horario_atencion VARCHAR(100),
    modalidad VARCHAR(50) DEFAULT 'Presencial y Virtual', -- 'Presencial', 'Virtual', 'Presencial y Virtual'
    descripcion TEXT,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. Tabla de Emprendimientos Estudiantiles
CREATE TABLE IF NOT EXISTS emprendimientos (
    id SERIAL PRIMARY KEY,
    nombre_proyecto VARCHAR(150) NOT NULL,
    categoria VARCHAR(100) NOT NULL, -- 'Telecomunicaciones', 'Software', 'Servicios', 'Comercio Digital'
    responsable VARCHAR(150) NOT NULL,
    contacto VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    enlace_redes VARCHAR(255),
    estado VARCHAR(50) DEFAULT 'En Incubación', -- 'Idea', 'En Incubación', 'Activo'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 6. Tabla Transaccional de Inscripciones (Muchos a Muchos: Usuarios <-> Actividades)
-- Controla cupos, fecha de registro y registro de asistencia
CREATE TABLE IF NOT EXISTS inscripciones (
    id SERIAL PRIMARY KEY,
    usuario_id INT NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    actividad_id INT NOT NULL REFERENCES actividades(id) ON DELETE CASCADE,
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(30) DEFAULT 'Inscrito', -- 'Inscrito', 'Asistió', 'No Asistió', 'Cancelado'
    asistio BOOLEAN DEFAULT FALSE,
    observaciones TEXT,
    CONSTRAINT unique_usuario_actividad UNIQUE (usuario_id, actividad_id)
);

-- 7. Tabla de Contactos / Atención al Usuario
CREATE TABLE IF NOT EXISTS contactos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    cedula VARCHAR(20),
    tipo VARCHAR(50) NOT NULL, -- 'Propuesta', 'Reclamo', 'Consulta', 'Sugerencia'
    asunto VARCHAR(150) NOT NULL,
    mensaje TEXT NOT NULL,
    estado VARCHAR(30) DEFAULT 'Pendiente', -- 'Pendiente', 'En Revisión', 'Atendido'
    respuesta TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índices para optimizar consultas frecuentes
CREATE INDEX IF NOT EXISTS idx_usuarios_rol ON usuarios(rol_id);
CREATE INDEX IF NOT EXISTS idx_usuarios_email ON usuarios(email);
CREATE INDEX IF NOT EXISTS idx_actividades_estado ON actividades(estado);
CREATE INDEX IF NOT EXISTS idx_inscripciones_usuario ON inscripciones(usuario_id);
CREATE INDEX IF NOT EXISTS idx_inscripciones_actividad ON inscripciones(actividad_id);
