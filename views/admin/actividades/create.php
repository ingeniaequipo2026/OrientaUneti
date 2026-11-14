<?php
$pageTitle = 'Crear Actividad';
require_once __DIR__ . '/../../layout/header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-uneti border-0 shadow-sm">
                <div class="card-header bg-gradient-uneti text-white p-4 rounded-top-4">
                    <h4 class="fw-bold mb-0"><i class="bi bi-calendar-plus me-2"></i>Registrar Nueva Actividad</h4>
                    <small class="text-white-50">Oferta deportiva, cultural, talleres o recreación para la comunidad UNETI</small>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="/admin/actividades/crear" method="POST">
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="titulo" class="form-label fw-semibold">Título de la Actividad *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ej: Torneo Inter-Trayectos de Ajedrez" required>
                            </div>

                            <div class="col-md-4">
                                <label for="categoria" class="form-label fw-semibold">Categoría / Área *</label>
                                <select class="form-select" id="categoria" name="categoria" required>
                                    <option value="Deporte">Deporte</option>
                                    <option value="Cultura">Cultura</option>
                                    <option value="Talleres">Talleres</option>
                                    <option value="Salud">Salud</option>
                                    <option value="Recreación">Recreación</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="descripcion" class="form-label fw-semibold">Descripción Detallada *</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Explique objetivos, dinámica y requisitos de la actividad..." required></textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="instructor" class="form-label fw-semibold">Instructor / Facilitador</label>
                                <input type="text" class="form-control" id="instructor" name="instructor" placeholder="Ej: Prof. Ramón Gil">
                            </div>

                            <div class="col-md-6">
                                <label for="ubicacion" class="form-label fw-semibold">Ubicación / Espacio Físico o Virtual *</label>
                                <input type="text" class="form-control" id="ubicacion" name="ubicacion" placeholder="Ej: Cancha Múltiple UNETI / Aula Virtual 3" required>
                            </div>

                            <div class="col-md-4">
                                <label for="cupo_maximo" class="form-label fw-semibold">Cupo Máximo *</label>
                                <input type="number" class="form-control" id="cupo_maximo" name="cupo_maximo" value="20" min="1" max="500" required>
                                <div class="form-text small">El sistema bloqueará inscripciones al alcanzar este límite.</div>
                            </div>

                            <div class="col-md-8">
                                <label for="horario" class="form-label fw-semibold">Horario / Días</label>
                                <input type="text" class="form-control" id="horario" name="horario" placeholder="Ej: Jueves y Viernes 2:00 PM - 5:00 PM">
                            </div>

                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label fw-semibold">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                            </div>

                            <div class="col-md-6">
                                <label for="fecha_fin" class="form-label fw-semibold">Fecha de Culminación</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                            </div>

                            <div class="col-md-6">
                                <label for="estado" class="form-label fw-semibold">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="Activa" selected>Activa (Abierta para inscripciones)</option>
                                    <option value="En Curso">En Curso</option>
                                    <option value="Finalizada">Finalizada</option>
                                    <option value="Cancelada">Cancelada</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="/admin/actividades" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-uneti px-4">
                                <i class="bi bi-save me-1"></i> Guardar Actividad
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
