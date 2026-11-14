-- =====================================================
-- Datos Semilla (Seed) MySQL - OrientaUneti
-- =====================================================

-- Inserción de Usuarios de Prueba (Hash bcrypt de 'Admin123*' y 'Estudiante123*')
INSERT INTO usuarios (rol_id, cedula, nombre, apellido, email, password, telefono, trayectoria) VALUES
(1, 'V-10234567', 'Administrador', 'OrientaUneti', 'admin@uneti.edu.ve', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0414-1234567', 'Dirección de Bienestar Estudiantil'),
(2, 'V-28123456', 'Carlos', 'Mendoza', 'estudiante@uneti.edu.ve', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0412-9876543', 'PNF Informática - Trayecto II'),
(2, 'V-27891234', 'Mariana', 'Silva', 'mariana.silva@uneti.edu.ve', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0424-5551234', 'PNF Telecomunicaciones - Trayecto III')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);

-- Actividades de Bienestar
INSERT INTO actividades (titulo, categoria, descripcion, instructor, ubicacion, fecha_inicio, fecha_fin, horario, cupo_maximo, estado) VALUES
('Torneo de Ajedrez Universitario', 'Deporte', 'Competencia de ajedrez rápido orientada a fomentar la concentración, pensamiento estratégico y camaradería entre estudiantes de la UNETI.', 'Prof. Ramón Gil', 'Plaza Central UNETI / Aula Virtual', DATE_ADD(CURRENT_DATE, INTERVAL 5 DAY), DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY), 'Jueves y Viernes 2:00 PM - 5:00 PM', 16, 'Activa'),
('Taller de Ciberseguridad y Ética Digital', 'Talleres', 'Taller práctico sobre buenas prácticas de protección de datos personales, prevención de phishing y seguridad en entornos académicos.', 'Ing. Valeria Torres', 'Laboratorio de Redes 2 / Sesión Híbrida', DATE_ADD(CURRENT_DATE, INTERVAL 10 DAY), DATE_ADD(CURRENT_DATE, INTERVAL 12 DAY), 'Sábados 9:00 AM - 1:00 PM', 25, 'Activa'),
('Grupo de Yoga y Manejo del Estrés', 'Salud', 'Sesiones dirigidas a la respiración consciente, estiramientos y técnicas para aliviar la tensión académica antes de las semanas de entrega.', 'Lic. Sofía Rivas', 'Gimnasio Tech UNETI / Modalidad Mixta', DATE_ADD(CURRENT_DATE, INTERVAL 3 DAY), DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY), 'Lunes y Miércoles 7:30 AM - 8:30 AM', 20, 'Activa'),
('Club de Robótica e IoT Aplicado', 'Talleres', 'Espacio colaborativo para crear proyectos basados en microcontroladores ESP32 y sensores para automatización universitaria.', 'Ing. Andrés Bello', 'MakerSpace UNETI', DATE_ADD(CURRENT_DATE, INTERVAL 14 DAY), DATE_ADD(CURRENT_DATE, INTERVAL 45 DAY), 'Martes 3:00 PM - 6:00 PM', 12, 'Activa'),
('Ensamble Musical UNETI', 'Cultura', 'Prácticas de canto e instrumentos tradicionales y modernos. No se requiere experiencia previa avanzada, solo vocación artística.', 'Prof. José Rondón', 'Auditorio de Telecomunicaciones', DATE_ADD(CURRENT_DATE, INTERVAL 4 DAY), DATE_ADD(CURRENT_DATE, INTERVAL 60 DAY), 'Viernes 4:00 PM - 7:00 PM', 15, 'Activa'),
('Liga Universitaria de Fútbol Sala', 'Deporte', 'Torneo inter-trayectos de fútsal masculino y femenino. Inscripciones por equipos o individuales con asignación de grupo.', 'Entrenador Marcos Peña', 'Cancha Múltiple UNETI', DATE_ADD(CURRENT_DATE, INTERVAL 8 DAY), DATE_ADD(CURRENT_DATE, INTERVAL 40 DAY), 'Sábados 10:00 AM - 2:00 PM', 30, 'Activa');

-- Centros de Salud Mental
INSERT INTO centros_salud (nombre_institucion, tipo_servicio, direccion, telefono, correo, horario_atencion, modalidad, descripcion) VALUES
('Servicio de Orientación y Psicología UNETI (SOE)', 'Atención Psicológica', 'Edificio Central UNETI, Piso 2, Módulo de Bienestar Estudiantil', '0212-5550190', 'bienestar.psico@uneti.edu.ve', 'Lunes a Viernes 8:00 AM - 4:00 PM', 'Presencial y Virtual', 'Atención individual, contención emocional, orientación vocacional y talleres grupales de bienestar mental para estudiantes activos.'),
('Línea Telefónica de Apoyo Emocional 0800-SALUD', 'Línea de Crisis', 'Atención nacional 24/7 gratuita', '0800-7258300', 'apoyo@salud.gob.ve', '24 horas / 7 días a la semana', 'Telefónica', 'Línea de emergencia psicológica y contención en situaciones de crisis, ansiedad severa o desorientación emocional.'),
('Centro de Atención a la Salud Mental del Municipio', 'Atención Psicológica Integral', 'Av. Principal con Calle Los Cedros, Centro Comunitario', '0212-7894561', 'saludmental.comunitaria@gmail.com', 'Lunes a Jueves 8:30 AM - 2:30 PM', 'Presencial', 'Evaluación psicológica, terapia breve y seguimiento en red de salud pública articulada con convenios universitarios.');

-- Emprendimientos Estudiantiles
INSERT INTO emprendimientos (nombre_proyecto, categoria, responsable, contacto, descripcion, enlace_redes, estado) VALUES
('RedUneti LoRa: Telemetría Universitaria', 'Telecomunicaciones', 'Carlos Mendoza y Equipo Alfa', 'carlos.mendoza@uneti.edu.ve', 'Red de sensores de bajo consumo usando modulación LoRa para monitorear variables ambientales y seguridad en el campus.', 'https://github.com/reduneti-lora', 'Activo'),
('AprendeFácil: Plataforma P2P de Asesorías', 'Software', 'Mariana Silva', '0424-5551234', 'Plataforma para conectar estudiantes avanzados con estudiantes de nuevo ingreso para tutorías en matemáticas, programación y física.', 'https://instagram.com/aprendefacil_uneti', 'En Incubación'),
('EcoTech 3D: Reciclaje y Filamento 3D', 'Innovación', 'David Pernía', 'david.pernia@uneti.edu.ve', 'Iniciativa que recolecta plástico tipo PET para convertirlo en filamento reciclado destinado a prototipos del MakerSpace.', 'https://t.me/ecotech3d_uneti', 'Idea');

-- Inscripciones Iniciales
INSERT INTO inscripciones (usuario_id, actividad_id, estado, asistio, observaciones) VALUES
(2, 1, 'Inscrito', false, 'Inscripción confirmada vía portal'),
(2, 2, 'Inscrito', true, 'Asistió a sesión inaugural'),
(3, 1, 'Inscrito', false, 'Inscripción confirmada')
ON DUPLICATE KEY UPDATE estado=VALUES(estado);

-- Solicitud de Atención de Ejemplo
INSERT INTO contactos (nombre, correo, cedula, tipo, asunto, mensaje, estado) VALUES
('Pedro Alarcón', 'pedro.alarcon@uneti.edu.ve', 'V-26555444', 'Propuesta', 'Club de Desarrollo de Videojuegos', 'Quisiera proponer la apertura de un club estudiantil de desarrollo de videojuegos usando Godot Engine los días sábados.', 'Pendiente');
