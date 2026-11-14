<?php
$pageTitle = 'Mis Actividades Inscritas';
require_once __DIR__ . '/../layout/header.php';
$user = auth_user();
?>

<div class="container py-4">
    <!-- Encabezado con Botón Descargar Constancia PDF -->
    <div class="card card-uneti border-0 shadow-sm p-4 mb-4 bg-white">
        <div class="row align-items-center g-3">
            <div class="col-md-7">
                <span class="badge bg-uneti-green-light text-uneti-green px-3 py-1 rounded-pill fw-bold mb-1">
                    Mi Expediente de Bienestar
                </span>
                <h3 class="fw-bold mb-1">Mis Actividades Inscritas</h3>
                <p class="text-muted small mb-0">
                    Estudiante: <strong><?= e($user['nombre'] . ' ' . $user['apellido']) ?></strong> | C.I. <strong><?= e($user['cedula']) ?></strong>
                </p>
            </div>
            <div class="col-md-5 text-md-end">
                <a href="/estudiante/descargar-constancia" class="btn btn-green btn-lg shadow-sm">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Descargar Constancia en PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla de Actividades Inscritas -->
    <div class="card card-uneti shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-journal-check text-uneti-green me-2"></i>Historial de Inscripciones y Estado
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Actividad</th>
                            <th>Área</th>
                            <th>Facilitador</th>
                            <th>Horario / Ubicación</th>
                            <th>Fecha Inscripción</th>
                            <th>Estado / Asistencia</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($misInscripciones)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-2 text-secondary"></i>
                                    <h5>No te encuentras inscrito en ninguna actividad actualmente</h5>
                                    <p class="small text-muted mb-3">Revisa el catálogo disponible e inscríbete para obtener tu constancia oficial.</p>
                                    <a href="/actividades" class="btn btn-uneti">
                                        <i class="bi bi-plus-circle me-1"></i> Ir al Catálogo de Actividades
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($misInscripciones as $ins): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark"><?= e($ins['titulo']) ?></div>
                                        <small class="text-muted"><?= e(substr($ins['descripcion'], 0, 80)) ?>...</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-uneti-blue-light text-uneti-blue border">
                                            <?= e($ins['categoria']) ?>
                                        </span>
                                    </td>
                                    <td class="small"><?= e($ins['instructor'] ?: 'Coordinación') ?></td>
                                    <td class="small text-muted">
                                        <div><i class="bi bi-clock me-1"></i> <?= e($ins['horario'] ?: 'Por definir') ?></div>
                                        <div><i class="bi bi-geo-alt me-1 text-danger"></i> <?= e($ins['ubicacion']) ?></div>
                                    </td>
                                    <td class="small text-muted">
                                        <?= date('d/m/Y', strtotime($ins['fecha_inscripcion'])) ?>
                                    </td>
                                    <td>
                                        <?php if ($ins['asistio']): ?>
                                            <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Asistió</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary"><i class="bi bi-clock-history me-1"></i> <?= e($ins['estado']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="/estudiante/cancelar-inscripcion" method="POST" class="d-inline form-cancelar-inscripcion">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="actividad_id" value="<?= $ins['actividad_id'] ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Liberar mi cupo">
                                                <i class="bi bi-x-circle me-1"></i> Cancelar
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
        <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
            <span class="small text-muted">
                Total de inscripciones: <strong><?= count($misInscripciones) ?></strong>
            </span>
            <a href="/estudiante/descargar-constancia" class="btn btn-blue btn-sm">
                <i class="bi bi-download me-1"></i> Descargar Constancia PDF
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
