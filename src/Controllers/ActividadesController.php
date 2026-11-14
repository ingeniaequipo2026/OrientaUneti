<?php
/**
 * Controlador de Actividades de Bienestar (Deportes, Cultura, Talleres, Recreación)
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Actividad.php';
require_once __DIR__ . '/../Models/Inscripcion.php';

class ActividadesController {
    private Actividad $actividadModel;
    private Inscripcion $inscripcionModel;

    public function __construct() {
        $this->actividadModel = new Actividad();
        $this->inscripcionModel = new Inscripcion();
    }

    // ==========================================
    // VISTAS Y ACCIONES DEL ESTUDIANTE
    // ==========================================

    public function studentCatalogo(): void {
        $categoria = $_GET['categoria'] ?? null;
        $actividades = $this->actividadModel->getAll($categoria, 'Activa');
        
        $user = auth_user();
        $usuarioId = $user ? (int)$user['id'] : null;

        // Anexar estado de si el estudiante actual ya está inscrito en cada actividad
        foreach ($actividades as &$act) {
            $act['usuario_inscrito'] = $usuarioId ? $this->inscripcionModel->isUserInscrito($usuarioId, (int)$act['id']) : false;
        }

        require_once __DIR__ . '/../../views/student/actividades.php';
    }

    // ==========================================
    // VISTAS Y ACCIONES DEL ADMINISTRADOR (CRUD)
    // ==========================================

    public function adminIndex(): void {
        $categoria = $_GET['categoria'] ?? null;
        $actividades = $this->actividadModel->getAll($categoria);
        require_once __DIR__ . '/../../views/admin/actividades/index.php';
    }

    public function adminCreate(): void {
        require_once __DIR__ . '/../../views/admin/actividades/create.php';
    }

    public function adminStore(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/actividades');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Token CSRF inválido.');
            redirect('/admin/actividades/crear');
        }

        $titulo       = trim($_POST['titulo'] ?? '');
        $categoria    = trim($_POST['categoria'] ?? '');
        $descripcion  = trim($_POST['descripcion'] ?? '');
        $instructor   = trim($_POST['instructor'] ?? '');
        $ubicacion    = trim($_POST['ubicacion'] ?? '');
        $fecha_inicio = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
        $fecha_fin    = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
        $horario      = trim($_POST['horario'] ?? '');
        $cupo_maximo  = (int)($_POST['cupo_maximo'] ?? 20);
        $estado       = $_POST['estado'] ?? 'Activa';

        if (empty($titulo) || empty($categoria) || empty($descripcion) || empty($ubicacion) || $cupo_maximo <= 0) {
            set_flash('danger', 'Por favor complete los campos obligatorios y asigne un cupo válido.');
            redirect('/admin/actividades/crear');
        }

        $id = $this->actividadModel->create([
            'titulo'       => $titulo,
            'categoria'    => $categoria,
            'descripcion'  => $descripcion,
            'instructor'   => $instructor,
            'ubicacion'    => $ubicacion,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin'    => $fecha_fin,
            'horario'      => $horario,
            'cupo_maximo'  => $cupo_maximo,
            'estado'       => $estado,
        ]);

        if ($id > 0) {
            set_flash('success', 'Actividad "' . e($titulo) . '" creada exitosamente con cupo de ' . $cupo_maximo . ' participantes.');
            redirect('/admin/actividades');
        } else {
            set_flash('danger', 'Error al guardar la actividad en la base de datos.');
            redirect('/admin/actividades/crear');
        }
    }

    public function adminEdit(string $id): void {
        $actividad = $this->actividadModel->findById((int)$id);
        if (!$actividad) {
            set_flash('danger', 'Actividad no encontrada.');
            redirect('/admin/actividades');
        }
        require_once __DIR__ . '/../../views/admin/actividades/edit.php';
    }

    public function adminUpdate(string $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/actividades');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Token CSRF inválido.');
            redirect('/admin/actividades/editar/' . $id);
        }

        $titulo       = trim($_POST['titulo'] ?? '');
        $categoria    = trim($_POST['categoria'] ?? '');
        $descripcion  = trim($_POST['descripcion'] ?? '');
        $instructor   = trim($_POST['instructor'] ?? '');
        $ubicacion    = trim($_POST['ubicacion'] ?? '');
        $fecha_inicio = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
        $fecha_fin    = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
        $horario      = trim($_POST['horario'] ?? '');
        $cupo_maximo  = (int)($_POST['cupo_maximo'] ?? 20);
        $estado       = $_POST['estado'] ?? 'Activa';

        $exito = $this->actividadModel->update((int)$id, [
            'titulo'       => $titulo,
            'categoria'    => $categoria,
            'descripcion'  => $descripcion,
            'instructor'   => $instructor,
            'ubicacion'    => $ubicacion,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin'    => $fecha_fin,
            'horario'      => $horario,
            'cupo_maximo'  => $cupo_maximo,
            'estado'       => $estado,
        ]);

        if ($exito) {
            set_flash('success', 'Actividad actualizada correctamente.');
            redirect('/admin/actividades');
        } else {
            set_flash('danger', 'No se pudo actualizar la actividad.');
            redirect('/admin/actividades/editar/' . $id);
        }
    }

    public function adminDelete(string $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/actividades');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Token CSRF inválido.');
            redirect('/admin/actividades');
        }

        $exito = $this->actividadModel->delete((int)$id);
        if ($exito) {
            set_flash('info', 'Actividad eliminada correctamente.');
        } else {
            set_flash('danger', 'Error al eliminar la actividad.');
        }
        redirect('/admin/actividades');
    }
}
