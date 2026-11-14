<?php
/**
 * Generador de Constancias y Reportes en Formato PDF
 * Utiliza FPDF puro sin dependencias de Composer ni librerías externas
 */

require_once __DIR__ . '/fpdf.php';
require_once __DIR__ . '/../../config/config.php';

class PDFGenerator {

    /**
     * Genera la Constancia de Inscripción en Actividades de Bienestar para el Estudiante
     */
    public static function generarConstancia(array $usuario, array $inscripciones): void {
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(20, 20, 20);
        $pdf->AddPage();

        // 1. Franja decorativa institucional superior (Degradado conceptual UNETI)
        $pdf->SetFillColor(40, 122, 56); // #287A38 Verde UNETI
        $pdf->Rect(20, 15, 85, 3, 'F');
        $pdf->SetFillColor(25, 49, 156); // #19319C Azul UNETI
        $pdf->Rect(105, 15, 85, 3, 'F');

        // 2. Membrete Institucional Oficial
        $pdf->SetY(22);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(0, 4, utf8_decode('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $pdf->Cell(0, 4, utf8_decode('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetTextColor(25, 49, 156);
        $pdf->Cell(0, 5, utf8_decode('UNIVERSIDAD NACIONAL EXPERIMENTAL DE TELECOMUNICACIONES E INFORMÁTICA (UNETI)'), 0, 1, 'C');
        $pdf->SetFont('Helvetica', 'I', 9);
        $pdf->SetTextColor(40, 122, 56);
        $pdf->Cell(0, 4, utf8_decode('DIRECCIÓN DE DESARROLLO Y BIENESTAR ESTUDIANTIL - SISTEMA ORIENTAUNETI'), 0, 1, 'C');

        $pdf->Ln(6);
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
        $pdf->Ln(6);

        // 3. Título del Documento
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->SetTextColor(25, 49, 156);
        $pdf->Cell(0, 7, utf8_decode('CONSTANCIA DE PARTICIPACIÓN EN BIENESTAR ESTUDIANTIL'), 0, 1, 'C');
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(100, 100, 100);
        $codigoVerif = 'UNETI-BIEN-' . date('Y') . '-' . str_pad((string)$usuario['id'], 5, '0', STR_PAD_LEFT);
        $pdf->Cell(0, 4, utf8_decode('Código de Validación: ' . $codigoVerif), 0, 1, 'C');

        $pdf->Ln(8);

        // 4. Párrafo de Certificación
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(40, 40, 40);
        
        $nombreCompleto = strtoupper($usuario['nombre'] . ' ' . $usuario['apellido']);
        $cedula = $usuario['cedula'];
        $carrera = !empty($usuario['trayectoria']) ? $usuario['trayectoria'] : 'Comunidad Estudiantil UNETI';

        $cuerpo = "Quien suscribe, la Dirección de Desarrollo y Bienestar Estudiantil de la Universidad Nacional Experimental de Telecomunicaciones e Informática (UNETI), por medio de la presente CERTIFICA que el ciudadano(a):";
        $pdf->MultiCell(0, 5, utf8_decode($cuerpo), 0, 'J');

        $pdf->Ln(4);

        // Cuadro de Datos del Estudiante
        $pdf->SetFillColor(245, 247, 250);
        $pdf->SetDrawColor(220, 225, 235);
        $pdf->Rect(20, $pdf->GetY(), 170, 24, 'FD');

        $pdf->SetY($pdf->GetY() + 2);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetTextColor(25, 49, 156);
        $pdf->Cell(45, 5, utf8_decode('  Nombres y Apellidos:'), 0, 0, 'L');
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(30, 30, 30);
        $pdf->Cell(120, 5, utf8_decode($nombreCompleto), 0, 1, 'L');

        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetTextColor(25, 49, 156);
        $pdf->Cell(45, 5, utf8_decode('  Cédula de Identidad:'), 0, 0, 'L');
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(30, 30, 30);
        $pdf->Cell(120, 5, utf8_decode($cedula . '  |  Correo: ' . $usuario['email']), 0, 1, 'L');

        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetTextColor(25, 49, 156);
        $pdf->Cell(45, 5, utf8_decode('  Trayectoria / Carrera:'), 0, 0, 'L');
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(30, 30, 30);
        $pdf->Cell(120, 5, utf8_decode($carrera), 0, 1, 'L');

        $pdf->Ln(6);

        // 5. Tabla de Actividades Inscritas
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetTextColor(40, 122, 56);
        $pdf->Cell(0, 6, utf8_decode('REGISTRO DE ACTIVIDADES EXTRACURRICULARES Y PROGRAMAS INSCRITOS:'), 0, 1, 'L');

        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetFillColor(40, 122, 56);
        $pdf->SetTextColor(255, 255, 255);
        
        $pdf->Cell(70, 7, utf8_decode('Actividad / Programa'), 1, 0, 'L', true);
        $pdf->Cell(28, 7, utf8_decode('Área / Cat.'), 1, 0, 'C', true);
        $pdf->Cell(42, 7, utf8_decode('Ubicación / Horario'), 1, 0, 'L', true);
        $pdf->Cell(30, 7, utf8_decode('Estado / Asist.'), 1, 1, 'C', true);

        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor(40, 40, 40);

        if (empty($inscripciones)) {
            $pdf->Cell(170, 8, utf8_decode('No registra actividades inscritas actualmente.'), 1, 1, 'C');
        } else {
            $fill = false;
            foreach ($inscripciones as $row) {
                $pdf->SetFillColor(248, 250, 252);
                
                $titulo = strlen($row['titulo']) > 40 ? substr($row['titulo'], 0, 38) . '...' : $row['titulo'];
                $cat = $row['categoria'];
                $horario = substr($row['horario'] ?? 'S/H', 0, 22);
                $estado = $row['asistio'] ? 'Asistió' : $row['estado'];

                $pdf->Cell(70, 6, utf8_decode($titulo), 1, 0, 'L', $fill);
                $pdf->Cell(28, 6, utf8_decode($cat), 1, 0, 'C', $fill);
                $pdf->Cell(42, 6, utf8_decode($horario), 1, 0, 'L', $fill);
                
                // Color para el estado
                if ($row['asistio']) {
                    $pdf->SetTextColor(40, 122, 56);
                } else {
                    $pdf->SetTextColor(25, 49, 156);
                }
                $pdf->Cell(30, 6, utf8_decode($estado), 1, 1, 'C', $fill);
                
                $pdf->SetTextColor(40, 40, 40);
                $fill = !$fill;
            }
        }

        $pdf->Ln(6);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->MultiCell(0, 4.5, utf8_decode("Constancia que se expide a petición de la parte interesada en la ciudad de Caracas, Venezuela, a los " . date('d') . " días del mes de " . self::mesEspanol((int)date('m')) . " del año " . date('Y') . "."), 0, 'J');

        // 6. Sellos y Firmas Institucionales
        $pdf->Ln(15);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetTextColor(60, 60, 60);

        $yFirmas = $pdf->GetY();
        $pdf->Line(30, $yFirmas, 85, $yFirmas);
        $pdf->Line(125, $yFirmas, 180, $yFirmas);

        $pdf->SetY($yFirmas + 2);
        $pdf->SetX(30);
        $pdf->Cell(55, 4, utf8_decode('Coordinación de Bienestar'), 0, 0, 'C');
        $pdf->SetX(125);
        $pdf->Cell(55, 4, utf8_decode('Firma del Estudiante'), 0, 1, 'C');

        $pdf->SetX(30);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(55, 3.5, utf8_decode('UNETI - Sede Central'), 0, 0, 'C');
        $pdf->SetX(125);
        $pdf->Cell(55, 3.5, utf8_decode('C.I. ' . $cedula), 0, 1, 'C');

        // Pie de página de validez
        $pdf->SetY(270);
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->SetTextColor(130, 130, 130);
        $pdf->Cell(0, 4, utf8_decode('Documento oficial generado por la Plataforma OrientaUneti - Para verificar validez consulte a bienestar@uneti.edu.ve'), 0, 1, 'C');

        $pdf->Output('D', 'Constancia_Bienestar_' . preg_replace('/[^a-zA-Z0-9]/', '', $cedula) . '.pdf');
    }

    /**
     * Genera el Reporte Oficial de Estudiantes y Asistencia para el Administrador
     */
    public static function generarReporteAsistencia(array $inscripciones, ?string $tituloFiltro = null): void {
        $pdf = new FPDF('L', 'mm', 'A4'); // Horizontal para mayor espacio en columnas
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();

        // Barra decorativa
        $pdf->SetFillColor(40, 122, 56);
        $pdf->Rect(15, 10, 135, 3, 'F');
        $pdf->SetFillColor(25, 49, 156);
        $pdf->Rect(150, 10, 132, 3, 'F');

        // Encabezado
        $pdf->SetY(16);
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->SetTextColor(25, 49, 156);
        $pdf->Cell(0, 5, utf8_decode('UNIVERSIDAD NACIONAL EXPERIMENTAL DE TELECOMUNICACIONES E INFORMÁTICA (UNETI)'), 0, 1, 'L');
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetTextColor(40, 122, 56);
        $pdf->Cell(0, 4, utf8_decode('DIRECCIÓN DE BIENESTAR ESTUDIANTIL - PLANILLA DE ESTUDIANTES ACTIVOS Y ASISTENCIA'), 0, 1, 'L');
        
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor(100, 100, 100);
        $sub = 'Fecha de Generación: ' . date('d/m/Y H:i:s');
        if ($tituloFiltro) {
            $sub .= ' | Actividad: ' . $tituloFiltro;
        }
        $pdf->Cell(0, 4, utf8_decode($sub), 0, 1, 'L');

        $pdf->Ln(4);

        // Tabla de Datos
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetFillColor(25, 49, 156);
        $pdf->SetTextColor(255, 255, 255);

        $pdf->Cell(24, 7, utf8_decode('Cédula'), 1, 0, 'C', true);
        $pdf->Cell(55, 7, utf8_decode('Estudiante'), 1, 0, 'L', true);
        $pdf->Cell(45, 7, utf8_decode('Carrera / Trayecto'), 1, 0, 'L', true);
        $pdf->Cell(65, 7, utf8_decode('Actividad / Taller'), 1, 0, 'L', true);
        $pdf->Cell(25, 7, utf8_decode('Teléfono'), 1, 0, 'C', true);
        $pdf->Cell(25, 7, utf8_decode('F. Inscripción'), 1, 0, 'C', true);
        $pdf->Cell(28, 7, utf8_decode('Asistencia'), 1, 1, 'C', true);

        $pdf->SetFont('Helvetica', '', 7.5);
        $pdf->SetTextColor(30, 30, 30);

        if (empty($inscripciones)) {
            $pdf->Cell(267, 8, utf8_decode('No se encontraron registros de inscripción para el criterio seleccionado.'), 1, 1, 'C');
        } else {
            $fill = false;
            foreach ($inscripciones as $row) {
                $pdf->SetFillColor(245, 248, 252);

                $nombre = substr($row['apellido'] . ', ' . $row['nombre'], 0, 32);
                $tray = substr($row['trayectoria'] ?? 'Sin Trayecto', 0, 26);
                $act = substr($row['actividad_titulo'] ?? '', 0, 38);
                $tel = $row['telefono'] ?: 'N/A';
                $fecha = date('d/m/Y', strtotime($row['fecha_inscripcion']));
                $asistencia = $row['asistio'] ? 'Asistió [X]' : 'Pendiente [ ]';

                $pdf->Cell(24, 6, utf8_decode($row['cedula']), 1, 0, 'C', $fill);
                $pdf->Cell(55, 6, utf8_decode($nombre), 1, 0, 'L', $fill);
                $pdf->Cell(45, 6, utf8_decode($tray), 1, 0, 'L', $fill);
                $pdf->Cell(65, 6, utf8_decode($act), 1, 0, 'L', $fill);
                $pdf->Cell(25, 6, utf8_decode($tel), 1, 0, 'C', $fill);
                $pdf->Cell(25, 6, utf8_decode($fecha), 1, 0, 'C', $fill);

                if ($row['asistio']) {
                    $pdf->SetTextColor(40, 122, 56);
                    $pdf->SetFont('Helvetica', 'B', 7.5);
                } else {
                    $pdf->SetTextColor(120, 120, 120);
                    $pdf->SetFont('Helvetica', '', 7.5);
                }
                $pdf->Cell(28, 6, utf8_decode($asistencia), 1, 1, 'C', $fill);

                $pdf->SetTextColor(30, 30, 30);
                $pdf->SetFont('Helvetica', '', 7.5);
                $fill = !$fill;
            }
        }

        $pdf->Ln(4);
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->Cell(0, 4, utf8_decode('Total de registros listados: ' . count($inscripciones) . ' | OrientaUneti - Plataforma de Gestión de Bienestar'), 0, 1, 'R');

        $pdf->Output('D', 'Reporte_Asistencia_OrientaUneti_' . date('Ymd_His') . '.pdf');
    }

    private static function mesEspanol(int $m): string {
        $meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        return $meses[$m] ?? '';
    }
}
