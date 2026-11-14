<?php
$pageTitle = 'Editar Actividad';
require_once __DIR__ . '/../../layout/header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-uneti border-0 shadow-sm">
                <div class="card-header bg-gradient-uneti text-white p-4 rounded-top-4">
                    <h4 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Actividad</h4>
                    <small class="text-white-50">Modificar parámetros, fechas y cupo de la actividad</small>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="/admin/actividades/editar/<?= $actividad['id'] ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="titulo" class="form-label fw-semibold">Título de la Actividad *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" value="<?= e($actividad['titulo']) ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label for="categoria" class="form-label fw-semibold">Categoría / Área *</label>
                                <select class="form-select" id="categoria" name="categoria" required>
                                    <?php foreach (['Deporte', 'Cultura', 'Talleres', 'Salud', 'Recreación'] as $cat): ?>
                                        <option value="<?= $cat ?>" <?= $actividad['categoria'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="descripcion" class="form-label fw-semibold">Descripción Detallada *</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?= e($actividad['descripcion']) ?></textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="instructor" class="form-label fw-semibold">Instructor / Facilitador</label>
                                <input type="text" class="form-control" id="instructor" name="instructor" value="<?= e($actividad['instructor']) ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="ubicacion" class="form-label fw-semibold">Ubicación / Espacio *</label>
                                <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="<?= e($actividad['ubicacion']) ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label for="cupo_maximo" class="form-label fw-semibold">Cupo Máximo *</label>
                                <input type="number" class="form-control" id="cupo_maximo" name="cupo_maximo" value="<?= $actividad['cupo_maximo'] ?>" min="1" max="500" required>
                                <div class="form-text small">Inscritos actuales: <strong><?= $actividad['cupos_ocupados'] ?></strong></div>
                            </div>

                            <div class="col-md-8">
                                <label for="horario" class="form-label fw-semibold">Horario / Días</label>
                                <input type="text" class="form-control" id="horario" name="horario" value="<?= e($actividad['horario']) ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label fw-semibold">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= e($actividad['fecha_inicio']) ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="fecha_fin" class="form-label fw-semibold">Fecha de Culminación</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= e($actividad['fecha_fin']) ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="estado" class="form-label fw-semibold">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="Activa" <?= $actividad['estado'] === 'Activa' ? 'selected' : '' ?>>Activa</option>
                                    <option value="En Curso" <?= $actividad['estado'] === 'En Curso' ? 'selected' : '' ?>>En Curso</option>
                                    <option value="Finalizada" <?= $actividad['estado'] === 'Finalizada' ? 'selected' : '' ?>>Finalizada</option>
                                    <option value="Cancelada" <?= $actividad['estado'] === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="/admin/actividades" class="btn btn-outline-secondary">Cancelar</a>
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
