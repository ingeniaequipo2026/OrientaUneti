<?php
$pageTitle = 'Bienestar Estudiantil UNETI';
require_once __DIR__ . '/../layout/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 text-center text-lg-start">
                <span class="badge bg-white text-uneti-blue px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm">
                    <i class="bi bi-star-fill text-uneti-amber me-1"></i> Plataforma Oficial UNETI
                </span>
                <h1 class="display-4 fw-extrabold mb-3 text-white">
                    Bienestar, Salud Mental y Desarrollo Integral en un Solo Lugar
                </h1>
                <p class="lead text-white-50 mb-4">
                    OrientaUneti centraliza la oferta extracurricular, apoyo psicológico e iniciativas de emprendimiento para la comunidad estudiantil de la UNETI, eliminando la fricción burocrática y digitalizando tus constancias al instante.
                </p>
                <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3">
                    <?php if (!is_logged_in()): ?>
                        <a href="/registro" class="btn btn-light btn-lg px-4 fw-bold shadow">
                            <i class="bi bi-person-plus-fill me-1 text-uneti-green"></i> Unirme a OrientaUneti
                        </a>
                        <a href="/login" class="btn btn-outline-light btn-lg px-4 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                        </a>
                    <?php else: ?>
                        <a href="/actividades" class="btn btn-light btn-lg px-4 fw-bold shadow">
                            <i class="bi bi-compass-fill me-1 text-uneti-blue"></i> Explorar Actividades
                        </a>
                        <a href="/estudiante/mis-actividades" class="btn btn-outline-light btn-lg px-4 fw-semibold">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Mis Constancias
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-lg-5">
                <div class="card card-uneti border-0 shadow-lg p-3 bg-white text-dark">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-3 bg-uneti-blue-light rounded-3 me-3 text-uneti-blue">
                                <i class="bi bi-shield-check fs-2"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Gestión Transparente</h5>
                                <small class="text-muted">Control estricto de cupos en tiempo real</small>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-0 small">
                            <li class="py-2 border-bottom d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-uneti-green me-2"></i>
                                <span>Inscripción con 1 solo clic y bloqueo por cupo lleno.</span>
                            </li>
                            <li class="py-2 border-bottom d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-uneti-green me-2"></i>
                                <span>Descarga inmediata de Constancia en PDF con código institucional.</span>
                            </li>
                            <li class="py-2 border-bottom d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-uneti-green me-2"></i>
                                <span>Directorio de Salud Mental y líneas telefónicas oficiales 24/7.</span>
                            </li>
                            <li class="py-2 d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-uneti-green me-2"></i>
                                <span>Canal de Atención al Usuario para reclamos y propuestas.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Estadísticas Rápidas -->
<section class="py-4 bg-white border-bottom shadow-sm">
    <div class="container">
        <div class="row text-center g-3">
            <div class="col-6 col-md-3">
                <div class="fw-bold fs-3 text-uneti-green"><?= $totalEstudiantes ?>+</div>
                <div class="text-muted small">Estudiantes Registrados</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold fs-3 text-uneti-blue"><?= $totalActividades ?></div>
                <div class="text-muted small">Actividades y Talleres</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold fs-3 text-uneti-health"><?= $totalCentrosSalud ?></div>
                <div class="text-muted small">Centros de Apoyo Psicológico</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold fs-3 text-uneti-amber"><?= $totalEmprendimientos ?></div>
                <div class="text-muted small">Emprendimientos TIC</div>
            </div>
        </div>
    </div>
</section>

