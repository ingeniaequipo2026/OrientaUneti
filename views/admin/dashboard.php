<?php
$pageTitle = 'Panel de Administración';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <span class="badge bg-uneti-blue-light text-uneti-blue px-3 py-1 rounded-pill fw-bold mb-1">Dirección de Bienestar</span>
            <h2 class="fw-bold mb-0">Panel de Control Administrativo</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/actividades/crear" class="btn btn-uneti-green btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Nueva Actividad
            </a>
            <a href="/admin/reportes" class="btn btn-uneti btn-sm">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Descargar Reportes
            </a>
        </div>
    </div>

    <!-- Métricas Generales -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-2">
            <div class="stat-card bg-uneti-blue">
                <div class="fs-4 fw-bold"><?= $stats['total_estudiantes'] ?></div>
                <div class="small text-white-50">Estudiantes</div>
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="stat-card bg-uneti-green">
                <div class="fs-4 fw-bold"><?= $stats['total_actividades'] ?></div>
                <div class="small text-white-50">Actividades</div>
                <i class="bi bi-calendar-event-fill"></i>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="stat-card bg-uneti-sky">
                <div class="fs-4 fw-bold"><?= $stats['total_inscritos'] ?></div>
                <div class="small text-white-50">Inscripciones</div>
                <i class="bi bi-journal-check"></i>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="stat-card bg-uneti-green">
                <div class="fs-4 fw-bold"><?= $stats['total_asistencias'] ?></div>
                <div class="small text-white-50">Asistencias</div>
                <i class="bi bi-check-circle-fill"></i>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="stat-card bg-uneti-blue">
                <div class="fs-4 fw-bold"><?= $stats['total_salud'] ?></div>
                <div class="small text-white-50">Salud Mental</div>
                <i class="bi bi-heart-pulse-fill"></i>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="stat-card bg-uneti-green">
                <div class="fs-4 fw-bold"><?= $stats['mensajes_pend'] ?></div>
                <div class="small text-white-50">Mensajes Pend.</div>
                <i class="bi bi-chat-dots-fill"></i>
            </div>
        </div>
    </div>

    <!-- Menú de Acceso Rápido a Módulos -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <a href="/admin/actividades" class="text-decoration-none">
                <div class="card card-uneti p-3 border-start border-4 border-uneti-green">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-range fs-2 text-uneti-green me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Gestión de Actividades</h6>
                            <small class="text-muted">Crear, editar cupos y horarios</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="/admin/inscripciones" class="text-decoration-none">
                <div class="card card-uneti p-3 border-start border-4 border-uneti-sky">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-card-checklist fs-2 text-uneti-blue me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Control de Asistencia</h6>
                            <small class="text-muted">Marcar presencia y notas</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="/admin/salud-mental" class="text-decoration-none">
                <div class="card card-uneti p-3 border-start border-4 border-uneti-green">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-hospital fs-2 text-uneti-green me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Centros de Salud Mental</h6>
                            <small class="text-muted">Directorio de apoyo psicológico</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="/admin/contactos" class="text-decoration-none">
                <div class="card card-uneti p-3 border-start border-4 border-uneti-sky">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-inbox fs-2 text-uneti-sky me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Atención al Usuario</h6>
                            <small class="text-muted">Revisar reclamos y propuestas</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Tablas Recientes -->
    <div class="row g-4">
        <!-- Últimas Actividades -->
        <div class="col-lg-6">
            <div class="card card-uneti shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-calendar-event me-2 text-uneti-green"></i>Actividades Ofertadas</h6>
                    <a href="/admin/actividades" class="btn btn-sm btn-link text-decoration-none">Gestionar</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Título</th>
                                    <th>Categoría</th>
                                    <th>Cupos</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ultimasActividades as $act): ?>
                                    <tr>
                                        <td class="ps-3 fw-semibold"><?= e($act['titulo']) ?></td>
                                        <td><span class="badge bg-light text-dark border"><?= e($act['categoria']) ?></span></td>
                                        <td>
                                            <?php if ($act['esta_llena']): ?>
                                                <span class="badge badge-cupo-lleno">Lleno (<?= $act['cupo_maximo'] ?>)</span>
                                            <?php else: ?>
                                                <span class="badge badge-cupo-disponible"><?= $act['cupos_ocupados'] ?> / <?= $act['cupo_maximo'] ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="badge bg-uneti-green"><?= e($act['estado']) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimas Inscripciones -->
        <div class="col-lg-6">
            <div class="card card-uneti shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-person-check me-2 text-uneti-blue"></i>Últimas Inscripciones</h6>
                    <a href="/admin/inscripciones" class="btn btn-sm btn-link text-decoration-none">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Estudiante</th>
                                    <th>Actividad</th>
                                    <th>Asistencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($ultimasInscripciones)): ?>
                                    <tr><td colspan="3" class="text-center py-3 text-muted">No hay inscripciones registradas</td></tr>
                                <?php else: ?>
                                    <?php foreach ($ultimasInscripciones as $ins): ?>
                                        <tr>
                                            <td class="ps-3">
                                                <strong><?= e($ins['nombre'] . ' ' . $ins['apellido']) ?></strong><br>
                                                <span class="text-muted"><?= e($ins['cedula']) ?></span>
                                            </td>
                                            <td><?= e($ins['actividad_titulo']) ?></td>
                                            <td>
                                                <?= $ins['asistio'] ? '<span class="badge bg-uneti-green">Asistió</span>' : '<span class="badge bg-uneti-blue-light text-uneti-blue">Inscrito</span>' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
