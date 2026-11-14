<?php
/**
 * Controlador de Tableros Principales (Landing, Admin y Estudiante)
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Models/Actividad.php';
require_once __DIR__ . '/../Models/SaludMental.php';
require_once __DIR__ . '/../Models/Emprendimiento.php';
require_once __DIR__ . '/../Models/Inscripcion.php';
require_once __DIR__ . '/../Models/Contacto.php';

class DashboardController {
    private Usuario $usuarioModel;
    private Actividad $actividadModel;
    private SaludMental $saludModel;
    private Emprendimiento $empModel;
    private Inscripcion $inscripcionModel;
    private Contacto $contactoModel;

    public function __construct() {
        $this->usuarioModel     = new Usuario();
        $this->actividadModel   = new Actividad();
        $this->saludModel       = new SaludMental();
        $this->empModel         = new Emprendimiento();
        $this->inscripcionModel = new Inscripcion();
        $this->contactoModel    = new Contacto();
    }

    /**
     * Página de Inicio Pública
     */
    public function home(): void {
        $actividadesDestacadas = array_slice($this->actividadModel->getAll(null, 'Activa'), 0, 3);
        $totalEstudiantes      = $this->usuarioModel->countTotalStudents();
        $totalActividades      = $this->actividadModel->countTotal();
        $totalCentrosSalud     = $this->saludModel->countTotal();
        $totalEmprendimientos  = $this->empModel->countTotal();

        require_once __DIR__ . '/../../views/home/index.php';
    }

    /**
     * Panel Principal del Administrador
     */
    public function adminDashboard(): void {
        $stats = [
            'total_estudiantes' => $this->usuarioModel->countTotalStudents(),
            'total_actividades' => $this->actividadModel->countTotal(),
            'total_inscritos'   => $this->inscripcionModel->countTotal(),
            'total_asistencias' => $this->inscripcionModel->countAsistencias(),
            'total_salud'       => $this->saludModel->countTotal(),
            'total_emprend'     => $this->empModel->countTotal(),
            'mensajes_pend'     => $this->contactoModel->countPendientes(),
        ];

        $ultimasActividades = array_slice($this->actividadModel->getAll(), 0, 5);
        $ultimasInscripciones = array_slice($this->inscripcionModel->getAllWithDetails(), 0, 6);

        require_once __DIR__ . '/../../views/admin/dashboard.php';
    }

    /**
     * Panel Principal del Estudiante
     */
    public function studentDashboard(): void {
        $user = auth_user();
        $misInscripciones = $this->inscripcionModel->getByUsuario((int)$user['id']);
        $actividadesDisponibles = $this->actividadModel->getAll(null, 'Activa');
        $totalCentrosSalud = $this->saludModel->countTotal();

        require_once __DIR__ . '/../../views/student/dashboard.php';
    }
}
