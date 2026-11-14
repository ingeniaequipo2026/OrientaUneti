<?php
$pageTitle = 'Iniciar Sesión';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container py-5 my-auto">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-uneti border-0 shadow-lg">
                <div class="card-header bg-gradient-uneti text-white text-center py-4 rounded-top-4">
                    <span class="p-2 bg-white rounded-3 text-uneti-blue d-inline-block mb-2 shadow-sm">
                        <i class="bi bi-person-lock fs-3"></i>
                    </span>
                    <h4 class="fw-bold mb-1">Acceso a OrientaUneti</h4>
                    <p class="small text-white-50 mb-0">Sistema de Bienestar y Salud Mental UNETI</p>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form action="/login" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@uneti.edu.ve" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-uneti w-100 py-2 fw-bold mb-3 shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                        </button>
                    </form>

                    <div class="text-center small text-muted">
                        ¿Aún no tienes una cuenta estudiantil? <br>
                        <a href="/registro" class="text-uneti-blue fw-bold text-decoration-none">
                            Registrarse aquí <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Credenciales Demo para Pruebas del Evaluador -->
                    <div class="mt-4 p-3 bg-light rounded-3 border small">
                        <div class="fw-bold text-secondary mb-1">
                            <i class="bi bi-info-circle-fill text-primary me-1"></i> Credenciales de Prueba:
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span><strong>Admin:</strong> admin@uneti.edu.ve</span>
                            <code>Admin123*</code>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><strong>Estudiante:</strong> estudiante@uneti.edu.ve</span>
                            <code>Estudiante123*</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
