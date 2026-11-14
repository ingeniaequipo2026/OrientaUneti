<?php
$pageTitle = 'Registro de Estudiante';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container py-5 my-auto">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card card-uneti border-0 shadow-lg">
                <div class="card-header bg-gradient-uneti text-white text-center py-4 rounded-top-4">
                    <span class="p-2 bg-white rounded-3 text-uneti-green d-inline-block mb-2 shadow-sm">
                        <i class="bi bi-person-plus-fill fs-3"></i>
                    </span>
                    <h4 class="fw-bold mb-1">Registro de Estudiante UNETI</h4>
                    <p class="small text-white-50 mb-0">Crea tu cuenta para acceder a la oferta de bienestar y certificaciones</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="/registro" method="POST">
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="cedula" class="form-label fw-semibold">Cédula de Identidad *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-card-text"></i></span>
                                    <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Ej: V-28123456" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Correo Electrónico *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="estudiante@uneti.edu.ve" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="nombre" class="form-label fw-semibold">Nombres *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Carlos Eduardo" required>
                            </div>

                            <div class="col-md-6">
                                <label for="apellido" class="form-label fw-semibold">Apellidos *</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ej: Mendoza Rivas" required>
                            </div>

                            <div class="col-md-6">
                                <label for="telefono" class="form-label fw-semibold">Teléfono de Contacto</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ej: 0412-1234567">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="trayectoria" class="form-label fw-semibold">Carrera / PNF y Trayecto</label>
                                <select class="form-select" id="trayectoria" name="trayectoria" required>
                                    <option value="">Seleccione su programa...</option>
                                    <option value="PNF Informática - Trayecto Inicial">PNF Informática - Trayecto Inicial</option>
                                    <option value="PNF Informática - Trayecto I">PNF Informática - Trayecto I</option>
                                    <option value="PNF Informática - Trayecto II">PNF Informática - Trayecto II</option>
                                    <option value="PNF Informática - Trayecto III">PNF Informática - Trayecto III</option>
                                    <option value="PNF Informática - Trayecto IV">PNF Informática - Trayecto IV</option>
                                    <option value="PNF Telecomunicaciones - Trayecto Inicial">PNF Telecomunicaciones - Trayecto Inicial</option>
                                    <option value="PNF Telecomunicaciones - Trayecto I">PNF Telecomunicaciones - Trayecto I</option>
                                    <option value="PNF Telecomunicaciones - Trayecto II">PNF Telecomunicaciones - Trayecto II</option>
                                    <option value="PNF Telecomunicaciones - Trayecto III">PNF Telecomunicaciones - Trayecto III</option>
                                    <option value="PNF Telecomunicaciones - Trayecto IV">PNF Telecomunicaciones - Trayecto IV</option>
                                    <option value="Postgrado / Diplomado TIC">Postgrado / Diplomado TIC</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Contraseña *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Mínimo 6 caracteres" required minlength="6">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirm" class="form-label fw-semibold">Confirmar Contraseña *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-shield-lock"></i></span>
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Repita la contraseña" required minlength="6">
                                </div>
                            </div>
                        </div>

                        <div class="my-4 p-3 bg-light rounded-3 small text-muted border">
                            <i class="bi bi-shield-check text-success me-1"></i>
                            <strong>Protección de Datos:</strong> Sus datos académicos y personales son resguardados bajo estricta confidencialidad para fines exclusivos de gestión de bienestar estudiantil y emisión de constancias universitarias.
                        </div>

                        <button type="submit" class="btn btn-uneti w-100 py-2 fw-bold mb-3 shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Completar Registro e Ingresar
                        </button>
                    </form>

                    <div class="text-center small text-muted">
                        ¿Ya dispones de una cuenta activa? <br>
                        <a href="/login" class="text-uneti-blue fw-bold text-decoration-none">
                            Iniciar sesión aquí <i class="bi bi-box-arrow-in-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
