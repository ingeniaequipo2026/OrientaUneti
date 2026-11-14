<?php
$pageTitle = 'Administrar Emprendimientos';
require_once __DIR__ . '/../../layout/header.php';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-1">Módulo de Emprendimiento</span>
            <h2 class="fw-bold mb-0">Gestión de Emprendimientos Estudiantiles</h2>
        </div>
        <a href="/admin/emprendimientos/crear" class="btn btn-uneti">
            <i class="bi bi-plus-circle me-1"></i> Registrar Emprendimiento
        </a>
    </div>

    <div class="card card-uneti shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Proyecto</th>
                            <th>Área / Categoría</th>
                            <th>Líder / Responsable</th>
                            <th>Contacto</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($emprendimientos)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No hay emprendimientos registrados en el catálogo</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($emprendimientos as $emp): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-dark"><?= e($emp['nombre_proyecto']) ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= e($emp['categoria']) ?></span></td>
                                    <td><?= e($emp['responsable']) ?></td>
                                    <td class="small text-muted"><?= e($emp['contacto']) ?></td>
                                    <td><span class="badge bg-warning text-dark"><?= e($emp['estado']) ?></span></td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="/admin/emprendimientos/editar/<?= $emp['id'] ?>" class="btn btn-outline-primary" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="/admin/emprendimientos/eliminar/<?= $emp['id'] ?>" method="POST" class="d-inline form-eliminar">
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