<!-- Módulos de la Plataforma -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-uneti-blue-light text-uneti-blue px-3 py-1 rounded-pill fw-bold mb-2">Pilares de Bienestar</span>
            <h2 class="fw-bold">Nuestros Módulos de Desarrollo</h2>
            <p class="text-muted max-w-600 mx-auto">
                Diseñados para complementar tu formación académica en telecomunicaciones e informática con herramientas para tu vida.
            </p>
        </div>

        <div class="row g-4">
            <!-- Deportes y Cultura -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-uneti h-100 p-3">
                    <div class="card-body d-flex flex-column">
                        <div class="p-3 bg-uneti-green-light rounded-4 text-uneti-green d-inline-block mb-3" style="width: fit-content;">
                            <i class="bi bi-trophy-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Deportes y Cultura</h5>
                        <p class="text-muted small flex-grow-1">
                            Ajedrez, fútbol sala, ensamble musical y talleres artísticos para fomentar la recreación y el trabajo en equipo.
                        </p>
                        <a href="/actividades" class="btn btn-sm btn-uneti-green mt-2">
                            Ver Actividades <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Salud Mental -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-uneti h-100 p-3">
                    <div class="card-body d-flex flex-column">
                        <div class="p-3 rounded-4 text-uneti-health d-inline-block mb-3 bg-uneti-health-light" style="width: fit-content;">
                            <i class="bi bi-heart-pulse-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Salud Mental</h5>
                        <p class="text-muted small flex-grow-1">
                            Acceso directo al Servicio de Orientación Estudiantil (SOE), líneas de crisis 24/7 y centros psicológicos certificados.
                        </p>
                        <a href="/salud-mental" class="btn btn-sm btn-uneti-health mt-2">
                            Ver Centros y Apoyo <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Emprendimiento -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-uneti h-100 p-3">
                    <div class="card-body d-flex flex-column">
                        <div class="p-3 rounded-4 text-uneti-amber d-inline-block mb-3 bg-uneti-amber-light" style="width: fit-content;">
                            <i class="bi bi-lightbulb-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Emprendimiento</h5>
                        <p class="text-muted small flex-grow-1">
                            Impulso a startups tecnológicas, proyectos IoT y soluciones digitales desarrolladas por estudiantes de la UNETI.
                        </p>
                        <a href="/emprendimientos" class="btn btn-sm btn-uneti-amber mt-2">
                            Vitrina de Proyectos <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Atención al Usuario -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-uneti h-100 p-3">
                    <div class="card-body d-flex flex-column">
                        <div class="p-3 bg-uneti-blue-light rounded-4 text-uneti-blue d-inline-block mb-3" style="width: fit-content;">
                            <i class="bi bi-chat-quote-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Atención al Usuario</h5>
                        <p class="text-muted small flex-grow-1">
                            Canal directo para formular propuestas estudiantiles, reclamos, solicitudes o sugerencias de mejora con seguimiento.
                        </p>
                        <a href="/contacto" class="btn btn-sm btn-uneti-blue mt-2">
                            Formular Solicitud <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Actividades Destacadas -->
