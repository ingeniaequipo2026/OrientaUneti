<?php
$pageTitle = 'Reportes y Planillas';
require_once __DIR__ . '/../../layout/header.php';
$filtroActividad = $_GET['actividad_id'] ?? '';
?>

<div class="container-fluid py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <span class="badge bg-uneti-green-light text-uneti-green px-3 py-1 rounded-pill fw-bold mb-1">Módulo de Auditoría</span>
            <h2 class="fw-bold mb-0">Planillas de Estudiantes Activos y Asistencia</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/reportes/excel?actividad_id=<?= urlencode($filtroActividad) ?>" class="btn btn-success shadow-sm">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Descargar Planilla Excel (CSV)
            </a>
            <a href="/admin/reportes/pdf?actividad_id=<?= urlencode($filtroActividad) ?>" class="btn btn-blue shadow-sm" style="background-color: #1E3A8A; border-color: #1E3A8A; color: #fff;">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Descargar Planilla PDF
            </a>
        </div>
    </div>

    <!-- Filtro -->
    <div class="card card-uneti border-0 shadow-sm p-3 mb-4">
        <form method="GET" action="/admin/reportes" class="row g-2 align-items-center">
            <div class="col-md-5">
                <label for="actividad_id" class="form-label small fw-semibold text-muted mb-1">Filtrar Planilla por Actividad:</label>
                <select name="actividad_id" id="actividad_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Consolidado General (Todas las Actividades) --</option>
                    <?php foreach ($actividades as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= $filtroActividad == $a['id'] ? 'selected' : '' ?>>
                            <?= e($a['titulo']) ?> (<?= $a['cupos_ocupados'] ?>/<?= $a['cupo_maximo'] ?> cupos)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if (!empty($filtroActividad)): ?>
                <div class="col-md-3 pt-md-4">
                    <a href="/admin/reportes" class="btn btn-outline-secondary btn-sm">Restablecer Consolidado</a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Vista Previa de la Planilla -->
    <div class="card card-uneti shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-table text-primary me-2"></i>Vista Previa de la Planilla Oficial
            </h5>
            <span class="badge bg-light text-dark border">
                Total Registros: <?= count($inscripciones) ?>
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0 small">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">Cédula</th>
                            <th>Apellidos y Nombres</th>
                            <th>Correo Electrónico</th>
                            <th>Carrera / Trayecto</th>
                            <th>Actividad / Taller</th>
                            <th>Categoría</th>
                            <th>F. Inscripción</th>
                            <th>Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($inscripciones)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    No se encontraron registros para generar la planilla.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($inscripciones as $row): ?>
                                <tr>
                                    <td class="ps-3 fw-bold"><?= e($row['cedula']) ?></td>
                                    <td><?= e($row['apellido'] . ', ' . $row['nombre']) ?></td>
                                    <td class="text-muted"><?= e($row['email']) ?></td>
                                    <td><?= e($row['trayectoria'] ?: 'N/A') ?></td>
                                    <td class="fw-semibold text-primary"><?= e($row['actividad_titulo']) ?></td>
                                    <td><span class="badge bg-secondary"><?= e($row['actividad_categoria']) ?></span></td>
                                    <td><?= date('d/m/Y', strtotime($row['fecha_inscripcion'])) ?></td>
                                    <td>
                                        <?php if ($row['asistio']): ?>
                                            <span class="badge bg-success"><i class="bi bi-check-lg"></i> Asistió</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Pendiente</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3 d-flex justify-content-end gap-2">
            <a href="/admin/reportes/excel?actividad_id=<?= urlencode($filtroActividad) ?>" class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-excel me-1"></i> Exportar CSV/Excel
            </a>
            <a href="/admin/reportes/pdf?actividad_id=<?= urlencode($filtroActividad) ?>" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i> Exportar PDF
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
