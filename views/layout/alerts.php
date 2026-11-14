<?php
$flash = get_flash();
if ($flash):
    $alertClass = match($flash['type']) {
        'success' => 'alert-success',
        'danger'  => 'alert-danger',
        'warning' => 'alert-warning',
        'info'    => 'alert-info',
        default   => 'alert-primary'
    };
    $iconClass = match($flash['type']) {
        'success' => 'bi-check-circle-fill',
        'danger'  => 'bi-exclamation-triangle-fill',
        'warning' => 'bi-exclamation-circle-fill',
        'info'    => 'bi-info-circle-fill',
        default   => 'bi-bell-fill'
    };
?>
<div class="container mt-3">
    <div class="alert <?= $alertClass ?> alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
        <i class="bi <?= $iconClass ?> me-2 fs-5"></i>
        <div class="flex-grow-1">
            <?= e($flash['message']) ?>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
</div>
<?php endif; ?>