<?php if (!empty($actividadesDestacadas)): ?>
<section class="py-5 bg-white border-top">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-uneti-green-light text-uneti-green px-3 py-1 rounded-pill fw-bold mb-1">Oferta Vigente</span>
                <h3 class="fw-bold mb-0">Próximas Actividades Disponibles</h3>
            </div>
            <a href="/actividades" class="btn btn-uneti-outline btn-sm">
                Ver Catálogo Completo <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php foreach ($actividadesDestacadas as $act): ?>
                <div class="col-md-4">
                    <div class="card card-uneti h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-uneti-blue-light text-uneti-blue fw-bold">
                                    <?= e($act['categoria']) ?>
                                </span>
                                <?php if ($act['esta_llena']): ?>
                                    <span class="badge badge-cupo-lleno"><i class="bi bi-x-circle me-1"></i> Cupo lleno</span>
                                <?php else: ?>
                                    <span class="badge badge-cupo-disponible"><i class="bi bi-check-circle me-1"></i> <?= $act['cupos_disponibles'] ?> cupos</span>
                                <?php endif; ?>
                            </div>

                            <h5 class="card-title fw-bold text-dark mt-2 mb-2"><?= e($act['titulo']) ?></h5>
                            <p class="card-text text-muted small flex-grow-1">
                                <?= e(substr($act['descripcion'], 0, 120)) ?>...
                            </p>

                            <div class="small text-muted mb-3">
                                <div><i class="bi bi-geo-alt me-1 text-uneti-health"></i> <?= e($act['ubicacion']) ?></div>
                                <div><i class="bi bi-clock me-1 text-uneti-blue"></i> <?= e($act['horario'] ?: 'Por definir') ?></div>
                            </div>

                            <div class="mt-auto">
                                <a href="/actividades" class="btn btn-uneti w-100 btn-sm">
                                    <i class="bi bi-pencil-square me-1"></i> Consultar e Inscribirse
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Sección Sobre Nosotros -->
<section class="py-5 bg-uneti-surface-alt" id="sobre-nosotros">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-gradient-uneti text-white px-3 py-1 rounded-pill fw-bold mb-2">Sobre Nosotros</span>
                <h2 class="fw-bold mb-3">Movimiento Estudiantil UNETI Código 200</h2>
                <p class="text-muted mb-3">
                    OrientaUneti no es un sistema impuesto; es el resultado de la unión del Movimiento Estudiantil UNETI Código 200, hemos tomado el control de nuestra experiencia universitaria para erradicar la desinformación. Este es un ecosistema digital creado por y para estudiantes, diseñado para garantizar que el acceso a actividades formativas, deportivas y de esparcimiento sea rápido, justo y sin intermediarios. 
                </p>
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded-3 shadow-sm border-start border-4 border-uneti-green">
                            <h6 class="fw-bold text-uneti-green mb-1"><i class="bi bi-bullseye me-1"></i> Misión</h6>
                            <p class="small text-muted mb-0">Promover desde las bases estudiantiles un desarrollo integral real. Queremos un movimiento activo que impulse el bienestar, la inclusión y la salud mental preventiva, asegurando que ningún estudiante de la UNETI se quede fuera por falta de información o burocracia institucional.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded-3 shadow-sm border-start border-4 border-uneti-blue">
                            <h6 class="fw-bold text-uneti-blue mb-1"><i class="bi bi-eye me-1"></i> Visión</h6>
                            <p class="small text-muted mb-0">Consolidar una comunidad universitaria unida y empoderada, donde los estudiantes sean los protagonistas absolutos de su ecosistema digital. Visualizamos una UNETI vanguardista, ética y accesible, donde la tecnología esté al servicio de nuestro crecimiento.</p>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center p-3 bg-white rounded-3 border">
                    <i class="bi bi-shield-shaded fs-2 text-uneti-blue me-3"></i>
                    <div class="small">
                        <strong>Transparencia y Cero Sesgos:</strong> Las oportunidades de bienestar se distribuyen por orden de registro estricto según la disponibilidad de cupos, sin algoritmos discriminatorios ni cajas negras.
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient-uneti text-white p-4">
                        <h5 class="fw-bold mb-1"><i class="bi bi-diagram-3-fill me-2"></i> Estructura del Sistema OrientaUneti</h5>
                        <small class="text-white-50">Articulación técnica y humana al servicio estudiantil</small>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <div class="timeline small">
                            <div class="d-flex mb-3">
                                <div class="me-3 text-uneti-green"><i class="bi bi-1-circle-fill fs-4"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Registro y Autenticación Segura</h6>
                                    <p class="text-muted mb-0">Acceso mediante credenciales con contraseñas encriptadas con algoritmo Bcrypt y perfiles diferenciados.</p>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="me-3 text-uneti-blue"><i class="bi bi-2-circle-fill fs-4"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Inscripción Atómica con Control de Cupos</h6>
                                    <p class="text-muted mb-0">Garantía transaccional en PostgreSQL: cuando una actividad llega a su tope, el sistema bloquea inmediatamente nuevos registros.</p>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="me-3 text-uneti-green"><i class="bi bi-3-circle-fill fs-4"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Generación Inmediata de Constancias</h6>
                                    <p class="text-muted mb-0">Descarga directa en formato PDF con membrete oficial y código de validación institucional.</p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="me-3 text-uneti-health"><i class="bi bi-4-circle-fill fs-4"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Auditoría, Asistencia y Reportes</h6>
                                    <p class="text-muted mb-0">Los coordinadores de bienestar pueden descargar planillas completas en Excel y PDF con un solo clic.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
