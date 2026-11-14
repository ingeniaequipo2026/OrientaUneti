<?php
/**
 * Controlador de Salud Mental y Apoyo Psicológico
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/SaludMental.php';

class SaludMentalController {
    private SaludMental $saludModel;

    public function __construct() {
        $this->saludModel = new SaludMental();
    }

    public function directorio(): void {
        $centros = $this->saludModel->getAll(true);
        require_once __DIR__ . '/../../views/student/salud_mental.php';
    }

    public function adminIndex(): void {
        $centros = $this->saludModel->getAll(false);
        require_once __DIR__ . '/../../views/admin/salud_mental/index.php';
    }

    public function adminCreate(): void {
        require_once __DIR__ . '/../../views/admin/salud_mental/create.php';
    }

    public function adminStore(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/salud-mental');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Token CSRF inválido.');
            redirect('/admin/salud-mental/crear');
        }

        $nombre    = trim($_POST['nombre_institucion'] ?? '');
        $tipo      = trim($_POST['tipo_servicio'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $telefono  = trim($_POST['telefono'] ?? '');
        $correo    = trim($_POST['correo'] ?? '');
        $horario   = trim($_POST['horario_atencion'] ?? '');
        $modalidad = $_POST['modalidad'] ?? 'Presencial y Virtual';
        $desc      = trim($_POST['descripcion'] ?? '');
        $activo    = isset($_POST['activo']) ? 1 : 0;

        if (empty($nombre) || empty($tipo) || empty($direccion)) {
            set_flash('danger', 'Complete el nombre de la institución, tipo de servicio y dirección.');
            redirect('/admin/salud-mental/crear');
        }

        $id = $this->saludModel->create([
            'nombre_institucion' => $nombre,
            'tipo_servicio'      => $tipo,
            'direccion'          => $direccion,
            'telefono'           => $telefono,
            'correo'             => $correo,
            'horario_atencion'   => $horario,
            'modalidad'          => $modalidad,
            'descripcion'        => $desc,
            'activo'             => $activo
        ]);

        if ($id > 0) {
            set_flash('success', 'Centro de apoyo psicológico registrado exitosamente.');
            redirect('/admin/salud-mental');
        } else {
            set_flash('danger', 'Error al registrar el centro.');
            redirect('/admin/salud-mental/crear');
        }
    }

    public function adminEdit(string $id): void {
        $centro = $this->saludModel->findById((int)$id);
        if (!$centro) {
            set_flash('danger', 'Centro no encontrado.');
            redirect('/admin/salud-mental');
        }
        require_once __DIR__ . '/../../views/admin/salud_mental/edit.php';
    }

    public function adminUpdate(string $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/salud-mental');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Token CSRF inválido.');
            redirect('/admin/salud-mental/editar/' . $id);
        }

        $exito = $this->saludModel->update((int)$id, [
            'nombre_institucion' => trim($_POST['nombre_institucion'] ?? ''),
            'tipo_servicio'      => trim($_POST['tipo_servicio'] ?? ''),
            'direccion'          => trim($_POST['direccion'] ?? ''),
            'telefono'           => trim($_POST['telefono'] ?? ''),
            'correo'             => trim($_POST['correo'] ?? ''),
            'horario_atencion'   => trim($_POST['horario_atencion'] ?? ''),
            'modalidad'          => $_POST['modalidad'] ?? 'Presencial y Virtual',
            'descripcion'        => trim($_POST['descripcion'] ?? ''),
            'activo'             => isset($_POST['activo']) ? 1 : 0
        ]);

        if ($exito) {
            set_flash('success', 'Datos del centro actualizados correctamente.');
            redirect('/admin/salud-mental');
        } else {
            set_flash('danger', 'Error al actualizar el centro.');
            redirect('/admin/salud-mental/editar/' . $id);
        }
    }

    public function adminDelete(string $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/salud-mental');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Token CSRF inválido.');
            redirect('/admin/salud-mental');
        }

        $this->saludModel->delete((int)$id);
        set_flash('info', 'Centro eliminado correctamente.');
        redirect('/admin/salud-mental');
    }
}
