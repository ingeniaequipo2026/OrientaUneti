<?php
$pageTitle = 'Atención al Usuario - Solicitudes';
require_once __DIR__ . '/../../layout/header.php';
$filtroEstado = $_GET['estado'] ?? '';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-1">Módulo de Atención</span>
            <h2 class="fw-bold mb-0">Bandeja de Propuestas, Reclamos y Consultas</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/contactos" class="btn btn-sm <?= empty($filtroEstado) ? 'btn-uneti' : 'btn-light' ?>">Todas</a>
            <a href="/admin/contactos?estado=Pendiente" class="btn btn-sm <?= $filtroEstado === 'Pendiente' ? 'btn-warning text-dark' : 'btn-light' ?>">Pendientes</a>
            <a href="/admin/contactos?estado=Atendido" class="btn btn-sm <?= $filtroEstado === 'Atendido' ? 'btn-success' : 'btn-light' ?>">Atendidas</a>
        </div>
    </div>

    <!-- Lista de Mensajes -->
    <div class="card card-uneti shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Remitente</th>
                            <th>Tipo</th>
                            <th>Asunto</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th class="pe-4 text-end">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($mensajes)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No hay solicitudes registradas con este filtro.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($mensajes as $m): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold"><?= e($m['nombre']) ?></div>
                                        <small class="text-muted"><?= e($m['correo']) ?> | <?= e($m['cedula'] ?: 'S/C') ?></small>
                                    </td>
                                    <td>
                                        <?php
                                            $badgeTipo = match($m['tipo']) {
                                                'Propuesta' => 'bg-info text-dark',
                                                'Reclamo'   => 'bg-danger',
                                                'Sugerencia'=> 'bg-warning text-dark',
                                                default     => 'bg-secondary'
                                            };
                                        ?>
                                        <span class="badge <?= $badgeTipo ?>"><?= e($m['tipo']) ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark"><?= e($m['asunto']) ?></div>
                                        <small class="text-muted d-block text-truncate" style="max-width: 320px;">
                                            <?= e($m['mensaje']) ?>
                                        </small>
                                    </td>
                                    <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>
                                    <td>
                                        <span class="badge <?= $m['estado'] === 'Pendiente' ? 'bg-warning text-dark' : 'bg-success' ?>">
                                            <?= e($m['estado']) ?>
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalMensaje<?= $m['id'] ?>">
                                            <i class="bi bi-eye me-1"></i> Ver y Atender
                                        </button>

                                        <!-- Modal de Detalle -->
                                        <div class="modal fade" id="modalMensaje<?= $m['id'] ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered text-start">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-gradient-uneti text-white">
                                                        <h5 class="modal-title fw-bold">Solicitud #<?= $m['id'] ?> - <?= e($m['tipo']) ?></h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Remitente:</strong> <?= e($m['nombre']) ?> (<?= e($m['correo']) ?>)</p>
                                                        <p><strong>Cédula:</strong> <?= e($m['cedula'] ?: 'No indicada') ?></p>
                                                        <p><strong>Asunto:</strong> <?= e($m['asunto']) ?></p>
                                                        <div class="p-3 bg-light rounded-3 mb-3">
                                                            <strong>Mensaje:</strong><br>
                                                            <?= nl2br(e($m['mensaje'])) ?>
                                                        </div>

                                                        <form action="/admin/contactos/actualizar" method="POST">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="id" value="<?= $m['id'] ?>">

                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Cambiar Estado:</label>
                                                                <select name="estado" class="form-select">
                                                                    <option value="Pendiente" <?= $m['estado'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                                    <option value="En Revisión" <?= $m['estado'] === 'En Revisión' ? 'selected' : '' ?>>En Revisión</option>
                                                                    <option value="Atendido" <?= $m['estado'] === 'Atendido' ? 'selected' : '' ?>>Atendido / Resuelto</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Nota o Respuesta Interna:</label>
                                                                <textarea name="respuesta" class="form-control" rows="2" placeholder="Acciones tomadas..."><?= e($m['respuesta'] ?? '') ?></textarea>
                                                            </div>

                                                            <div class="d-flex justify-content-end gap-2">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                                <button type="submit" class="btn btn-uneti">Actualizar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
