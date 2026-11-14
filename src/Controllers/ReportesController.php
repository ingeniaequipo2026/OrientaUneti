<?php
/**
 * Controlador de Reportes Administrativos (PDF y Excel / CSV)
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Inscripcion.php';
require_once __DIR__ . '/../Models/Actividad.php';
require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Lib/PDFGenerator.php';

class ReportesController {
    private Inscripcion $inscripcionModel;
    private Actividad $actividadModel;
    private Usuario $usuarioModel;

    public function __construct() {
        $this->inscripcionModel = new Inscripcion();
        $this->actividadModel   = new Actividad();
        $this->usuarioModel     = new Usuario();
    }

    public function index(): void {
        $actividad_id = isset($_GET['actividad_id']) && !empty($_GET['actividad_id']) ? (int)$_GET['actividad_id'] : null;
        $actividades = $this->actividadModel->getAll();
        $inscripciones = $this->inscripcionModel->getAllWithDetails($actividad_id);
        
        $estudiantes = $this->usuarioModel->getAllStudents();

        require_once __DIR__ . '/../../views/admin/reportes/index.php';
    }

    /**
     * Descarga de Planilla en PDF (Criterio de Aceptación)
     */
    public function descargarPDF(): void {
        $actividad_id = isset($_GET['actividad_id']) && !empty($_GET['actividad_id']) ? (int)$_GET['actividad_id'] : null;
        $inscripciones = $this->inscripcionModel->getAllWithDetails($actividad_id);

        $tituloFiltro = null;
        if ($actividad_id) {
            $act = $this->actividadModel->findById($actividad_id);
            if ($act) {
                $tituloFiltro = $act['titulo'];
            }
        }

        PDFGenerator::generarReporteAsistencia($inscripciones, $tituloFiltro);
        exit;
    }

    /**
     * Descarga de Planilla en Formato Excel (CSV delimitado compatible con Excel)
     */
    public function descargarExcel(): void {
        $actividad_id = isset($_GET['actividad_id']) && !empty($_GET['actividad_id']) ? (int)$_GET['actividad_id'] : null;
        $inscripciones = $this->inscripcionModel->getAllWithDetails($actividad_id);

        $filename = 'Reporte_Estudiantes_Asistencia_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        // BOM UTF-8 para apertura correcta de caracteres y tildes en Microsoft Excel
        fputs($output, "\xEF\xBB\xBF");

        // Encabezados de columnas
        fputcsv($output, [
            'Cédula',
            'Apellidos',
            'Nombres',
            'Correo Institucional / Personal',
            'Teléfono',
            'Carrera / Trayecto',
            'Actividad / Programa',
            'Categoría',
            'Fecha de Inscripción',
            'Asistió',
            'Observaciones'
        ], ';');

        foreach ($inscripciones as $row) {
            fputcsv($output, [
                $row['cedula'],
                $row['apellido'],
                $row['nombre'],
                $row['email'],
                $row['telefono'] ?: 'N/A',
                $row['trayectoria'] ?: 'N/A',
                $row['actividad_titulo'],
                $row['actividad_categoria'],
                date('d/m/Y H:i', strtotime($row['fecha_inscripcion'])),
                $row['asistio'] ? 'SÍ' : 'NO',
                $row['observaciones'] ?? ''
            ], ';');
        }

        fclose($output);
        exit;
    }
}
