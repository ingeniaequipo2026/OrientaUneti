<?php
$pageTitle = 'Administrar Centros de Salud Mental';
require_once __DIR__ . '/../../layout/header.php';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <span class="badge bg-danger text-white px-3 py-1 rounded-pill fw-bold mb-1">Módulo Salud Mental</span>
            <h2 class="fw-bold mb-0">Gestión de Centros e Instituciones de Apoyo</h2>
        </div>
        <a href="/admin/salud-mental/crear" class="btn btn-uneti">
            <i class="bi bi-plus-circle me-1"></i> Registrar Nuevo Centro
        </a>
    </div>

    <div class="card card-uneti shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Institución</th>
                            <th>Tipo de Servicio</th>
                            <th>Ubicación / Dirección</th>
                            <th>Contacto</th>
                            <th>Modalidad</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($centros)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">No hay centros de salud mental registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($centros as $c): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-dark"><?= e($c['nombre_institucion']) ?></td>
                                    <td><span class="badge bg-uneti-blue-light text-uneti-blue border"><?= e($c['tipo_servicio']) ?></span></td>
                                    <td class="small text-muted"><?= e($c['direccion']) ?></td>
                                    <td class="small">
                                        <div><i class="bi bi-telephone text-success me-1"></i><?= e($c['telefono'] ?: 'N/A') ?></div>
                                        <div><i class="bi bi-envelope text-primary me-1"></i><?= e($c['correo'] ?: 'N/A') ?></div>
                                    </td>
                                    <td class="small"><?= e($c['modalidad']) ?></td>
                                    <td>
                                        <?= $c['activo'] ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>' ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="/admin/salud-mental/editar/<?= $c['id'] ?>" class="btn btn-outline-primary" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="/admin/salud-mental/eliminar/<?= $c['id'] ?>" method="POST" class="d-inline form-eliminar">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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
