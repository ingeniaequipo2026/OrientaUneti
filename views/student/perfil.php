<?php
$pageTitle = 'Mi Perfil';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-uneti border-0 shadow-sm">
                <div class="card-header bg-gradient-uneti text-white p-4 rounded-top-4">
                    <div class="d-flex align-items-center">
                        <span class="p-3 bg-white rounded-circle text-uneti-blue me-3 shadow-sm">
                            <i class="bi bi-person-circle fs-2"></i>
                        </span>
                        <div>
                            <h4 class="fw-bold mb-0">Mi Perfil Estudiantil</h4>
                            <p class="small text-white-50 mb-0">Actualiza tus datos de contacto y académicos</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="/estudiante/perfil" method="POST">
                        <?= csrf_field() ?>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">Cédula de Identidad (No editable)</label>
                                <input type="text" class="form-control bg-light" value="<?= e($user['cedula']) ?>" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">Correo Institucional (No editable)</label>
                                <input type="email" class="form-control bg-light" value="<?= e($user['email']) ?>" readonly>
                            </div>

                            <div class="col-md-6">
                                <label for="nombre" class="form-label fw-semibold">Nombres *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= e($user['nombre']) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="apellido" class="form-label fw-semibold">Apellidos *</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" value="<?= e($user['apellido']) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="telefono" class="form-label fw-semibold">Teléfono de Contacto</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" value="<?= e($user['telefono'] ?? '') ?>" placeholder="0412-1234567">
                            </div>

                            <div class="col-md-6">
                                <label for="trayectoria" class="form-label fw-semibold">Carrera / PNF y Trayecto</label>
                                <input type="text" class="form-control" id="trayectoria" name="trayectoria" value="<?= e($user['trayectoria'] ?? '') ?>" placeholder="Ej: PNF Informática - Trayecto II">
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-shield-lock text-primary me-2"></i>Cambiar Contraseña (Opcional)</h5>
                        <p class="small text-muted">Deje estos campos en blanco si no desea modificar su contraseña actual.</p>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Mínimo 6 caracteres" minlength="6">
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirm" class="form-label fw-semibold">Confirmar Nueva Contraseña</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Repita la nueva contraseña" minlength="6">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="/estudiante/dashboard" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-uneti">
                                <i class="bi bi-save me-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
