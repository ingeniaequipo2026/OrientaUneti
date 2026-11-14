<?php
$pageTitle = 'Emprendimientos Estudiantiles';
require_once __DIR__ . '/../layout/header.php';
$categoriaActiva = $_GET['categoria'] ?? 'Todas';
$categorias = ['Todas', 'Telecomunicaciones', 'Software', 'Servicios', 'Innovación'];
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <span class="badge bg-black text-white px-3 py-1 rounded-pill fw-bold mb-1">Innovación & Talento UNETI</span>
            <h2 class="fw-bold mb-0">Vitrina de Emprendimientos Estudiantiles</h2>
        </div>
        <a href="/contacto" class="btn btn-uneti btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Postular mi Emprendimiento
        </a>
    </div>

    <!-- Filtro por Área -->
    <div class="card card-uneti border-0 shadow-sm p-3 mb-4">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="fw-semibold small text-muted me-2"><i class="bi bi-funnel me-1"></i> Área:</span>
            <?php foreach ($categorias as $cat): ?>
                <?php $esActivo = ($categoriaActiva === $cat || ($cat === 'Todas' && empty($_GET['categoria']))); ?>
                <a href="/emprendimientos?categoria=<?= urlencode($cat) ?>" 
                   class="btn btn-sm <?= $esActivo ? 'btn-uneti' : 'btn-light' ?> rounded-pill px-3">
                    <?= e($cat) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Tarjetas de Emprendimientos -->
    <div class="row g-4">
        <?php if (empty($emprendimientos)): ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-lightbulb-off fs-1 text-muted d-block mb-3"></i>
                <h5 class="fw-bold text-muted">No se encontraron emprendimientos en esta área</h5>
                <p class="small text-muted">¿Tienes una idea innovadora? ¡Contáctanos y postúlala!</p>
            </div>
        <?php else: ?>
            <?php foreach ($emprendimientos as $emp): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-uneti h-100 shadow-sm border-top border-4 border-black">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-light text-dark border fw-bold">
                                    <?= e($emp['categoria']) ?>
                                </span>
                                <span class="badge bg-black text-white">
                                    <?= e($emp['estado']) ?>
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-2"><?= e($emp['nombre_proyecto']) ?></h5>
                            <p class="text-muted small flex-grow-1">
                                <?= nl2br(e($emp['descripcion'])) ?>
                            </p>

                            <div class="bg-light p-3 rounded-3 small text-muted mb-3">
                                <div class="mb-1">
                                    <i class="bi bi-person-fill text-primary me-1"></i>
                                    <strong>Responsable:</strong> <?= e($emp['responsable']) ?>
                                </div>
                                <div class="mb-1">
                                    <i class="bi bi-chat-text-fill text-success me-1"></i>
                                    <strong>Contacto:</strong> <?= e($emp['contacto']) ?>
                                </div>
                            </div>

                            <?php if (!empty($emp['enlace_redes'])): ?>
                                <a href="<?= e($emp['enlace_redes']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Conocer más del Proyecto
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
