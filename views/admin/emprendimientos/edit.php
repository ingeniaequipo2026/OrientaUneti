<?php
$pageTitle = 'Editar Emprendimiento';
require_once __DIR__ . '/../../layout/header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-uneti border-0 shadow-sm">
                <div class="card-header bg-gradient-uneti text-white p-4 rounded-top-4">
                    <h4 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Emprendimiento</h4>
                    <small class="text-white-50">Actualizar información del proyecto o estado de incubación</small>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="/admin/emprendimientos/editar/<?= $emp['id'] ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <div class="col-md-7">
                                <label for="nombre_proyecto" class="form-label fw-semibold">Nombre del Proyecto *</label>
                                <input type="text" class="form-control" id="nombre_proyecto" name="nombre_proyecto" value="<?= e($emp['nombre_proyecto']) ?>" required>
                            </div>

                            <div class="col-md-5">
                                <label for="categoria" class="form-label fw-semibold">Categoría *</label>
                                <select class="form-select" id="categoria" name="categoria" required>
                                    <?php foreach (['Telecomunicaciones', 'Software', 'Servicios', 'Innovación', 'Comercio Digital'] as $cat): ?>
                                        <option value="<?= $cat ?>" <?= $emp['categoria'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="responsable" class="form-label fw-semibold">Responsable *</label>
                                <input type="text" class="form-control" id="responsable" name="responsable" value="<?= e($emp['responsable']) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="contacto" class="form-label fw-semibold">Contacto *</label>
                                <input type="text" class="form-control" id="contacto" name="contacto" value="<?= e($emp['contacto']) ?>" required>
                            </div>

                            <div class="col-12">
                                <label for="descripcion" class="form-label fw-semibold">Descripción del Proyecto *</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?= e($emp['descripcion']) ?></textarea>
                            </div>

                            <div class="col-md-7">
                                <label for="enlace_redes" class="form-label fw-semibold">Enlace Web o Red Social</label>
                                <input type="url" class="form-control" id="enlace_redes" name="enlace_redes" value="<?= e($emp['enlace_redes']) ?>">
                            </div>

                            <div class="col-md-5">
                                <label for="estado" class="form-label fw-semibold">Fase / Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="Idea" <?= $emp['estado'] === 'Idea' ? 'selected' : '' ?>>Idea / Prototipo</option>
                                    <option value="En Incubación" <?= $emp['estado'] === 'En Incubación' ? 'selected' : '' ?>>En Incubación</option>
                                    <option value="Activo" <?= $emp['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="/admin/emprendimientos" class="btn btn-outline-secondary">Cancelar</a>
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
