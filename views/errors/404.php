<?php
$pageTitle = 'Página No Encontrada';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container py-5 text-center my-auto">
    <div class="py-5">
        <div class="display-1 fw-bold text-uneti-blue">404</div>
        <h2 class="fw-bold mb-3">Página No Encontrada</h2>
        <p class="text-muted mb-4 lead">
            La ruta o recurso al que intenta acceder no existe o fue movido en el sistema.
        </p>
        <div class="d-flex justify-content-center gap-2">
            <a href="/" class="btn btn-uneti">
                <i class="bi bi-house-door me-1"></i> Ir al Inicio
            </a>
            <a href="/actividades" class="btn btn-uneti-outline">
                <i class="bi bi-calendar-event me-1"></i> Ver Actividades
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
