<?php
$pageTitle = 'Atención al Usuario';
require_once __DIR__ . '/../layout/header.php';
$user = auth_user();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-uneti border-0 shadow-lg">
                <div class="card-header bg-gradient-uneti text-white p-4 rounded-top-4">
                    <span class="p-2 bg-white rounded-3 text-uneti-blue d-inline-block mb-2 shadow-sm">
                        <i class="bi bi-chat-quote-fill fs-3"></i>
                    </span>
                    <h3 class="fw-bold mb-1">Módulo de Atención al Usuario</h3>
                    <p class="text-white-50 mb-0">Canal oficial para formular propuestas, reclamos, inquietudes o solicitudes</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="/contacto" method="POST">
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label fw-semibold">Nombre Completo *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?= e($user ? $user['nombre'] . ' ' . $user['apellido'] : '') ?>" 
                                       placeholder="Ej: Ana Contreras" required>
                            </div>

                            <div class="col-md-6">
                                <label for="correo" class="form-label fw-semibold">Correo Electrónico de Contacto *</label>
                                <input type="email" class="form-control" id="correo" name="correo" 
                                       value="<?= e($user['email'] ?? '') ?>" 
                                       placeholder="ana@uneti.edu.ve" required>
                            </div>

                            <div class="col-md-6">
                                <label for="cedula" class="form-label fw-semibold">Cédula de Identidad</label>
                                <input type="text" class="form-control" id="cedula" name="cedula" 
                                       value="<?= e($user['cedula'] ?? '') ?>" 
                                       placeholder="Ej: V-27123456">
                            </div>

                            <div class="col-md-6">
                                <label for="tipo" class="form-label fw-semibold">Tipo de Solicitud *</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="Propuesta">💡 Propuesta / Nueva Actividad</option>
                                    <option value="Reclamo">⚠️ Reclamo / Inconformidad</option>
                                    <option value="Consulta" selected>❓ Consulta / Información General</option>
                                    <option value="Sugerencia">✨ Sugerencia de Mejora</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="asunto" class="form-label fw-semibold">Asunto *</label>
                                <input type="text" class="form-control" id="asunto" name="asunto" placeholder="Resumen breve de su solicitud" required>
                            </div>

                            <div class="col-12">
                                <label for="mensaje" class="form-label fw-semibold">Detalle del Mensaje *</label>
                                <textarea class="form-control" id="mensaje" name="mensaje" rows="5" placeholder="Escriba aquí los detalles de su solicitud, propuesta o reclamo..." required></textarea>
                            </div>
                        </div>

                        <div class="my-4 p-3 bg-light rounded-3 small text-muted border">
                            <i class="bi bi-envelope-check text-primary me-1"></i>
                            <strong>Notificación al Sistema:</strong> Su solicitud será enviada por correo institucional al equipo de Bienestar Estudiantil y registrada con número de folio para su seguimiento.
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="/" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-uneti px-4">
                                <i class="bi bi-send-fill me-1"></i> Enviar Mensaje
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
