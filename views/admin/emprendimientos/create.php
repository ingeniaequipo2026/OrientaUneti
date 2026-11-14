<?php
$pageTitle = 'Registrar Emprendimiento';
require_once __DIR__ . '/../../layout/header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-uneti border-0 shadow-sm">
                <div class="card-header bg-gradient-uneti text-white p-4 rounded-top-4">
                    <h4 class="fw-bold mb-0"><i class="bi bi-lightbulb me-2"></i>Registrar Proyecto de Emprendimiento</h4>
                    <small class="text-white-50">Catálogo de innovación tecnológica e iniciativas estudiantiles UNETI</small>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="/admin/emprendimientos/crear" method="POST">
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <div class="col-md-7">
                                <label for="nombre_proyecto" class="form-label fw-semibold">Nombre del Proyecto / Emprendimiento *</label>
                                <input type="text" class="form-control" id="nombre_proyecto" name="nombre_proyecto" placeholder="Ej: RedUneti LoRa" required>
                            </div>

                            <div class="col-md-5">
                                <label for="categoria" class="form-label fw-semibold">Categoría *</label>
                                <select class="form-select" id="categoria" name="categoria" required>
                                    <option value="Telecomunicaciones">Telecomunicaciones</option>
                                    <option value="Software">Software</option>
                                    <option value="Servicios">Servicios</option>
                                    <option value="Innovación">Innovación</option>
                                    <option value="Comercio Digital">Comercio Digital</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="responsable" class="form-label fw-semibold">Estudiante o Equipo Responsable *</label>
                                <input type="text" class="form-control" id="responsable" name="responsable" placeholder="Ej: Carlos Mendoza y Equipo Alfa" required>
                            </div>

                            <div class="col-md-6">
                                <label for="contacto" class="form-label fw-semibold">Contacto (Teléfono o Correo) *</label>
                                <input type="text" class="form-control" id="contacto" name="contacto" placeholder="Ej: 0412-1234567 / info@red.com" required>
                            </div>

                            <div class="col-12">
                                <label for="descripcion" class="form-label fw-semibold">Descripción del Proyecto *</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Explique la propuesta de valor y tecnologías empleadas..." required></textarea>
                            </div>

                            <div class="col-md-7">
                                <label for="enlace_redes" class="form-label fw-semibold">Enlace Web o Red Social</label>
                                <input type="url" class="form-control" id="enlace_redes" name="enlace_redes" placeholder="https://instagram.com/mi_proyecto">
                            </div>

                            <div class="col-md-5">
                                <label for="estado" class="form-label fw-semibold">Fase / Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="Idea">Idea / Prototipo</option>
                                    <option value="En Incubación" selected>En Incubación</option>
                                    <option value="Activo">Activo en Mercado</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="/admin/emprendimientos" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-uneti px-4">
                                <i class="bi bi-save me-1"></i> Guardar Emprendimiento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
