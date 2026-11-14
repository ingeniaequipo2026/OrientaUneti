<?php
$pageTitle = 'Salud Mental y Apoyo Psicológico';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container py-4">
    <!-- Encabezado con Franja Informativa -->
    <div class="p-4 p-md-5 mb-4 rounded-4 text-white shadow-sm bg-uneti-health-gradient">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-white text-uneti-health px-3 py-1 rounded-pill fw-bold mb-2">Bienestar Emocional</span>
                <h2 class="fw-bold mb-2">Directorio de Salud Mental y Apoyo Psicológico</h2>
                <p class="text-white-50 mb-0">
                    Tu bienestar psicológico es fundamental para tu éxito académico. La UNETI pone a tu disposición canales oficiales, orientación individual y líneas gratuitas de contención y emergencia emocional 24/7.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="bg-white p-3 rounded-3 text-dark text-center shadow-sm">
                    <small class="text-uneti-health fw-bold d-block"><i class="bi bi-telephone-inbound-fill me-1"></i> Línea de Emergencia Emocional</small>
                    <span class="fs-4 fw-bold text-dark">0800-SALUD (72583)</span>
                    <small class="text-muted d-block">Atención Gratuita Nacional 24 Horas</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Nota de Confidencialidad y Ética -->
    <div class="alert border-0 shadow-sm d-flex align-items-center mb-4 bg-uneti-blue-light">
        <i class="bi bi-shield-lock-fill fs-3 text-uneti-blue me-3"></i>
        <div class="small">
            <strong>Garantía de Confidencialidad y Privacidad:</strong> Este portal solo difunde canales institucionales de apoyo. OrientaUneti <em>no almacena historiales médicos ni registros clínicos personales</em>, respetando estrictamente el secreto profesional y tu privacidad.
        </div>
    </div>

    <!-- Centros de Salud Registrados -->
    <div class="row g-4">
        <?php if (empty($centros)): ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">No hay centros registrados en este momento.</p>
            </div>
        <?php else: ?>
            <?php foreach ($centros as $c): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-uneti h-100 shadow-sm border-top border-4 border-uneti-health">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-uneti-blue-light text-uneti-blue fw-bold">
                                    <?= e($c['tipo_servicio']) ?>
                                </span>
                                <span class="badge bg-light text-secondary border">
                                    <?= e($c['modalidad']) ?>
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-2"><?= e($c['nombre_institucion']) ?></h5>
                            <p class="text-muted small flex-grow-1">
                                <?= nl2br(e($c['descripcion'])) ?>
                            </p>

                            <div class="bg-light p-3 rounded-3 small text-muted mb-3">
                                <div class="mb-1">
                                    <i class="bi bi-geo-alt-fill text-uneti-health me-1"></i>
                                    <strong>Ubicación:</strong> <?= e($c['direccion']) ?>
                                </div>
                                <?php if (!empty($c['telefono'])): ?>
                                    <div class="mb-1">
                                        <i class="bi bi-telephone-fill text-uneti-green me-1"></i>
                                        <strong>Teléfono:</strong> <a href="tel:<?= e($c['telefono']) ?>" class="text-dark fw-semibold"><?= e($c['telefono']) ?></a>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($c['correo'])): ?>
                                    <div class="mb-1">
                                        <i class="bi bi-envelope-fill text-uneti-blue me-1"></i>
                                        <strong>Correo:</strong> <?= e($c['correo']) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($c['horario_atencion'])): ?>
                                    <div>
                                        <i class="bi bi-clock-fill text-uneti-amber me-1"></i>
                                        <strong>Horario:</strong> <?= e($c['horario_atencion']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($c['telefono'])): ?>
                                <a href="tel:<?= e($c['telefono']) ?>" class="btn btn-uneti-health w-100 btn-sm">
                                    <i class="bi bi-telephone-outbound me-1"></i> Contactar Servicio
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
