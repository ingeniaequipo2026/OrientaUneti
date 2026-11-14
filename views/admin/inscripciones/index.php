<?php
$pageTitle = 'Control de Asistencia e Inscripciones';
require_once __DIR__ . '/../../layout/header.php';
$filtroActividad = $_GET['actividad_id'] ?? '';
?>

<div class="container-fluid py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <span class="badge bg-uneti-blue-light text-uneti-blue px-3 py-1 rounded-pill fw-bold mb-1">Control Transaccional</span>
            <h2 class="fw-bold mb-0">Inscripciones y Registro de Asistencia</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/reportes/excel?actividad_id=<?= urlencode($filtroActividad) ?>" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel me-1"></i> Descargar Excel
            </a>
            <a href="/admin/reportes/pdf?actividad_id=<?= urlencode($filtroActividad) ?>" class="btn btn-danger btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i> Descargar PDF
            </a>
        </div>
    </div>

    <!-- Filtro por Actividad -->
    <div class="card card-uneti border-0 shadow-sm p-3 mb-4">
        <form method="GET" action="/admin/inscripciones" class="row g-2 align-items-center">
            <div class="col-md-5">
                <label for="actividad_id" class="form-label small fw-semibold text-muted mb-1">Filtrar por Actividad:</label>
                <select name="actividad_id" id="actividad_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Todas las Actividades --</option>
                    <?php foreach ($actividades as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= $filtroActividad == $a['id'] ? 'selected' : '' ?>>
                            <?= e($a['titulo']) ?> (<?= $a['cupos_ocupados'] ?>/<?= $a['cupo_maximo'] ?> inscritos)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if (!empty($filtroActividad)): ?>
                <div class="col-md-3 pt-md-4">
                    <a href="/admin/inscripciones" class="btn btn-outline-secondary btn-sm">Limpiar Filtro</a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Tabla de Inscripciones y Control de Asistencia -->
    <div class="card card-uneti shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-people-fill text-uneti-blue me-2"></i>Estudiantes Inscritos (<?= count($inscripciones) ?>)
            </h5>
            <small class="text-muted">Marque la casilla para registrar asistencia confirmada</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Cédula</th>
                            <th>Estudiante</th>
                            <th>Carrera / Trayecto</th>
                            <th>Actividad</th>
                            <th>Contacto</th>
                            <th>Fecha Inscripción</th>
                            <th>Estado Actual</th>
                            <th class="pe-4 text-center" style="width: 280px;">Registro de Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($inscripciones)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-person-x fs-1 d-block mb-2 text-secondary"></i>
                                    No hay estudiantes inscritos en el criterio seleccionado.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($inscripciones as $row): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-dark"><?= e($row['cedula']) ?></td>
                                    <td>
                                        <div class="fw-semibold"><?= e($row['nombre'] . ' ' . $row['apellido']) ?></div>
                                        <small class="text-muted"><?= e($row['email']) ?></small>
                                    </td>
                                    <td class="small"><?= e($row['trayectoria'] ?: 'Sin asignar') ?></td>
                                    <td>
                                        <span class="badge bg-uneti-blue-light text-uneti-blue border d-block mb-1 text-truncate" style="max-width: 200px;">
                                            <?= e($row['actividad_titulo']) ?>
                                        </span>
                                    </td>
                                    <td class="small text-muted"><?= e($row['telefono'] ?: 'N/A') ?></td>
                                    <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($row['fecha_inscripcion'])) ?></td>
                                    <td>
                                        <?php if ($row['asistio']): ?>
                                            <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Asistió</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><i class="bi bi-hourglass-split me-1"></i> Pendiente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4">
                                        <!-- Formulario para marcar asistencia en línea -->
                                        <form action="/admin/asistencia/marcar" method="POST" class="d-flex align-items-center gap-2">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="inscripcion_id" value="<?= $row['id'] ?>">
                                            <input type="hidden" name="actividad_id" value="<?= e($filtroActividad) ?>">
                                            
                                            <select name="asistio" class="form-select form-select-sm" style="width: 120px;">
                                                <option value="1" <?= $row['asistio'] ? 'selected' : '' ?>>Presente</option>
                                                <option value="0" <?= !$row['asistio'] ? 'selected' : '' ?>>Ausente</option>
                                            </select>

                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Guardar asistencia">
                                                <i class="bi bi-save"></i>
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

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
