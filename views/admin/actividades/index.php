<?php
$pageTitle = 'Gestión de Actividades';
require_once __DIR__ . '/../../layout/header.php';
$categoriaActiva = $_GET['categoria'] ?? 'Todas';
$categorias = ['Todas', 'Deporte', 'Cultura', 'Talleres', 'Salud', 'Recreación'];
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <span class="badge bg-uneti-green-light text-uneti-green px-3 py-1 rounded-pill fw-bold mb-1">Módulo Administrativo</span>
            <h2 class="fw-bold mb-0">Gestión de Actividades de Bienestar</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/actividades/crear" class="btn btn-uneti">
                <i class="bi bi-plus-circle me-1"></i> Crear Nueva Actividad
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card card-uneti border-0 shadow-sm p-3 mb-4">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="fw-semibold small text-muted me-2"><i class="bi bi-funnel me-1"></i> Categoría:</span>
            <?php foreach ($categorias as $cat): ?>
                <?php $esActivo = ($categoriaActiva === $cat || ($cat === 'Todas' && empty($_GET['categoria']))); ?>
                <a href="/admin/actividades?categoria=<?= urlencode($cat) ?>" 
                   class="btn btn-sm <?= $esActivo ? 'btn-uneti' : 'btn-light' ?> rounded-pill px-3">
                    <?= e($cat) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Tabla CRUD de Actividades -->
    <div class="card card-uneti shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Título de la Actividad</th>
                            <th>Área</th>
                            <th>Facilitador</th>
                            <th>Horario / Ubicación</th>
                            <th>Ocupación / Cupo</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($actividades)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    No hay actividades registradas en esta categoría.<br>
                                    <a href="/admin/actividades/crear" class="btn btn-uneti btn-sm mt-2">Crear la primera actividad</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($actividades as $act): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted"><?= $act['id'] ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= e($act['titulo']) ?></div>
                                    </td>
                                    <td><span class="badge bg-uneti-blue-light text-uneti-blue border"><?= e($act['categoria']) ?></span></td>
                                    <td class="small"><?= e($act['instructor'] ?: 'Coordinación') ?></td>
                                    <td class="small text-muted">
                                        <div><i class="bi bi-geo-alt text-danger me-1"></i><?= e($act['ubicacion']) ?></div>
                                        <div><i class="bi bi-clock text-info me-1"></i><?= e($act['horario'] ?: 'Por definir') ?></div>
                                    </td>
                                    <td>
                                        <?php if ($act['esta_llena']): ?>
                                            <span class="badge badge-cupo-lleno">
                                                <i class="bi bi-exclamation-circle-fill me-1"></i> Lleno (<?= $act['cupo_maximo'] ?>)
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-cupo-disponible">
                                                <?= $act['cupos_ocupados'] ?> / <?= $act['cupo_maximo'] ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-success"><?= e($act['estado']) ?></span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="/admin/inscripciones?actividad_id=<?= $act['id'] ?>" class="btn btn-outline-info" title="Ver Inscritos y Asistencia">
                                                <i class="bi bi-people"></i>
                                            </a>
                                            <a href="/admin/actividades/editar/<?= $act['id'] ?>" class="btn btn-outline-primary" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="/admin/actividades/eliminar/<?= $act['id'] ?>" method="POST" class="d-inline form-eliminar">
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
