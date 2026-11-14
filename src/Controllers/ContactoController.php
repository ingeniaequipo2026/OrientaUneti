<?php
/**
 * Controlador de Atención al Usuario (Contacto, Propuestas y Reclamos)
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Models/Contacto.php';

class ContactoController {
    private Contacto $contactoModel;

    public function __construct() {
        $this->contactoModel = new Contacto();
    }

    public function showForm(): void {
        require_once __DIR__ . '/../../views/home/contacto.php';
    }

    public function enviar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/contacto');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Token CSRF inválido.');
            redirect('/contacto');
        }

        $nombre  = trim($_POST['nombre'] ?? '');
        $correo  = strtolower(trim($_POST['correo'] ?? ''));
        $cedula  = trim($_POST['cedula'] ?? '');
        $tipo    = trim($_POST['tipo'] ?? 'Consulta');
        $asunto  = trim($_POST['asunto'] ?? '');
        $mensaje = trim($_POST['mensaje'] ?? '');

        if (empty($nombre) || empty($correo) || empty($asunto) || empty($mensaje)) {
            set_flash('danger', 'Por favor complete todos los campos obligatorios del formulario.');
            redirect('/contacto');
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            set_flash('danger', 'El correo electrónico proporcionado no es válido.');
            redirect('/contacto');
        }

        // 1. Guardar en Base de Datos para persistencia y auditoría
        $id = $this->contactoModel->create([
            'nombre'  => $nombre,
            'correo'  => $correo,
            'cedula'  => $cedula,
            'tipo'    => $tipo,
            'asunto'  => $asunto,
            'mensaje' => $mensaje
        ]);

        // 2. Enviar correo electrónico al sistema institucional
        $destino = getenv('MAIL_TO') ?: 'bienestar@uneti.edu.ve';
        $subject = "[OrientaUneti - {$tipo}] " . $asunto;
        $body = "Se ha recibido una nueva solicitud de Atención al Usuario en OrientaUneti:\n\n"
              . "Tipo: {$tipo}\n"
              . "Remitente: {$nombre} ({$cedula})\n"
              . "Correo de contacto: {$correo}\n"
              . "Asunto: {$asunto}\n\n"
              . "Mensaje:\n{$mensaje}\n\n"
              . "Fecha: " . date('d/m/Y H:i:s') . "\n";

        $headers = "From: no-reply@uneti.edu.ve\r\n"
                 . "Reply-To: {$correo}\r\n"
                 . "X-Mailer: PHP/" . phpversion();

        // Intento de envío vía mail()
        @mail($destino, $subject, $body, $headers);

        // Guardar log de correo para verificar en entornos locales sin servidor SMTP activo
        $logDir = __DIR__ . '/../../logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        @file_put_contents(
            $logDir . '/correos_enviados.log',
            "[" . date('Y-m-d H:i:s') . "] Para: {$destino} | Asunto: {$subject} | De: {$correo}\n" . $body . "\n-------------------------\n",
            FILE_APPEND
        );

        set_flash('success', '¡Tu mensaje ha sido enviado exitosamente a la Dirección de Bienestar Estudiantil! Te responderemos a la brevedad.');
        redirect('/contacto');
    }

    public function adminIndex(): void {
        $estado = $_GET['estado'] ?? null;
        $mensajes = $this->contactoModel->getAll($estado);
        require_once __DIR__ . '/../../views/admin/contactos/index.php';
    }

    public function adminActualizar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/contactos');
        }

        if (!verify_csrf_token()) {
            set_flash('danger', 'Token CSRF inválido.');
            redirect('/admin/contactos');
        }

        $id = (int)($_POST['id'] ?? 0);
        $estado = $_POST['estado'] ?? 'Pendiente';
        $respuesta = trim($_POST['respuesta'] ?? '');

        if ($id > 0) {
            $this->contactoModel->updateEstado($id, $estado, $respuesta);
            set_flash('success', 'Solicitud actualizada.');
        }

        redirect('/admin/contactos');
    }
}
