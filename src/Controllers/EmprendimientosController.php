<?php
/**
 * Controlador de Emprendimientos Estudiantiles
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Emprendimiento.php';

class EmprendimientosController {
    private Emprendimiento $empModel;

    public function __construct() {
        $this->empModel = new Emprendimiento();
    }

    public function vitrina(): void {
        $categoria = $_GET['categoria'] ?? null;
        $emprendimientos = $this->empModel->getAll($categoria);
        require_once __DIR__ . '/../../views/student/emprendimientos.php';
    }

    public function adminIndex(): void {
        $categoria = $_GET['categoria'] ?? null;
        $emprendimientos = $this->empModel->getAll($categoria);
        require_once __DIR__ . '/../../views/admin/emprendimientos/index.php';
    }

    public function adminCreate(): void {
        require_once __DIR__ . '/../../views/admin/emprendimientos/create.php';
    }

    public function adminStore(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/emprendimientos');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Token CSRF inválido.');
            redirect('/admin/emprendimientos/crear');
        }

        $nombre = trim($_POST['nombre_proyecto'] ?? '');
        $categoria = trim($_POST['categoria'] ?? '');
        $responsable = trim($_POST['responsable'] ?? '');
        $contacto = trim($_POST['contacto'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $enlace = trim($_POST['enlace_redes'] ?? '');
        $estado = $_POST['estado'] ?? 'En Incubación';

        if (empty($nombre) || empty($categoria) || empty($responsable) || empty($contacto) || empty($descripcion)) {
            set_flash('danger', 'Por favor complete todos los datos requeridos del emprendimiento.');
            redirect('/admin/emprendimientos/crear');
        }

        $id = $this->empModel->create([
            'nombre_proyecto' => $nombre,
            'categoria'       => $categoria,
            'responsable'     => $responsable,
            'contacto'        => $contacto,
            'descripcion'     => $descripcion,
            'enlace_redes'    => $enlace,
            'estado'          => $estado
        ]);

        if ($id > 0) {
            set_flash('success', 'Emprendimiento registrado en el catálogo universitario.');
            redirect('/admin/emprendimientos');
        } else {
            set_flash('danger', 'Error al registrar el emprendimiento.');
            redirect('/admin/emprendimientos/crear');
        }
    }

    public function adminEdit(string $id): void {
        $emp = $this->empModel->findById((int)$id);
        if (!$emp) {
            set_flash('danger', 'Emprendimiento no encontrado.');
            redirect('/admin/emprendimientos');
        }
        require_once __DIR__ . '/../../views/admin/emprendimientos/edit.php';
    }

    public function adminUpdate(string $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/emprendimientos');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Token CSRF inválido.');
            redirect('/admin/emprendimientos/editar/' . $id);
        }

        $exito = $this->empModel->update((int)$id, [
            'nombre_proyecto' => trim($_POST['nombre_proyecto'] ?? ''),
            'categoria'       => trim($_POST['categoria'] ?? ''),
            'responsable'     => trim($_POST['responsable'] ?? ''),
            'contacto'        => trim($_POST['contacto'] ?? ''),
            'descripcion'     => trim($_POST['descripcion'] ?? ''),
            'enlace_redes'    => trim($_POST['enlace_redes'] ?? ''),
            'estado'          => $_POST['estado'] ?? 'En Incubación'
        ]);

        if ($exito) {
            set_flash('success', 'Emprendimiento actualizado correctamente.');
            redirect('/admin/emprendimientos');
        } else {
            set_flash('danger', 'Error al actualizar el emprendimiento.');
            redirect('/admin/emprendimientos/editar/' . $id);
        }
    }

    public function adminDelete(string $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/emprendimientos');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Token CSRF inválido.');
            redirect('/admin/emprendimientos');
        }

        $this->empModel->delete((int)$id);
        set_flash('info', 'Emprendimiento eliminado del catálogo.');
        redirect('/admin/emprendimientos');
    }
}
