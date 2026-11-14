<?php
/**
 * Controlador de Inscripciones, Control de Cupos y Asistencia
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Inscripcion.php';
require_once __DIR__ . '/../Models/Actividad.php';
require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Lib/PDFGenerator.php';

class InscripcionesController {
    private Inscripcion $inscripcionModel;
    private Actividad $actividadModel;
    private Usuario $usuarioModel;

    public function __construct() {
        $this->inscripcionModel = new Inscripcion();
        $this->actividadModel   = new Actividad();
        $this->usuarioModel     = new Usuario();
    }

    // ==========================================
    // ACCIONES DEL ESTUDIANTE
    // ==========================================

    /**
     * Inscribir al estudiante en una actividad con verificación de cupos
     */
    public function inscribir(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/estudiante/actividades');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Token CSRF inválido.');
            redirect('/estudiante/actividades');
        }

        $user = auth_user();
        if (!$user) {
            redirect('/login');
        }

        $actividad_id = (int)($_POST['actividad_id'] ?? 0);
        if ($actividad_id <= 0) {
            set_flash('danger', 'Actividad no válida.');
            redirect('/estudiante/actividades');
        }

        // Ejecutar inscripción transaccional con control de cupos
        $resultado = $this->inscripcionModel->inscribir((int)$user['id'], $actividad_id);

        if ($resultado['success']) {
            set_flash('success', $resultado['message']);
            redirect('/estudiante/mis-actividades');
        } else {
            set_flash('warning', $resultado['message']);
            redirect('/estudiante/actividades');
        }
    }

    /**
     * Cancelar inscripción (libera cupo)
     */
    public function cancelar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/estudiante/mis-actividades');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Token CSRF inválido.');
            redirect('/estudiante/mis-actividades');
        }

        $user = auth_user();
        $actividad_id = (int)($_POST['actividad_id'] ?? 0);

        if ($actividad_id > 0 && $user) {
            $this->inscripcionModel->cancelar((int)$user['id'], $actividad_id);
            set_flash('info', 'Has cancelado tu inscripción en la actividad. El cupo ha quedado disponible para otro estudiante.');
        }

        redirect('/estudiante/mis-actividades');
    }

    /**
     * Lista de actividades en las que el estudiante está inscrito
     */
    public function misActividades(): void {
        $user = auth_user();
        $misInscripciones = $this->inscripcionModel->getByUsuario((int)$user['id']);
        require_once __DIR__ . '/../../views/student/mis_actividades.php';
    }

    /**
     * Descarga de Constancia de Inscripción en PDF para el Estudiante
     * Criterio de Aceptación: Genera un PDF válido con membrete, datos del estudiante y actividades
     */
    public function descargarConstancia(): void {
        $user = auth_user();
        if (!$user) {
            redirect('/login');
        }

        $usuarioDetallado = $this->usuarioModel->findById((int)$user['id']);
        $inscripciones = $this->inscripcionModel->getByUsuario((int)$user['id']);

        if (!$usuarioDetallado) {
            set_flash('danger', 'No se pudieron recuperar los datos del estudiante.');
            redirect('/estudiante/mis-actividades');
        }

        // Generar y enviar PDF al navegador
        PDFGenerator::generarConstancia($usuarioDetallado, $inscripciones);
        exit;
    }

    // ==========================================
    // ACCIONES DEL ADMINISTRADOR
    // ==========================================

    /**
     * Listado administrativo de inscripciones con filtro por actividad
     */
    public function adminIndex(): void {
        $actividad_id = isset($_GET['actividad_id']) && !empty($_GET['actividad_id']) ? (int)$_GET['actividad_id'] : null;
        $actividades = $this->actividadModel->getAll();
        $inscripciones = $this->inscripcionModel->getAllWithDetails($actividad_id);

        require_once __DIR__ . '/../../views/admin/inscripciones/index.php';
    }

    /**
     * Marcar asistencia de un estudiante en una actividad
     */
    public function marcarAsistencia(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/inscripciones');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Token CSRF inválido.');
            redirect('/admin/inscripciones');
        }

        $inscripcion_id = (int)($_POST['inscripcion_id'] ?? 0);
        $asistio        = isset($_POST['asistio']) && $_POST['asistio'] == '1';
        $observaciones  = trim($_POST['observaciones'] ?? '');
        $actividad_id   = $_POST['actividad_id'] ?? '';

        if ($inscripcion_id > 0) {
            $this->inscripcionModel->marcarAsistencia($inscripcion_id, $asistio, $observaciones);
            set_flash('success', 'Asistencia actualizada correctamente.');
        }

        $urlRetorno = '/admin/inscripciones';
        if (!empty($actividad_id)) {
            $urlRetorno .= '?actividad_id=' . urlencode($actividad_id);
        }
        redirect($urlRetorno);
    }
}
