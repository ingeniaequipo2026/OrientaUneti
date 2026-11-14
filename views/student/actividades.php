<?php
$pageTitle = 'Catálogo de Actividades';
require_once __DIR__ . '/../layout/header.php';
$categoriaActiva = $_GET['categoria'] ?? 'Todas';
$categorias = ['Todas', 'Deporte', 'Cultura', 'Talleres', 'Salud', 'Recreación'];
?>

<div class="container py-4">
    <!-- Encabezado de Página -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <span class="badge bg-uneti-green-light text-uneti-green px-3 py-1 rounded-pill fw-bold mb-1">Oferta Institucional</span>
            <h2 class="fw-bold mb-0">Actividades Extracurriculares y Bienestar</h2>
        </div>
        <?php if (is_student()): ?>
            <a href="/estudiante/mis-actividades" class="btn btn-uneti-outline btn-sm">
                <i class="bi bi-card-checklist me-1"></i> Ver Mis Actividades Inscritas
            </a>
        <?php endif; ?>
    </div>

    <!-- Filtro por Categorías -->
    <div class="card card-uneti border-0 shadow-sm p-3 mb-4">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="fw-semibold small text-muted me-2"><i class="bi bi-funnel me-1"></i> Filtrar por Área:</span>
            <?php foreach ($categorias as $cat): ?>
                <?php $esActivo = ($categoriaActiva === $cat || ($cat === 'Todas' && empty($_GET['categoria']))); ?>
                <a href="/actividades?categoria=<?= urlencode($cat) ?>" 
                   class="btn btn-sm <?= $esActivo ? 'btn-uneti' : 'btn-light' ?> rounded-pill px-3">
                    <?= e($cat) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Listado de Tarjetas de Actividades -->
    <div class="row g-4">
        <?php if (empty($actividades)): ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-calendar-x fs-1 text-muted d-block mb-3"></i>
                <h4 class="fw-bold text-muted">No se encontraron actividades disponibles en esta categoría</h4>
                <p class="text-muted small">Pronto se abrirán nuevas convocatorias institucionales.</p>
                <a href="/actividades" class="btn btn-uneti btn-sm mt-2">Ver Todas las Actividades</a>
            </div>
        <?php else: ?>
            <?php foreach ($actividades as $act): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-uneti h-100 d-flex flex-column shadow-sm">
                        <!-- Cabecera de la Tarjeta -->
                        <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                            <span class="badge bg-uneti-blue-light text-uneti-blue fw-bold">
                                <?= e($act['categoria']) ?>
                            </span>

                            <!-- Estado del Cupo -->
                            <?php if ($act['esta_llena']): ?>
                                <span class="badge badge-cupo-lleno" title="Cupo lleno. No hay plazas disponibles.">
                                    <i class="bi bi-x-circle-fill me-1"></i> Cupo lleno
                                </span>
                            <?php else: ?>
                                <span class="badge badge-cupo-disponible" title="Cupos restantes para inscripción">
                                    <i class="bi bi-people-fill me-1"></i> <?= $act['cupos_disponibles'] ?> / <?= $act['cupo_maximo'] ?> cupos
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Cuerpo de la Tarjeta -->
                        <div class="card-body d-flex flex-column">
                            <h5 class="fw-bold text-dark mb-2"><?= e($act['titulo']) ?></h5>
                            <p class="text-muted small flex-grow-1">
                                <?= nl2br(e($act['descripcion'])) ?>
                            </p>

                            <div class="bg-light p-3 rounded-3 small text-muted mb-3">
                                <div class="mb-1">
                                    <i class="bi bi-person-badge text-primary me-1"></i>
                                    <strong>Facilitador:</strong> <?= e($act['instructor'] ?: 'Coordinación UNETI') ?>
                                </div>
                                <div class="mb-1">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                    <strong>Lugar:</strong> <?= e($act['ubicacion']) ?>
                                </div>
                                <div class="mb-1">
                                    <i class="bi bi-clock-fill text-info me-1"></i>
                                    <strong>Horario:</strong> <?= e($act['horario'] ?: 'Por coordinar') ?>
                                </div>
                                <?php if (!empty($act['fecha_inicio'])): ?>
                                    <div>
                                        <i class="bi bi-calendar-check text-success me-1"></i>
                                        <strong>Fecha:</strong> <?= format_date($act['fecha_inicio']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Botón de Inscripción / Criterio de Aceptación -->
                            <div class="mt-auto">
                                <?php if (!is_logged_in()): ?>
                                    <a href="/login" class="btn btn-outline-primary w-100 btn-sm">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar sesión para inscribirse
                                    </a>
                                <?php elseif (is_admin()): ?>
                                    <a href="/admin/actividades/editar/<?= $act['id'] ?>" class="btn btn-outline-secondary w-100 btn-sm">
                                        <i class="bi bi-pencil me-1"></i> Editar (Admin)
                                    </a>
                                <?php elseif (!empty($act['usuario_inscrito'])): ?>
                                    <button class="btn btn-secondary w-100 btn-sm" disabled>
                                        <i class="bi bi-check-circle-fill text-success me-1"></i> Ya estás inscrito
                                    </button>
                                <?php elseif ($act['esta_llena']): ?>
                                    <button class="btn btn-danger w-100 btn-sm opacity-75" disabled>
                                        <i class="bi bi-lock-fill me-1"></i> Cupo lleno
                                    </button>
                                <?php else: ?>
                                    <form action="/estudiante/inscribir" method="POST">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="actividad_id" value="<?= $act['id'] ?>">
                                        <button type="submit" class="btn btn-uneti w-100 btn-sm shadow-sm">
                                            <i class="bi bi-pencil-square me-1"></i> Inscribirme Ahora
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
