<?php
$pageTitle = 'Editar Centro de Salud Mental';
require_once __DIR__ . '/../../layout/header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-uneti border-0 shadow-sm">
                <div class="card-header bg-gradient-uneti text-white p-4 rounded-top-4">
                    <h4 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Centro de Salud Mental</h4>
                    <small class="text-white-50">Actualizar datos de contacto y horarios de atención</small>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="/admin/salud-mental/editar/<?= $centro['id'] ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <div class="col-md-7">
                                <label for="nombre_institucion" class="form-label fw-semibold">Nombre de la Institución / Servicio *</label>
                                <input type="text" class="form-control" id="nombre_institucion" name="nombre_institucion" value="<?= e($centro['nombre_institucion']) ?>" required>
                            </div>

                            <div class="col-md-5">
                                <label for="tipo_servicio" class="form-label fw-semibold">Tipo de Servicio *</label>
                                <input type="text" class="form-control" id="tipo_servicio" name="tipo_servicio" value="<?= e($centro['tipo_servicio']) ?>" required>
                            </div>

                            <div class="col-12">
                                <label for="direccion" class="form-label fw-semibold">Dirección / Ubicación *</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" value="<?= e($centro['direccion']) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="telefono" class="form-label fw-semibold">Teléfono de Contacto</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" value="<?= e($centro['telefono']) ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="correo" class="form-label fw-semibold">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correo" name="correo" value="<?= e($centro['correo']) ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="horario_atencion" class="form-label fw-semibold">Horario de Atención</label>
                                <input type="text" class="form-control" id="horario_atencion" name="horario_atencion" value="<?= e($centro['horario_atencion']) ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="modalidad" class="form-label fw-semibold">Modalidad</label>
                                <select class="form-select" id="modalidad" name="modalidad">
                                    <?php foreach (['Presencial y Virtual', 'Presencial', 'Virtual', 'Telefónica'] as $mod): ?>
                                        <option value="<?= $mod ?>" <?= $centro['modalidad'] === $mod ? 'selected' : '' ?>><?= $mod ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="descripcion" class="form-label fw-semibold">Descripción del Servicio</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= e($centro['descripcion']) ?></textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" <?= $centro['activo'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="activo">Centro Activo y Visible para Estudiantes</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="/admin/salud-mental" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-uneti px-4">
                                <i class="bi bi-save me-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
