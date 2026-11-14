<?php
$pageTitle = 'Tablero del Estudiante';
require_once __DIR__ . '/../layout/header.php';
$user = auth_user();
?>

<div class="container py-4">
    <!-- Bienvenida -->
    <div class="p-4 p-md-5 mb-4 rounded-4 bg-gradient-uneti text-white shadow-sm position-relative overflow-hidden">
        <div class="position-relative" style="z-index: 2;">
            <span class="badge bg-white text-uneti-green px-3 py-1 rounded-pill fw-bold mb-2">Panel Estudiantil</span>
            <h2 class="fw-bold mb-1">¡Hola, <?= e($user['nombre']) ?>!</h2>
            <p class="text-white-50 mb-3">
                <?= e($user['trayectoria'] ?: 'Estudiante UNETI') ?> | C.I. <?= e($user['cedula']) ?>
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="/actividades" class="btn btn-light fw-bold">
                    <i class="bi bi-calendar-plus me-1 text-uneti-green"></i> Inscribir Actividades
                </a>
                <a href="/estudiante/descargar-constancia" class="btn btn-outline-light">
                    <i class="bi bi-file-earmark-pdf-fill me-1 text-uneti-health"></i> Descargar Constancia PDF
                </a>
                <a href="/contacto" class="btn btn-outline-light">
                    <i class="bi bi-chat-heart me-1"></i> Atención al Usuario
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjetas Métricas del Estudiante -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card card-uneti h-100 p-3 border-start border-4 border-uneti-green">
                <div class="d-flex align-items-center">
                    <div class="p-3 bg-uneti-green-light rounded-4 text-uneti-green me-3">
                        <i class="bi bi-check2-circle fs-2"></i>
                    </div>
                    <div>
                        <div class="display-6 fw-bold text-dark"><?= count($misInscripciones) ?></div>
                        <div class="text-muted small fw-semibold">Mis Actividades Inscritas</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-uneti h-100 p-3 border-start border-4 border-uneti-blue">
                <div class="d-flex align-items-center">
                    <div class="p-3 bg-uneti-blue-light rounded-4 text-uneti-blue me-3">
                        <i class="bi bi-calendar-event fs-2"></i>
                    </div>
                    <div>
                        <div class="display-6 fw-bold text-dark"><?= count($actividadesDisponibles) ?></div>
                        <div class="text-muted small fw-semibold">Actividades Ofertadas</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-uneti h-100 p-3 border-start border-4 border-uneti-health">
                <div class="d-flex align-items-center">
                    <div class="p-3 rounded-4 text-uneti-health me-3 bg-uneti-health-light">
                        <i class="bi bi-heart-pulse-fill fs-2"></i>
                    </div>
                    <div>
                        <div class="display-6 fw-bold text-dark"><?= $totalCentrosSalud ?></div>
                        <div class="text-muted small fw-semibold">Centros de Salud Mental</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mis Actividades Recientes -->
    <div class="card card-uneti shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-award-fill text-uneti-green me-2"></i>Mis Inscripciones Activas
            </h5>
            <a href="/estudiante/mis-actividades" class="btn btn-sm btn-uneti-outline">
                Ver Todas y Constancias <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Actividad</th>
                            <th>Área</th>
                            <th>Horario</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($misInscripciones)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Aún no te has inscrito en ninguna actividad de bienestar.<br>
                                    <a href="/actividades" class="btn btn-uneti btn-sm mt-2">
                                        <i class="bi bi-plus-circle me-1"></i> Explorar Actividades Ofertadas
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($misInscripciones as $ins): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold text-dark"><?= e($ins['titulo']) ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= e($ins['categoria']) ?></span></td>
                                    <td class="small text-muted"><?= e($ins['horario'] ?: 'Por definir') ?></td>
                                    <td class="small text-muted"><?= e($ins['ubicacion']) ?></td>
                                    <td>
                                        <?php if ($ins['asistio']): ?>
                                            <span class="badge bg-uneti-green"><i class="bi bi-check-circle me-1"></i> Asistió</span>
                                        <?php else: ?>
                                            <span class="badge bg-uneti-blue-light text-uneti-blue"><?= e($ins['estado']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="/estudiante/cancelar-inscripcion" method="POST" class="d-inline form-cancelar-inscripcion">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="actividad_id" value="<?= $ins['actividad_id'] ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Cancelar cupo">
                                                <i class="bi bi-x-circle"></i> Cancelar
                                            </button>
                                        </form>
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

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
